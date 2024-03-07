<?php
$vehicle_details = $this->db->where('id', $old_trip['vehicle_id'])->get('vehicles')->row_array();
?>
<div class="row">
    <div class="col-12">
        <h5 class="mt-0 mb-3"><?php echo get_phrase('ongoing_trip'); ?></h5>
    </div>
</div>

<div class="row">
    <div class="col-xxl-9 mb-3 mb-xxl-0">
        <div class="row">
            <div class="col-12">
                <div id="map" style="height: 350px; width: 100%; border-radius: 5px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3">
        <h5><?php echo get_phrase('trip_info'); ?></h5>
        <form class="ongoing_trip_details">
            <div class="row mb-3">
                <div class="col-xl-4 col-xxl-12">
                    <div class="form-group mb-3">
                        <label for="route"><?php echo get_phrase('vehicle'); ?></label>
                        <input class="form-control" value="<?php echo $vehicle_details['vh_num'] . ', ' . $vehicle_details['vh_model']; ?>" disabled></input>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-12">
                    <div class="form-group mb-3">
                        <label for="route"><?php echo get_phrase('route'); ?></label>
                        <input class="form-control" value="<?php echo $vehicle_details['route'] ?> " disabled></input>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-12">
                    <div class="form-group mb-3">
                        <label for="route"><?php echo get_phrase('started_at'); ?></label>
                        <input class="form-control" value="<?php echo date('m-d-y h:i a', $old_trip['start_time']) ?>" disabled></input>
                    </div>
                </div>

                <div class="col-xl-4 col-xxl-12">
                    <button class="btn btn-primary w-100" type="submit"><?php echo get_phrase('end_trip'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('.ongoing_trip_details').submit(function(e) {
        e.preventDefault();

        let trip_id = '<?php echo $old_trip['id']; ?>';

        $.ajax({
            type: "POST",
            url: "<?php echo route('trip/end_trip/') ?>" + trip_id,
            success: function(response) {
                let res = JSON.parse(response)
                if (res.status) {
                    success_notify(res.notification);
                } else {
                    error_notify(res.notification);
                }

                setTimeout(() => {
                    if (res.refresh) {
                        window.location.reload();
                    }
                }, 3000);
            }
        });
    });
</script>



<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script type="text/javascript">
    "use strict";

    let trackLocation = "<?php echo count($old_trip); ?>";
    trackLocation = Number(trackLocation);

    let trip_id = "<?php echo $old_trip['id']; ?>";
    trip_id = Number(trip_id);

    let track = 'once';

    // when any trip has been started then start tracking
    if (trackLocation > 0) {
        let map = L.map('map');
        map.setView([51.505, -0.09], 13);

        let marker, circle, zoomed;
        navigator.geolocation.watchPosition(success, error);

        function success(pos) {
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const accuracy = pos.coords.accuracy;

            // update driver location in database trip table fields
            console.log(lat + "......" + lng);

            $.ajax({
                type: 'POST',
                url: '<?php echo route('update_driver_location'); ?>',
                data: {
                    latitude: lat,
                    longitude: lng,
                    trip_id: trip_id,
                    track: track,
                }
            });

            // now start tracking update location
            track = 'twice';

            if (marker) {
                map.removeLayer(marker);
                map.removeLayer(circle);
            }
            // Removes any existing marker and circle (new ones about to be set)
            marker = L.marker([lat, lng], {
                opacity: 1,
            }).addTo(map);
            circle = L.circle([lat, lng], {
                radius: 700
            }).addTo(map);

            /*
            this is popup
            let popup = marker.bindPopup('Your current location').openPopup();
            popup.addTo(map);
            */

            // Adds marker to the map and a circle for accuracy
            if (!zoomed) {
                zoomed = map.fitBounds(circle.getBounds());
            }
            map.setView([lat, lng]);
        };

        function error(err) {
            if (err.code === 1) {
                alert("Please allow geolocation access");
            }
        };
    }


    // get route when select a vehicle
    function routeByVehicle(classId) {
        let url = "{{ route('driver.vehicle.route', ['id' => ':classId']) }}";
        url = url.replace(":classId", classId);
        $.ajax({
            url: url,
            success: function(response) {
                $('#start_journey').val(response);
            }
        });
    }


    $(document).ready(function() {
        $(".eChoice-multiple-with-remove").select2();
    });
</script>