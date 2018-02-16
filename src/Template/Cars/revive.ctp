<?= $this->Html->css('style.css') ?>
<?php $user = $this->request->session()->read('Auth.User');?>

<?= $this->Flash->render(); ?>
    <div class="container">
        <div class="text-center teal-text darken-4"><h4><b>Revive Cars</b></h4></div>
        <div class="row">
            <?php if (empty($cars)) echo '<p style="font-size: 2em; padding-top: 20px; line-height: 150%"><i class="material-icons medium">feedback</i> There is no suspended cars at the moment.</p>'; else
            foreach ($cars as $car): ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-image">
                            <?php if ($car['image']) echo $this->Html->image($car['image'].'-1.jpg', ['style' => 'max-height: 265px;']);
                            else echo '<img src="http://www.donaldcampbellmotors.co.uk/wp-content/uploads/2014/06/Awaiting-image11.jpg" style="max-height: 265px;" alt=""/>'; ?>
                            <span class="card-title" style="background-color: darkgray; border-radius: 20px; padding: 7px; font-size: medium; margin-left: 10px;"><?= $car['make'].' '.$car['model'].' '.$car['year']; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="row" style="margin: 0;">
                                <div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-dashboard"> ODO</i> <p class="small font-bold"><?= $car['odometer'].'kms'; ?></p></div>
                                <div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-car"> BODY</i> <p class="small font-bold"><?= $car['type']; ?></p></div>
                                <div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-rocket"> DRIVE</i> <p class="small font-bold"><?= $car['drivetype']; ?></p></div>
                                <div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-beer"> FUEL</i> <p class="small font-bold"><?= $car['fuel']; ?></p></div>
                            </div>
                            <div class="row" style="margin: 0;">
                                <div class="table-responsive small" style="margin: 0;">
                                    <table class="table" style="margin: 0;">
                                        <tr><th scope="row"></th><td><?= $car['shortdesc']; ?></td></tr>
                                        <tr><th scope="row">Engine</th><td><?php $engine = '';
                                                if ($car['enginesize'])
                                                    $engine .= round($car['enginesize']/1000,1).'L '.$car['cylinder'].' Cylinders / '.$car['enginetype'].' ';
                                                if ($car['induction'])
                                                    $engine .= $car['induction'].' Engine';
                                                else
                                                    $engine .= 'Aspirated Engine.';
                                                echo $engine; ?></td></tr>
                                        <tr><th scope="row">Price</th><td><?= '$'.round($car['ddprice'],2).' / day (incl. 200km/day) + $'.round($car['kmprice']*1.75,2).' / 50km (overdue) (*)<br/>Or $'.round($car['kmprice']*1,2).' / 50km (short rental) (*)'; ?></td></tr>
                                        <tr><th scope="row"></th><td class="small">(*) Please refer to <?= $this->Html->link(__('Pricing'), ['controller' => 'Pages', 'action' => 'pricing']); ?> for more details.</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <?= $this->Html->link('View', ['action' => 'view', '?' => ['carid' => $car['id'], 'from' => 'carreview']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?>
                            <?php if ($user && $user['role'] == 'customer') echo $this->Html->link('Add to cart', ['controller' => 'rentals', 'action' => 'add', '?' => ['carid' => $car['id'], 'from' => 'carindex']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']);
                            else if ($user && $user['role'] == 'admin') {
                                echo $this->Html->link('Update Info', ['controller' => 'cars', 'action' => 'editFirst', $car['id']], ['class' => 'waves-effect waves-light btn btn-sm btn-info', 'style' => 'margin-right: 5px;']);
                                echo $this->Html->link('Revive', ['action' => 'revision', '?' => ['carid' => $car['id'], 'from' => 'carreview']], ['confirm' => __('Please confirm to revise this car: {0}', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => 'waves-effect waves-light btn btn-sm btn-success']); } ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="paginator small">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
    </div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>