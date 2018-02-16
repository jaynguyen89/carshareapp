<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Customer;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 *
 * @method \App\Model\Entity\Message[] paginate($object = null, array $settings = [])
 */
class MessagesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'edit', 'delete', 'undoReply']);
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

        $customer = $this->Messages->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        $customersByMessage = array();

        if ($user['role'] == 'customer')
            $messages = $this->Messages->find('all', ['conditions' => ['customer_id' => $customer['id']]])->toArray();
        else {
            $messages = $this->Messages->find('all', ['conditions' => ['new' => 1]])->toArray();
            foreach ($messages as $message)
                if ($message['title'] == 'customer') {
                    $aCustomer = $this->Messages->Customers->get($message['customer_id']);
                    $customersByMessage[$message['id']] = $aCustomer;
                }
                else
                    $customersByMessage[$message['id']] = null;
        }

        if ($user['role'] == 'admin')
            $this->set(compact('customersByMessage'));

        $this->set(compact('messages', 'customer'));
        $this->set('_serialize', ['messages']);
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $message = $this->Messages->get($id, [
            'contain' => ['Customers']
        ]);

        $this->set('message', $message);
        $this->set('_serialize', ['message']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->request->session()->read('Auth.User');

        $message = $this->Messages->newEntity();
        if ($user) {
            $customer = $this->Messages->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
            $message->customer_id = $customer['id'];
            $message->title = 'customer';
        }
        else
            $message->customer_id = 1;

        $message->new = 1;
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $message->title = ($user) ? $message->title : $data['contactemail'].' - '.$data['contactname'];
            $message->content = $data['contactmessage'];

            date_default_timezone_set('Australia/Melbourne');
            $message->created = date('Y-m-d H:i:s', time());

            if ($this->Messages->save($message)) {
                $this->Flash->success(__('Your message has been sent successfully.'));
                return $this->redirect('/');
            }
            else
                $this->Flash->error(__('Your message was unable to send due to an unknown error. Please try again.'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Message id.
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

        $message = $this->Messages->get($id);
        $customer = $this->Messages->Customers->find('all', ['conditions' => ['user_id' => $user['id']]])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if ($data['repmsg']) {
                $message->content = $message->content.'##reply##'.$data['repmsg'];

                date_default_timezone_set('Australia/Melbourne');
                $message->created = date('Y-m-d H:i:s', time());

                if ($this->Messages->save($message)) {
                    $this->Flash->success(__('Your message has been sent successfully.'));

                    $mcount = $this->request->session()->read('Messages.count');
                    $this->request->session()->write('Messages.count', $mcount - 1);

                    return $this->redirect(['action' => 'index']);
                }
                else
                    $this->Flash->error(__('Unable to send message due to an unknown server error. Please try again.'));
            }
            else
                $this->Flash->warning(__('Message content is empty! Nothing to send.'));
        }

        $this->set(compact('message', 'customer'));
        $this->set('_serialize', ['message']);
    }

    public function ignore($id = null) {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $message = $this->Messages->get($id);
        $message->new = 0;

        date_default_timezone_set('Australia/Melbourne');
        $message->created = date('Y-m-d H:i:s', time());

        if ($this->Messages->save($message)) {
            $mcount = $this->request->session()->read('Messages.count');
            $this->request->session()->write('Messages.count', $mcount - 1);

            $this->Flash->success(__('The message has been ignored.'));
        }
        else
            $this->Flash->error(__('The message could not be saved. Please, try again.'));

        return $this->redirect(['action' => 'index']);
    }

    public function undoReply($id = null) {
        $this->autoRender = false;

        $user = $this->request->session()->read('Auth.User');
        if (!$user) {
            $this->Flash->warning(__('Your session has expired. Please login again.'));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }

        $message = $this->Messages->get($id);
        $tokens = explode('##reply##', $message['content']);

        unset($tokens[count($tokens) - 1]);
        $oldContent = implode('##reply##', $tokens);

        $message->content = $oldContent;
        date_default_timezone_set('Australia/Melbourne');
        $message->created = date('Y-m-d H:i:s', time());

        if ($this->Messages->save($message)) {
            $mcount = $this->request->session()->read('Messages.count');
            $this->request->session()->write('Messages.count', $mcount + 1);

            $this->Flash->success(__('Your latest reply has been removed from history.'));
        }
        else
            $this->Flash->error(__('Unable to undo due to an unknown server error. Please try again.'));

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Message id.
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
        $message = $this->Messages->get($id);
        if ($this->Messages->delete($message)) {
            $mcount = $this->request->session()->read('Messages.count');
            $this->request->session()->write('Messages.count', $mcount - 1);

            $this->Flash->success(__('The message has been deleted.'));
        } else
            $this->Flash->error(__('The message could not be deleted. Please, try again.'));

        return $this->redirect(['action' => 'index']);
    }
}
