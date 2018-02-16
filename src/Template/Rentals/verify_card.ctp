<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Verify Card</b></h4></div>
    <div class="card horizontal" style="width: 100%">
        <div class="card-header bg-indigo darken-4">
            <h2>Customer: <?= $customer['name']; ?>
                <small style="color: #aeea00 !important;">Please verify your card information to continue payment.</small>
            </h2>
            <ul class="header-dropdown m-r--5">
                <?= $this->Html->link(__('Cancel'), ['controller' => 'Rentals', 'action' => 'index'], ['class' => 'waves-effect waves-light btn btn-sm btn-danger']); ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="margin: 0;">
                <table class="table">
                    <tr><th scope="row">Payment Total</th><td><b>$<?= $total; ?></b> for <?= $count == 1 ? $count.' rental' : $count.' rentals'; ?></td></tr>
                    <tr><th scope="row">Card Holder</th><td><?= $customer['cardholder'] ? $customer['cardholder'] : $customer['name']; ?></td></tr>
                    <tr><th scope="row">Card Code</th><td><?= $customer['cardcode'] ? 'xxxx xxxx xxxx x'.substr($customer['cardcode'], -3) : 'xxxx xxxx xxxx xxxx'; ?></td></tr>
                    <tr><th scope="row">Card Date</th><td><?php $date = new \Cake\Chronos\Date($customer['carddate']); echo $date->format('d-m-Y'); ?></td></tr>
                    <tr><th scope="row">Card Address</th><td><?= $customer['cardaddress'] ? $customer['cardaddress'] : $customer['address']; ?></td></tr>
                </table>
            </div>
            <script type="text/javascript">
                function checkCode(ele) {
                    var code = ele.value;
                    var btn = document.getElementById('paynow');

                    if (!isNaN(code) && code.length === 3)
                        btn.classList.remove('disabled');
                    else
                        btn.classList.add('disabled');
                }
            </script>
            <form method="post" action="/rentals/verifyCard">
            <div class="card-body">
                <div class="table-responsive" style="margin: 0;">
                    <table class="table">
                        <tr><th scope="row">Security Code</th>
                            <td><input type="text" class="form-control" pattern="[0-9]{3}" required placeholder="Exactly 3 digits required. Ex: 567" onchange="checkCode(this)" /></td></tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <a id="paynow" class="btn btn-sm btn-info lighten-2 waves-effect waves-light disabled" href="/rentals/commonCheckout">Pay Now</a>
            </div>
            </form>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
