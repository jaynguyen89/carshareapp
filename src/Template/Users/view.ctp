<?= $this->Html->css('style.css') ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Account Overview</b></h4></div>
    <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
        <li class="tab"><a class="active" href="#accinfo">Account Information</a></li>
        <li class="tab"><a href="#rental">Rental Summary</a></li>
        <li class="tab"><a href="#request"><?= $requests ? '<i class="material-icons" style="color: orangered; font-size: medium">info_outline</i>' : ''?> Requests</a></li>
    </ul>
    <div id="accinfo" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <small style="color: #aeea00 !important;">Review and update your profile</small>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><?= $this->Html->link(__('Edit Profile'), ['controller' => 'customers', 'action' => 'adminEdit', $admin['id']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?></li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <tr>
                            <th scope="row">Staff ID</th>
                            <td><?= $admin['paypal']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?= $admin['email']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone</th>
                            <td><?= $admin['phone']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Home Address</th>
                            <td><?= $admin['address']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="rental" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <small style="color: #aeea00 !important;">A brief summary and statistics of recent rentals.</small>
                </h2>
            </div>
            <div class="body">
                <div class="card">
                    <div class="header"><h2>RENTALS STATISTICS <small style="color: #0c5460 !important;">Income x1000</small></h2></div>
                    <div class="body">
                        <div id="rentalstats"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-xs-12">
                        <div class="card">
                            <div class="header"><h2>AVG. RENTAl DURATIONS <small style="color: #0c5460 !important;">Short rental: hours - Long rental: days</small></h2></div>
                            <div class="body">
                                <div id="avgrentaldur"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-xs-12">
                        <div class="card">
                            <div class="header"><h2>AVG. IDLE TIME <small style="color: #0c5460 !important;">Minutes</small></h2></div>
                            <div class="body">
                                <div id="avgidletime"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="header"><h2>RATIO OF RENTALS BY PICKUP / RETURN / CANCELLED <small style="color: #0c5460 !important;">Percentage (%)</small></h2></div>
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-xs-6 center"><label class="label">2017 Q1</label>
                                <div id="rentalratio1" style="margin-top: -60px;"></div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-6 center"><label class="label">2017 Q2</label>
                                <div id="rentalratio2" style="margin-top: -60px;"></div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-6 center"><label class="label">2017 Q3</label>
                                <div id="rentalratio3" style="margin-top: -60px;"></div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-6 center"><label class="label">2017 Q4</label>
                                <div id="rentalratio4" style="margin-top: -60px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="header"><h2>TOP TRENDING CARS <small style="color: #0c5460 !important;">What people are having their eyes on</small></h2></div>
                    <div class="body">
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="card">
                                    <div class="card-image" style="margin-bottom: 0;">
                                        <img src="/img/5bd79ac6ca64ebe2c20455e69571ed3e-1.jpg" style="max-height: 180px;" class="responsive-img" alt=""/>
                                        <span class="chip bg-info" style="margin: 5px;">3209 Views - 1868 Watchers</span>
                                        <p style="font-size: small; font-weight: bold; margin-left: 10px;"><a href="/cars/view/1">Toyota Camry RZ 2013</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="card">
                                    <div class="card-image" style="margin-bottom: 0;">
                                        <img src="/img/e728dda72fc4da698a75fe9b4db48156-1.jpg" style="max-height: 180px;" class="responsive-img" alt=""/>
                                        <span class="chip bg-info" style="margin: 5px;">2017 Views - 994 Watchers</span>
                                        <p style="font-size: small; font-weight: bold; margin-left: 10px;"><a href="/cars/view/7">Toyota Land Cruiser VX 2008</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="card">
                                    <div class="card-image" style="margin-bottom: 0;">
                                        <img src="/img/5db245efe89b4cb17714181475e0e3d9-1.jpg" style="max-height: 180px;" class="responsive-img" alt=""/>
                                        <span class="chip bg-info" style="margin: 5px;">1866 Views - 421 Watchers</span>
                                        <p style="font-size: small; font-weight: bold; margin-left: 10px;"><a href="/cars/view/8">Toyota Land Cruiser GXL 2011</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="header bg-pink lighten-2"><h2>THE MOST PROFITABLE CAR</h2></div>
                    <div class="body">
                        <div class="card" style="margin: 0 auto; max-width: 400px;">
                            <div class="card-image">
                                <img src="/img/5bd79ac6ca64ebe2c20455e69571ed3e-1.jpg" class="responsive-img" alt=""/>
                                <span class="chip bg-info" style="margin: 5px;">3209 Views - 1868 Watchers</span>
                                <p style="font-size: small; font-weight: bold; margin-left: 10px;"><a href="/cars/view/1">Toyota Camry RZ 2013</a></p>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-lg-6 col-md-12 col-xs-12 center">
                                <label class="label bg-cyan" style="width: 100%; border-radius: 10px;">2017 Q2</label>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12 center"><label class="label">RENTAL RATIO</label>
                                        <div id="rentalratio" style="margin-top: -60px;"></div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 center"><label class="label">INCOME RATIO</label>
                                        <div id="incomeratio" style="margin-top: -60px;"></div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 center"><label class="label">AVG. IDLE</label>
                                        <div id="avgidle" style="margin-top: -60px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-xs-12 center">
                                <label class="label bg-cyan" style="width: 100%; border-radius: 10px;">2017 Q3</label>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12 center"><label class="label">RENTAL RATIO</label>
                                        <div id="rentalratios" style="margin-top: -60px;"></div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 center"><label class="label">INCOME RATIO</label>
                                        <div id="incomeratios" style="margin-top: -60px;"></div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 center"><label class="label">AVG. IDLE</label>
                                        <div id="avgidles" style="margin-top: -60px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="request" class="col s12">
        <div class="card">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <small style="color: #aeea00 !important;">A convenient way to resolve customers' requests.</small>
                </h2>
            </div>
            <div class="body">
                <?php if ($requests) { ?>
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0;">
                        <tr><th scope="col">#</th><th scope="col">Customer</th><th scope="col">Rental</th><th scope="col">Message</th><th scope="col">Action</th></tr>
                        <?php $i = 1; foreach ($requests as $request) { ?>
                        <tr><td><?= $i; ?></td>
                            <td><?= $this->Html->link($customersByRequest[$request['id']]['name'], ['controller' => 'Customers', 'action' => 'view', $customersByRequest[$request['id']]['id']]); ?></td>
                            <td><?= $this->Html->image($carsByRequest[$request['id']]['image'].'-1.jpg', ['style' => 'max-width: 100px; margin-right: 15px;', 'class' => 'responsive-img pull-left']); ?>
                                <div>
                                    <small><b>Rental ID:</b> <?= $this->Html->link($rentalsByRequest[$request['id']]['id'], ['controller' => 'Rentals', 'action' => 'view', $rentalsByRequest[$request['id']]['id']]); ?> (<?= $rentalsByRequest[$request['id']]['done'] ? 'Active' : 'Completed'; ?>)</small><br>
                                    <small><b>Car:</b> <?php $car = $carsByRequest[$request['id']]; echo $car['make'].' '.$car['model'].' '.$car['year'].' ('.$car['color'].')';?></small><br>
                                    <small><?= ucfirst($rentalsByRequest[$request['id']]['type']); ?> Rental - <?php if ($rentalsByRequest[$request['id']]['done'])
                                            echo '<b>Ends on:</b> '.((new DateTime($rentalsByRequest[$request['id']]['todate']))->format('d/m/Y H:i'));
                                        else echo '<b>Starts on:</b> '.((new DateTime($rentalsByRequest[$request['id']]['fromdate']))->format('d/m/Y H:i')); ?></small>
                                </div></td>
                            <td><div style="word-wrap: break-word;"><?= $request['content']; ?></div></td>
                            <td><?= $this->Form->postLink(__('Resolve...'), ['controller' => 'Rentals', 'action' => 'edit', '?' => ['rid' => $rentalsByRequest[$request['id']]['id'], 'from' => 'adminview']], ['style' => 'margin-bottom: 15px;']); ?><br>
                                <?= $this->Form->postLink(__('Ignore'), ['controller' => 'Requests', 'action' => 'edit', $request['id']], ['confirm' => __('Ignore request #{0}. Are you sure?', $i), 'class' => 'text-danger']); ?></td></tr>
                        <?php $i++; } ?>
                    </table>
                </div>
                <?php } else { ?>
                    <p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> There is no more requests at the moment. Well done!</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<script type="text/javascript">
    new Morris.Line({
        element: 'rentalstats',
        data: [
            { month: '2017-04', short: 524, long: 281, income: 142},
            { month: '2017-05', short: 706,  long: 245, income: 213},
            { month: '2017-06', short: 487,  long: 332, income: 168},
            { month: '2017-07', short: 602,  long: 297, income: 155},
            { month: '2017-08', short: 819,  long: 312, income: 228},
            { month: '2017-09', short: 483,  long: 253, income: 127},
            { month: '2017-10', short: 86, long: 28, income: 25}
        ],
        xkey: 'month',
        ykeys: ['short', 'long','income'],
        labels: ['Short Rentals', 'Long Rentals','Income'],
        colors: ['Red','Blue','Green'],
        hideHover: true
    });

    new Morris.Bar({
        element: 'avgrentaldur',
        data: [
            { quarter: '2017 Q1', short: 5.8, long: 13.7 },
            { quarter: '2017 Q2', short: 11.3,  long: 9.1 },
            { quarter: '2017 Q3', short: 8.9,  long: 12.3 },
            { quarter: '2017 Q4', short: 2.5, long: 4.2 }
        ],
        xkey: 'quarter',
        ykeys: ['short', 'long'],
        labels: ['Short Rentals', 'Long Rentals'],
        colors: ['Blue','Green'],
        hideHover: true
    });

    new Morris.Bar({
        element: 'avgidletime',
        data: [
            { quarter: '2017 Q1', idle: 57.7 },
            { quarter: '2017 Q2', idle: 48.3 },
            { quarter: '2017 Q3', idle: 43.9 },
            { quarter: '2017 Q4', idle: 52.5 }
        ],
        xkey: 'quarter',
        ykeys: ['idle'],
        labels: ['Idle'],
        colors: ['Blue'],
        hideHover: true
    });

    new Morris.Donut({
        element: 'rentalratio1',
        data: [
            { label: 'Cancelled', value: 12 },
            { label: 'In-time', value: 71 },
            { label: 'Late', value: 17 }
        ]
    });

    new Morris.Donut({
        element: 'rentalratio2',
        data: [
            { label: 'Cancelled', value: 7 },
            { label: 'In-time', value: 65 },
            { label: 'Late', value: 28 }
        ]
    });

    new Morris.Donut({
        element: 'rentalratio3',
        data: [
            { label: 'Cancelled', value: 14 },
            { label: 'In-time', value: 77 },
            { label: 'Late', value: 9 }
        ]
    });

    new Morris.Donut({
        element: 'rentalratio4',
        data: [
            { label: 'Cancelled', value: 0 },
            { label: 'In-time', value: 94 },
            { label: 'Late', value: 6 }
        ]
    });

    new Morris.Donut({
        element: 'rentalratio',
        data: [
            { label: 'Others', value: 81.6 },
            { label: 'This Car', value: 18.4 }
        ]
    });

    new Morris.Donut({
        element: 'incomeratio',
        data: [
            { label: 'Others', value: 72.3 },
            { label: 'This Car', value: 27.7 }
        ]
    });

    new Morris.Donut({
        element: 'avgidle',
        data: [
            { label: 'Others', value: 74.5 },
            { label: 'This Car', value: 26.5 }
        ]
    });

    new Morris.Donut({
        element: 'rentalratios',
        data: [
            { label: 'Others', value: 74.6 },
            { label: 'This Car', value: 25.4 }
        ]
    });

    new Morris.Donut({
        element: 'incomeratios',
        data: [
            { label: 'Others', value: 67.3 },
            { label: 'This Car', value: 32.7 }
        ]
    });

    new Morris.Donut({
        element: 'avgidles',
        data: [
            { label: 'Others', value: 80.6 },
            { label: 'This Car', value: 19.4 }
        ]
    });
</script>