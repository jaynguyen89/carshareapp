<?= $this->Html->css('style.css') ?>
<?= $this->Html->script("http://maps.google.com/maps/api/js?key=AIzaSyDiyQicNZizEhQo6UkxKSaAHJS3tChj3Gw"); ?>

<style type="text/css">
    #map { position: relative; }
    #map, #displaycars {
        width: 30%;
        height: 30%;
        position: absolute;
        top: 0;
        left: 0;
        padding-top: 150px;
        padding-left: 50px;
        z-index: 100;
    }
</style>

<div class="row">
    <div class="card" style="width: 100%; margin: 5px;">
        <div class="card-body" style="margin-bottom: 695px;">
            <div id="map" style="width: 100%; height: 750px;"></div>
            <div id="displaycars"></div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        var locations = [
            <?php for ($j = 0; $j < count($cars); $j++) {
            $carTitle = $cars[$j]['make'].' '.$cars[$j]['model'].' '.$cars[$j]['year'];

            if ($j == count($cars) - 1)
                echo '[\''.$cars[$j]['id'].'\', \''.$carTitle.'\', '.$cars[$j]['latitude'].', '.$cars[$j]['longitude'].']';
            else
                echo '[\''.$cars[$j]['id'].'\', \''.$carTitle.'\', '.$cars[$j]['latitude'].', '.$cars[$j]['longitude'].'], ';
        } ?>
        ];

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: new google.maps.LatLng(-37.8104504, 144.9629779),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();
        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][2], locations[i][3]),
                title: locations[i][0],
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][1]);
                    infowindow.open(map, marker);
                    saveMarkerState(this);
                    displayMarkerContents();
                }
            })(marker, i));

            google.maps.event.addListener(infowindow, 'closeclick', function () {
                closeMarkerContents();
            });
        }

        var selectedMarker = '';
        function saveMarkerState(marker) {
            selectedMarker = marker.title;
        }

        var displayData = <?= json_encode($mapData); ?>;

        var displayCars = document.getElementById('displaycars');
        function displayMarkerContents() {
            if (selectedMarker) {
                var contents = '';
                var data = null;
                for (var k = 0; k < displayData.length; k++)
                    if (parseInt(selectedMarker) == displayData[k]["id"]) {
                        data = displayData[k];
                        break;
                    }

                contents += '' +
                    '<div class="card">' +
                        '<div class="card-image">' +
                            '<img src="/img/' + data['image'] + '-1.jpg" style="max-height: 265px"/>' +
                            '<span class="card-title" style="background-color: darkgray; border-radius: 20px; padding: 7px; font-size: medium; margin-left: 10px;">' + data['title'] + '</span>' +
                        '</div>' +
                        '<div class="card-body">' +
                            '<div class="row" style="margin: 0">' +
                                '<div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-dashboard"> ODO</i> <p class="small font-bold">' + data['odometer'] + 'kms</p></div>' +
                                '<div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-car"> BODY</i> <p class="small font-bold"></p>' + data['body'] + '</div>' +
                                '<div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-rocket"> DRIVE</i> <p class="small font-bold">' + data['drive'] + '</p></div>' +
                                '<div class="col-md-3 col-sm-6 col-xs-6"><i class="blue-grey-text darken-4 fa fa-beer"> FUEL</i> <p class="small font-bold"></p>' + data['fuel'] + '</div>' +
                            '</div>' +
                            '<div class="row" style="margin: 0">' +
                                'Parking location: ' + data['parking'] +
                            '</div>' +
                        '</div>' +
                        '<div class="card-action">' +
                            '<a class="waves-effect waves-light btn btn-sm btn-info" style="margin-right: 10px;" href="/cars/View?carid=' + selectedMarker + '&from=carmap">View</a>' +
                        <?php $user = $this->request->session()->read('Auth.User'); if ($user) { ?>
                            '<a class="waves-effect waves-light btn btn-sm btn-info" href="/rentals/add?carid=' + data['id'] + '&from=carmap">Add to cart</a>' +
                        <?php } ?>
                        '</div>' +
                    '</div>';

                displayCars.innerHTML = contents;
                displayCars.style.display = '';
            }
            else
                closeMarkerContents();
        }

        function closeMarkerContents() {
            displayCars.innerHTML = '';
            displayCars.style.display = 'none';
        }
    </script>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>