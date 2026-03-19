<?php
$exif = exif_read_data(__DIR__ . '/test.jpg');
echo "<pre>";
print_r($exif);
echo "</pre>";

// Test GPS conversion
if (isset($exif['GPSLatitude']) && isset($exif['GPSLongitude'])) {
    $lat = $exif['GPSLatitude'];
    $lon = $exif['GPSLongitude'];
    $latRef = $exif['GPSLatitudeRef'];
    $lonRef = $exif['GPSLongitudeRef'];

    echo "<h3>GPS Data:</h3>";
    echo "Lat: " . print_r($lat, true) . "<br>";
    echo "Lon: " . print_r($lon, true) . "<br>";
    echo "LatRef: $latRef, LonRef: $lonRef<br>";

    // Convert
    function fractionToDecimal($value) {
        if ($value === null) return null;
        if (is_float($value) || is_int($value)) return (float)$value;
        if (is_string($value)) {
            $parts = explode('/', $value);
            if (count($parts) === 2) {
                $numerator = (float) $parts[0];
                $denominator = (float) $parts[1];
                if ($denominator !== 0) return $numerator / $denominator;
            }
            return (float) $value;
        }
        return null;
    }

    $latDec = fractionToDecimal($lat[0]) + (fractionToDecimal($lat[1]) / 60) + (fractionToDecimal($lat[2]) / 3600);
    $lonDec = fractionToDecimal($lon[0]) + (fractionToDecimal($lon[1]) / 60) + (fractionToDecimal($lon[2]) / 3600);

    if ($latRef === 'S' || $latRef === 'South') $latDec = -$latDec;
    if ($lonRef === 'W' || $lonRef === 'West') $lonDec = -$lonDec;

    echo "<h3>Converted:</h3>";
    echo "Latitude: $latDec<br>";
    echo "Longitude: $lonDec<br>";
}
