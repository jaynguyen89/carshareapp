<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use \DateTime;
use Cake\Utility\Security;

/**
 * Rentals Controller
 *
 * @property \App\Model\Table\RentalsTable $Rentals
 *
 * @method \App\Model\Entity\Rental[] paginate($object = null, array $settings = [])
 */
class RentalsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'edit', 'timetable', 'delete', 'returnCar', 'proceed', 'resolve', 'commonCheckout', 'verifyCard']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Rentals->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $rentals = $this->Rentals->find('all', ['conditions' => ['customer_id' => $customer['id'], 'done' => 0, 'paid' => 0]])->toArray();

        $carNamesByRental = array();
        $carColorsByRental = array();
        $total = 0.0;
        foreach ($rentals as $rental) {
            $car = $this->Rentals->Cars->get($rental['car_id']);
            $carNamesByRental[$rental['id']] = $car['make'].' '.$car['model'].' '.$car['year'];
            $carColorsByRental[$rental['id']] = $car['color'];

            $total += $rental['value'];
        }

        $this->set(compact('rentals', 'customer', 'carNamesByRental', 'total', 'carColorsByRental'));
    }

    public function commonCheckout() {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        $customer = $this->Rentals->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();

        $rentals = $this->Rentals->find('all', ['conditions' => ['customer_id' => $customer['id'], 'done' => 0, 'paid' => 0]])->toArray();

        foreach ($rentals as $rental) {
            $record = $this->Rentals->get($rental['id']);
            $record->paid = 1;

            $reference = Security::hash($record->id, 'md5', true);
            $record->reference = $reference;

            date_default_timezone_set('Australia/Melbourne');
            $record->modified = date('Y-m-d H:i:s', time());

            $this->Rentals->save($record);
        }

        $this->Flash->success(__('Your payment has been updated. You can view your upcoming rentals in your dashboard now.'));
        return $this->redirect(['controller' => 'customers', 'action' => 'dashboard']);
    }

    public function verifyCard() {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Rentals->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $rentals = $this->Rentals->find('all', ['conditions' => ['customer_id' => $customer['id'], 'done' => 0, 'paid' => 0]])->toArray();

        $total = 0.0;
        $count = 0;
        foreach ($rentals as $rental) {
            $total += $rental['value'];
            $count++;
        }

        $this->set(compact('customer', 'total', 'count'));
    }

    /**
     * View method
     *
     * @param string|null $id Rental id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rental = $this->Rentals->get($id, [
            'contain' => ['Customers', 'Cars', 'Requests']
        ]);

        $this->set('rental', $rental);
        $this->set('_serialize', ['rental']);
    }

    public function timetable($id = null) {

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Rentals->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();

        $count = $this->Rentals->find('all', ['conditions' => ['customer_id' => $customer->id, 'paid' => 0]])->count();
        $count += 1;

        $session = $this->request->session();
        $session->write('Rentals.count', $count);

        $id = $this->request->query('carid');
        $from = $this->request->query('from');
        $car = $this->Rentals->Cars->get($id);

        $rental = $this->Rentals->newEntity();
        $rental->car_id = $car['id'];
        $rental->customer_id = $customer->id;

        date_default_timezone_set('Australia/Melbourne');
        $rental->created = date('Y-m-d H:i:s', time());
        $rental->modified = null;

        if ($this->Rentals->save($rental)) {
            $conn = ConnectionManager::get('default');
            $stmt = $conn->prepare('SELECT * FROM rentals WHERE id = (SELECT MAX(id) FROM rentals);');
            $stmt->execute();
            $result = $stmt->fetchAll('assoc');
            $recordID = $result[0]['id'];

            return $this->redirect(['action' => 'edit', '?' => ['rid' => $recordID, 'from' => $from]]);
        }
        else {
            $this->Flash->error(__('Errors occurred while processing rental. Please try again.'));
            return $this->redirect($this->request->referer());
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Rental id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $rid = $this->request->query('rid');
        $from = $this->request->query('from');

        $rental = $this->Rentals->get($rid);
        $car = $this->Rentals->Cars->get($rental->car_id);

        $rentalsByCustomer = $this->Rentals->find('all', ['conditions' => ['customer_id' => $rental->customer_id, 'done' => 0]])->toArray();
        $rentalsByCar = $this->Rentals->find('all', ['conditions' => ['car_id' => $rental->car_id, 'done' => 0]])->toArray();

        usort($rentalsByCar, function($a, $b) {
            $atime = new DateTime($a['fromdate']);
            $btime = new DateTime($b['fromdate']);

            return $atime == $btime ? 0 : ($atime < $btime ? -1 : 1);
        });

        usort($rentalsByCustomer, function($a, $b) {
            $atime = new DateTime($a['fromdate']);
            $btime = new DateTime($b['fromdate']);

            return $atime == $btime ? 0 : ($atime < $btime ? -1 : 1);
        });

        $returnDate = null;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $utimeValid = false;
            $ctimeValid = false;

            if (!isset($data['rental']))
                $data['rental'] = 'discarded';

            if ($data['rental'] == 'short') {
                if ($data['date']) {
                    $rentDate = new DateTime($data['date']);

                    if ($data['hours'] == 1)
                        $returnDate = $rentDate->modify('+1 hour');
                    else
                        $returnDate = $rentDate->modify('+'.$data['hours'].' hours');

                    $ctimeValid = $this->checkCarTimes($rentDate, $rentDate, $rentalsByCar);
                    $utimeValid = $this->checkCustomerTimes($rentDate, $rentDate, $rentalsByCustomer);
                }
                else
                    $rental->todate = null;
            }
            else if ($data['rental'] == 'long') {
                if ($data['date']) {
                    $rentDate = new DateTime($data['date']);

                    if ($data['days'] == 1)
                        $returnDate = $rentDate->modify('+1 day');
                    else
                        $returnDate = $rentDate->modify('+'.$data['days'].' days');

                    $ctimeValid = $this->checkCarTimes($rentDate, $rentDate, $rentalsByCar);
                    $utimeValid = $this->checkCustomerTimes($rentDate, $rentDate, $rentalsByCustomer);
                }
                else
                    $rental->todate = null;
            }

            if ($ctimeValid && $utimeValid) {
                $rental->fromdate = ($data['date'] ? $data['date'] : null);
                $rental->note = ($data['note'] ? $data['note'] : null);

                date_default_timezone_set('Australia/Melbourne');
                $rental->modified = date('Y-m-d H:i:s', time());

                $strDate = $returnDate->format('Y-m-d H:i:s');
                $rental->todate = $strDate;

                $rental->type = $data['rental'];
                $rental->duration = ($data['rental'] == 'short') ? $data['hours'] : $data['days'];

                $rental->value = ($data['rental'] == 'short') ? round($car['kmprice'], 2) : round(((int)$data['days'])*(float)$car['ddprice'], 2);

                if ($this->Rentals->save($rental)) {
                    $this->Flash->success(__('The rental has been saved.'));

                    if ($from == 'carmap')
                        return $this->redirect(['controller' => 'Cars', 'action' => 'carsOnMap']);
                    elseif ($from == 'carindex')
                        return $this->redirect(['controller' => 'Cars', 'action' => 'index']);
                    elseif ($from == 'adminview')
                        return $this->redirect(['controller' => 'Users', 'action' => 'view']);
                    else
                        return $this->redirect(['controller' => 'Rentals', 'action' => 'index']);
                } else
                    $this->Flash->error(__('Database error. Please try again!'));
            }
            else if (!$ctimeValid && !$utimeValid && ($data['rental'] != 'discarded'))
                $this->Flash->error(__(($from == 'adminview') ? 'The selected rental time clashes with customer\'s and car\'s timetable.' : 'Your rental time clashes with both car\'s timetable and your active rental.'));
            else if (!$ctimeValid && ($data['rental'] != 'discarded'))
                $this->Flash->error(__(($from == 'adminview') ? 'The selected rental time clashes with car\'s timetable.' : 'Your rental time clashes with car\'s timetable.'));
            else if (!$utimeValid && ($data['rental'] != 'discarded'))
                $this->Flash->error(__(($from == 'adminview') ? 'The selected rental time clashes with customer\'s timetable.' : 'Your rental time clashes with your active rental.'));
        }

        $this->set(compact('rental', 'car', 'from'));
        $this->set('_serialize', ['rental']);
    }

    private function checkCarTimes($rentDate, $returnDate, $rentalsByCar) {
        $ctimeValid = false;
        $detected = false;

        if (empty($rentalsByCar) || count($rentalsByCar) == 1)
            return true;
        else
            foreach ($rentalsByCar as $rental)
                if ($rental['fromdate']) {
                    $detected = true;
                    break;
                }

        if (!$detected)
            return true;
        else
            for ($i = 0; $i < count($rentalsByCar); $i++)
                if ($i == 0) {
                    if (($returnDate->modify('+3 hours')) <= (new DateTime($rentalsByCar[$i]['fromdate']))) {
                        $ctimeValid = true;
                        break;
                    }
                }
                else if ($i == count($rentalsByCar) - 1) {
                    if ($rentDate >= (new DateTime($rentalsByCar[$i]['todate']))->modify('+3 hours'))
                        $ctimeValid = true;
                }
                else {
                    if ($rentDate >= (new DateTime($rentalsByCar[$i - 1]['todate']))->modify('+3 hours') &&  ($returnDate->modify('+3 hours')) <= (new DateTime($rentalsByCar[$i]['fromdate']))) {
                        $ctimeValid = true;
                        break;
                    }
                }

        return $ctimeValid;
    }

    private function checkCustomerTimes($rentDate, $returnDate, $rentalsByCustomer) {
        $utimeValid = false;
        $detected = false;

        if (empty($rentalsByCustomer) || count($rentalsByCustomer) == 1)
            return true;
        else
            foreach ($rentalsByCustomer as $rental)
                if ($rental['fromdate']) {
                    $detected = true;
                    break;
                }

        if (!$detected)
            return true;
        else
            for ($i = 0; $i < count($rentalsByCustomer); $i++)
                if ($i == 0) {
                    if ($returnDate <= (new DateTime($rentalsByCustomer[$i]['fromdate']))) {
                        $utimeValid = true;
                        break;
                    }
                }
                else if ($i == count($rentalsByCustomer) - 1) {
                    if ($rentDate >= (new DateTime($rentalsByCustomer[$i]['todate'])))
                        $utimeValid = true;
                }
                else {
                    if ($rentDate >= (new DateTime($rentalsByCustomer[$i - 1]['todate'])) &&  ($returnDate <= (new DateTime($rentalsByCustomer[$i]['fromdate'])))) {
                        $utimeValid = true;
                        break;
                    }
                }

        return $utimeValid;
    }

    /**
     * Delete method
     *
     * @param string|null $id Rental id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        $session = $this->request->session();
        $user = $session->read('Auth.User');

        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $rid = $this->request->query('rid');
        $origin = $this->request->query('from');

        $this->request->allowMethod(['post', 'delete']);
        $rental = $this->Rentals->get($rid);
        if ($this->Rentals->delete($rental)) {
            $this->Flash->success(__('The car has been removed from your Rental Cart.'));

            $rCount = $session->read('Rentals.count');
            $session->write('Rentals.count', $rCount - 1);

            if ($origin == 'carmap')
                return $this->redirect(['controller' => 'Cars', 'action' => 'carsOnMap']);
            elseif ($origin == 'carindex')
                return $this->redirect(['controller' => 'Cars', 'action' => 'index']);
            elseif ($origin == 'adminview')
                return $this->redirect(['controller' => 'Users', 'action' => 'view']);
            else
                return $this->redirect(['controller' => 'Rentals', 'action' => 'index']);
        } else
            $this->Flash->error(__('Database encountered a problem. Please try again in your Rental Cart.'));
    }

    public function returnCar() {
        $session = $this->request->session();
        $user = $session->read('Auth.User');

        if (!$user) {
            $this->Flash->warning(__('Please login before you can proceed to return car.'));
            $this->redirect(['controller' => 'users', 'action' => 'login', '?' => ['from' => 'rental']]);
        }

        $customer = $this->Rentals->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $rentals = $this->Rentals->find('all', ['conditions' => ['customer_id' => $customer['id'], 'paid' => 1, 'done' => 0]])->toArray();

        $carsToReturn = array();
        $carImgByRental = array();
        $carNameByRental = array();
        foreach ($rentals as $rental) {
            if ((new DateTime($rental['todate'])) > Time::now())
                continue;

            $car = $this->Rentals->Cars->get($rental['car_id']);
            $carImgByRental[$rental['id']] = $car['image'];
            $carNameByRental[$rental['id']] = $car['make'].' '.$car['model'].' '.$car['year'];
            array_push($carsToReturn, $rental);
        }

        $this->set(compact('customer', 'carsToReturn', 'carImgByRental', 'carNameByRental'));
    }

    public function proceed($id = null) {
        $session = $this->request->session();
        $user = $session->read('Auth.User');

        if (!$user) {
            $this->Flash->warning(__('Please login before you can proceed to return car.'));
            $this->redirect(['controller' => 'users', 'action' => 'login', '?' => ['from' => 'rental']]);
        }

        $customer = $this->Rentals->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $rental = $this->Rentals->get($id);
        $car = $this->Rentals->Cars->get($rental['car_id']);

        $carImgByRental = array();
        $carNameByRental = array();
        $carImgByRental[$rental['id']] = $car['image'];
        $carNameByRental[$rental['id']] = $car['make'].' '.$car['model'].' '.$car['year'];
        $carOdo = $car['odometer'];

        $this->set(compact('rental', 'customer', 'carImgByRental', 'carNameByRental', 'carOdo'));
    }

    public function resolve($id = null) {
        $this->autoRender = false;

        $session = $this->request->session();
        $user = $session->read('Auth.User');

        if (!$user) {
            $this->Flash->warning(__('Please login before you can proceed to return car.'));
            $this->redirect(['controller' => 'users', 'action' => 'login', '?' => ['from' => 'rental']]);
        }

        $rental = $this->Rentals->get($id);
        $car = $this->Rentals->Cars->get($rental['car_id']);

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $rental->done = 1;
            $finalValue = 0.0;
            if ($rental['type'] == 'short') {
                $finalValue += $car->kmprice*((double)$data['odometer'] - (double)$car->odometer)/50.0;

                $duedate = new DateTime($rental['todate']);
                $today = Time::now();

                if ($today > $duedate->modify('+15 minutes')) {
                    $differ = $duedate->diff($today);
                    $finalValue += $car->kmprice*2.0*((double)$differ->m)/60.0;
                }
            }
            else {
                $finalValue += $rental['duration']*(double)$car->ddprice;

                $duedate = new DateTime($rental['todate']);
                $today = Time::now();

                if ($today >= $duedate->modify('+15 minutes')) {
                    $differ = $duedate->diff($today);
                    $finalValue += $car->kmprice*2*((double)$differ->m)/60;

                    if ($data['odometer'] > $car->odometer)
                        $finalValue += $car->kmprice*((double)$data['odometer'] - (double)$car->odometer)/50.0;
                }
                else {
                    if ($data['odometer'] > $car->odometer)
                        $finalValue += $car->kmprice*((double)$data['odometer'] - (double)$car->odometer)/50.0;
                }
            }

            if ($finalValue <= $rental['value'])
                $rental->fine = 0;
            else
                $rental->fine = abs($finalValue - $rental['value']);

            if ($this->Rentals->save($rental)) {
                $car->odometer = $data['odometer'];
                $car->parking = $data['parking'];
                $car->latitude = $data['latitude'];
                $car->longitude = $data['longitude'];

                if ($this->Rentals->Cars->save($car)) {
                    $this->Flash->success(__('Your rental has been returned successfully. Please check your email for payment instruction.'));
                    return $this->redirect(['controller' => 'Customers', 'action' => 'dashboard']);
                }
                else
                    $this->Flash->error(__('Database encountered a problem. Please try again.'));
            }
            else
                $this->Flash->error(__('Database encountered a problem. Please try again.'));
        }
    }
}
