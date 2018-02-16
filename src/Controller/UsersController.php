<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout']);
    }

    public function login()
    {
        $from = $this->request->query('from');

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $session = $this->request->session();
                $mcCount = 0;

                if ($user['role'] == 'customer') {
                    $customer = $this->Users->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
                    $rentals = $this->Users->Customers->Rentals->find('all', ['conditions' => ['customer_id' => $customer->id, 'paid' => 0, 'done' => 0]])->count();

                    $messages = TableRegistry::get('Messages')->find('all', ['conditions' => ['customer_id' => $customer->id, 'new' => 1]])->toArray();
                    foreach ($messages as $message) {
                        $needleCount = substr_count($message, '##reply##');
                        if ($needleCount % 2 != 0)
                            $mcCount++;
                    }

                    $session->write('Rentals.count', $rentals);
                }
                else {
                    $messages = TableRegistry::get('Messages')->find('all', ['conditions' => ['new' => 1]])->toArray();
                    foreach ($messages as $message) {
                        $needleCount = substr_count($message, '##reply##');
                        if ($needleCount % 2 == 0)
                            $mcCount++;
                    }
                }

                $session->write('Messages.count', $mcCount);

                if ($user['role'] && $user['role'] === 'customer') {
                    $this->Flash->success(__('Congrat! You have logged in successfully.'));

                    if ($from)
                        return $this->redirect(['controller' => 'Rentals', 'action' => 'returnCar']);

                    return $this->redirect(['controller' => 'Customers', 'action' => 'dashboard']);
                }
                else
                    return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password. Please try again.'));
        }
    }

    public function logout()
    {
        $session = $this->request->session();
        $session->destroy();

        return $this->redirect($this->Auth->logout());
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);
        $current_user = $this->Auth->user();

        $this->set(compact('users','current_user'));
        $this->set('_serialize', ['users','current_user']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $id = $this->Auth->user('id');
        $user = $this->Users->get($id);

        $admin = TableRegistry::get('Customers')->find('all', ['conditions' => ['user_id' => $user->id]])->first();

        $requests = TableRegistry::get('Requests')->find('all', ['conditions' => ['status' => 1]])->toArray();
        $customersByRequest = array();
        $rentalsByRequest = array();
        $carsByRequest = array();

        foreach ($requests as $request) {
            $aCustomer = TableRegistry::get('Customers')->get($request['customer_id']);
            $customersByRequest[$request['id']] = $aCustomer;

            $aRental = TableRegistry::get('Rentals')->get($request['rental_id']);
            $rentalsByRequest[$request['id']] = $aRental;

            $aCar = TableRegistry::get('Cars')->get($aRental['car_id']);
            $carsByRequest[$request['id']] = $aCar;
        }

        $this->set(compact('user', 'admin', 'requests', 'customersByRequest', 'rentalsByRequest', 'carsByRequest'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();

        if ($this->request->is('Post')) {
            $signupData = $this->request->getData();

            $specialChars = ['*', '^', '$', '&', '%', '#', '@@', '@@@', '@@@@', '!', '(', ')', '+', '=', '{', '}', '[', ']', '|', ':', ';', '?', '<', '>', '~'];
            $specialCheck = false;
            for ($i = 0; $i < count($specialChars); $i++) {
                if (strpos($signupData['email'], $specialChars[$i]) != false)
                    break;

                if ($i == count($specialChars) - 1 && strpos($signupData['email'], $specialChars[$i]) == false)
                    $specialCheck = true;
            }

            if (!$specialCheck)
                $this->Flash->warning(__('Email contains special characters or was mistyped.'));
            else {
                $domainCheck = false;
                if (strpos($signupData['email'], '.com') !== false)
                    $domainCheck = true;

                if (!$domainCheck)
                    $this->Flash->Warning(__('Email domain may contain mistake.'));
                else {
                    if (!preg_match('/[^A-Za-z ]/', $signupData['name'])) {
                        if (ctype_digit($signupData['phone'])) {
                            $userData = array();
                            $userData['username'] = $signupData['username'];
                            $userData['password'] = $signupData['password'];
                            $userData['resetcode'] = null;

                            $userData['role'] = ($signupData['admin'] ? 'admin' : 'customer');

                            $user = $this->Users->patchEntity($user, $userData);
                            if ($this->Users->save($user)) {
                                $conn = ConnectionManager::get('default');
                                $stmt = $conn->prepare('SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users);');
                                $stmt->execute();
                                $result = $stmt->fetchAll('assoc');
                                $recordID = $result[0]['id'];

                                $customerData = array();
                                $customerData['user_id'] = $recordID;
                                $customerData['name'] = ucwords(strtolower($signupData['name']));
                                $customerData['email'] = strtolower($signupData['email']);
                                $customerData['phone'] = $signupData['phone'];
                                $customerData['address'] = ucwords(strtolower($signupData['address']));

                                $customerData['paypal'] = ($signupData['admin'] ? strtoupper($signupData['staffid']) : null);

                                $customer = $this->Users->Customers->newEntity();
                                $customer = $this->Users->Customers->patchEntity($customer, $customerData);

                                if ($this->Users->Customers->save($customer)) {
                                    $this->Flash->success(__('Congrat! You have successfully registered an account. Log-in now!'));
                                    return $this->redirect(['action' => 'login']);
                                } else
                                    $this->Flash->error(__('Unable to process data. Please try again later!'));
                            }
                            else
                                $this->Flash->warning(__('Username already taken. Please choose another!'));
                        }
                        else
                            $this->Flash->warning(__('Phone number should contain digits only.'));
                    }
                    else
                        $this->Flash->warning(__('Name was mistyped. Alphabets only!'));
                }
            }
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
