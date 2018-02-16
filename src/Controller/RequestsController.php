<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * Requests Controller
 *
 * @property \App\Model\Table\RequestsTable $Requests
 *
 * @method \App\Model\Entity\Request[] paginate($object = null, array $settings = [])
 */
class RequestsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'delete', 'edit']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Rentals']
        ];
        $requests = $this->paginate($this->Requests);

        $this->set(compact('requests'));
        $this->set('_serialize', ['requests']);
    }

    /**
     * View method
     *
     * @param string|null $id Request id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request = $this->Requests->get($id, [
            'contain' => ['Customers', 'Rentals']
        ]);

        $this->set('request', $request);
        $this->set('_serialize', ['request']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Requests->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $rental = $this->Requests->Rentals->get($id);

        $car = $this->Requests->Rentals->Cars->get($rental['car_id']);
        $request = $this->Requests->newEntity();

        $process = true;
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $requestData = array();

            $requestData['customer_id'] = $customer['id'];
            $requestData['rental_id'] = $rental['id'];
            $requestData['type'] = 'Sent - '.$data['title'];
            $requestData['content'] = $data['content'];
            $requestData['status'] = 1;

            $newFileName = '';
            $ext = '';
            if ($data['file']) {
                if ($this->request->data['file']['size'] < 2000000) {
                    $fileName = $data['file'];
                    $ext = substr(strtolower(strrchr($fileName, '.')), 1);

                    $newFileName = time() . "_" . rand(000000, 999999);
                    $requestData['note'] = $newFileName.'.'.$ext;
                }
                else {
                    $process = false;
                    $this->Flash->error(__('File size exceeds the limit! Please choose another file.'));
                }
            }

            if ($process) {
                date_default_timezone_set('Australia/Melbourne');
                $requestData['created'] = date('Y-m-d H:i:s', time());
                $requestData['modified'] = date('Y-m-d H:i:s', time());

                $request = $this->Requests->patchEntity($request, $requestData);
                if ($this->Requests->save($request)) {
                    if ($data['file']) {
                        move_uploaded_file($this->request->data['file']['tmp_name'], WWW_ROOT . 'file/' . $newFileName . '.' . $ext);
                        chmod(WWW_ROOT . 'file/' . $newFileName . '.' . $ext, 0755);
                    }

                    $this->Flash->success(__('Your request has been sent. On average a request will be resolved within 24 hours.'));
                    return $this->redirect(['controller' => 'customers', 'action' => 'dashboard']);
                }
                $this->Flash->error(__('Unable to send your request due to server error. Please try again later.'));
            }
        }

        $this->set(compact('request', 'customer', 'rental', 'car', 'data'));
        $this->set('_serialize', ['request']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Request id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $request = $this->Requests->get($id);
        $request->status = 0;

        $toks = explode(' - ', $request->type);
        $request->type = 'Replied - '.$toks[1];

        date_default_timezone_set('Australia/Melbourne');
        $request->modified = date('Y-m-d H:i:s', time());

        if ($this->Requests->save($request))
            $this->Flash->success(__('The request has been ignored.'));
        else
            $this->Flash->error(__('The request could not be saved. Please, try again.'));

        return $this->redirect(['controller' => 'Users', 'action' => 'view']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Request id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $request = $this->Requests->get($id);
        if ($this->Requests->delete($request)) {
            $this->Flash->success(__('The request has been deleted.'));
        } else
            $this->Flash->error(__('The request could not be deleted. Please, try again.'));

        return $this->redirect(['controller' => 'customers', 'action' => 'dashboard']);
    }
}
