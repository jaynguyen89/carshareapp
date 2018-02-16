<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>

<script type="text/javascript">
    var allModels = <?= json_encode($modelOptions); ?>;
    var allMakes = <?= json_encode($makeOptions); ?>;

    var checkMake = true;
    var checkModel = true;
    var checkType = true;
    var checkFuel = true;

    function switchMake() {
        var makecheck = document.getElementById('selectmake');
        var modelcheck = document.getElementById('selectmodel');

        var makeDrop = document.getElementById('make');
        var modelDrop = document.getElementById('model');

        var makeText = document.getElementById('maketext');
        var modelText = document.getElementById('modeltext');

        var makeEr = document.getElementById('makeEr');
        var modelEr = document.getElementById('modelEr');

        if (makecheck.checked) {
            modelcheck.checked = true;
            makeDrop.disabled = true;
            modelDrop.disabled = true;
            makeText.disabled = false;
            modelText.disabled = false;
            checkMake = checkMakeText();
        }
        else {
            modelcheck.checked = false;
            makeDrop.disabled = false;
            modelDrop.disabled = false;
            makeText.disabled = true;
            modelText.disabled = true;
            makeEr.style.display = 'none';
            modelEr.style.display = 'none';
            checkMake = true;
            checkModel = true;
        }
    }

    function switchModel() {
        var checkbox = document.getElementById('selectmodel');
        var modelDrop = document.getElementById('model');
        var modelText = document.getElementById('modeltext');
        var modelEr = document.getElementById('modelEr');

        if (checkbox.checked) {
            modelDrop.disabled = true;
            modelText.disabled = false;
            checkModel = checkModelText();
        }
        else {
            modelDrop.disabled = false;
            modelText.disabled = true;
            modelEr.style.display = 'none';
            checkModel = true;
        }
    }

    function switchType() {
        var checkbox = document.getElementById('selecttype');
        var typeDrop = document.getElementById('type');
        var typeText = document.getElementById('typetext');
        var typeEr = document.getElementById('typeEr');

        if (checkbox.checked) {
            typeDrop.disabled = true;
            typeText.disabled = false;
            checkType = checkTypeText();
        }
        else {
            typeDrop.disabled = false;
            typeText.disabled = true;
            checkType = true;
            typeEr.style.display = 'none';
        }
    }

    function checkTypeText() {
        var type = document.getElementById('typetext').value;
        var typeEr = document.getElementById('typeEr');

        var types = <?= json_encode($typeOptions); ?>;
        if (type.length === 0) {
            typeEr.innerHTML = '';
            typeEr.style.display = 'none';
            checkType = false;
        }
        else {
            var retype = toUpper(type);
            if (types.indexOf(retype) !== -1) {
                typeEr.innerHTML = 'This body type is available in dropdown. Please use dropdown instead.';
                typeEr.style.display = '';
                checkType = true;
            }
            else {
                typeEr.innerHTML = '';
                typeEr.style.display = 'none';
                checkType = false;
            }
        }
    }

    function switchFuel() {
        var checkbox = document.getElementById('selectfuel');
        var fuelDrop = document.getElementById('fuel');
        var fuelText = document.getElementById('fueltext');
        var fuelEr = document.getElementById('fuelEr');

        if (checkbox.checked) {
            fuelDrop.disabled = true;
            fuelText.disabled = false;
            checkFuel = checkFuelText();
        }
        else {
            fuelDrop.disabled = false;
            fuelText.disabled = true;
            checkFuel = true;
            fuelEr.style.display = 'none';
        }
    }

    function checkFuelText() {
        var fuel = document.getElementById('fueltext').value;
        var fuelEr = document.getElementById('fuelEr');

        var fuels = <?= json_encode($fuelOptions); ?>;
        if (fuel.length === 0) {
            fuelEr.innerHTML = '';
            fuelEr.style.display = 'none';
            checkFuel = false;
        }
        else {
            var refuel = toUpper(fuel);
            if (fuels.indexOf(refuel) !== -1) {
                fuelEr.innerHTML = 'This fuel type is available in dropdown. Please use dropdown instead.';
                fuelEr.style.display = '';
                checkFuel = false;
            }
            else {
                fuelEr.innerHTML = '';
                fuelEr.style.display = 'none';
                checkFuel = true;
            }
        }
    }

    function changeModel() {
        var make = document.getElementById('make').value;
        var makeText = allMakes[parseInt(make)];
        var models = allModels[makeText];

        var selected = false;
        var i = 0;
        while (!selected) {
            if (models.hasOwnProperty(i)) {
                selected = true;
                continue;
            }

            i++;
        }

        document.getElementById('model').value = i;
    }

    function changeMake() {
        var model = document.getElementById('model').value;
        var firstChoice = '';
        for (var make in allModels)
            for (var key in allModels[make])
                if (parseInt(key) === parseInt(model)) {
                    firstChoice = make;
                    break;
                }


        var i = 0;
        var selected = false;
        while (!selected) {
            if (allMakes[i] === firstChoice) {
                selected = true;
                continue;
            }

            i++;
        }

        document.getElementById('make').value = i;
    }

    function checkMakeText() {
        var make = document.getElementById('maketext').value;
        var makeEr = document.getElementById('makeEr');

        if (make.length === 0) {
            makeEr.innerHTML = '';
            makeEr.style.display = 'none';
            checkMake = false;
        }
        else {
            var remake = toUpper(make);
            if (allMakes.indexOf(remake) !== -1) {
                makeEr.innerHTML = 'This brand is available in dropdown. Please use dropdown instead.';
                makeEr.style.display = '';
                checkMake = false;
            }
            else {
                makeEr.innerHTML = '';
                makeEr.style.display = 'none';
                checkMake = true;
            }
        }
    }

    function checkModelText() {
        var model = document.getElementById('modeltext').value;
        var modelEr = document.getElementById('modelEr');
        var makeDrop = document.getElementById('make');

        var selectedMake = '';
        if (document.getElementById('selectmake').checked) {
            selectedMake = document.getElementById('maketext').value;
            checkMake = checkMakeText();
        }
        else {
            selectedMake = makeDrop.options[makeDrop.selectedIndex].value;
            checkMake = true;
        }

        var make = allMakes[selectedMake];

        if (model.length === 0) {
            checkModel = false;
            modelEr.innerHTML = '';
            modelEr.style.display = 'none';
        }
        else {
            var remodel = toUpper(model);
            var models = allModels[make];

            var found = false;
            for (var key in models)
                if (models[key] === remodel) {
                    found = true;
                    break;
                }

            if (!found) {
                checkModel = true;
                modelEr.innerHTML = '';
                modelEr.style.display = 'none';
            }
            else {
                checkModel = false;
                modelEr.innerHTML = 'This car has been added already. You can edit it instead.';
                modelEr.style.display = '';
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
        var color = document.getElementsByName('color');
        var transmit = document.getElementsByName('transmission');
        var odom = document.getElementById('odometer').value;
        var odoEr = document.getElementById('odoEr');

        var checkColor = false;
        if (color[0].checked || color[1].checked || color[2].checked)
            checkColor = true;

        var checkTrans = false;
        if (transmit[0].checked || transmit[1].checked || transmit[2].checked)
            checkTrans = true;

        var checkOdom = false;
        if (odom === 0) {
            odoEr.style.display = 'none';
            checkOdom = false;
        }
        else if (odom < 0) {
            odoEr.innerHTML = 'Invalid number input. Please check again.';
            odoEr.style.display = '';
            checkOdom = false;
        }
        else {
            checkOdom = true;
            odoEr.style.display = 'none';
        }

        var checkDimen = false;
        var length = document.getElementById('length').value;
        var width = document.getElementById('width').value;
        var height = document.getElementById('height').value;
        var dimenEr = document.getElementById('dimenEr');
        if (length === 0 && width === 0 && height === 0) {
            dimenEr.innerHTML = '*Please enter values in milimeters (mm).';
            dimenEr.style.color = 'black';
            checkDimen = false;
        }
        else if ((length + width + height) !== 0 && length <= 0 || width <= 0 || height <= 0) {
            dimenEr.innerHTML = 'Dimension values are invalid. Please check again.';
            dimenEr.style.color = 'orangered';
            checkDimen = false;
        }
        else {
            dimenEr.innerHTML = 'Dimension values are valid. But please double check before next step!';
            dimenEr.style.color = 'black';
            checkDimen = true;
        }

        var year = document.getElementById('year').value;
        var yearEr = document.getElementById('yearEr');

        var seats = document.getElementById('seats').value;
        var seatEr = document.getElementById('seatEr');

        var checkYear = false;
        var checkSeats = false;

        if (year < 1990 && year > 0) {
            yearEr.innerHTML = 'Year is valid. But you really want to add this car to the business?';
            yearEr.style.display = '';
            yearEr.style.color = 'black';
            checkYear = true;
        }
        else if (year > 2018 || year < 0) {
            yearEr.innerHTML = 'Year is invalid or nonsense. Please check again.';
            yearEr.style.display = '';
            yearEr.style.color = 'orangered';
            checkYear = false;
        }
        else {
            yearEr.innerHTML = '';
            yearEr.style.display = 'none';
            checkYear = true;
        }

        if (seats < 1) {
            seatEr.innerHTML = 'The number of seats is invalid. Please check again.';
            seatEr.style.color = 'orangered';
            seatEr.style.display = '';
            checkSeats = false;
        }
        else if (seats >= 100) {
            seatEr.innerHTML = 'The number of seats are valid. But please double check if it is correct.';
            seatEr.style.color = 'black';
            seatEr.style.display = '';
            checkSeats = true;
        }
        else {
            seatEr.style.display = 'none';
            checkSeats = true;
        }

        nextBtn.disabled = (checkMake && checkModel && checkType && checkFuel && checkColor &&
            checkTrans && checkOdom && !checkDimen && checkYear && checkSeats);
    }
</script>

<?= $this->Flash->render(); ?>
<div class="container">
    <div class="text-center teal-text darken-4"><h2><b>Add New Car: Step 1</b></h2></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <div class="progress" style="width: 75%; margin-bottom: 0;">
                        <div class="progress-bar bg-teal progress-bar-striped" role="progressbar" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"
                             style="width: 18%">Step 1 of 5</div>
                    </div>
                </h2>
            </div>
            <?= $this->Form->create($car); ?>
            <div class="card-body">
                <div class="table" style="margin: 0;">
                    <table class="table-responsive" style="margin: 0;">
                        <tr><th scope="row">Make</th>
                            <td><?= $this->Form->control('make', ['class' => 'form-control', 'type' => 'select', 'options' => $makeOptions, 'label' => false, 'id' => 'make', 'onchange' => 'changeModel()']); ?></td>
                            <td><input type="checkbox" name="selectmake" value="selectmake" id="selectmake" onclick="switchMake();checkButton()"><label for="selectmake" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('make', ['type' => 'text', 'label' => false, 'placeholder' => 'Make', 'required' => true, 'id' => 'maketext', 'disabled' => true, 'onchange' => 'checkMakeText();checkButton()']); ?>
                                <p class="small" id="makeEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Model</th>
                            <td><?= $this->Form->control('model', ['class' => 'form-control', 'type' => 'select', 'options' => $modelOptions, 'label' => false, 'id' => 'model', 'onchange' => 'changeMake()']); ?></td>
                            <td><input type="checkbox" name="selectmodel" value="selectmodel" id="selectmodel" onclick="switchModel();checkButton()"><label for="selectmodel" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('model', ['type' => 'text', 'label' => false, 'placeholder' => 'Model', 'required' => true, 'id' => 'modeltext', 'disabled' => true, 'onchange' => 'checkModelText();checkButton()']); ?>
                                <p class="small" id="modelEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Year</th>
                            <td colspan="2"><?= $this->Form->control('year', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'year', 'value' => 2017, 'min' => 1990, 'max' => 2018, 'onchange' => 'checkButton()']); ?>
                                <p class="small" id="yearEr" style="display: none"></p></td></tr>
                        <tr><th scope="row">Color</th>
                            <td colspan="2"><div class="row">
                                    <div class="col-sm-4"><input type="radio" name="color" value="White" id="whiteradio"><label for="whiteradio" style="color: black;">Close to White</label></div>
                                    <div class="col-sm-4"><input type="radio" name="color" value="Black" id="blackradio"><label for="blackradio" style="color: black;">Close to Black</label></div>
                                    <div class="col-sm-4"><input type="radio" name="color" value="Others" id="otherradio"><label for="otherradio" style="color: black;">Other Colors</label></div>
                                </div></td></tr>
                        <tr><th scope="row">Transmission</th>
                            <td colspan="2"><div class="row">
                                    <div class="col-sm-4"><input type="radio" name="transmission" value="0" id="transauto"><label for="transauto" style="color: black;">Auto</label></div>
                                    <div class="col-sm-4"><input type="radio" name="transmission" value="1" id="transmanu"><label for="transmanu" style="color: black;">Manual</label></div>
                                    <div class="col-sm-4"><input type="radio" name="transmission" value="2" id="transmix"><label for="transmix" style="color: black;">Dual</label></div>
                                </div></td></tr>
                        <tr><th scope="row">Body Type</th>
                            <td><?= $this->Form->control('type', ['class' => 'form-control', 'type' => 'select', 'options' => $typeOptions, 'label' => false, 'id' => 'type', 'onchange' => 'validateForm()']); ?></td>
                            <td><input type="checkbox" name="selecttype" value="selecttype" id="selecttype" onclick="switchType();checkButton()"><label for="selecttype" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('type', ['type' => 'text', 'label' => false, 'placeholder' => 'Body Type', 'required' => true, 'id' => 'typetext', 'disabled' => true, 'onchange' => 'checkTypeText();checkButton()']); ?>
                                <p class="small" id="typeEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Seats</th>
                            <td colspan="2"><?= $this->Form->control('seats', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'id' => 'seats', 'value' => 5, 'min' => 1, 'max' => 100, 'onchange' => 'checkButton()']); ?>
                                <p class="small" id="seatEr" style="display: none"></p></td></tr>
                        <tr><th scope="row">Fuel Type</th>
                            <td><?= $this->Form->control('fuel', ['class' => 'form-control', 'type' => 'select', 'options' => $fuelOptions, 'label' => false, 'id' => 'fuel', 'onchange' => 'validateForm()']); ?></td>
                            <td><input type="checkbox" name="selectfuel" value="selectfuel" id="selectfuel" onclick="switchFuel();checkButton()"><label for="selectfuel" style="color: black;">Manually enter</label>
                                <?= $this->Form->control('fuel', ['type' => 'text', 'label' => false, 'placeholder' => 'Fuel Type', 'required' => true, 'id' => 'fueltext', 'disabled' => true, 'onchange' => 'checkFuelText();checkButton()']); ?>
                                <p class="small" id="fuelEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Odometer (km)</th>
                            <td colspan="2"><?= $this->Form->control('odometer', ['class' => 'form-control',  'placeholder' => 'Numbers without separators', 'type' => 'number', 'label' => false, 'id' => 'odometer', 'onchange' => 'checkButton()']); ?>
                                <p class="small" id="odoEr" style="color: orangered; display: none"></p></td></tr>
                        <tr><th scope="row">Dimensions (*)</th>
                            <td colspan="2"><div class="row">
                                    <div class="col-sm-4"><?= $this->Form->control('length', ['class' => 'form-control', 'placeholder' => 'Length', 'type' => 'number', 'label' => false, 'id' => 'length', 'required' => true, 'min' => 0, 'onchange' => 'checkButton()']); ?></div>
                                    <div class="col-sm-4"><?= $this->Form->control('width', ['class' => 'form-control', 'placeholder' => 'Width', 'type' => 'number', 'label' => false, 'id' => 'width', 'required' => true, 'min' => 0, 'onchange' => 'checkButton()']); ?></div>
                                    <div class="col-sm-4"><?= $this->Form->control('height', ['class' => 'form-control', 'placeholder' => 'Height', 'type' => 'number', 'label' => false, 'id' => 'height', 'required' => true, 'min' => 0, 'onchange' => 'checkButton()']); ?></div>
                                </div><p class="small" id="dimenEr">*Please enter values in milimeters (mm).</p></td></tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Next Step'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'nextstep', 'type' => 'submit', 'disabled' => true, 'style' => 'width: 200px;']); ?>
                <?= $this->Form->end(); ?>
                <?= $this->Html->link(__('Cancel'), $this->request->referer(), ['class' => 'waves-effect waves-light btn btn-sm btn-danger']); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
