<?= $this->Html->css('style.css') ?>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Rental Cart</b></h4></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%;">
            <div class="header bg-indigo darken-4">
                <h2>Customer: <?= $customer['name']; ?>
                    <small style="color: #aeea00 !important;">Review your rental cart</small>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <?= $this->Html->link(__('Continue Broswing'), ['controller' => 'Cars', 'action' => 'carsOnMap'], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?>
                </ul>
            </div>
            <div class="card-body">
                <?php if ($rentals) { ?>
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <tr><th scope="col">#</th><th scope="col">Car</th><th scope="col">Color</th><th scope="col">Rental Start</th><th scope="col">Rental End</th><th scope="col">Note</th><th scope="col">Value</th><th scope="col">Action</th></tr>
                        <?php $i = 1; foreach ($rentals as $rental) { ?>
                        <tr><td><?= $i; ?></td>
                            <td><?= $this->Html->link($carNamesByRental[$rental['id']], ['controller' => 'Cars', 'action' => 'view', '?' => ['carid' => $rental['car_id'], 'from' => 'cart']]); ?></td>
                            <td><?= $carColorsByRental[$rental['id']]; ?></td>
                            <td><?= (new DateTime($rental['fromdate']))->format('d/m/Y H:i'); ?></td>
                            <td><?= (new DateTime($rental['todate']))->format('d/m/Y H:i'); ?></td>
                            <td><?= (strlen($rental['note']) > 30) ? substr($rental['note'], 0, 20).'...' : $rental['note']; ?></td>
                            <td class="right-align">$<?= $rental['value']; ?></td>
                            <td>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', '?' => ['rid' => $rental['id'], 'from' => 'cart']]); ?>
                                <?= $this->Form->postLink('Remove', ['action' => 'delete', '?' => ['rid' => $rental['id'], 'from' => 'cart']], ['confirm' => __('{0} will be removed from your Cart. Are you sure?', $carNamesByRental[$rental['id']]), 'class' => 'pull-right text-danger']); ?>
                            </td></tr>
                        <?php $i++; } ?>
                        <tr>
                            <th scope="row" class="right-align" colspan="4">Grant Total <small>(initial charges)</small></th>
                            <td colspan="2" class="right-align">$<?= $total; ?></td>
                        </tr>
                    </table>
                </div>
                <?php } else { ?>
                    <div class="header center-align"><p style="font-size: 2em; padding-top: 20px; line-height: 150%">You don't have active rentals.</p><p>Please take a look at our <a href="/cars/cars_on_map">impressive cars!</a></p></div>
                <?php } ?>
            </div>
            <?php if ($rentals) { ?>
            <div class="card-action">
                <div id="paypal-button" class="pull-left" style="margin-right: 5px;"></div>
                <a href="/rentals/verify-card" style="border-radius: 25px;" class="waves-effect waves-light btn btn-sm btn-success <?= ($customer['cardcode'] ? '' : 'disabled'); ?>"><i class="fa fa-cc-visa"></i> Card Checkout</a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    paypal.Button.render({

        env: 'sandbox', // Or 'sandbox'

        style: {
            label: 'checkout',
            size:  'medium',    // small | medium | large | responsive
            shape: 'pill',     // pill | rect
            color: 'blue'      // gold | blue | silver | black
        },

        client: {
            sandbox:    'AcuwneqRyOp9MX_n87Rfl0m8V01NkWeLxxmQdIxokH1E960-E--xVL5K_wlB78ElKFL7WScpfFWl6U49'
        },

        commit: true, // Show a 'Pay Now' button

        payment: function(data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: <?= $total; ?>, currency: 'AUD' }
                        }
                    ]
                }
            });
        },

        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function(payment) {
                window.alert('Payment ' + payment + ' has been completed!');
                window.location.href = '/rentals/commonCheckout';
            });
        }

    }, '#paypal-button');
</script>
<?= $this->Html->script('admin.js') ?>
