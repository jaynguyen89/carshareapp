<?= $this->Html->css('style.css') ?>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Account Overview</b></h4></div>
    <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
        <li class="tab"><a class="active" href="#accinfo">Account Information</a></li>
        <li class="tab"><a href="#rental">Rental History</a></li>
        <li class="tab"><a href="#recent">Recent Browsing</a></li>
        <li class="tab"><a href="#watched">Watched Cars</a></li>
        <li class="tab"><a href="#message">Requests</a></li>
    </ul>
    <div id="accinfo" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Customer: <?= $customer['name']; ?>
                    <small style="color: #aeea00 !important;">Your Profile Completion: <b><?= $percentage; ?>%</b>.
                        <?php if ($percentage < 80)
                            echo ' Please update your payment information. We accept Credit Card and Paypal.';
                        else if ($percentage < 90)
                            echo ' Your payment method is Paypal. You can also provide <b>Credit Card</b> payment.'; ?>
                    </small>
                </h2>
                <ul class="header-dropdown m-r--5">                        
                    <li><?= $this->Html->link(__($percentage == 100 ? 'Edit Profile' : 'Update Profile'), ['action' => 'edit', $customer['id']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?></li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <tr>
                            <th scope="row">Email</th>
                            <td><?= $customer['email']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone</th>
                            <td><?= $customer['phone']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Home Address</th>
                            <td><?= $customer['address']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <p class="font-bold">Card Payment</p>
                <?php if (empty($customer['cardholder']) && empty($customer['cardcode']) &&
                    empty($customer['carddate']) && empty($customer['cardaddress']))
                    echo '<p>Please update your Credit Card payment.</p>';
                else { ?>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th scope="row">Card Name</th>
                                <td><?php if (!empty($customer['cardholder'])) echo $customer['cardholder']; else echo 'Please update your card information.'; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Card Code</th>
                                <td><?php if (!empty($customer['cardcode'])) {
                                        $lastDigits = substr($customer['cardcode'], -3);
                                        echo 'xxxx xxxx xxxx x'.$lastDigits;
                                    } else echo 'Please update your card information.'; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Expiry Date</th>
                                <td><?php if (!empty($customer['carddate'])) {
                                        $carddate = new \Cake\Chronos\Date($customer['carddate']);
                                        echo $carddate->format('d-m-Y');
                                    } else echo 'Please update your card information.'; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Card Address</th>
                                <td><?php if (!empty($customer['cardaddress'])) echo $customer['cardaddress']; else echo 'Please update your card information.'; ?></td>
                            </tr>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <div class="card-action">
                <p class="font-bold">Paypal Payment</p>
                <?php if (empty($customer['paypal']))
                    echo '<p>Please update your Paypal payment.</p>';
                else { ?>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th scope="row">Paypal</th>
                            <td><?= $customer['paypal']; ?></td>
                        </tr>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div id="rental" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Customer: <?= $customer['name']; ?>
                    <small style="color: #aeea00 !important;">Select a transaction to see details.</small>
                </h2>
            </div>
            <?php if ($upcomingRentals) { ?>
            <div class="card-body">
                <div class="row"><p style="font-size: large; font-weight: bold;">Your Upcoming Rentals (in date order)</p></div>
                <div class="table-responsive">
                    <table style="margin-bottom: 0;" class="table">
                        <tr><th scope="col">ID</th><th scope="col">Car</th><th scope="col">Rental Date</th><th scope="col">Note</th><th scope="col">Value</th><th scope="col">Action</th></tr>
                        <?php foreach ($upcomingRentals as $upcomingRental) { ?>
                        <tr><td><?= $upcomingRental['id']; ?></td>
                            <td><?= $this->Html->image($carImagesByRental[$upcomingRental['id']].'-1.jpg', ['style' => 'max-width: 100px;', 'class' => 'responsive-img']); ?>
                                <?= $this->Html->link($carNamesByRental[$upcomingRental['id']], ['controller' => 'Cars', 'action' => 'view', $upcomingRental['car_id']]); ?></td>
                            <td><p style="margin-bottom: 0;"><?= (new DateTime($upcomingRental['fromdate']))->format('d/m/Y H:i'); ?></p>
                                <p style="margin-top: 0;">Duration: <?php $label = ($upcomingRental['type'] == 'short') ? ' hour(s)' : ' day(s)'; echo $upcomingRental['duration'].$label; ?></p></td>
                            <td><?= (strlen($upcomingRental['note']) > 30) ? substr($upcomingRental['note'], 0, 20).'...' : $upcomingRental['note']; ?></td>
                            <td>$<?= $upcomingRental['value']; ?></td>
                            <td><?= $this->Html->link(__('Request...'), ['controller' => 'Requests', 'action' => 'add', $upcomingRental['id']]); ?></td></tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } ?>
            <?php if ($pastRentals) { ?>
            <div class="card-body">
                <div class="row"><p style="font-size: large; font-weight: bold;">Your Rental History (latest first)<br><small style="color: orangered">*** You can only initiate a request for rentals not older than 1 month ***</small></p></div>
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <tr><th scope="col">ID</th><th scope="col">Car</th><th scope="col">Rental Date</th><th scope="col">Value</th><th scope="col">Fines</th><th scope="col">Action</th></tr>
                        <?php foreach ($pastRentals as $pastRental) { ?>
                        <tr><td><?= $pastRental['id']; ?></td>
                            <td><?= $this->Html->image($carImagesByPast[$pastRental['id']].'-1.jpg', ['style' => 'max-width: 100px;', 'class' => 'responsive-img']); ?>
                                <?= $this->Html->link($carNamesByPast[$pastRental['id']], ['controller' => 'Cars', 'action' => 'view', $pastRental['car_id']]); ?></td>
                            <td><p style="margin-bottom: 0;"><?= (new DateTime($pastRental['fromdate']))->format('d/m/Y H:i'); ?></p>
                                <p style="margin-top: 0;">Duration: <?php $label = ($pastRental['type'] == 'short') ? ' hour(s)' : ' day(s)'; echo $pastRental['duration'].$label; ?></p></td>
                            <td>$<?= $pastRental['value']; ?></td><td>$<?= $pastRental['fine'] != 0 ? $pastRental['fine'] : '0.0'; ?></td>
                            <td><?php $totime = new DateTime($pastRental['todate']); $totimeAdded = $totime->modify('+1 month');
                            if ($totimeAdded >= new DateTime())
                                echo $this->Html->link(__('Request...'), ['controller' => 'Requests', 'action' => 'add', $pastRental['id']]); ?></td></tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } ?>
            <?php if (!$upcomingRentals && !$pastRentals) { ?>
                <p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> You haven't made any rental yet. Take a look at our cars to ride one now!</p>
            <?php } ?>
        </div>
    </div>
    <div id="recent" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Customer: <?= $customer['name']; ?>
                    <small style="color: #aeea00 !important;">Don't miss out on these amazing cars!</small>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><a class="<?php if (!$browsings) echo 'disabled'; ?> waves-effect waves-light btn btn-sm btn-danger" href="#">Clear Browsings</a></li>
                </ul>
            </div>
            <div class="body">
                <?php if (!$browsings) { ?>
                <div class="header center-align">
                    <p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> Your browsing is empty. Go to see amazing cars now!</p>
                </div>
                <?php } else { ?>
                <div class="row" style="margin-bottom: 0;">
                    <?php foreach ($browsings as $browsing): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card">
                                <div class="card-image">
                                    <?= $this->Html->image($browsing['car']['image'].'-1.jpg', ['style' => 'max-height: 150px;', 'class' => 'responsive-img']); ?>
                                    <span class="badge bg-info" style="margin-top: 5px; color: white">Seen: <?= $browsing['look']; ?></span>
                                </div>
                                <div class="card-content" style="margin: 0;">
                                    <p style="font-size: small; font-weight: bold; margin-top: 10px;"><?= $this->Html->link($browsing['car']['make'].' '.$browsing['car']['model'].' '.$browsing['car']['year'], ['controller' => 'Cars', 'action' => 'view', $browsing['car_id']]); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div id="watched" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Customer: <?= $customer['name']; ?>
                    <small style="color: #aeea00 !important;">Hurry! A lot of people are having their eyes on these cars.</small>
                </h2>
            </div>
            <div class="body">
                <?php if (!$watches) { ?>
                    <div class="header center-align">
                        <p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> You haven't had a watched car yet.</p>
                    </div>
                <?php } else { echo '<div class="row" style="margin-bottom: 0;">';
                    foreach ($watches as $watch) { ?>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-image" style="margin-bottom: 0;">
                                <?= $this->Html->image($imageNames[$watch['car_id']].'-1.jpg', ['style' => 'max-height: 180px;', 'class' => 'responsive-img']); ?>
                                <span class="chip bg-info" style="margin: 5px;"><?= $watchesCount[$watch['car_id']] > 1 ? $watchesCount[$watch['car_id']].' Watchers' : $watchesCount[$watch['car_id']].' Watcher'; ?></span>
                                <span class="chip bg-info" style="margin: 5px;"><?= $rentalCount[$watch['car_id']] > 1 ? $rentalCount[$watch['car_id']].' Active Rentals' : $rentalCount[$watch['car_id']].' Active Rental'; ?></span>
                            </div>
                            <div class="card-body" style="margin: 0;">
                                <p style="font-size: small; font-weight: bold; margin-top: 0px; margin-bottom: 0;"><?= $this->Html->link($carNames[$watch['car_id']], ['controller' => 'Cars', 'action' => 'view', $watch['car_id']]); ?></p>
                                <?= $this->Form->postLink(__('Unwatch'), ['controller' => 'Browsings', 'action' => 'edit', $watch['id']], ['confirm' => __('{0} will be removed from your watched list?', $carNames[$watch['car_id']]), 'class' => 'text-danger']); ?>
                            </div>
                        </div>
                    </div>
                <?php } echo '</div>'; } ?>
            </div>
        </div>
    </div>
    <div id="message" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Customer: <?= $customer['name']; ?>
                    <small style="color: #aeea00 !important;">Reviewing and responding to your requests made easy.</small>
                </h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <tr><th scope="col">#</th><th scope="col">Rental</th><th scope="col">Note</th><th scope="col">Tracking</th><th scope="col">Files</th><th scope="col">Status</th><th scope="col">Action</th></tr>
                        <?php $i = 1; foreach ($requests as $request) { ?>
                        <tr><td><?= $i; ?></td>
                            <td><?= $this->Html->image($carsByRequest[$request['id']]['image'].'-1.jpg', ['style' => 'max-width: 100px; margin-right: 15px;', 'class' => 'responsive-img pull-left']); ?>
                                <div>
                                    <small><b>Rental ID:</b> <?= $rentalsByRequest[$request['id']]['id']; ?> (<?= $rentalsByRequest[$request['id']]['done'] ? 'Active' : 'Completed'; ?>)</small><br>
                                    <small><b>Car:</b> <?php $car = $carsByRequest[$request['id']]; echo $car['make'].' '.$car['model'].' '.$car['year'].' ('.$car['color'].')';?></small><br>
                                    <small><?= ucfirst($rentalsByRequest[$request['id']]['type']); ?> Rental - <?php if ($rentalsByRequest[$request['id']]['done'])
                                        echo '<b>Ends on:</b> '.((new DateTime($rentalsByRequest[$request['id']]['todate']))->format('d/m/Y H:i'));
                                        else echo '<b>Starts on:</b> '.((new DateTime($rentalsByRequest[$request['id']]['fromdate']))->format('d/m/Y H:i')); ?></small>
                                </div></td>
                            <td><?php $tok = explode(' - ', $request['type']); echo $tok[1]; ?></td>
                            <td>Created: <?= ((new DateTime($request['created']))->format('d/m/Y H:i')); ?>
                                <?php if ($request['status']) echo '<br>Resolved: ---';
                                    else echo '<br>Resolved: '.((new DateTime($request['modified']))->format('d/m/Y H:i')); ?></td>
                            <td><?php if ($request['note']) { $toks = explode('.', $request['note']); echo strtoupper($toks[1]); } else echo '---'; ?></td>
                            <td><?= ($request['status']) ? 'Opened' : 'Closed'; ?></td>
                            <td><?= ($request['status']) ? $this->Form->postLink(__('Cancel'), ['controller' => 'Requests', 'action' => 'delete', $request['id']], ['confirm' => __('Request #{0} will be cancelled', $i), 'class' => 'text-danger']) : ''; ?></td></tr>
                        <?php $i++; } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>





