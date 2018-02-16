<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Browsing $browsing
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Browsing'), ['action' => 'edit', $browsing->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Browsing'), ['action' => 'delete', $browsing->id], ['confirm' => __('Are you sure you want to delete # {0}?', $browsing->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Browsings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Browsing'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customers'), ['controller' => 'Customers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['controller' => 'Customers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cars'), ['controller' => 'Cars', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Car'), ['controller' => 'Cars', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="browsings view large-9 medium-8 columns content">
    <h3><?= h($browsing->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Customer') ?></th>
            <td><?= $browsing->has('customer') ? $this->Html->link($browsing->customer->name, ['controller' => 'Customers', 'action' => 'view', $browsing->customer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Car') ?></th>
            <td><?= $browsing->has('car') ? $this->Html->link($browsing->car->id, ['controller' => 'Cars', 'action' => 'view', $browsing->car->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($browsing->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Look') ?></th>
            <td><?= $this->Number->format($browsing->look) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Watched') ?></th>
            <td><?= $browsing->watched ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
