<?= $this->Html->css('style.css') ?>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Return Car</b></h4></div>
    <div class="card">
        <div class="header bg-indigo darken-4">
            <h2>Customer: <?= $customer['name']; ?>
                <small style="color: #aeea00 !important;">Please select a rental to return car.</small>
            </h2>
        </div>
        <div class="body">
            <?php if ($carsToReturn) { ?>
                <div class="table-responsive">
                    <table style="margin: 0;" class="table">
                        <tr><th scope="col">ID</th><th scope="col">Car</th><th scope="col">Rental End</th><th scope="col">Value</th><th scope="col">Action</th></tr>
                        <?php foreach($carsToReturn as $rental): ?>
                            <tr><td><?= $rental['id']; ?></td>
                                <td><?= $this->Html->image($carImgByRental[$rental['id']].'-1.jpg', ['style' => 'max-width: 100px;', 'class' => 'responsive-img']); ?>
                                    <?= $this->Html->link($carNameByRental[$rental['id']], ['controller' => 'Cars', 'action' => 'view', $rental['car_id']]); ?></td>
                                <td><p style="margin-bottom: 0;"><?= (new DateTime($rental['todate']))->format('d/m/Y H:i'); ?></p>
                                    <p style="margin-top: 0;">Duration: <?php $label = ($rental['type'] == 'short') ? ' hour(s)' : ' day(s)'; echo $rental['duration'].$label; ?></p></td>
                                <td>$<?= $rental['value']; ?></td>
                                <td><?= $this->Html->link(__('Return'), ['controller' => 'Rentals', 'action' => 'proceed', $rental['id']]); ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php } else { ?>
                <p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> You don't have cars to return. Take a look at our cars and rent now.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
