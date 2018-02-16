<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>
<?= $this->Html->script("http://maps.google.com/maps/api/js?key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw"); ?>

<div class="container">
    <div class="row">
        <div class="card horizontal" style="width: 100%;">
            <div class="header bg-indigo darken-4">
                <h2><?= $car['make'].' '.$car['model'].' '.$car['year']; ?> <small style="color: #aeea00;">Like this car? Rent now or add it to your watch list.</small></h2>
                <ul class="header-dropdown m-r--5">
                    <?php $user = $this->request->session()->read('Auth.User');
                    if ($user && $user['role'] == 'customer') echo $this->Form->postLink(__('Watch'), ['controller' => 'Browsings', 'action' => 'add', $car['id']], ['class' => $watched ? 'waves-effect waves-light btn btn-sm btn-info disabled' : 'waves-effect waves-light btn btn-sm btn-info']);
                    else if ($user && $user['role'] == 'admin') echo $this->Html->link('Delete', ['action' => 'review', '?' => ['carid' => $car['id'], 'from' => 'carview']], ['confirm' => __('{0} will be removed from business. Are you sure?', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => (($car['available']) ? 'waves-effect waves-light btn btn-sm btn-danger' : 'waves-effect waves-light btn btn-sm btn-danger disabled')]); ?>
                </ul>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
                                <li class="tab"><a class="active" href="#generalinfo">General</a></li>
                                <li class="tab"><a href="#specification">Specification</a></li>
                                <li class="tab"><a href="#convenience">Convenience</a></li>
                                <li class="tab"><a href="#carimages">Photos</a></li>
                            </ul>
                            <div id="generalinfo" class="col s12">
                                <div class="table-responsive small" style="margin: 0;">
                                    <table class="table" style="margin: 0;">
                                        <tr><th scope="row">Forewords</th><td><?= $car['shortdesc']; ?></td></tr>
                                        <tr><th>Description</th><td><?= (empty($car['description']) ? 'N/A' : $car['description']); ?></td></tr>
                                        <tr><th scope="row">Odometer</th><td><?= (empty($car['odometer']) ? 'N/A' : $car['odometer'].' kms'); ?></td></tr>
                                        <tr><th scope="row">Body Type</th><td><?= (empty($car['type']) ? 'N/A' : $car['type']); ?></td></tr>
                                        <tr><th scope="row">Color</th><td><?= (empty($car['color']) ? 'N/A' : ($car['color'] == 'Others' ? 'Various colors available. See photos.' : $car['color'])); ?></td></tr>
                                        <tr><th scope="row">Seats</th><td><?= (empty($car['seats']) ? 'N/A' : $car['seats'].'\'s'); ?></td></tr>
                                        <tr><th scope="row">Fuel</th><td><?= (empty($car['fuel']) ? 'N/A' : $car['fuel']); ?></td></tr>
                                        <tr><th scope="row">Available</th><td><?= (empty($car['quantity']) ? 'N/A' : $car['quantity'].' cars left'); ?></td></tr>
                                        <tr><th scope="row">Price</th><td><?= '$'.round($car['ddprice'],2).' / day (incl. 200km/day) + $'.round($car['kmprice']*1.5,2).' / 50km (overdue) (*)<br/>Or $'.round($car['kmprice']*1,2).' / 50km (short rental) (*)'; ?></td></td></tr>
                                        <tr><th scope="row"></th><td class="small">(*) Please refer to <?= $this->Html->link(__('Pricing'), ['controller' => 'Pages', 'action' => 'pricing']); ?> for more details.</td></tr>
                                    </table>
                                </div>
                            </div>
                            <div id="specification" class="col s12">
                                <div class="table-responsive small" style="margin: 0;">
                                    <table class="table" style="margin: 0;">
                                        <tr><th scope="row">Gear</th><td><?= (empty($car['transmission']) ? 'Automatic ' : $car['geartype'].' '.($car['transmission'] == 0 ? 'Automatic ' : ($car['transmission'] == 1 ? 'Manual ' : 'Dual Mode '))).$car['gear'].' Levels'; ?></td></tr>
                                        <tr><th scope="row">Drive</th><td><?= (empty($car['drivetype']) ? 'N/A' : ($car['drivetype'] == 'RWD' ? $car['drivetype'].' - Rear Wheels Drive' : ($car['drivetype'] == 'FWD' ? $car['drivetype'].' - Front Wheels Drive' : ($car['drivetype'] == 'AWD' ? $car['drivetype'].' - All Wheels Drive' : $car['drivetype'].' - Four Wheels Drive')))); ?></td></tr>
                                        <tr><th scope="row">Engine</th><td><?php $engine = '';
                                                if ($car['enginesize'])
                                                    $engine .= round($car['enginesize']/1000,1).'L '.$car['cylinder'].' Cylinders / '.$car['enginetype'].' ';
                                                if ($car['induction'])
                                                    $engine .= $car['induction'].' Engine';
                                                else
                                                    $engine .= 'Aspirated Engine';
                                                echo $engine; ?></td></tr>
                                        <tr><th scope="row">Power</th><td><?= (empty($car['power']) ? 'N/A' : $car['power']); ?></td></tr>
                                        <tr><th scope="row">Fuel Capacity</th><td><?= (empty($car['fuelcap']) ? 'N/A' : $car['fuelcap'].' litters'); ?></td></tr>
                                        <tr><th scope="row">Fuel Consume</th><td><?= (empty($car['fuelconsume']) ? 'N/A' : $car['fuelconsume']).' L/100km (average - urbane - rural)'; ?></td></tr>
                                        <tr><th scope="row">Average Travels</th><td><?php if (empty($car['fuelconsume']) || empty($car['fuelcap']))
                                                    echo '<p><b>Convenience: </b><p class="small">Awaiting information to be updated. We are sorry!</p></p>';
                                                else {
                                                    $travels = ''; $consume = explode(' - ', $car['fuelconsume']);
                                                    foreach ($consume as $cons)
                                                        $travels .= round($car['fuelcap']*100/$cons, 1).' - ';

                                                    echo $travels.' (average - urbane - rural) kilometers';
                                                }?></td></tr>
                                        <tr><th scope="row">Dimensions</th><td><?= (empty($car['measures']) ? 'N/A' : $car['measures']); ?></td></tr>
                                    </table>
                                </div>
                            </div>
                            <div id="convenience" class="col s12">
                                <div class="row" style="margin-top: 15px; margin-left: 15px;">
                                    <div class="row">
                                        <div class="header font-bold">Convenience</div>
                                        <div class="row">
                                            <?php $convdata = array();
                                            if (!empty($car['convenience'])) {
                                                $tokens = explode(', ', $car['convenience']);
                                                for ($i = 0; $i < count($tokens); $i++)
                                                    $convdata[$i] = $tokens[$i];
                                            }

                                            if (!empty($convdata)) { foreach ($convdata as $conv) {?>
                                                <a class="showit z-depth-1 bg-teal darken-4" style="padding: 10px; border-radius: 10px; margin: 7px; font-size: medium;">
                                                    <i class="material-icons">assistant</i><?= $conv; ?>
                                                    <div class="showme small">More details on this item. Awaiting data update.</div>
                                                </a>
                                            <?php }} else echo '<p><b>Convenience: </b>Awaiting information to be updated. We are sorry!</p>'; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="header font-bold">Lights & View</div>
                                        <div class="row">
                                            <?php $litdata = array();
                                            if (!empty($car['lightsview'])) {
                                                $items = explode(', ', $car['lightsview']);
                                                for ($i = 0; $i < count($items); $i++)
                                                    $litdata[$i] = $items[$i];
                                            }

                                            if (!empty($litdata)) { foreach ($litdata as $lit) {?>
                                                <a class="showit z-depth-1 bg-blue-grey darken-2" style="padding: 10px; border-radius: 10px; margin: 7px; font-size: medium;">
                                                    <i class="material-icons">brightness_high</i><?= $lit; ?>
                                                    <div class="showme small">More details on this item. Awaiting data update.</div>
                                                </a>
                                            <?php }} else echo '<p><b>Lights & View: </b>Awaiting information to be updated. We are sorry!</p>'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="carimages" class="col s12">
                                <div class="row" style="margin-top: 15px;">
                                    <?php foreach ($carImages as $image): ?>
                                        <?= $this->Html->image($image, ['style' => 'max-height: 420px;', 'class' => 'responsive-img']); ?>
                                    <?php endforeach; ?>
                                    <button class="w3-button w3-display-left" onclick="plusDivs(-1)"><i class="material-icons">skip_previous</i></button>
                                    <button class="w3-button w3-display-right" onclick="plusDivs(+1)"><i class="material-icons">skip_next</i></button>
                                </div>
                                <script type="text/javascript">
                                    var slideIndex = 1;
                                    showDivs(slideIndex);

                                    function plusDivs(n) {
                                        showDivs(slideIndex += n);
                                    }

                                    function showDivs(n) {
                                        var i;
                                        var x = document.getElementsByClassName("responsive-img");
                                        if (n > x.length) {slideIndex = 1}
                                        if (n < 1) {slideIndex = x.length} ;
                                        for (i = 0; i < x.length; i++) {
                                            x[i].style.display = "none";
                                        }
                                        x[slideIndex-1].style.display = "block";
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <iframe class="gmap" id="map"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw&q=<?= $parkString; ?>" allowfullscreen>
                        </iframe>
                        <button class="btn btn-info btn-sm waves-effect waves-light <?= ($user && $user['role'] == 'admin') ? 'disabled' : ''?>" onclick="calculateDistance();">Show Distance</button>
                        <p id="distance" class="font-bold" style="display: none;"></p><p id="destination" style="display: none;"><?= $car['parking'].', Australia'; ?></p>
                    </div>
                </div>
            </div>
            <?php $user = $this->request->session()->read('Auth.User');
            if ($user) { ?>
                <div class="card-action">
                    <?= $user['role'] == 'customer' ? $this->Html->link('Add to cart', ['controller' => 'rentals', 'action' => 'add', '?' => ['carid' => $car['id'], 'from' => $from]], ['class' => 'waves-effect waves-light btn btn-sm btn-info']) : $this->Html->link('Update Info', ['controller' => 'cars', 'action' => 'edit', $car['id']], ['class' => 'waves-effect waves-light btn btn-sm btn-info']); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (!$user || ($user && $user['role'] != 'admin')) { ?>
    <script type="text/javascript">
        var showDistance = document.getElementById('distance');
        var destination = document.getElementById('destination').innerHTML;

        function calculateDistance() {
            if (confirm('X-Oto wants to access your location.')) {
                if (navigator.geolocation)
                    navigator.geolocation.getCurrentPosition(processDistance, showError);
                else {
                    showDistance.innerHTML = 'Location Service was not available.';
                    showDistance.style.display = '';
                }
            }
            else {
                showDistance.innerHTML = 'Location access was not given.';
                showDistance.style.display = '';
            }
        }

        function processDistance(position) {
            var address = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + position.coords.latitude + ',' + position.coords.longitude + '&key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw';
            //var geoCoder = new google.maps.Geocoder();

            $.getJSON(address, function (data) {
                if (data['status'] == 'OK')
                //showDistance.innerHTML = data['results'][0]['formatted_address'];
                    calculate(data['results'][0]['formatted_address'], destination);
                else
                    showDistance.innerHTML = 'Server failed to respond. Please try again later.';
            });

            showDistance.style.display = '';
        }

        var userAddress = '';
        function calculate(origin, destination) {
            userAddress = origin;
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.METRIC,
                    avoidHighways: false,
                    avoidTolls: true
                }, printDistance);
        }

        function initMap() {
            var mapOptions = {zoom: 10, center: {lat: -37.81, lng: 144.96}};
            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var directionService = new google.maps.DirectionsService;
            var directionDisplay = new google.maps.DirectionsRenderer;

            directionDisplay.setMap(map);
            showDirections(directionService, directionDisplay);
            //var onClickFunction = function () { showDirections(directionService, directionDisplay); };
            //document.getElementById('showdistance').addEventListener('click', onClickFunction);
        }

        function printDistance(response, status) {
            if (status != google.maps.DistanceMatrixStatus.OK) {
                showDistance.innerHTML = status;
                showDistance.style.display = '';
            } else {
                //var origin = response.originAddresses[0];
                //var destination = response.destinationAddresses[0];


                var distance = response.rows[0].elements[0].distance;
                //var distance_value = distance.value;
                var distance_text = distance.text;
                var kilometers = distance_text.substring(0, distance_text.length - 3);
                showDistance.innerHTML = 'Driving distance: ' + kilometers + 'km. Advoid Tolls.';
                initMap();
            }
        }

        function showDirections(directionService, directionDisplay) {
            directionService.route({
                origin: userAddress,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(respond, status) {
                if (status === google.maps.DirectionsStatus.OK)
                    directionDisplay.setDirections(response);
                else
                    alert('Directions service failed to respond. Please refresh page if you wish to try again.');
            });
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    showDistance.innerHTML = 'Permission denied. Refresh page if you wish to try again.';
                    break;
                case error.TIMEOUT:
                    showDistance.innerHTML = 'Request timed out. Please check your internet service.';
                    break;
                case error.UNKNOWN_ERR:
                    showDistance.innerHTML = 'An error has occurred. Please try again.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    showDistance.innerHTML = 'Location Service was not available. Please make sure GPS is enabled.';
                    break;
            }

            showDistance.style.display = '';
        }
    </script>
<?php } ?>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>

