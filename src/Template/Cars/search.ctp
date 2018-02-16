<?= $this->Html->css('style.css') ?>
<?php $user = $this->request->session()->read('Auth.User');
if ($user['role'] == 'admin') { ?>
    <script type="text/javascript">
        function checkSearchFields() {
            var idEr = document.getElementById('idEr');
            var keyEr = document.getElementById('keyEr');

            var submit = document.getElementById('search_submit');
            var carid = document.getElementById('carid').value;
            var carword = document.getElementById('carword').value;

            if (carid === '' && carword === '') {
                submit.disabled = true;
                idEr.style.display = 'none';
                keyEr.style.display = 'none';
            }
            else if (carid !== '' && carword !== '') {
                submit.disabled = true;
                idEr.style.display = '';
                idEr.innerHTML = 'Please only use 1 search type at a time.';
            }
            else {
                if (carid !== '') {
                    if (/^\d+$/.test(carid)) {
                        idEr.style.display = 'none';
                        submit.disabled = false;
                    }
                    else {
                        idEr.innerHTML = 'Car ID only contains digits.';
                        idEr.style.display = '';
                    }
                }
                else
                    idEr.style.display = 'none';

                if (carword !== '') {
                    keyEr.style.display = 'none';
                    submit.disabled = false;
                }
            }
        }
    </script>
<?php } else { ?>
    <script type="text/javascript">
        function checkSearchField() {
            var submit = document.getElementById('search_submit');
            var carword = document.getElementById('carword').value;

            submit.disabled = (carword === '');
        }
    </script>
<?php } ?>

<?= $this->Flash->render(); ?>
    <div class="container">
        <div class="row">
            <div class="card horizontal" style="width: 100%; color: #304a74;;">
                <form method="post" action="/Cars/search">
                    <div style="display:none;"><input type="hidden" name="method" value="POST"/></div>
                    <div class="header bg-indigo darken-4">
                        <h2>Refine Search <small style="color: #aeea00;">Easier to see a car of your wish.</small></h2>
                        <ul class="header-dropdown m-r--5">
                            <li><button class="btn btn-sm btn-info lighten-2 waves-effect waves-light" id="search_submit" type="submit" disabled="disabled">Search</button></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <?php if ($user['role'] != 'admin') { ?>
                            <div class="col-lg-12">
                                <?= $this->Form->control('carword', ['class' => 'form-control', 'label' => false, 'placeholder' => 'Keywords', 'id' => 'carword', 'type' => 'text', 'onchange' => 'checkSearchField()']); ?>
                                <p class="small">Keyword search only applies for make, model, year, body type.</p>
                                <input style="display: none" name="carid" type="text" value="" />
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?= $this->Form->control('carid', ['class' => 'form-control', 'label' => false, 'placeholder' => 'Car ID', 'id' => 'carid', 'type' => 'text', 'onchange' => 'checkSearchFields()']); ?>
                                    <p id="idEr" style="color: orangered; display: none;" class="small">Search error message</p>
                                </div>
                                <div class="col-sm-2 center" style="font-size: larger; font-weight: bolder;">OR</div>
                                <div class="col-sm-4">
                                    <?= $this->Form->control('carword', ['class' => 'form-control', 'label' => false, 'placeholder' => 'Keywords', 'id' => 'carword', 'type' => 'text', 'onchange' => 'checkSearchFields()']); ?>
                                    <p id="keyEr" style="color: orangered; display: none;" class="small">Search error message</p>
                                    <p class="small">Keyword search only applies for make, model, year, body type.</p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <?php foreach ($cars as $car): ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-image">
                            <?= $this->Html->image($car['image'].'-1.jpg', ['style' => 'max-height: 265px;']); ?>
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
                            <?= $this->Html->link('View', ['action' => 'view', '?' => ['carid' => $car['id'], 'from' => 'carindex']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?>
                            <?php if ($user && $user['role'] == 'customer') echo $this->Html->link('Add to cart', ['controller' => 'rentals', 'action' => 'add', '?' => ['carid' => $car['id'], 'from' => 'carindex']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']);
                            else if ($user && $user['role'] == 'admin') {
                                echo $this->Html->link('Update Info', ['controller' => 'cars', 'action' => 'edit', $car['id']], ['class' => 'waves-effect waves-light btn btn-sm btn-info', 'style' => 'margin-right: 5px;']);
                                echo $this->Html->link('Suspend', ['action' => 'revision', '?' => ['carid' => $car['id'], 'from' => 'carindex']], ['confirm' => __('{0} will be removed from business. Are you sure?', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => 'waves-effect waves-light btn btn-sm btn-danger']); } ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>