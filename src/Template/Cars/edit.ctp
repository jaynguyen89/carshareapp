<?php
/**
  * @var \App\View\AppView $this
  */
?>
<div class="container">
<div class="cars form large-9 medium-8 columns content">
    <?= $this->Form->create($car) ?>
    <fieldset>
        <legend><?= __('Edit Car') ?></legend>
        <?php
            echo $this->Form->control('make');
            echo $this->Form->control('model');
            echo $this->Form->control('year');
            echo $this->Form->control('color');
            echo $this->Form->control('seats');
            echo $this->Form->control('fuel');
            echo $this->Form->control('odometer');
            echo $this->Form->control('transmission');
            echo $this->Form->control('drivetype');
            echo $this->Form->control('enginetype');
            echo $this->Form->control('enginesize');
            echo $this->Form->control('induction');
            echo $this->Form->control('cylinder');
            echo $this->Form->control('power');
            echo $this->Form->control('gear');
            echo $this->Form->control('geartype');
            echo $this->Form->control('fuelcap');
            echo $this->Form->control('fuelconsume');
            echo $this->Form->control('measures');
            echo $this->Form->control('audiodesc');
            echo $this->Form->control('safety');
            echo $this->Form->control('convenience');
            echo $this->Form->control('lightsview');
            echo $this->Form->control('otherspecs');
            echo $this->Form->control('image');
            echo $this->Form->control('shortdesc');
            echo $this->Form->control('description');
            echo $this->Form->control('ddprice');
            echo $this->Form->control('kmprice');
            echo $this->Form->control('available');
            echo $this->Form->control('type');
            echo $this->Form->control('parking');
            echo $this->Form->control('latitude');
            echo $this->Form->control('longitude');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
</div>