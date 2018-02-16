<?php
use Cake\View\Helper;
$cakeDescription = 'X-oto: Vicotria\'s largest car share network.';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.css') ?>
    <?= $this->Html->css('materialize.css') ?>
    <?= $this->Html->css('parallax-template.css') ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <?= $this->Html->script('html5shiv.js') ?>
    <?= $this->Html->script('respond.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">
        <div class="container">
            <?= $this->Html->link("X-Oto", array('controller' => 'Pages', 'action' => 'display'), array('class' => 'navbar-brand text-warning')); ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" style="background-color: #304a74 !important;" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active"><?= $this->Html->link(__('Home'), ['controller' => 'Pages', 'action' => 'display'], ['class' => 'nav-link']); ?></li>
                    <?php $session = $this->request->session(); $user = $session->read('Auth.User');
                    if ($user['role'] == 'admin') { ?>
                        <li class="nav-item"><?= $this->Html->link(__('Cars'), ['controller' => 'Cars', 'action' => 'index'], ['class' => 'nav-link']); ?></li>
                        <li class="nav-item"><?= $this->Html->link(__('Revive'), ['controller' => 'Cars', 'action' => 'revive'], ['class' => 'nav-link']); ?></li>
                        <li class="nav-item"><?= $this->Html->link(__('Add'), ['controller' => 'Cars', 'action' => 'addFirst'], ['class' => 'nav-link']); ?></li>
                    <?php } else { ?>
                        <li class="nav-item"><?= $this->Html->link(__('Browse'), ['controller' => 'Cars', 'action' => 'index'], ['class' => 'nav-link']); ?></li>
                        <li class="nav-item"><?= $this->Html->link(__('Map'), ['controller' => 'Cars', 'action' => 'carsOnMap'], ['class' => 'nav-link']); ?></li>
                        <li class="nav-item"><?= $this->Html->link(__('Pricing'), ['controller' => 'Pages', 'action' => 'pricing'], ['class' => 'nav-link']); ?></li>
                        <li class="nav-item"><?= $this->Html->link(__('Return'), ['controller' => 'Rentals', 'action' => 'returnCar'], ['class' => 'nav-link']); ?></li>
                    <?php } ?>
                </ul>
                <ul class="form-inline" style="list-style-type: none;">
                    <?php $session = $this->request->session();
                    $user = $session->read('Auth.User');

                    if($user) {?>
                        <li class="nav-item"><a class='dropdown-button' href='#' data-activates='dropdown1'><?= 'Welcome! '.ucfirst($user['username']); ?></a>
                            <ul id='dropdown1' style="margin-top: 50px;" class='dropdown-content'>
                                <li><a href="/<?= $user['role'] == 'customer' ? 'customers/dashboard/' : 'users/view/'.$user['id']; ?>"><i class="fa fa-vcard"></i> Dashboard</a></li>
                                <li><a href="/messages/index/"><i class="fa fa-envelope"></i> Inbox <span class="badge bg-info pull-right"><small><?php $mcount = $session->read('Messages.count'); echo ($mcount == 0) ? '' : $mcount.' new'; ?></small></span></a></li>
                                <?php if ($user['role'] == 'customer') { ?>
                                    <li><a href="/rentals/index/"><i class="fa fa-shopping-bag"></i> <?php $rcount = $session->read('Rentals.count'); echo ($rcount < 2) ? $rcount.' item' : $rcount.' items'; ?></a></li>
                                <?php } ?>
                                <li class="divider"></li>
                                <li><a href="/users/logout/" style="color: orangered" onmouseover="this.style.color=&quot;#FF5B00&quot;;" onmouseout="this.style.color=&quot;orangered&quot;;" ><i class="fa fa-user-times"></i> Logout</a></li>
                            </ul>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('.dropdown-button').dropdown({
                                            inDuration: 300,
                                            outDuration: 225,
                                            constrainWidth: false,
                                            hover: true,
                                            gutter: 0,
                                            belowOrigin: false,
                                            alignment: 'left',
                                            stopPropagation: false
                                        }
                                    );
                                });
                            </script>
                        </li>
                    <?php } else {
                        if (strpos(Cake\Routing\Router::url('/', true), $this->request->here) != false) { ?>
                            <li class="nav-item">
                                <?= $this->Html->link(__('Log In'), ['controller' => 'Users', 'action' => 'login'], ['class' => 'waves-effect waves-light btn btn-sm btn-info lighten-2']); ?>
                                <?= $this->Html->link(__('Sign up'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'waves-effect waves-light btn btn-sm btn-info lighten-2']); ?>
                            </li>
                        <?php } elseif (strpos(Cake\Routing\Router::url('/', true) . 'users/add', $this->request->here) != false)
                            echo $this->Html->link(__('Log In'), ['controller' => 'Users', 'action' => 'login'], ['class' => 'waves-effect waves-light btn btn-sm btn-info lighten-2']);
                        elseif (strpos(Cake\Routing\Router::url('/', true) . 'users/login', $this->request->here) != false)
                            echo $this->Html->link(__('Sign up'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'waves-effect waves-light btn btn-sm btn-info lighten-2']);
                        else { ?>
                            <li class="nav-item">
                                <?= $this->Html->link(__('Log In'), ['controller' => 'Users', 'action' => 'login'], ['class' => 'waves-effect waves-light btn btn-sm btn-info lighten-2']); ?>
                                <?= $this->Html->link(__('Sign up'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'waves-effect waves-light btn btn-sm btn-info lighten-2']); ?>
                            </li>
                    <?php }} ?>
                </ul>
            </div>
        </div>
    </nav>
    <!--/.Navbar-->

    <!--Banner-->
    <div id="index-banner" class="parallax-container">
        <div class="section no-pad-bot">
            <div class="container">
                <br><br><br>
                <h1 class="header center light" style="padding-top: 40px;"><big class="text-warning">X-Oto</big> Car Sharing</h1>
                <div class="row center">
                    <h5 class="header col s12 light">Victoria's Largest Car Share Network</h5>
                </div>
                <div class="row center">
                    <a style="margin: auto;" href="http://materializecss.com/getting-started.html" class="btn btn-info btn-lg">Get Started</a>
                </div>
                <br>
                <br>
            </div>
            <div class="parallax">
                <img src="https://www.hdwallpapers.in/walls/rolls_royce_phantom_2018_4k-wide.jpg">
            </div>
        </div>
    </div>
    <!--/.Banner-->

    <?= $this->Flash->render() ?>
    <div style="min-height: 100%; padding-bottom: 1px;">
        <?= $this->fetch('content') ?>
    </div>

    <script type="text/javascript">
        <?php if(!$user) { ?>
        var cumsgVal = false;

        function checkCUForm() {
            var cuemail = document.getElementById('contactemail').value;
            var cuname = document.getElementById('contactname').value;
            var cunameEr = document.getElementById('cunameEr');
            var cuemailEr = document.getElementById('cuemailEr');
            var submit = document.getElementById('contactsubmit');

            var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
            var cuemailVal = false;
            if (cuemail.length === 0)
                cuemailEr.style.display = 'none';
            else {
                if (!filter.test(cuemail)) {
                    cuemailEr.innerHTML = 'Please enter a valid email address.'
                    cuemailEr.style.display = '';
                    cuemailVal = false;
                }
                else {
                    cuemailEr.style.display = 'none';
                    cuemailVal = true;
                }
            }

            var cunameVal = false;
            if (cuname.length === 0)
                cunameEr.style.display = 'none';
            else {
                if ((/\d/g).test(cuname)) {
                    cunameEr.innerHTML = 'Name should contain alphabet letters only.';
                    cunameEr.style.display = '';
                    cunameVal = false;
                }
                else {
                    cunameEr.style.display = 'none';
                    cunameVal = true;
                }
            }

            submit.disabled = !(cumsgVal && cunameVal && cuemailVal);
        }
        <?php } ?>

        function countCUChars() {
            var count = document.getElementById("cucount");
            var message = document.getElementById("contactmessage").value;

            if (200 - message.length == 200) {
                <?= $user ? 'document.getElementById(\'contactsubmit\').disabled = true;' : 'cumsgVal = false;'; ?>
                count.style.color = 'white';
                count.innerHTML = '200 characters left';
            }
            else if (200 - message.length > 0) {
                count.innerHTML = (200 - message.length).toString().concat(' characters left');
                count.style.color = 'white';
                <?= $user ? 'document.getElementById(\'contactsubmit\').disabled = false;' : 'cumsgVal = true;'; ?>
            }
            else {
                count.innerHTML = '0 char left!';
                count.style.color = 'orangered';
                <?= $user ? 'document.getElementById(\'contactsubmit\').disabled = true;' : 'cumsgVal = false;'; ?>
            }
        }
    </script>

    <footer class="page-footer center-on-small-only" style="position: relative; margin-top: -1px; bottom: 0; width: 100%; clear: both; background-color: #304a74;">
        <!--Footer Links-->
        <div class="container-fluid">
            <div class="row">

                <!--First column-->
                <div class="col-lg-3 col-md-6 ml-auto" style="color: whitesmoke">
                    <h5 class="title font-bold mt-3 mb-4">We <i class="material-icons" style="color: orangered">favorite</i> to hear from you!</h5>
                    <p><i class="material-icons" style="color: whitesmoke">place</i> 100 Example St., Somewhere, VIC 3200</p>
                    <p><i class="material-icons" style="color: whitesmoke">phone</i> 0432.500.500</p>
                    <p><i class="material-icons" style="color: whitesmoke">email</i> contactadmin@xoto.com.au</p>
                </div>
                <!--/.First column-->

                <hr class="w-100 clearfix d-sm-none">

                <!--Second column-->
                <div class="col-lg-5 col-md-6 ml-auto" style="color: whitesmoke">
                    <h5 class="title font-bold mt-3 mb-4"><i class="material-icons" style="color: orangered">send</i> Contact Us</h5>
                    <div class="row">
                        <form id="contactus" class="col s12" method="post" action="/messages/add">
                            <div class="row">
                                <div class="input-field col s6">
                                    <input name="contactemail" id="contactemail" type="email" class="validate" required <?= $user ? 'disabled' : 'onchange="checkCUForm()"'; ?> value="<?= $user['role'] == 'customer' ? $customer['email'] : ''; ?>">
                                    <label for="contactemail">Email</label>
                                    <p id="cuemailEr" class="small" style="color: orangered; display: none;"></p>
                                </div>
                                <div class="input-field col s6">
                                    <input name="contactname" id="contactname" type="text" class="validate" required <?= $user ? 'disabled' : 'onchange="checkCUForm()"'; ?> value="<?= $user['role'] == 'customer' ? $customer['name'] : ''; ?>">
                                    <label for="contactname">Name</label>
                                    <p id="cunameEr" class="small" style="color: orangered; display: none;"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea name="contactmessage" id="contactmessage" type="text" class="validate" required oninput="countCUChars()" <?= (!$user) ? 'onchange="checkCUForm()"' : ''; ?> <?= ($user && $user['role'] == 'admin') ? 'disabled' : ''; ?>></textarea>
                                    <label for="contactmessage">Message</label>
                                    <p id="cucount" class="small">200 characters left</p>
                                </div>
                            </div>
                            <button id="contactsubmit" type="submit" name="contactsubmit" disabled class="waves-effect waves-light btn btn-sm btn-info lighten-2 pull-right <?= ($user && $user['role'] == 'admin') ? 'disabled' : '' ?>">Send</button>
                        </form>
                    </div>
                </div>
                <!--/.Third column-->

                <hr class="w-100 clearfix d-sm-none">

                <!--Fourth column-->
                <div class="col-lg-3 col-md-6 ml-auto" style="color: whitesmoke">
                    <h5 class="title font-bold mt-3 mb-4"><i class="material-icons" style="color: orangered">group add</i> Follow Us On</h5>
                    <div class="row">
                        <div class="col s1"><i class="fa fa-facebook-square small" style="color: whitesmoke"></i> <a href="#">Facebook</a></div>
                        <div class="col s1"><i class="fa fa-twitter-square small" style="color: whitesmoke"></i> <a href="#">Twitter</a></div>

                    </div>
                    <div class="row">
                        <div class="col s1"><i class="fa fa-instagram small" style="color: whitesmoke"></i> <a href="#">Instagram</a></div>
                        <div class="col s1"><i class="fa fa-google-plus-square small" style="color: whitesmoke"></i> <a href="#">Google+</a></div>
                    </div>
                </div>
                <!--/.Fourth column-->
            </div>
        </div>
        <!--/.Footer Links-->

        <!--Copyright-->
        <div class="footer-copyright">
            <div class="container-fluid text-center">
                © 2017 Copyright: <a href="https://www.MDBootstrap.com"> X-Oto.com.au </a>
                --- RMIT University
            </div>
        </div>
        <!--/.Copyright-->
    </footer>
    <!--/.Footer-->

    <?= $this->Html->script('jquery.min.js'); ?>
    <?= $this->Html->script('bootstrap.js'); ?>
    <?= $this->Html->script('materialize.js'); ?>
    <?= $this->Html->script('parallax-template.js'); ?>

    <script>
        new WOW().init();
    </script>
</body>
</html>
