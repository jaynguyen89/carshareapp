<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Chronos\Date;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use \DateTime;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 *
 * @method \App\Model\Entity\Customer[] paginate($object = null, array $settings = [])
 */
class CustomersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['dashboard', 'logout', 'edit']);
    }

    public function dashboard() {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        
        $browsingsTable = TableRegistry::get('Browsings');
        $browsings = $browsingsTable->find('all', ['conditions' => ['customer_id' => $customer["id"]], 'contain' => array('Cars')]);
        
        $percentage = 0;
        if (!empty($customer['phone']))
            $percentage += 10;

        if (!empty($customer['address']))
            $percentage += 20;

        $cardCheck = false;
        if (!empty($customer['cardholder']) && !empty($customer['cardcode']) &&
            !empty($customer['carddate']) && !empty($customer['cardaddress']))
            $cardCheck = true;

        if (!empty($customer['paypal']) && $cardCheck)
            $percentage += 70;
        else if (!empty($customer['paypal']) && !$cardCheck)
            $percentage += 50;
        else if (empty($customer['paypal']) && $cardCheck)
            $percentage += 60;

        $watches = $this->Customers->Browsings->find('all', ['conditions' => ['customer_id' => $customer->id, 'watched' => 1]])->toArray();
        $rentalCount = array();
        $imageNames = array();
        $carNames = array();
        $watchesCount = array();
        foreach ($watches as $watch) {
            $countRentalsByCar = $this->Customers->Rentals->find('all', ['conditions' => ['car_id' => $watch['car_id'], 'done' => 0]])->count();
            $rentalCount[$watch['car_id']] = $countRentalsByCar;

            $aCar = $this->Customers->Rentals->Cars->get($watch['car_id']);
            $imageNames[$watch['car_id']] = $aCar['image'];
            $carNames[$watch['car_id']] = $aCar['make'].' '.$aCar['model'].' '.$aCar['year'];

            $countWatchesByCar = $this->Customers->Browsings->find('all', ['conditions' => ['watched' => 1, 'car_id' => $watch['car_id']]])->count();
            $watchesCount[$watch['car_id']] = $countWatchesByCar;
        }

        $upcomingRentals = $this->Customers->Rentals->find('all', ['conditions' => ['customer_id' => $customer['id'], 'paid' => 1, 'done' => 0]])->toArray();
        usort($upcomingRentals, function($a, $b) {
            $atime = new DateTime($a['fromdate']);
            $btime = new DateTime($b['fromdate']);

            return $atime == $btime ? 0 : ($atime < $btime ? -1 : 1);
        });

        $carNamesByRental = array();
        $carImagesByRental = array();
        foreach ($upcomingRentals as $upcomingRental) {
            $aCar = $this->Customers->Rentals->Cars->get($upcomingRental['car_id']);
            $carNamesByRental[$upcomingRental['id']] = $aCar['make'].' '.$aCar['model'].' '.$aCar['year'].' ('.$aCar['color'].')';
            $carImagesByRental[$upcomingRental['id']] = $aCar['image'];
        }

        $pastRentals = $this->Customers->Rentals->find('all', ['conditions' => ['customer_id' => $customer['id'], 'paid' => 1, 'done' => 1]])->toArray();
        usort($pastRentals, function($a, $b) {
            $atime = new DateTime($a['fromdate']);
            $btime = new DateTime($b['fromdate']);

            return $atime == $btime ? 0 : ($atime < $btime ? 1 : -1);
        });

        $carNamesByPast = array();
        $carImagesByPast = array();
        foreach ($pastRentals as $pastRental) {
            $aCar = $this->Customers->Rentals->Cars->get($pastRental['car_id']);
            $carNamesByPast[$pastRental['id']] = $aCar['make'].' '.$aCar['model'].' '.$aCar['year'].' ('.$aCar['color'].')';
            $carImagesByPast[$pastRental['id']] = $aCar['image'];
        }

        $requests = $this->Customers->Requests->find('all', ['conditions' => ['customer_id' => $customer['id']]])->toArray();
        $rentalsByRequest = array();
        $carsByRequest = array();
        foreach ($requests as $request) {
            $aRental = $this->Customers->Rentals->get($request['rental_id']);
            $rentalsByRequest[$request['id']] = $aRental;

            $aCar = $this->Customers->Rentals->Cars->get($aRental['car_id']);
            $carsByRequest[$request['id']] = $aCar;
        }

        $this->set(compact('customer', 'percentage', 'browsings', 'watches', 'pastRentals',
            'rentalCount', 'carNames', 'watchesCount', 'imageNames', 'upcomingRentals', 'carNamesByRental',
            'carImagesByRental', 'carNamesByPast', 'carImagesByPast', 'requests', 'rentalsByRequest', 'carsByRequest'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $customers = $this->paginate($this->Customers);

        $this->set(compact('customers'));
        $this->set('_serialize', ['customers']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => ['Users', 'Browsings', 'Messages', 'Rentals', 'Requests']
        ]);

        $this->set('customer', $customer);
        $this->set('_serialize', ['customer']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post')) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($this->Customers->save($customer)) {
                $this->Flash->success(__('The customer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer could not be saved. Please, try again.'));
        }
        $users = $this->Customers->Users->find('list', ['limit' => 200]);
        $this->set(compact('customer', 'users'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Customers->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $process = false;

            $specialChars = ['*', '^', '$', '&', '%', '#', '@@', '@@@', '@@@@', '!', '(', ')', '+', '=', '{', '}', '[', ']', '|', ':', ';', '?', '<', '>', '~'];
            $specialCheck = false;
            for ($i = 0; $i < count($specialChars); $i++) {
                if (strpos($data['email'], $specialChars[$i]) != false)
                    break;

                if ($i == count($specialChars) - 1 && strpos($data['email'], $specialChars[$i]) == false)
                    $specialCheck = true;
            }

            if (!$specialCheck)
                $this->Flash->warning(__('Email contains special characters or it was mistyped.'));
            else {
                $domainCheck = false;
                if (strpos($data['email'], '.com') !== false)
                    $domainCheck = true;

                if (!$domainCheck)
                    $this->Flash->Warning(__('Email domain may contain mistakes.'));
                else {
                    if (!preg_match('/[^A-Za-z ]/', $data['name'])) {
                        if (ctype_digit($data['phone'])) {
                            if (!$data['cardholder'] || !$data['cardcode'] || !$data['carddate'] || !$data['cardaddress']) {
                                if (!$data['paypal'])
                                    $this->Flash->warning(__('No changes to update! Please cancel your action.'));
                                else {
                                    if ($this->checkPaypal($data['paypal'])) {
                                        $customer->paypal = $data['paypal'];
                                        $process = true;
                                    }
                                    else
                                        $this->Flash->error(__('Paypal address was incorrect! Please check your paypal address.'));
                                }
                            }
                            else {
                                if ($data['paypal']) {
                                    if (!preg_match('/[^A-Za-z ]/', $data['cardholder'])) {
                                        $cardcode = $data['cardcode'];
                                        $nonspace_cardcode = preg_replace('/\s+/', '', $cardcode);
                                        if ($this->check_cc($cardcode) != false) {
                                            if ($this->checkPaypal($data['paypal'])) {
                                                $process = true;
                                                $customer->paypal = $data['paypal'];

                                                $lastDigits = substr($nonspace_cardcode, -3);
                                                $codeToSave = Security::hash($cardcode, 'md5', true) . $lastDigits;

                                                $customer->cardholder = $data['cardholder'];
                                                $customer->cardcode = $codeToSave;
                                                $customer->cardaddress = $data['cardaddress'];

                                                $carddate = new Date($data['carddate']);
                                                $customer->carddate = $carddate->format('Y-m-d');
                                            }
                                            else
                                                $this->Flash->error(__('Paypal address was incorrect! Please check your paypal address.'));
                                        }
                                        else
                                            $this->Flash->error(__('Credit Card serial number does not match any types of cards!'));
                                    }
                                    else
                                        $this->Flash->error(__('Credit Card holder was mistyped. Alphabets only!'));
                                }
                                else {
                                    if (!preg_match('/[^A-Za-z ]/', $data['cardholder'])) {
                                        $cardcode = $data['cardcode'];
                                        $nonspace_cardcode = preg_replace('/\s+/', '', $cardcode);

                                        if ($this->check_cc($cardcode) != false) {
                                            $process = true;
                                            $lastDigits = substr($nonspace_cardcode, -3);
                                            $codeToSave = Security::hash($cardcode, 'md5', true) . $lastDigits;

                                            $customer->cardholder = ucwords(strtolower($data['cardholder']));
                                            $customer->cardcode = $codeToSave;
                                            $customer->cardaddress = $data['cardaddress'];

                                            $carddate = new Date($data['carddate']);
                                            $customer->carddate = $carddate->format('Y-m-d');
                                        }
                                        else
                                            $this->Flash->error(__('Credit Card serial number does not match any types of cards!'));
                                    }
                                    else
                                        $this->Flash->error(__('Credit Card holder was mistyped. Alphabets only!'));
                                }
                            }

                            if ($process) {
                                $customer->name = $data['name'];
                                $customer->email = $data['email'];
                                $customer->phone = $data['phone'];
                                $customer->address = $data['address'];

                                if ($this->Customers->save($customer)) {
                                    $this->Flash->success(__('Your profile has been updated.'));
                                    return $this->redirect(['action' => 'dashboard']);
                                } else
                                    $this->Flash->error(__('Database error. Please try again!'));
                            }
                        }
                        else
                            $this->Flash->warning(__('Phone number should contain digits only.'));
                    }
                    else
                        $this->Flash->warning(__('Name was mistyped. Alphabets only!'));
                }
            }
        }

        $this->set(compact('customer'));
        $this->set('_serialize', ['customer']);
    }

    private function check_cc($cc){
        $cards = array(
            "visa" => "(4\d{12}(?:\d{3})?)",
            "amex" => "(3[47]\d{13})",
            "jcb" => "(35[2-8][89]\d\d\d{10})",
            "maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
            "solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
            "mastercard" => "(5[1-5]\d{14})",
            "switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)",
        );
        $names = array("Visa", "American Express", "JCB", "Maestro", "Solo", "Mastercard", "Switch");
        $matches = array();
        $pattern = "#^(?:".implode("|", $cards).")$#";
        $result = preg_match($pattern, str_replace(" ", "", $cc), $matches);
        return ($result>0) ? $names[sizeof($matches)-2] : false;
    }

    private function checkPaypal($paypal = null) {
        $specialChars = ['*', '^', '$', '&', '%', '#', '@@', '@@@', '@@@@', '!', '(', ')', '+', '=', '{', '}', '[', ']', '|', ':', ';', '?', '<', '>', '~'];
        $specialCheck = false;
        for ($i = 0; $i < count($specialChars); $i++) {
            if (strpos($paypal, $specialChars[$i]) != false)
                break;

            if ($i == count($specialChars) - 1 && strpos($paypal, $specialChars[$i]) == false)
                $specialCheck = true;
        }

        if (!$specialCheck)
            return false;
        else {
            $domainCheck = false;
            if (strpos($paypal, '.com') !== false)
                $domainCheck = true;

            if (!$domainCheck)
                return false;
            else
                return true;
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customer = $this->Customers->get($id);
        if ($this->Customers->delete($customer)) {
            $this->Flash->success(__('The customer has been deleted.'));
        } else {
            $this->Flash->error(__('The customer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function adminEdit($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = $this->Customers->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $specialChars = ['*', '^', '$', '&', '%', '#', '@@', '@@@', '@@@@', '!', '(', ')', '+', '=', '{', '}', '[', ']', '|', ':', ';', '?', '<', '>', '~'];
            $specialCheck = false;
            for ($i = 0; $i < count($specialChars); $i++) {
                if (strpos($data['email'], $specialChars[$i]) != false)
                    break;

                if ($i == count($specialChars) - 1 && strpos($data['email'], $specialChars[$i]) == false)
                    $specialCheck = true;
            }

            if (!$specialCheck)
                $this->Flash->warning(__('Email contains special characters or was mistyped.'));
            else {
                $domainCheck = false;
                if (strpos($data['email'], '.com') !== false)
                    $domainCheck = true;

                if (!$domainCheck)
                    $this->Flash->Warning(__('Email domain may contain mistake.'));
                else {
                    if (!preg_match('/[^A-Za-z ]/', $data['name'])) {
                        if (ctype_digit($data['phone'])) {
                            $admin->name = $data['name'];
                            $admin->email = $data['email'];
                            $admin->phone = $data['phone'];
                            $admin->address = $data['address'];

                            if ($this->Customers->save($admin)) {
                                $this->Flash->success(__('Your profile has been updated.'));
                                return $this->redirect(['controller' => 'users', 'action' => 'view']);
                            }
                            else
                                $this->Flash->error(__('Errors occurred while saving data. Please try again later!'));
                        }
                        else
                            $this->Flash->warning(__('Phone number should contain digits only.'));
                    }
                    else
                        $this->Flash->warning(__('Name was mistyped. Alphabets only!'));
                }
            }
        }

        $this->set(compact('admin'));
    }
}