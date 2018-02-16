<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>

<script type="text/javascript">
    var noteVal = true;
    var sdateVal = false;
    var ldateVal = false;

    function showRows() {
        var shortRadio = document.getElementById('short');
        var longRadio = document.getElementById('long');

        var dateshort = document.getElementById('dateshort');
        var datenum = document.getElementById('datenum');
        var datelong = document.getElementById('datelong');

        var submitBtn = document.getElementById('submit');

        if (shortRadio.checked) {
            dateshort.disabled = false;
            datenum.disabled = true;
            datelong.disabled = true;

            submitBtn.disabled = !(sdateVal && noteVal);
        }
        else {
            dateshort.disabled = true;
            datenum.disabled = false;
            datelong.disabled = false;

            submitBtn.disabled = !(ldateVal && noteVal);
        }

        if (longRadio.checked) {
            dateshort.disabled = true;
            datenum.disabled = false;
            datelong.disabled = false;

            submitBtn.disabled = !(ldateVal && noteVal);
        }
        else {
            dateshort.disabled = false;
            datenum.disabled = true;
            datelong.disabled = true;

            submitBtn.disabled = !(sdateVal && noteVal);
        }
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
        var shortRadio = document.getElementById('short');
        var longRadio = document.getElementById('long');

        var sdate = document.getElementById('dateshort').value;
        var sdateEr = document.getElementById('sdateEr');

        var ldate = document.getElementById('datelong').value;
        var ldateEr = document.getElementById('ldateEr');

        var submitBtn = document.getElementById('submit');

        if (shortRadio.checked) {
            var curDate = new Date();
            var insDate = new Date(sdate);

            if (sdate.length == 0)
                sdateEr.style.display = 'none';
            else if (curDate >= insDate) {
                sdateEr.innerHTML = 'Rental date should be after current date!';
                sdateEr.style.display = '';
            }
            else {
                sdateEr.style.display = 'none';
                sdateVal = true;
            }

            submitBtn.disabled = !(sdateVal && noteVal);
        }

        if (longRadio.checked) {
            var currDate = new Date();
            var inlDate = new Date(ldate);

            if (ldate.length == 0)
                ldateEr.style.display = 'none';
            else if (currDate >= inlDate) {
                ldateEr.innerHTML = 'Rental date should be after current date!';
                ldateEr.style.display = '';
            }
            else {
                ldateEr.style.display = 'none';
                ldateVal = true;
            }

            submitBtn.disabled = !(ldateVal && noteVal);
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
                <p style="margin-bottom: 0;">Please provide information to complete making rental:</p>
                <ul class="header-dropdown m-r--5">
                    <small style="color: orange !important;"><?= 'Color: '.$car['color'].' - Seats: '.$car['seats'].' - Fuel: '.$car['fuel'].' - Type: '.($car['transmission'] == 0 ? 'Auto' : ($car['transmission'] ? 'Manual' : 'Dual')); ?></small><br/>
                    <small style="color: orange !important; margin-bottom: 15px;"><?= 'Price: $'.round($car['ddprice'],2).' / day --- $'.round($car['kmprice'],2).' / 50km'; ?></small><br/>
                    <?= $this->Html->link('Cancel Rental', $this->request->referer(), ['class' => 'waves-effect waves-light btn btn-sm btn-danger pull-right']); ?>
                </ul>
            </div>
            <?= $this->Form->create($rental); ?>
            <div class="card-body">
                <div class="table" style="margin: 0;">
                    <table class="table-responsive" style="margin: 0;">
                        <tr><th scope="row">Rental Type * </th>
                            <td><input type="radio" id="short" name="rental" value="short" checked class="form-control" onclick="showRows()" /> <label for="short" style="color: black;">Short Rental</label></td>
                            <td><input type="radio" id="long" name="rental" value="long" class="form-control" onclick="showRows()" /> <label for="long" style="color: black;">Long Rental</label></td>
                        </tr>
                        <tr><th scope="row">Rental Date *</th>
                            <td colspan="2">
                                <?= $this->Form->control('date', ['class' => 'form-control', 'type' => 'datetime-local', 'label' => false, 'required' => true, 'id' => 'dateshort', 'onchange' => 'validateForm()']); ?>
                                <p id="sdateEr" style="display: none; color: orangered;" class="small"></p>
                            </td>
                        </tr>
                        <tr><th scope="row">Number of rental dates *</th>
                            <td colspan="3">
                                <?= $this->Form->control('datenum', ['class' => 'form-control', 'type' => 'number', 'label' => false, 'required' => true, 'disabled' => true, 'id' => 'datenum', 'min' => '2', 'value' => '2']); ?>
                            </td>
                        </tr>
                        <tr><th scope="row">Rental starts on *</th>
                            <td colspan="2">
                                <?= $this->Form->control('datefrom', ['class' => 'form-control', 'type' => 'datetime-local', 'label' => false, 'disabled' => true, 'required' => true, 'id' => 'datelong', 'onchange' => 'validateForm()']); ?>
                                <p id="ldateEr" class="small" style="display: none; color: orangered;"></p>
                            </td>
                        </tr>
                        <tr><th scope="row">Instruction (OPTIONAL)</th>
                            <td colspan="2">
                                <?= $this->Form->textarea('note', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'id' => 'rentalnote', 'oninput' => 'countChars()', 'onchange' => 'validateForm()']); ?>
                                <p id="count" class="small">250 characters left</p>
                            </td>
                        </tr>
                        <tr><th scope="row">Estimated minimum charges</th>
                            <td colspan="2">
                                <p id="charges">$0</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'submit', 'type' => 'submit', 'disabled' => true, 'style' => 'width: 200px;']); ?>
                <?= $this->Form->end(); ?>

                <?= $this->Html->link('Continue Browsing', $this->request->referer(), ['class' => 'waves-effect waves-light btn btn-sm btn-info pull-right']); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>

