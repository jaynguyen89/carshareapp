<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * Cars Controller
 *
 * @property \App\Model\Table\CarsTable $Cars
 *
 * @method \App\Model\Entity\Car[] paginate($object = null, array $settings = [])
 */
class CarsController extends AppController
{
    public $helpers = array('GoogleMap');

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['carsOnMap', 'search']);
    }

    public function carsOnMap() {
        $cars = $this->Cars->find('all', ['conditions' => ['available' => 1]])->toArray();
        $mapData = array();
        $i = 0;
        foreach ($cars as $car) {
            $data = array();
            $data['id'] = $car['id'];
            $data['title'] = $car['make'].' '.$car['model'].' '.$car['year'];
            $data['image'] = $car['image'];
            $data['odometer'] = $car['odometer'];
            $data['body'] = $car['type'];
            $data['drive'] = $car['drivetype'];
            $data['fuel'] = $car['fuel'];
            $data['parking'] = $car['parking'];

            $mapData[$i] = $data;
            $i++;
        }

        $this->set(compact('cars', 'mapData'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = ['conditions' => ['available' => 1]];

        $cars = $this->paginate($this->Cars);
        $images = $this->searchImages($cars);

        $this->set(compact('cars', 'images'));
        $this->set('_serialize', ['cars']);
    }

    private function searchImages($cars = null) {
        //$imageFolder = new Folder(WWW_ROOT.'img', true, 0444);
        $images = array();
        foreach ($cars as $car) {
            $allImages = glob(WWW_ROOT.'img\\'.'*.jpg');
            $images[$car['id']] = array();

            $i = 0;
            foreach ($allImages as $image)
                if (strpos($image, $car['image']) !== false) {
                    $tokens = explode('\\', $image);
                    $imageName = $tokens[count($tokens) - 1];
                    $images[$car['id']][$i] = $imageName;
                    $i++;
                }
        }
        return $images;
    }

    public function revive() {
        $this->paginate = ['conditions' => ['available' => 0]];

        $cars = $this->paginate($this->Cars);
        $images = $this->searchImages($cars);

        $this->set(compact('cars', 'images'));
        $this->set('_serialize', ['cars']);
    }

    /**
     * View method
     *
     * @param string|null $id Car id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {
        $userId = $this->Auth->user('id');
        $cust = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $userId]])->first();

        $id = $this->request->query('carid');
        $from = $this->request->query('from');

        $car = $this->Cars->get($id, [
            'contain' => ['Browsings', 'Rentals']
        ]);

        $allImages = glob(WWW_ROOT.'img\\'.'*.jpg');
        $carImages = array();

        $i = 0;
        foreach ($allImages as $image)
            if (strpos($image, $car['image']) !== false) {
                $tokens = explode('\\', $image);
                $imageName = $tokens[count($tokens) - 1];
                $carImages[$i] = $imageName;
                $i++;
            }

        $parkString = '';
        $addressTokens = explode(' ', $car['parking']);
        for ($j = 0; $j < count($addressTokens); $j++) {
            if ($j == count($addressTokens) - 1)
                $parkString .= $addressTokens[$j].',+Australia';
            else
                $parkString .= $addressTokens[$j].'+';
        }

        $aBrowsing = $this->Cars->Browsings->find('all', ['conditions' => ['customer_id' => $cust['id'], 'car_id' => $id]])->first();
        $watched = $aBrowsing['watched'];

        if($cust["id"]){
            $browsing = $this->Cars->Browsings->find('all', ['conditions' => ['car_id' => $id, 'customer_id' => $cust['id']]])->first();

            if(is_null($browsing)){
                $browsing = $this->Cars->Browsings->newEntity();
                $browsing->car_id = $id;
                $browsing->customer_id = $cust["id"];
                $browsing->look = 1;

                if ($this->Cars->Browsings->save($browsing)) {}
                else
                    $this->Flash->warning(__('This car was unable to recorded into your viewed items due to an unknown error.'));
            } else {
                $look = $browsing->look + 1;
                $browsing->look = $look;
                if ($this->Cars->Browsings->save($browsing)) {}
                else
                    $this->Flash->warning(__('This car was unable to recorded into your viewed items due to an unknown error.'));
            }
        }

        $this->set(compact('car', 'watched', 'carImages', 'parkString', 'from'));
        $this->set('_serialize', ['car']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addFirst()
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();

        $makes = $this->Cars->find('all')->select(['make'])->distinct(['make'])->orderAsc('make')->toArray();
        $makeOptions = array();
        for ($i = 0; $i < count($makes); $i++) {
            if ($this->isNullOrEmpty($makes[$i]['make']))
                continue;

            $makeOptions[$i] = $makes[$i]['make'];
        }

        $types = $this->Cars->find('all')->select(['type'])->distinct(['type'])->orderAsc('type')->toArray();
        $typeOptions = array();
        for ($i = 0; $i < count($types); $i++) {
            if ($this->isNullOrEmpty($types[$i]['type']))
                continue;

            $typeOptions[$i] = $types[$i]['type'];
        }

        $fuels = $this->Cars->find('all')->select(['fuel'])->distinct(['fuel'])->orderAsc('fuel')->toArray();
        $fuelOptions = array();
        for ($i = 0; $i < count($fuels); $i++) {
            if ($this->isNullOrEmpty($fuels[$i]['fuel']))
                continue;

            $fuelOptions[$i] = $fuels[$i]['fuel'];
        }

        $models = $this->Cars->find('all')->select(['make', 'model'])->distinct(['model'])->toArray();
        $modelOptions = array();
        foreach ($makes as $make) {
            $modelOptions[$make['make']] = array();
            $i = 0;
            foreach ($models as $model) {
                if ($make['make'] == $model['make'])
                    $modelOptions[$make['make']][$i] = $model['model'];

                $i++;
            }
        }

        $car = $this->Cars->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (is_numeric($data['model'])) {
                foreach ($modelOptions as $k => $models)
                    foreach ($models as $key => $model)
                        if ($data['model'] == $key) {
                            $car->model = $model;
                            break;
                        }
            }
            else
                $car->model = ucwords(strtolower($data['model']));

            $car->make = (is_numeric($data['make']) ? $makeOptions[$data['make']] : ucwords(strtolower($data['make'])));
            $car->year = $data['year'];
            $car->color = $data['color'];
            $car->seats = $data['seats'];
            $car->fuel = (is_numeric($data['fuel']) ? $fuelOptions[$data['fuel']] : ucwords(strtolower($data['fuel'])));
            $car->odometer = $data['odometer'];
            $car->transmission = $data['transmission'];
            $car->type = (is_numeric($data['type']) ? $typeOptions[$data['type']] : ucwords(strtolower($data['type'])));
            $car->measures = $data['length'].' mm x '.$data['width'].' mm x '.$data['height'].' mm';
            $car->available = false;

            if ($this->Cars->save($car)) {
                $conn = ConnectionManager::get('default');
                $stmt = $conn->prepare('SELECT * FROM cars WHERE id = (SELECT MAX(id) FROM cars);');
                $stmt->execute();
                $result = $stmt->fetchAll('assoc');
                $recordID = $result[0]['id'];

                $this->Flash->success(__('The car has been added to your business. Keep going to complete its information.'));
                return $this->redirect(['action' => 'addSecond', $recordID]);
            }
            $this->Flash->error(__('The car could not be saved. Please, try again.'));
        }

        $this->set(compact('car', 'admin', 'makeOptions', 'typeOptions', 'fuelOptions', 'modelOptions'));
        $this->set('_serialize', ['car']);
    }

    public function addSecond($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();

        $engine = $this->Cars->find('all')->select(['enginetype'])->distinct(['enginetype'])->orderAsc('enginetype')->toArray();
        $engineOptions = array();
        for ($i = 0; $i < count($engine); $i++) {
            if ($this->isNullOrEmpty($engine[$i]['enginetype']))
                continue;

            $engineOptions[$i] = $engine[$i]['enginetype'];
        }

        $inducts = $this->Cars->find('all')->select(['induction'])->distinct(['induction'])->orderAsc('induction')->toArray();
        $inductOptions = array();
        for ($i = 0; $i < count($inducts); $i++) {
            if ($this->isNullOrEmpty($inducts[$i]['induction']))
                continue;

            $inductOptions[$i] = $inducts[$i]['induction'];
        }

        $geartypes = $this->Cars->find('all')->select(['geartype'])->distinct(['geartype'])->orderAsc('geartype')->toArray();
        $gearOptions = array();
        for ($i = 0; $i < count($geartypes); $i++) {
            if ($this->isNullOrEmpty($geartypes[$i]['geartype']))
                continue;

            $gearOptions[$i] = $geartypes[$i]['geartype'];
        }

        $drivetypes = $this->Cars->find('all')->select(['drivetype'])->distinct(['drivetype'])->orderAsc('drivetype')->toArray();
        $driveOptions = array();
        for ($i = 0; $i < count($drivetypes); $i++) {
            if ($this->isNullOrEmpty($drivetypes[$i]['drivetype']))
                continue;

            $driveOptions[$i] = $drivetypes[$i]['drivetype'];
        }

        $car = $this->Cars->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->drivetype = $driveOptions[$data['drivetype']];
            $car->enginetype = (is_numeric($data['enginetype']) ? $engineOptions[$data['enginetype']] : ucwords(strtolower($data['enginetype'])));
            $car->enginesize = $data['enginesize'];
            $car->cylinder = $data['cylinder'];
            $car->induction = (is_numeric($data['induction']) ? $inductOptions[$data['induction']] : ucwords(strtolower($data['induction'])));
            $car->power = $data['watt'].'kW @ '.$data['round'].'rpm';
            $car->gear = $data['gear'];
            $car->geartype = (is_numeric($data['geartype']) ? $gearOptions[$data['geartype']] : ucwords(strtolower($data['geartype'])));
            $car->fuelcap = $data['fuelcap'];
            $car->fuelconsume = $data['average'].' - '.$data['urbane'].' - '.$data['rural'];

            if ($this->Cars->save($car)) {
                $conn = ConnectionManager::get('default');
                $stmt = $conn->prepare('SELECT * FROM cars WHERE id = (SELECT MAX(id) FROM cars);');
                $stmt->execute();
                $result = $stmt->fetchAll('assoc');
                $recordID = $result[0]['id'];

                $this->Flash->success(__('The information has been updated for new car. Keep going to complete its information.'));
                return $this->redirect(['action' => 'addThird', $recordID]);
            }
            $this->Flash->error(__('The information could not be saved at the moment. Please, try again.'));
        }

        $this->set(compact('car', 'admin', 'inductOptions', 'engineOptions', 'driveOptions', 'gearOptions', 'drivetypes'));
    }

    private function isNullOrEmpty($var = null) {
        return (!isset($var) || trim($var) === '');
    }

    public function addThird($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $car = $this->Cars->get($id);

        $audios = $this->Cars->find('all')->select(['audiodesc'])->toArray();
        $audioOptions = array();
        foreach ($audios as $audio) {
            $tokens = explode(', ', $audio['audiodesc']);
            foreach ($tokens as $token) {
                if (in_array($token, $audioOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($audioOptions, $token);
            }
        }

        $conveniences = $this->Cars->find('all')->select(['convenience'])->toArray();
        $convOptions = array();
        foreach ($conveniences as $convenience) {
            $tokens = explode(', ', $convenience['convenience']);
            foreach ($tokens as $token) {
                if (in_array($token, $convOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($convOptions, $token);
            }
        }

        $safeties = $this->Cars->find('all')->select(['safety'])->toArray();
        $safetyOptions = array();
        foreach ($safeties as $safety) {
            $tokens = explode(', ', $safety['safety']);
            foreach ($tokens as $token) {
                if (in_array($token, $safetyOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($safetyOptions, $token);
            }
        }

        $livis = $this->Cars->find('all')->select(['lightsview'])->toArray();
        $liviOptions = array();
        foreach ($livis as $livi) {
            $tokens = explode(', ', $livi['lightsview']);
            foreach ($tokens as $token) {
                if (in_array($token, $liviOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($liviOptions, $token);
            }
        }

        $others = $this->Cars->find('all')->select(['otherspecs'])->toArray();
        $otherOptions = array();
        foreach ($others as $other) {
            $tokens = explode(', ', $other['otherspecs']);
            foreach ($tokens as $token) {
                if (in_array($token, $otherOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($otherOptions, $token);
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->audiodesc = $data['audiodesc'];
            $car->convenience = $data['convenience'];
            $car->safety = $data['safety'];
            $car->lightsview = $data['lightsview'];
            $car->otherspecs = $data['otherspecs'];

            if ($this->Cars->save($car)) {
                $conn = ConnectionManager::get('default');
                $stmt = $conn->prepare('SELECT * FROM cars WHERE id = (SELECT MAX(id) FROM cars);');
                $stmt->execute();
                $result = $stmt->fetchAll('assoc');
                $recordID = $result[0]['id'];

                $this->Flash->success(__('The information has been updated for new car. Keep going to complete its information.'));
                return $this->redirect(['action' => 'addFourth', $recordID]);
            }
            $this->Flash->error(__('The information could not be saved at the moment. Please, try again.'));
        }

        $this->set(compact('car', 'admin', 'convOptions', 'audioOptions', 'safetyOptions', 'liviOptions', 'otherOptions'));
    }

    public function addFourth($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $car = $this->Cars->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->shortdesc = $data['shortdesc'];
            $car->description = $data['description'];

            if ($this->Cars->save($car)) {
                $conn = ConnectionManager::get('default');
                $stmt = $conn->prepare('SELECT * FROM cars WHERE id = (SELECT MAX(id) FROM cars);');
                $stmt->execute();
                $result = $stmt->fetchAll('assoc');
                $recordID = $result[0]['id'];

                $this->Flash->success(__('The information has been updated for new car. Keep going to complete its information.'));
                return $this->redirect(['action' => 'addLast', $recordID]);
            }
            $this->Flash->error(__('The information could not be saved at the moment. Please, try again.'));
        }

        $this->set(compact('car', 'admin'));
    }

    public function addLast($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $car = $this->Cars->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->ddprice = $data['ddprice'];
            $car->kmprice = $data['kmprice'];
            $car->parking = $data['parking'];
            $car->slot = $data['slot'];
            $car->latitude = $data['latitude'];
            $car->longitude = $data['longitude'];

            if ($this->Cars->save($car)) {
                $this->Flash->success(__('The car has been added into your business. You can find it here.'));
                return $this->redirect(['action' => 'revive']);
            }
            else
                $this->Flash->error(__('The car could not be saved at the moment. Please try again later.'));
        }

        $this->set(compact('car', 'admin'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Car id.
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

        $car = $this->Cars->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $car = $this->Cars->patchEntity($car, $this->request->getData());
            if ($this->Cars->save($car)) {
                $this->Flash->success(__('The car has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The car could not be saved. Please, try again.'));
        }

        $this->set(compact('car'));
        $this->set('_serialize', ['car']);
    }


    public function editFirst($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();

        $makes = $this->Cars->find('all')->select(['make'])->distinct(['make'])->orderAsc('make')->toArray();
        $makeOptions = array();
        for ($i = 0; $i < count($makes); $i++) {
            if ($this->isNullOrEmpty($makes[$i]['make']))
                continue;

            $makeOptions[$i] = $makes[$i]['make'];
        }

        $types = $this->Cars->find('all')->select(['type'])->distinct(['type'])->orderAsc('type')->toArray();
        $typeOptions = array();
        for ($i = 0; $i < count($types); $i++) {
            if ($this->isNullOrEmpty($types[$i]['type']))
                continue;

            $typeOptions[$i] = $types[$i]['type'];
        }

        $fuels = $this->Cars->find('all')->select(['fuel'])->distinct(['fuel'])->orderAsc('fuel')->toArray();
        $fuelOptions = array();
        for ($i = 0; $i < count($fuels); $i++) {
            if ($this->isNullOrEmpty($fuels[$i]['fuel']))
                continue;

            $fuelOptions[$i] = $fuels[$i]['fuel'];
        }

        $models = $this->Cars->find('all')->select(['make', 'model'])->distinct(['model'])->toArray();
        $modelOptions = array();
        foreach ($makes as $make) {
            $modelOptions[$make['make']] = array();
            $i = 0;
            foreach ($models as $model) {
                if ($make['make'] == $model['make'])
                    $modelOptions[$make['make']][$i] = $model['model'];

                $i++;
            }
        }

        $car = $this->Cars->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (is_numeric($data['model'])) {
                foreach ($modelOptions as $k => $models)
                    foreach ($models as $key => $model)
                        if ($data['model'] == $key) {
                            $car->model = $model;
                            break;
                        }
            }
            else
                $car->model = ucwords(strtolower($data['model']));

            $car->make = (is_numeric($data['make']) ? $makeOptions[$data['make']] : ucwords(strtolower($data['make'])));
            $car->year = $data['year'];
            $car->color = $data['color'];
            $car->seats = $data['seats'];
            $car->fuel = (is_numeric($data['fuel']) ? $fuelOptions[$data['fuel']] : ucwords(strtolower($data['fuel'])));
            $car->odometer = $data['odometer'];
            $car->transmission = $data['transmission'];
            $car->type = (is_numeric($data['type']) ? $typeOptions[$data['type']] : ucwords(strtolower($data['type'])));
            $car->measures = $data['length'].' mm x '.$data['width'].' mm x '.$data['height'].' mm';
            $car->available = false;

            if ($this->Cars->save($car)) {
                $this->Flash->success(__('Information have been updated (if any). Keep going to complete its information.'));
                return $this->redirect(['action' => 'editSecond', $car->id]);
            }
            $this->Flash->error(__('The car could not be saved. Please, try again.'));
        }

        $this->set(compact('car', 'admin', 'makeOptions', 'typeOptions', 'fuelOptions', 'modelOptions'));
        $this->set('_serialize', ['car']);
    }

    public function editSecond($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();

        $engine = $this->Cars->find('all')->select(['enginetype'])->distinct(['enginetype'])->orderAsc('enginetype')->toArray();
        $engineOptions = array();
        for ($i = 0; $i < count($engine); $i++) {
            if ($this->isNullOrEmpty($engine[$i]['enginetype']))
                continue;

            $engineOptions[$i] = $engine[$i]['enginetype'];
        }

        $inducts = $this->Cars->find('all')->select(['induction'])->distinct(['induction'])->orderAsc('induction')->toArray();
        $inductOptions = array();
        for ($i = 0; $i < count($inducts); $i++) {
            if ($this->isNullOrEmpty($inducts[$i]['induction']))
                continue;

            $inductOptions[$i] = $inducts[$i]['induction'];
        }

        $geartypes = $this->Cars->find('all')->select(['geartype'])->distinct(['geartype'])->orderAsc('geartype')->toArray();
        $gearOptions = array();
        for ($i = 0; $i < count($geartypes); $i++) {
            if ($this->isNullOrEmpty($geartypes[$i]['geartype']))
                continue;

            $gearOptions[$i] = $geartypes[$i]['geartype'];
        }

        $drivetypes = $this->Cars->find('all')->select(['drivetype'])->distinct(['drivetype'])->orderAsc('drivetype')->toArray();
        $driveOptions = array();
        for ($i = 0; $i < count($drivetypes); $i++) {
            if ($this->isNullOrEmpty($drivetypes[$i]['drivetype']))
                continue;

            $driveOptions[$i] = $drivetypes[$i]['drivetype'];
        }

        $car = $this->Cars->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->drivetype = $driveOptions[$data['drivetype']];
            $car->enginetype = (is_numeric($data['enginetype']) ? $engineOptions[$data['enginetype']] : ucwords(strtolower($data['enginetype'])));
            $car->enginesize = $data['enginesize'];
            $car->cylinder = $data['cylinder'];
            $car->induction = (is_numeric($data['induction']) ? $inductOptions[$data['induction']] : ucwords(strtolower($data['induction'])));
            $car->power = $data['watt'].'kW @ '.$data['round'].'rpm';
            $car->gear = $data['gear'];
            $car->geartype = (is_numeric($data['geartype']) ? $gearOptions[$data['geartype']] : ucwords(strtolower($data['geartype'])));
            $car->fuelcap = $data['fuelcap'];
            $car->fuelconsume = $data['average'].' - '.$data['urbane'].' - '.$data['rural'];

            if ($this->Cars->save($car)) {
                $this->Flash->success(__('Information have been updated (if any). Keep going to complete its information.'));
                return $this->redirect(['action' => 'editThird', $car->id]);
            }
            $this->Flash->error(__('The information could not be saved at the moment. Please, try again.'));
        }

        $this->set(compact('car', 'admin', 'inductOptions', 'engineOptions', 'driveOptions', 'gearOptions', 'drivetypes'));
    }

    public function editThird($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $car = $this->Cars->get($id);

        $audios = $this->Cars->find('all')->select(['audiodesc'])->toArray();
        $audioOptions = array();
        foreach ($audios as $audio) {
            $tokens = explode(', ', $audio['audiodesc']);
            foreach ($tokens as $token) {
                if (in_array($token, $audioOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($audioOptions, $token);
            }
        }

        $conveniences = $this->Cars->find('all')->select(['convenience'])->toArray();
        $convOptions = array();
        foreach ($conveniences as $convenience) {
            $tokens = explode(', ', $convenience['convenience']);
            foreach ($tokens as $token) {
                if (in_array($token, $convOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($convOptions, $token);
            }
        }

        $safeties = $this->Cars->find('all')->select(['safety'])->toArray();
        $safetyOptions = array();
        foreach ($safeties as $safety) {
            $tokens = explode(', ', $safety['safety']);
            foreach ($tokens as $token) {
                if (in_array($token, $safetyOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($safetyOptions, $token);
            }
        }

        $livis = $this->Cars->find('all')->select(['lightsview'])->toArray();
        $liviOptions = array();
        foreach ($livis as $livi) {
            $tokens = explode(', ', $livi['lightsview']);
            foreach ($tokens as $token) {
                if (in_array($token, $liviOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($liviOptions, $token);
            }
        }

        $others = $this->Cars->find('all')->select(['otherspecs'])->toArray();
        $otherOptions = array();
        foreach ($others as $other) {
            $tokens = explode(', ', $other['otherspecs']);
            foreach ($tokens as $token) {
                if (in_array($token, $otherOptions) || $this->isNullOrEmpty($token))
                    continue;

                array_push($otherOptions, $token);
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->audiodesc = $data['audiodesc'];
            $car->convenience = $data['convenience'];
            $car->safety = $data['safety'];
            $car->lightsview = $data['lightsview'];
            $car->otherspecs = $data['otherspecs'];

            if ($this->Cars->save($car)) {
                $this->Flash->success(__('Information have been updated (if any). Keep going to complete its information.'));
                return $this->redirect(['action' => 'editFourth', $car->id]);
            }
            $this->Flash->error(__('The information could not be saved at the moment. Please, try again.'));
        }

        $this->set(compact('car', 'admin', 'convOptions', 'audioOptions', 'safetyOptions', 'liviOptions', 'otherOptions'));
    }

    public function editFourth($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $car = $this->Cars->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->shortdesc = $data['shortdesc'];
            $car->description = $data['description'];

            if ($this->Cars->save($car)) {
                $this->Flash->success(__('Information have been updated (if any). Keep going to complete its information.'));
                return $this->redirect(['action' => 'editLast', $car->id]);
            }
            $this->Flash->error(__('The information could not be saved at the moment. Please, try again.'));
        }

        $this->set(compact('car', 'admin'));
    }

    public function editLast($id = null) {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $car = $this->Cars->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $car->ddprice = $data['ddprice'];
            $car->kmprice = $data['kmprice'];
            $car->parking = $data['parking'];
            $car->slot = $data['slot'];
            $car->latitude = $data['latitude'];
            $car->longitude = $data['longitude'];

            if ($this->Cars->save($car)) {
                $this->Flash->success(__('You have completed updating car information. You can find it here.'));
                return $this->redirect(['action' => 'revive']);
            }
            else
                $this->Flash->error(__('The car could not be saved at the moment. Please try again later.'));
        }

        $this->set(compact('car', 'admin'));
    }

    public function revision() {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $id = $this->request->query('carid');
        $from = $this->request->query('from');

        $car = $this->Cars->get($id);
        $car->available = ($from == 'carreview' ? '1' : '0');

        if ($this->Cars->save($car))
            $this->Flash->success(__('The car has been unavailable for further business activities.'));
        else
            $this->Flash->error(__('Unable to remove the car right now. Please try again later.'));

        if ($from == 'carreview')
            $this->redirect(['action' => 'review']);
        else
            $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Car id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $car = $this->Cars->get($id);
        if ($this->Cars->delete($car))
            $this->Flash->success(__('The car has been deleted.'));
        else
            $this->Flash->error(__('The car could not be deleted. Please, try again.'));

        return $this->redirect(['action' => 'index']);
    }

    public function search() {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if ($data['carid']) {
                $car = null;
                try {
                    $car = $this->Cars->get(intval($data['carid']));

                    if ($car) {
                        $this->Flash->success(__('The search was completed with 1 result. This is the car by the searched ID.'));
                        return $this->redirect(['action' => 'view', '?' => ['carid' => $car['id'], 'from' => 'adminview']]);
                    }
                } catch (RecordNotFoundException $ex) {
                    $this->Flash->warning(__('The search was not completed. No records found with the searched ID/Keyword.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            else {
                $keywords = explode(' ', strtolower($data['carword']));

                $allCars = $this->Cars->find('all')->toArray();
                $cars = $this->refineCars($allCars, $keywords);

                if (!empty($cars)) {
                    $images = $this->searchImages($cars);

                    $this->Flash->success(__('The search was completed with '.count($cars).' results.'));
                    $this->set(compact('cars', 'images'));
                    $this->set('_serialize', ['cars']);
                }
                else {
                    $this->Flash->warning(__('The search was not completed. No records found with the searched keywords.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
        }
    }

    public function refineCars($cars = null, $keywords = null) {
        $searches = array();
        if ($cars && $keywords) {
            foreach ($cars as $car) {
                if (in_array(strtolower($car['make']), $keywords) || in_array(strtolower($car['model']), $keywords) ||
                    in_array(strtolower($car['year']), $keywords) || in_array(strtolower($car['type']), $keywords))
                    array_push($searches, $car);
                else {
                    foreach ($keywords as $keyword)
                        if (strpos(strtolower($car['make']), $keyword) != false || strpos(strtolower($car['model']), $keyword) != false ||
                            strpos(strtolower($car['type']), $keyword) != false)
                            array_push($searches, $car);
                }
            }
        }

        return $searches;
    }
}
