<?php
/**
  * @var \App\View\AppView $this
  */
?>

<?= $this->Html->css('style.css') ?>

<script type="text/javascript">
    var loadpaypal = '';
    function checkForm() {
        var cardname = document.getElementById("cardname").value;
        var cardcode = document.getElementById("cardcode").value;
        var carddate = document.getElementById("carddate").value;
        var cardaddress = document.getElementById("cardaddress").value;
        var paypal = document.getElementById("paypal").value;
        var submit = document.getElementById("submit");

        var nameVal = checkName(cardname);
        var codeVal = checkCode(cardcode);
        var dateVal = checkDate(carddate);
        var addVal = checkAddress(cardaddress);
        var comboVal = checkCombo(cardname, cardcode, carddate, cardaddress);

        var nameEr = document.getElementById("nameEr");
        var codeEr = document.getElementById("codeEr");
        var dateEr = document.getElementById("dateEr");
        var addEr = document.getElementById("addEr");
        var message = document.getElementById("saveMsg");

        if (comboVal !== 0) {
            nameEr.style.display = 'none';
            codeEr.style.display = 'none';
            dateEr.style.display = 'none';
            addEr.style.display = 'none';
        }

        if (paypal.length === 0)
            submit.disabled = !(comboVal === 1);
        else
            submit.disabled = ((paypal.localeCompare(loadpaypal) === 0) && (comboVal === 0));

        if (!submit.disabled)
            message.style.display = 'none';
    }

    function compareDate() {
        var carddate = document.getElementById("carddate").value;
        var dateEr = document.getElementById("dateEr");

        var curDate = new Date();
        var insDate = new Date(carddate);

        if (curDate >= insDate) {
            dateEr.innerHTML = 'Rental date should be after current date!';
            dateEr.style.display = '';
            document.getElementById("submit").disabled = true;
        }
        else
            dateEr.style.display = 'none';
    }

    function showNameEr() {
        var cardname = document.getElementById("cardname").value;
        var cardcode = document.getElementById("cardcode").value;
        var carddate = document.getElementById("carddate").value;
        var cardaddress = document.getElementById("cardaddress").value;
        var nameEr = document.getElementById("nameEr");
        if (!checkName(cardname) && checkCombo(cardname, cardcode, carddate, cardaddress) === 0) {
            nameEr.innerHTML = "Credit Card holder is empty!";
            nameEr.style.display = '';
        }
        else
            nameEr.style.display = 'none';
    }
    function showCodeEr() {
        var cardname = document.getElementById("cardname").value;
        var cardcode = document.getElementById("cardcode").value;
        var carddate = document.getElementById("carddate").value;
        var cardaddress = document.getElementById("cardaddress").value;
        var codeEr = document.getElementById("codeEr");
        if (!checkCode(cardcode) && checkCombo(cardname, cardcode, carddate, cardaddress) === 0) {
            codeEr.innerHTML = "Credit Card serial code is empty!";
            codeEr.style.display = '';
        }
        else
            codeEr.style.display = 'none';
    }
    function showDateEr() {
        var cardname = document.getElementById("cardname").value;
        var cardcode = document.getElementById("cardcode").value;
        var carddate = document.getElementById("carddate").value;
        var cardaddress = document.getElementById("cardaddress").value;
        var dateEr = document.getElementById("dateEr");
        if (!checkDate(carddate) && checkCombo(cardname, cardcode, carddate, cardaddress) === 0) {
            dateEr.innerHTML = "Credit Card expiry date is empty!";
            dateEr.style.display = '';
        } else if (carddate !== '') {
            compareDate();
        }
        else
            dateEr.style.display = 'none';
    }
    function showAddEr() {
        var cardname = document.getElementById("cardname").value;
        var cardcode = document.getElementById("cardcode").value;
        var carddate = document.getElementById("carddate").value;
        var cardaddress = document.getElementById("cardaddress").value;
        var addEr = document.getElementById("addEr");
        if (!checkAddress(cardaddress) && checkCombo(cardname, cardcode, carddate, cardaddress) === 0) {
            addEr.innerHTML = "Credit Card address is empty!";
            addEr.style.display = '';
        }
        else
            addEr.style.display = 'none';
    }

    function checkName(name) { return !(name === ''); }
    function checkCode(code) { return !(code === ''); }
    function checkDate(date) { return !(date === ''); }
    function checkAddress(address) { return !(address === ''); }
    function checkCombo(name, code, date, address) {
        if (name === '' && code === '' && date === '' && address === '')
            return -1;
        else if (name === '' || code === '' || date === '' || address === '')
            return 0;

        return 1;
    }
</script>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Update Profile</b></h4></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <p style="font-size: larger;">Customer: <?= $customer['name']; ?></p>
                <ul class="header-dropdown m-r--5"><li><?= $this->Html->link(__('Cancel'), ['action' => 'dashboard', $customer['user_id']], ['class' => 'waves-effect waves-light btn btn-sm btn-danger']) ;?></li></ul>
            </div>
            <?= $this->Form->create($customer); ?>
            <div class="card-body">
                <p class="font-bold" style="font-size: larger">General Information</p>
                <table class="table" style="margin-bottom: 0;">
                    <tr><th scope="row">Full Name</th>
                        <td><?= $this->Form->control('name', array('value' => $customer['name'], 'class' => 'form-control', 'label' => false, 'id' => 'name', 'type' => 'text', 'required' => true)); ?></td>
                    </tr>
                    <tr><th scope="row">Email</th>
                        <td><?= $this->Form->control('email', array('value' => $customer['email'], 'class' => 'form-control', 'label' => false, 'id' => 'email', 'type' => 'text', 'required' => true)); ?></td>
                    </tr>
                    <tr><th scope="row">Phone</th>
                        <td><?= $this->Form->control('phone', array('value' => $customer['phone'], 'class' => 'form-control', 'label' => false, 'id' => 'phone', 'type' => 'text', 'required' => true)); ?></td>
                    </tr>
                    <tr><th scope="row">Home Address</th>
                        <td><?= $this->Form->control('address', array('value' => $customer['address'], 'class' => 'form-control', 'label' => false, 'id' => 'address', 'type' => 'text', 'required' => true)); ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <p class="font-bold" style="font-size: larger">Credit Card Information</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr><th scope="row">Name On Card</th>
                            <td><?= $this->Form->control('cardholder', array('value' => $customer['cardholder'], 'class' => 'form-control', 'label' => false, 'id' => 'cardname', 'type' => 'text', 'onchange' => 'checkForm()', 'onblur' => 'showNameEr()')); ?>
                                <p id="nameEr" style="display: none; color: orangered;" class="small"></p></td>
                        </tr>
                        <tr><th scope="row">Card Code</th>
                            <td><?php $value = (!empty($customer['cardcode'])) ? 'xxxx xxxx xxxx x'.substr($customer['cardcode'], -3) : '';
                                echo $this->Form->control('cardcode', array('value' => $value, 'class' => 'form-control', 'label' => false, 'id' => 'cardcode', 'type' => 'text', 'onchange' => 'checkForm()', 'onblur' => 'showCodeEr()')); ?>
                                <p id="codeEr" style="display: none; color: orangered;" class="small"></p></td>
                        </tr>
                        <tr><th scope="row">Card Expiry Date</th>
                            <td><input name="carddate" type="date" id="carddate" value="<?= $customer['carddate']; ?>" class="form-control" onchange="checkForm();compareDate();" onblur="showDateEr()"/>
                                <p id="dateEr" style="display: none; color: orangered;" class="small"></p></td>
                        </tr>
                        <tr><th scope="row">Your Address On Card</th>
                            <td><?= $this->Form->control('cardaddress', array('value' => $customer['cardaddress'], 'class' => 'form-control', 'label' => false, 'id' => 'cardaddress', 'type' => 'text', 'onchange' => 'checkForm()', 'onblur' => 'showAddEr()')); ?>
                                <p id="addEr" style="display: none; color: orangered;" class="small"></p></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <p class="font-bold" style="font-size: larger">Paypal Address</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr><th scope="row">Paypal Email</th>
                            <td><?= $this->Form->control('paypal', array('value' => $customer['paypal'], 'class' => 'form-control', 'label' => false, 'id' => 'paypal', 'type' => 'text', 'onchange' => 'checkForm()')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'submit', 'type' => 'submit', 'disabled' => true]); ?>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
