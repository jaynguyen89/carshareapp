<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Browsings Controller
 *
 * @property \App\Model\Table\BrowsingsTable $Browsings
 *
 * @method \App\Model\Entity\Browsing[] paginate($object = null, array $settings = [])
 */
class BrowsingsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'edit']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Cars']
        ];
        $browsings = $this->paginate($this->Browsings);

        $this->set(compact('browsings'));
        $this->set('_serialize', ['browsings']);
    }

    /**
     * View method
     *
     * @param string|null $id Browsing id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $browsing = $this->Browsings->get($id, [
            'contain' => ['Customers', 'Cars']
        ]);

        $this->set('browsing', $browsing);
        $this->set('_serialize', ['browsing']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $customer = $this->Browsings->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $browsing = $this->Browsings->find('all', ['conditions' => ['customer_id' => $customer->id, 'car_id' => $id]])->first();

        if (!$browsing) {
            $browsing = $this->Browsings->newEntity();
            $browsing->customer_id = $customer->id;
            $browsing->car_id = $id;
            $browsing->watched = 1;
        }
        else
            $browsing['watched'] = 1;

        if ($this->Browsings->save($browsing))
            $this->Flash->success(__('The car has been added to your watched list.'));
        else
            $this->Flash->warning(__('Watched item was unable to add due to an unknown error. Please try again.'));

        $this->redirect(['controller' => 'Cars', 'action' => 'view', $id]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Browsing id.
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

        $browsing = $this->Browsings->get($id);
        $browsing->watched = 0;

        if ($this->Browsings->save($browsing))
            $this->Flash->success(__('Your watched item has been removed successfully.'));
        else
            $this->Flash->error(__('Your watched item could not be removed due to an unknown error. Please try again.'));

        return $this->redirect(['controller' => 'Customers', 'action' => 'dashboard']);

        /*if ($this->request->is(['patch', 'post', 'put'])) {
            $browsing = $this->Browsings->patchEntity($browsing, $this->request->getData());
            if ($this->Browsings->save($browsing)) {
                $this->Flash->success(__('The browsing has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The browsing could not be saved. Please, try again.'));
        }
        $customers = $this->Browsings->Customers->find('list', ['limit' => 200]);
        $cars = $this->Browsings->Cars->find('list', ['limit' => 200]);
        $this->set(compact('browsing', 'customers', 'cars'));
        $this->set('_serialize', ['browsing']);*/
    }

    /**
     * Delete method
     *
     * @param string|null $id Browsing id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $browsing = $this->Browsings->get($id);
        if ($this->Browsings->delete($browsing))
            $this->Flash->success(__('Your watched item has been removed successfully.'));
        else
            $this->Flash->error(__('Your watched item could not be removed due to an unknown error. Please try again.'));

        return $this->redirect(['controller' => 'Customers', 'action' => 'dashboard']);
    }
}
