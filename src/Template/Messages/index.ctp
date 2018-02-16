<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $messages
  */
$user = $this->request->session()->read('Auth.User');
?>
<?= $this->Html->css('style.css') ?>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Mail Box</b></h4></div>
    <div class="card">
        <div class="header bg-indigo darken-4">
            <h2><?= $user['role'] == 'admin' ? 'Admin:' : 'Customer:'; ?> <?= $customer['name']; ?>
                <small style="color: #aeea00 !important;">Feel free to send us a message at any times</small>
            </h2>
        </div>
        <div class="body">
            <?php if (!$messages) { ?>
                <div class="header center-align">
                    <p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> You haven't sent us any messages yet.</p>
                </div>
            <?php } else { ?>
                <div class="row" style="margin: 0;">
                    <div class="table-responsive" style="width: 100%;">
                        <table class="table">
                            <tr><th scope="col">#</th><?= ($user['role'] == 'admin') ? '<th scope="col">Sender</th>' : '' ;?><th scope="col">Content</th><th scope="col">Sent</th><?= ($user['role'] == 'customer') ? '<th scope="col">Status</th>' : '' ;?><th scope="col">Action</th></tr>
                            <?php $i = 1; foreach($messages as $message) { ?>
                            <tr><td><?= $i; ?></td>
                                <?php if ($user['role'] == 'admin') { ?>
                                <td><?php if ($customersByMessage[$message['id']])
                                    echo $this->Html->link($customersByMessage[$message['id']]['name'], ['controller' => 'Customers', 'action' => 'view', $customersByMessage[$message['id']]['id']]);
                                    else { $toks = explode(' - ', $message['title']); $name = $toks[1]; $email = $toks[0];
                                            echo '<p style="margin: 0;">'.$name.'</p><p style="margin: 0;">'.$email.'</p>'; } ?></td><?php } ?>
                                <td><?php $tokens = explode('##reply##', $message['content']); $markup = '';
                                    for ($j = 0; $j < count($tokens); $j++) {
                                        $markup = ($j % 2 == 0) ? '<b>C:</b> ' : '<b>A:</b> ';

                                        if ($j == 0)
                                            echo $markup.$tokens[$j].'<br>';
                                        else echo str_repeat('&nbsp;', $j*3).$markup.$tokens[$j].'<br>';
                                    } ?></td>
                                <td><?= ((new DateTime($message['created']))->format('d/m/Y H:i')); ?></td>
                                <?php if ($user['role'] == 'customer') { ?><td><?= ($message['new']) ? 'Opened' : 'Closed'; ?></td><?php } ?>
                                <?php if ($user['role'] == 'admin') { ?>
                                <td><?= ($markup == '<b>A:</b> ') ? $this->Html->link(__('Undo Reply'), ['action' => 'undoReply', $message['id']]) : $this->Html->link(__('Reply'), ['action' => 'edit', $message['id']]); ?>
                                    <?= ($markup == '<b>A:</b> ') ? '' : $this->Form->postLink(__('Ignore'), ['action' => 'ignore', $message['id']], ['confirm' => __('Message #{0} will be ignored. Are you sure?', $i), 'class' => 'text-danger']); ?></td>
                                <?php } else { ?>
                                    <td><?= ($markup == '<b>C:</b> ' && count($tokens) != 1) ? $this->Html->link(__('Undo Reply'), ['action' => 'undoReply', $message['id']]) : ((count($tokens) == 1) ? '' : $this->Html->link(__('Reply'), ['action' => 'edit', $message['id']])); ?>
                                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $message['id']], ['confirm' => __('Message #{0} will be deleted. Are you sure?', $i), 'class' => 'text-danger']); ?></td>
                                <?php } ?>
                            </tr>
                            <?php $i++; } ?>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>