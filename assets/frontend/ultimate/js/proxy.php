<?php
if (isset($_GET['address'])) {
    $query = urlencode($_GET['address']);
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . $query;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ajouter un en-tête User-Agent pour se conformer aux exigences de Nominatim
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: wayo (info@wayo.cloud)' // Remplacez "YourAppName" par le nom de votre application et utilisez une adresse e-mail valide
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $response;
} else {
    echo json_encode(["error" => "Address parameter missing"]);
}
?>
