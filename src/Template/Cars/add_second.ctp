<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>

<script type="text/javascript">
    var enginetypes = <?= json_encode($engineOptions); ?>;
    var inductions = <?= json_encode($inductOptions); ?>;
    var geartypes = <?= json_encode($gearOptions); ?>;

    var checkEngineType = true;
    var checkInduction = true;
    var checkGearType = true;

    function switchEngineType() {
        var checkbox = document.getElementById('selectenginetype');
        var engineDrop = document.getElementById('enginetype');
        var engineText = document.getElementById('enginetypetext');
        var enginetypeEr = document.getElementById('enginetypeEr');

        if (checkbox.checked) {
            engineDrop.disabled = true;
            engineText.disabled = false;
            checkEngineTypeText();
        }
        else {
            engineDrop.disabled = false;
            engineText.disabled = true;
            checkEngineType = true;
            enginetypeEr.style.display = 'none';
        }
    }

    function checkEngineTypeText() {
        var enginetype = document.getElementById('enginetypetext').value;
        var enginetypeEr = document.getElementById('enginetypeEr');

        if (enginetype.length === 0) {
            enginetypeEr.style.display = 'none';
            checkEngineType = false;
        }
        else {
            var reengine = toUpper(enginetype);
            var found = false;
            for (var key in enginetypes)
                if (enginetypes[key] === reengine) {
                    found = true;
                    break;
                }

            if (found) {
                enginetypeEr.innerHTML = 'Engine type found in dropdown. Please use dropdown instead.';
                enginetypeEr.style.display = '';
                checkEngineType = false;
            }
            else {
                enginetypeEr.style.display = 'none';
                checkEngineType = true;
            }
        }
    }
    
    function switchInduction() {
        var checkbox = document.getElementById('selectinduction');
        var inductDrop = document.getElementById('induction');
        var inductText = document.getElementById('inductiontext');
        var inductionEr = document.getElementById('inductionEr');

        if (checkbox.checked) {
            inductDrop.disabled = true;
            inductText.disabled = false;
            checkInductionText();
        }
        else {
            inductDrop.disabled = false;
            inductText.disabled = true;
            checkInduction = true;
            inductionEr.style.display = 'none';
        }
    }

    function checkInductionText() {
        var induction = document.getElementById('inductiontext').value;
        var inductionEr = document.getElementById('inductionEr');

        if (induction.length === 0) {
            inductionEr.style.display = 'none';
            checkInduction = false;
        }
        else {
            var reinduct = toUpper(induction);
            var found = false;
            for (var key in inductions)
                if (inductions[key] === reinduct) {
                    found = true;
                    break;
                }

            if (found) {
                inductionEr.innerHTML = 'Induction record found in dropdown. Please use dropdown instead.';
                inductionEr.style.display = '';
                checkInduction = false;
            }
            else {
                inductionEr.style.display = 'none';
                checkInduction = true;
            }
        }
    }

    function switchGear() {
        var checkbox = document.getElementById('selectgeartype');
        var gearDrop = document.getElementById('geartype');
        var gearText = document.getElementById('geartext');
        var gearEr = document.getElementById('geartypeEr');

        if (checkbox.checked) {
            gearDrop.disabled = true;
            gearText.disabled = false;
            checkGearText();
        }
        else {
            gearDrop.disabled = false;
            gearText.disabled = true;
            gearEr.style.display = 'none';
            checkGearType = true;
        }
    }

    function checkGearText() {
        var gear = document.getElementById('geartext').value;
        var gearEr = document.getElementById('geartypeEr');

        if (gear.length === 0) {
            gearEr.style.display = 'none';
            checkGearType = false;
        }
        else {
            var regear = toUpper(gear);
            var found = false;
            for (var key in geartypes)
                if (geartypes[key] === regear) {
                    found = true;
                    break;
                }

            if (found) {
                gearEr.innerHTML = 'Gear type found in dropdown. Please use dropdown instead.';
                gearEr.style.display = '';
                checkGearType = false;
            }
            else {
                gearEr.style.display = 'none';
                checkGearType = true;
            }
        }
    }

    function toUpper(str) {
        return str
            .toLowerCase()
            .split(' ')
            .map(function(word) {
                return word[0].toUpperCase() + word.substr(1);
            })
            .join(' ');
    }

    function checkButton() {
        var nextBtn = document.getElementById('nextstep');

        var checkEngineSize = false;
        var enginesize = document.getElementById('enginesize').value;
        var enginesizeEr = document.getElementById('enginesizeEr');
        if (enginesize === 0) {
            enginesizeEr.style.display = 'none';
            checkEngineSize = false;
        }
        else if (enginesize < 0) {
            checkEngineSize = false;
            enginesizeEr.innerHTML = 'Engine size is invalid. Please check again.';
            enginesizeEr.style.display = '';
            enginesizeEr.style.color = 'orangered';
        }
        else if (enginesize > 8500) {
            checkEngineSize = true;
            enginesizeEr.innerHTML = 'Engine size is valid. But please check again before next step.';
            enginesizeEr.style.display = '';
            enginesizeEr.style.color = 'black';
        }
        else {
            checkEngineSize = true;
            enginesizeEr.style.display = 'none';
        }

        var checkCylinder = false;
        var cylinder = document.getElementById('cylinder').value;
        var cylinderEr = document.getElementById('cylinderEr');
        if (cylinder === 0) {
            cylinderEr.style.display = 'none';
            checkCylinder = false;
        }
        else if (cylinder > 10) {
            checkCylinder = true;
            cylinderEr.innerHTML = 'Cylinder is valid. But please check again before next step.';
            cylinderEr.style.display = '';
            cylinderEr.style.color = 'black';
        }
        else if (cylinder < 0) {
            checkCylinder = false;
            cylinderEr.innerHTML = 'Cylinder is invalid. Please check again.';
            cylinderEr.style.display = '';
            cylinderEr.style.color = 'orangered';
        }
        else {
            checkCylinder = true;
            cylinderEr.style.display = 'none';
        }

        var checkPower = false;
        var watt = document.getElementById('watt').value;
        var round = document.getElementById('round').value;
        var powerEr = document.getElementById('powerEr');
        if (watt === 0 && round === 0) {
            checkPower = false;
            powerEr.style.display = 'none';
        }
        else if ((watt < 0 && round >= 0) || (watt >= 0 && round < 0) || (watt < 0 && round < 0)) {
            checkPower = false;
            powerEr.innerHTML = 'Either or both of Watt or/and RPM value is invalid. Please check again.';
            powerEr.style.display = '';
        }
        else {
            checkPower = true;
            powerEr.style.display = 'none';
        }

        var checkGear = false;
        var gear = document.getElementById('gear').value;
        var gearEr = document.getElementById('gearEr');
        if (gear === 0) {
            checkGear = false;
            gearEr.style.display = 'none';
        }
        else if (gear < 0) {
            checkGear = false;
            gearEr.style.display = '';
            gearEr.style.color = 'orangered';
            gearEr.innerHTML = 'Gear is invalid. Please check again.';
        }
        else if (gear > 10) {
            checkGear = true;
            gearEr.style.display = '';
            gearEr.style.color = 'black';
            gearEr.innerHTML = 'Gear is valid. But please check again before next step.';
        }
        else {
            checkGear = true;
            gearEr.style.display = 'none';
        }

        var checkFuel = false;
        var fuelcap = document.getElementById('fuelcap').value;
        var fuelcapEr = document.getElementById('fuelcapEr');
        if (fuelcap === 0) {
            checkFuel = false;
            fuelcapEr.style.display = 'none';
        }
        else if (fuelcap < 0) {
            checkFuel = false;
            fuelcapEr.style.display = '';
            fuelcapEr.innerHTML = 'Fuel capacity is invalid. Please check again.';
        }
        else {
            checkFuel = true;
            fuelcapEr.style.display = 'none';
        }

        var checkConsume = false;
        var average = document.getElementById('average').value;
        var rural = document.getElementById('rural').value;
        var urbane = document.getElementById('urbane').value;
        var consumeEr = document.getElementById('consumeEr');
        if(average === 0 && rural === 0 && urbane === 0) {
            checkConsume = false;
            consumeEr.style.display = false;
        }
        else if (average > 0 && urbane > 0 && rural > 0) {
            checkConsume = true;
            consumeEr.style.display = 'none';
        }
        else {
            checkConsume = false;
            consumeEr.innerHTML = 'Either of consumption values is invalid. Please check again.';
            consumeEr.style.display = '';
        }

        nextBtn.disabled = !(checkEngineType && checkInduction && checkGearType && checkEngineSize && checkCylinder && checkPower && checkGear && checkFuel && checkConsume);
    }
</script>

<?= $this->Flash->render(); ?>
<div class="container">
    <div class="text-center teal-text darken-4"><h2><b>Add New Car: Step 2</b></h2></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <div class="progress" style="width: 75%; margin-bottom: 0;">
                        <div class="progress-bar bg-teal progress-bar-striped" role="progressbar" aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"
                             style="width: 36%">Step 2 of 5</div>
                    </div>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><?= $this->Html->link(__('Cancel'), ['action' => 'delete', $car['id']], ['confirm' => __('{0} will be completely removed from the system. Are you sure to cancel?', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => 'waves-effect waves-light btn btn-sm btn-danger']); ?></li>
                </ul>
            </div>
            <?= $this->Form->create($car); ?>
            <div class="card-body">
                <div class="table" style="margin: 0;">
                    <table class="table-responsive" style="margin: 0;">
                        <tr><th scope="row">Drive Type</th>
                            <td colspan="2"><?= $this->Form->control('drivetype', ['class' => 'form-control', 'type' => 'select', 'options' => $driveOptions, 'label' => false, 'id' => 'drivetype']); ?></td></tr>
                        <tr><th scope="row">Engine Type</th>
                            <td><?= $this->Form->control('enginetype', ['class' => 'form-control', 'type' => 'select', 'options' => $engineOptions, 'label' => false, 'id' => 'enginetype']); ?></td>
                            <td><input type="checkbox" name="selectenginetype" value="selectenginetype" id="selectenginetype" onclick="switchEngineType()"><label for="selectenginetype" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('enginetype', ['type' => 'text', 'label' => false, 'placeholder' => 'Engine Type', 'required' => true, 'id' => 'enginetypetext', 'disabled' => true, 'onchange' => 'checkEngineTypeText();checkButton()']); ?>
                                <p class="small" id="enginetypeEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Induction</th>
                            <td><?= $this->Form->control('induction', ['class' => 'form-control', 'type' => 'select', 'options' => $inductOptions, 'label' => false, 'id' => 'induction']); ?></td>
                            <td><input type="checkbox" name="selectinduction" value="selectinduction" id="selectinduction" onclick="switchInduction()"><label for="selectinduction" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('induction', ['type' => 'text', 'label' => false, 'placeholder' => 'Induction', 'required' => true, 'id' => 'inductiontext', 'disabled' => true, 'onchange' => 'checkInductionText();checkButton()']); ?>
                                <p class="small" id="inductionEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Engine Size (ml)</th>
                            <td colspan="2"><?= $this->Form->control('enginesize', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'enginesize', 'placeholder' => 'Engine volume is in milliliters (ml).', 'onchange' => 'checkButton()']); ?>
                                <p class="small" id="enginesizeEr" style="display: none"></p></td></tr>
                        <tr><th scope="row">Cylinder</th>
                            <td colspan="2"><?= $this->Form->control('cylinder', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'cylinder', 'value' => 2, 'onchange' => 'checkButton()']); ?>
                                <p class="small" id="cylinderEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Power</th>
                            <td colspan="2"><div class="row" style="margin: 0">
                                    <div class="col-sm-4"><?= $this->Form->control('watt', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'watt', 'placeholder' => 'Ex. 150', 'onchange' => 'checkButton()']); ?></div>
                                    <div class="col-sm-2 center bg-indigo" style="padding-top: 15px;">kW - At</div>
                                    <div class="col-sm-4"><?= $this->Form->control('round', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'round', 'placeholder' => 'Ex. 5000', 'onchange' => 'checkButton()']); ?></div>
                                    <div class="col-sm-2 center bg-indigo" style="padding-top: 15px;">RPM</div>
                                </div><p class="small" id="powerEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Gear</th>
                            <td colspan="2"><?= $this->Form->control('gear', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'gear', 'value' => 2, 'onchange' => 'checkButton()']); ?>
                                <p class="small" id="gearEr" style="display: none"></p></td></tr>
                        <tr><th scope="row">Gear Type</th>
                            <td><?= $this->Form->control('geartype', ['class' => 'form-control', 'type' => 'select', 'options' => $gearOptions, 'label' => false, 'id' => 'geartype']); ?></td>
                            <td><input type="checkbox" name="selectgeartype" value="selectgeartype" id="selectgeartype" onclick="switchGear()"><label for="selectgeartype" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('geartype', ['type' => 'text', 'label' => false, 'placeholder' => 'Gear Type', 'required' => true, 'id' => 'geartext', 'disabled' => true, 'onchange' => 'checkGearText();checkButton()']); ?>
                                <p class="small" id="geartypeEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Fuel Capacity (litter)</th>
                            <td colspan="2"><?= $this->Form->control('fuelcap', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'id' => 'fuelcap', 'placeholder' => 'Volume is in litters.','onchange' => 'checkButton()']); ?>
                                <p class="small" id="fuelcapEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Fuel Consume</th>
                            <td colspan="2"><div class="row">
                                    <div class="col-sm-4"><?= $this->Form->control('average', ['class' => 'form-control', 'placeholder' => 'Average Consumption', 'type' => 'number', 'step' => '0.1', 'label' => false, 'id' => 'average', 'required' => true, 'min' => 0, 'onchange' => 'checkButton()']); ?></div>
                                    <div class="col-sm-4"><?= $this->Form->control('rural', ['class' => 'form-control', 'placeholder' => 'Consumption in Rural areas', 'type' => 'number', 'step' => '0.1', 'label' => false, 'id' => 'rural', 'required' => true, 'min' => 0, 'onchange' => 'checkButton()']); ?></div>
                                    <div class="col-sm-4"><?= $this->Form->control('urbane', ['class' => 'form-control', 'placeholder' => 'Consumption in Urbane areas', 'type' => 'number', 'step' => '0.1', 'label' => false, 'id' => 'urbane', 'required' => true, 'min' => 0, 'onchange' => 'checkButton()']); ?></div>
                                </div><p class="small">*Please enter volumes in litters.</p><p class="small" id="consumeEr" style="color: orangered; display: none"></p></td></tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Next Step'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'nextstep', 'type' => 'submit', 'disabled' => true, 'style' => 'width: 200px;']); ?>
                <?= $this->Form->end(); ?>
                <?= $this->Html->link(__('Save for later'), ['action' => 'revive'], ['class' => 'btn btn-lg btn-warning lighten-2 waves-effect waves-light', 'style' => 'width: 200px;']); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
