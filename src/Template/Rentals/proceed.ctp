<?= $this->Html->css('style.css') ?>

<script type="text/javascript">
    var checkOdom = false;
    var checkPark = false;
    var checkSlots = false;

    function checkOdo() {
        var odo = document.getElementById('odometer').value;
        var odoEr = document.getElementById('odoEr');

        if (parseInt(odo) === 0 || odo === '') {
            checkOdom = false;
            odoEr.style.display = 'none';
        }
        else if (parseInt(odo) < <?= $carOdo; ?>) {
            checkOdom = false;
            odoEr.style.display = '';
            odoEr.innerHTML = 'Odometer is non-sense. Please check again.';
        }
        else {
            checkOdom = true;
            odoEr.style.display = 'none';
        }
    }

    function checkParkin() {
        var park = document.getElementById('parking').value;
        var parkEr = document.getElementById('parkEr');
        if(park === '') {
            checkPark = false;
            parkEr.style.display = 'none';
        }
        else {
            var address = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + park.replace(/\s/g, '+') + '&key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw';
            $.getJSON(address, function (data) {
                if (data['status'] === 'OK') {
                    document.getElementById('latitude').value = data['results'][0]['geometry']['location']['lat'];
                    document.getElementById('longitude').value = data['results'][0]['geometry']['location']['lng'];

                    parkEr.style.display = 'none';
                    checkPark = true;
                }
                else {
                    checkPark = false;
                    parkEr.style.display = '';
                    parkEr.innerHTML = 'Unable to locate the Parking Address. Please check again.';
                }
            });
        }
    }

    function putLocation() {
        var parkEr = document.getElementById('parkEr');
        if (confirm('X-Oto wants to access your location.')) {
            if (navigator.geolocation)
                navigator.geolocation.getCurrentPosition(showLocation, showError);
            else {
                parkEr.innerHTML = 'Location Service was not available.';
                parkEr.style.display = '';
                checkPark = false;
            }
        }
        else {
            parkEr.innerHTML = 'Location access was not given.';
            parkEr.style.display = '';
            checkPark = false;
        }
    }

    function showLocation(position) {
        var address = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + position.coords.latitude + ',' + position.coords.longitude + '&key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw';
        var parkEr = document.getElementById('parkEr');

        $.getJSON(address, function (data) {
            if (data['status'] === 'OK') {
                document.getElementById('parking').value = data['results'][0]['formatted_address'];
                document.getElementById('latitude').value = data['results'][0]['geometry']['location']['lat'];
                document.getElementById('longitude').value = data['results'][0]['geometry']['location']['lng'];
                parkEr.style.display = 'none';
                checkPark = true;
                checkButton();
            }
            else {
                parkEr.innerHTML = 'Server failed to respond. Please try again later.';
                parkEr.style.display = '';
                checkPark = false;
            }
        });
    }

    function showError(error) {
        var parkEr = document.getElementById('parkEr');
        switch (error.code) {
            case error.PERMISSION_DENIED:
                parkEr.innerHTML = 'Permission denied. Refresh page if you wish to try again.';
                break;
            case error.TIMEOUT:
                parkEr.innerHTML = 'Request timed out. Please check your internet service.';
                break;
            case error.UNKNOWN_ERR:
                parkEr.innerHTML = 'An error has occurred. Please try again.';
                break;
            case error.POSITION_UNAVAILABLE:
                parkEr.innerHTML = 'Location Service was not available. Please make sure GPS is enabled.';
                break;
        }

        parkEr.style.display = '';
    }

    function checkSlot() {
        var slot = document.getElementById('slot').value;
        checkSlots = !(slot === '');
    }

    function checkButton() {
        var btn = document.getElementById('proceed');
        btn.disabled = !(checkPark && checkOdom && checkSlots);
    }
</script>

<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Return Process</b></h4></div>
    <div class="card">
        <div class="header bg-indigo darken-4">
            <h2>Customer: <?= $customer['name']; ?>
                <small style="color: #aeea00 !important;">Please select a rental to return car.</small>
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table style="margin: 0;" class="table">
                    <tr><th scope="col">ID</th><th scope="col">Car</th><th scope="col">Rental End</th><th scope="col">Value</th><th scope="col">Action</th></tr>
                    <tr><td><?= $rental['id']; ?></td>
                        <td><?= $this->Html->image($carImgByRental[$rental['id']].'-1.jpg', ['style' => 'max-width: 100px;', 'class' => 'responsive-img']); ?>
                            <?= $this->Html->link($carNameByRental[$rental['id']], ['controller' => 'Cars', 'action' => 'view', $rental['car_id']]); ?></td>
                        <td><p style="margin-bottom: 0;"><?= (new DateTime($rental['todate']))->format('d/m/Y H:i'); ?></p>
                            <p style="margin-top: 0;">Duration: <?php $label = ($rental['type'] == 'short') ? ' hour(s)' : ' day(s)'; echo $rental['duration'].$label; ?></p></td>
                        <td>$<?= $rental['value']; ?></td>
                        <td><?= $this->Html->link(__('Return'), ['controller' => 'Rentals', 'action' => 'proceed', $rental['id']]); ?></td></tr>
                </table>
            </div>
        </div>
        <form method="post" action="/rentals/resolve/<?= $rental['id']; ?>">
            <div class="card-body">
                <div class="table-responsive">
                    <table style="margin: 0;" class="table">
                        <tr><th>Odometer</th>
                            <td><input name="odometer" id="odometer" type="number" class="form-control" required onchange="checkOdo();checkButton()" />
                                <p id="odoEr" class="small" style="color: orangered; display: none;"></p></td></tr>
                        <tr><th>Parking Address</th>
                            <td><input name="parking" id="parking" type="text" class="form-control" required onchange="checkParkin();checkButton()" />
                                <a onclick="putLocation()" class="waves-effect waves-light btn btn-sm btn-success lighten-2 pull-right"><i class="material-icons">gps_fixed</i></a>
                                <p id="parkEr" class="small" style="color: orangered; display: none;"></p></td></tr>
                        <tr><th>Parking Slot</th>
                            <td><input name="slot" id="slot" type="text" class="form-control" required onchange="checkSlot();checkButton()" /></td></tr>
                        <tr style="display: none;"><td><input type="hidden" name="latitude" id="latitude" value="" /></td>
                            <td><input type="hidden" name="longitude" id="longitude" value=""/></td></tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <button id="proceed" type="submit" disabled name="proceed" class="waves-effect waves-light btn btn-sm btn-info lighten-2">Submit</button>
            </div>
        </form>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
