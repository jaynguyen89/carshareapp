<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>

<script type="text/javascript">
    var dd = false;
    var km = false;

    function checkDD() {
        var ddp = document.getElementById('ddprice').value;
        var ddEr = document.getElementById('ddEr');

        if (parseFloat(ddp) === 0 || ddp === '') {
            dd = false;
            ddEr.style.display = 'none';
        }
        else if (parseFloat(ddp) < 0) {
            dd = false;
            ddEr.innerHTML = 'Invalid price. Price can not be negative.';
            ddEr.style.display = '';
        }
        else {
            dd = true;
            ddEr.style.display = 'none';
        }
    }

    function checkKM() {
        var ddp = document.getElementById('kmprice').value;
        var ddEr = document.getElementById('kmEr');

        if (parseFloat(ddp) === 0 || ddp === '') {
            km = false;
            ddEr.style.display = 'none';
        }
        else if (parseFloat(ddp) < 0) {
            km = false;
            ddEr.innerHTML = 'Invalid price. Price can not be negative.';
            ddEr.style.display = '';
        }
        else {
            km = true;
            ddEr.style.display = 'none';
        }
    }

    function checkPark() {
        var parkad = document.getElementById('parking').value;
        var parkEr = document.getElementById('parkEr');

        if (parkad === '')
            parkEr.style.display = 'none';
        else {
            var address = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + parkad.replace(/\s/g, '+') + '&key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw';
            $.getJSON(address, function (data) {
                if (data['status'] === 'OK') {
                    document.getElementById('latitude').value = data['results'][0]['geometry']['location']['lat'];
                    document.getElementById('longitude').value = data['results'][0]['geometry']['location']['lng'];

                    parkEr.style.display = 'none';
                }
                else {
                    parkEr.style.display = '';
                    parkEr.innerHTML = 'Unable to locate the Parking Address. Please check again.';
                    document.getElementById('publish').disabled = true;
                }
            });
        }
    }

    function checkButton() {
        var button = document.getElementById('publish');
        button.disabled = !(dd && km);
    }
</script>

<?= $this->Flash->render(); ?>
    <div class="container">
        <div class="text-center teal-text darken-4"><h2><b>Add New Car: Step 5</b></h2></div>
        <div class="text-center"><div class="chip bg-red lighten-3" style="margin-bottom: 5px">Please Note: Car will be set unavailable by default after adding.<i class="close material-icons">close</i></div></div>
        <div class="text-center"><div class="chip bg-red lighten-3" style="margin-bottom: 5px">Please review your car and publish it in `Revive`<i class="close material-icons">close</i></div></div>
        <div class="row">
            <div class="card horizontal" style="width: 100%">
                <div class="header bg-indigo darken-4">
                    <h2>Admin: <?= $admin['name']; ?>
                        <div class="progress" style="width: 75%; margin-bottom: 0;">
                            <div class="progress-bar bg-teal progress-bar-striped" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"
                                 style="width: 90%">Step 5 of 5</div>
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
                            <tr><th scope="row">Price Per Day</th>
                                <td><?= $this->Form->control('ddprice', ['class' => 'form-control', 'type' => 'number', 'step' => '0.01', 'placeholder' => '* Required', 'required' => true, 'label' => false, 'id' => 'ddprice', 'onchange' => 'checkDD();checkButton()']); ?>
                                    <p class="small" id="ddEr" style="color: orangered; display: none"></p></td></tr>
                            <tr><th scope="row">Price Per Km</th>
                                <td><?= $this->Form->control('kmprice', ['class' => 'form-control', 'type' => 'number', 'step' => '0.01', 'placeholder' => '* Required', 'required' => true, 'label' => false, 'id' => 'kmprice', 'onchange' => 'checkKM();checkButton()']); ?>
                                    <p class="small" id="kmEr" style="color: orangered; display: none"></p></td></tr>
                            <tr><th scope="row">Parking Address</th>
                                <td><?= $this->Form->control('parking', ['class' => 'form-control', 'type' => 'text', 'placeholder' => '* Required', 'required' => true, 'label' => false, 'id' => 'parking', 'onchange' => 'checkPark()']); ?>
                                    <p class="small" id="parkEr" style="color: orangered; display: none"></p></td></tr>
                            <tr><th scope="row">Parking Slot</th>
                                <td><?= $this->Form->control('slot', ['class' => 'form-control', 'type' => 'text', 'placeholder' => '* Required', 'label' => false, 'required' => true, 'id' => 'slotad']); ?></td></tr>
                            <tr style="display: none;"><td><input type="hidden" name="latitude" id="latitude" value="" /></td>
                                <td><input type="hidden" name="longitude" id="longitude" value=""/></td></tr>
                        </table>
                    </div>
                </div>
                <div class="card-action">
                    <?= $this->Form->button(__('Finish'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'publish', 'disabled' => true, 'type' => 'submit', 'style' => 'width: 200px;']); ?>
                    <?= $this->Form->end(); ?>
                    <?= $this->Html->link(__('Save for later'), ['action' => 'revive'], ['class' => 'btn btn-lg btn-warning lighten-2 waves-effect waves-light', 'style' => 'width: 200px;']); ?>
                </div>
            </div>
        </div>
    </div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>