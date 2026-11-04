<?php
/**
 * Hitung arah kiblat (bearing dari utara, searah jarum jam)
 * Koordinat Ka'bah: 21.4225°N, 39.8262°E
 */
function qiblaDirection($lat, $lng) {
    $kaabaLat = deg2rad(21.4225);
    $kaabaLng = deg2rad(39.8262);
    $phi1 = deg2rad($lat);
    $deltaLng = $kaabaLng - deg2rad($lng);

    $y = sin($deltaLng);
    $x = cos($phi1) * tan($kaabaLat) - sin($phi1) * cos($deltaLng);
    $bearing = rad2deg(atan2($y, $x));

    return fmod($bearing + 360, 360);
}