<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>

<script type="text/javascript">
    var noteVal = true;
    var dateVal = false;

    function showRows() {
        var shortRadio = document.getElementById('short');
        var longRadio = document.getElementById('long');

        var hoursfield = document.getElementById('hours');
        var daysfield = document.getElementById('days');

        var submitBtn = document.getElementById('submit');

        if (shortRadio.checked) {
            hoursfield.disabled = false;
            daysfield.disabled = true;
        }
        else {
            hoursfield.disabled = true;
            daysfield.disabled = false;
        }

        if (longRadio.checked) {
            hoursfield.disabled = true;
            daysfield.disabled = false;
        }
        else {
            hoursfield.disabled = false;
            daysfield.disabled = true;
        }

        submitBtn.disabled = !(dateVal && noteVal);
    }

    function countChars() {
        var count = document.getElementById("count");
        var message = document.getElementById("rentalnote").value;

        if (250 - message.length > 0) {
            count.innerHTML = (250 - message.length).toString().concat(' characters left');
            count.style.color = 'black';
            noteVal = true;
        }
        else {
            count.innerHTML = '0 char left!';
            count.style.color = 'orangered';
            noteVal = false;
        }
    }

    function validateForm() {
        var date = document.getElementById('date').value;
        var dateEr = document.getElementById('dateEr');

        var submitBtn = document.getElementById('submit');

        var curDate = new Date();
        var insDate = new Date(date);

        if (date.length == 0)
            dateEr.style.display = 'none';
        else if (curDate >= insDate) {
            dateEr.innerHTML = 'Rental date should be after current date!';
            dateEr.style.display = '';
        }
        else {
            dateEr.style.display = 'none';
            dateVal = true;
        }

        submitBtn.disabled = !(dateVal && noteVal);
    }

    function calculateCharges() {
        var shortRadio = document.getElementById('short');
        var longRadio = document.getElementById('long');

        var charges = document.getElementById('charges');

        if (shortRadio.checked)
            charges.innerHTML = '$<?= $car['kmprice'] ?> / 50km, plus fines (if any).';

        if (longRadio.checked) {
            var days = document.getElementById('days').value;
            var m = Math.round((parseFloat(days) * parseFloat(<?= $car['ddprice']; ?>))*100)/100;
            charges.innerHTML = '$'.concat(m.toString()).concat(' approx., plus fines (if any).');
        }
    }
</script>

<?= $this->Flash->render(); ?>
<div class="container">
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <?= $this->Html->image($car['image'].'-1.jpg', ['class' => 'img-thumbnail', 'style' => 'max-width: 150px; max-height: 120px; float: left; margin-right: 15px;']); ?>
                <h2><?= $car['make'].' '.$car['model'].' '.$car['year']; ?></h2>
                <small style="color: orange !important;"><?= 'Color: '.$car['color'].' - Seats: '.$car['seats'].' - Fuel: '.$car['fuel'].' - Type: '.($car['transmission'] == 0 ? 'Auto' : ($car['transmission'] ? 'Manual' : 'Dual')); ?></small><br/>
                <small style="color: orange !important; margin-bottom: 15px;"><?= 'Price: $'.round($car['ddprice'],2).' / day --- $'.round($car['kmprice'],2).' / 50km'; ?></small><br/>
                <p style="margin-bottom: 0;">Please provide information to complete making rental:</p>
                <ul class="header-dropdown m-r--5">
                    <?php if ($from == 'carmap' || $from == 'carindex') { ?>
                        <p>Want to do this later? </p>
                        <?= $this->Html->link('Continue Browsing', $this->request->referer(), ['class' => 'btn btn-sm btn-info lighten-2 waves-effect waves-light pull-right']); ?>
                    <?php } else if ($from == 'adminview') echo $this->Html->link('Back To Admin', $this->request->referer(), ['class' => 'btn btn-sm btn-info lighten-2 waves-effect waves-light pull-right']);
                        else echo $this->Html->link('Back To Cart', $this->request->referer(), ['class' => 'btn btn-sm btn-info lighten-2 waves-effect waves-light pull-right']); ?>
                </ul>
            </div>
            <?= $this->Form->create($rental); ?>
            <div class="card-body">
                <div class="table" style="margin: 0;">
                    <table class="table-responsive" style="margin: 0;">
                        <tr><th scope="row">Rental Type</th>
                            <td><input type="radio" id="short" name="rental" value="short" <?= ($rental['type'] ? ($rental['type'] == 'short' ? 'checked' : '') : 'checked'); ?> class="form-control" onclick="showRows();calculateCharges()" /> <label for="short" style="color: black;">Short Rental</label></td>
                            <td><input type="radio" id="long" name="rental" value="long" <?= ($rental['type'] ? ($rental['type'] == 'long' ? 'checked' : '') : ''); ?> class="form-control" onclick="showRows();calculateCharges()" /> <label for="long" style="color: black;">Long Rental</label></td>
                        </tr>
                        <tr><th scope="row">Rental Date</th>
                            <td colspan="2">
                                <?= $this->Form->control('date', ['value' => ($rental['fromdate'] ? (new DateTime($rental['fromdate']))->format('Y-m-d H:i:s') : ''), 'class' => 'form-control', 'type' => 'datetime-local', 'label' => false, 'required' => true, 'id' => 'date', 'onchange' => 'validateForm();calculateCharges()']); ?>
                                <p id="dateEr" style="display: none; color: orangered;" class="small"></p>
                                <p class="small">Click <a href="" onclick="showpopup()">here</a> to view this car's timetable</p>
                            </td>
                        </tr>
                        <tr><th scope="row">Rental Duration in Hours</th>
                            <td colspan="3">
                                <?= $this->Form->control('hours', ['value' => ($rental['type'] == 'short') ? $rental['duration'] : '1', 'class' => 'form-control', 'type' => 'number', 'label' => false, 'required' => true, 'disabled' => ($rental['type'] ? ($rental['type'] == 'short' ? false : true) : false), 'id' => 'hours', 'min' => '1', 'max' => '23', 'onchange' => 'calculateCharges()']); ?>
                            </td>
                        </tr>
                        <tr><th scope="row">Rental Duration in Days</th>
                            <td colspan="3">
                                <?= $this->Form->control('days', ['value' => ($rental['type'] == 'long') ? $rental['duration'] : '1', 'class' => 'form-control', 'type' => 'number', 'label' => false, 'required' => true, 'disabled' => ($rental['type'] ? ($rental['type'] == 'long' ? false : true) : true), 'id' => 'days', 'min' => '1', 'onchange' => 'calculateCharges()']); ?>
                            </td>
                        </tr>
                        <tr><th scope="row">Instruction</th>
                            <td colspan="2">
                                <?= $this->Form->textarea('note', ['value' => $rental['note'], 'class' => 'form-control', 'type' => 'text', 'label' => false, 'id' => 'rentalnote', 'oninput' => 'countChars()', 'onchange' => 'validateForm()']); ?>
                                <p id="count" class="small">250 characters left</p>
                            </td>
                        </tr>
                        <tr><th scope="row">Estimated minimum charges</th>
                            <td colspan="2">
                                <p id="charges">$<?= ($rental['value']) ? $rental['value'] : '0.0'; ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'submit', 'type' => 'submit', 'disabled' => true, 'style' => 'width: 200px;']); ?>
                <?= $this->Form->end(); ?>

                <?= $this->Form->postLink('Cancel Rental', ['action' => 'delete', '?' => ['rid' => $rental['id'], 'from' => $from]], ['confirm' => __(($from == 'adminview') ? 'Are you sure to cancel this Rental on {0} for customer?' : '{0} won\'t be added to your Cart. Are you sure?', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => 'waves-effect waves-light btn btn-sm btn-danger pull-right']); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function showpopup() {
        window.open("/rentals/timetable", "_blank", "toolbar=no,scrollbars=no,resizable=yes,top=500,left=500,width=750,height=700");
    }
</script>

<?= $this->Html->script('admin.js') ?>