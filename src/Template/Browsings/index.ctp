<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Browsing[]|\Cake\Collection\CollectionInterface $browsings
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Browsing'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cars'), ['controller' => 'Cars', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Car'), ['controller' => 'Cars', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="browsings index large-9 medium-8 columns content">
    <h3><?= __('Browsings') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('customer_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('car_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('look') ?></th>
                <th scope="col"><?= $this->Paginator->sort('watched') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($browsings as $browsing): ?>
            <tr>
                <td><?= $this->Number->format($browsing->id) ?></td>
                <td><?= $browsing->has('customer') ? $this->Html->link($browsing->customer->name, ['controller' => 'Customers', 'action' => 'view', $browsing->customer->id]) : '' ?></td>
                <td><?= $browsing->has('car') ? $this->Html->link($browsing->car->id, ['controller' => 'Cars', 'action' => 'view', $browsing->car->id]) : '' ?></td>
                <td><?= $this->Number->format($browsing->look) ?></td>
                <td><?= h($browsing->watched) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $browsing->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $browsing->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $browsing->id], ['confirm' => __('Are you sure you want to delete # {0}?', $browsing->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
