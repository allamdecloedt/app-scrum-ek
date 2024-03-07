<?php if ($position != '') : ?>
<div class="row">
    <div class="col-12">
        <h5 class="mt-0 mb-3"><?php echo get_phrase('ongoing_trip'); ?></h5>
    </div>
</div>

<div class="col-12 mb-3 mb-xxl-0">
    <div class="row">
        <div class="col-12">
            <div id="map" style="height: 470px; width: 100%; border-radius: 5px;"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script type="text/javascript">
"use strict";

let trip_id = "<?php echo $ongoing_trip_id; ?>";
trip_id = Number(trip_id);

if (trip_id > 0) {
    var map = L.map('map').setView([51.505, -0.09], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var lat = 0;
    var lng = 0;

    var oldLat = 0;
    var oldLng = 0;

    var update = setInterval(locate, 3000);

    function locate() {
        $.ajax({
            type: 'POST',
            url: '<?php echo route('get_location') ?>',
            data: {
                trip_id: trip_id
            },
            success: function(response) {
                console.log(response);

                var marker, circle;
                let zoomed;

                if (response == "") {
                    clearInterval(update);
                    window.location.reload(true);
                } else {
                    var position = response;
                    var location = JSON.parse(position);
                    lat = location.latitude;
                    lng = location.longitude;

                    // if new position is same as previous don't execute next codes bellow
                    if (oldLat != lat) {
                        // console.log(lat + '......' + lng);

                        // Removes any existing marker and circle
                        if (marker) {
                            map.removeLayer(marker);
                            map.removeLayer(circle);
                        }

                        // add marker and circle
                        marker = L.marker([lat, lng], {
                            opacity: 1,
                        }).addTo(map);
                        circle = L.circle([lat, lng], {
                            radius: 700
                        }).addTo(map);

                        /*
                        this is popup
                        var popup = marker.bindPopup('Your current location').openPopup();
                        popup.addTo(map);
                        */

                        // Adds marker to the map and a circle for accuracy
                        if (!zoomed) {
                            zoomed = map.fitBounds(circle.getBounds());
                        }

                        // Set zoom to boundaries of accuracy circle
                        map.setView([lat, lng]);
                    } else {
                        if (marker) {
                            map.removeLayer(marker);
                            map.removeLayer(circle);
                        }
                    }
                    // store previous position
                    oldLat = lat;
                    oldLng = lng;
                }
            }
        });
    }
}
</script>
<?php else : ?>
<?php include APPPATH . 'views/backend/empty.php'; ?>
<?php endif; ?>
