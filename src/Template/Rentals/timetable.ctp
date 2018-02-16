<?= $this->Html->css('custom.css') ?>

<div class="container">
    <div class="row">
        <div class="row" >
            <h2 class="header text-center" style="width: 100%"> Car Timetable</h2>
        </div>
        <?php
        require_once (WWW_ROOT.DS.'calendar.php');

        $calendar = new Calendar();

        echo $calendar->show();
        ?>
    </div>
    <div class="row">
        <div class="row" >
            <h2 class="header text-center" style="width: 100%"> Your Timetable</h2>
        </div>
        <?php
        require_once (WWW_ROOT.DS.'calendar.php');

        $calendar = new Calendar();

        echo $calendar->show();
        ?>
    </div>
</div>


