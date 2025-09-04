<?php
// Quick test of the data classes
require_once('views/pages/geo/data/user_provincia_table.php');
require_once('views/pages/geo/data/province_activity_table.php');

echo "Testing User_provincia_table:\n";
if (class_exists('User_provincia_table')) {
    echo "- Class exists\n";
    if (method_exists('User_provincia_table', 'getprovinciainfo')) {
        echo "- Method exists\n";
        $geoData = User_provincia_table::getprovinciainfo();
        echo "- Data type: " . gettype($geoData) . "\n";
        echo "- Data count: " . (is_array($geoData) ? count($geoData) : 'not array') . "\n";
        if (is_array($geoData) && count($geoData) > 0) {
            echo "- First item: " . print_r($geoData[0], true) . "\n";
        }
    } else {
        echo "- Method does not exist\n";
    }
} else {
    echo "- Class does not exist\n";
}

echo "\nTesting Activity_province_table:\n";
if (class_exists('Activity_province_table')) {
    echo "- Class exists\n";
    if (method_exists('Activity_province_table', 'getprovinceactivity')) {
        echo "- Method exists\n";
        $provinceData = Activity_province_table::getprovinceactivity();
        echo "- Data type: " . gettype($provinceData) . "\n";
        echo "- Data count: " . (is_array($provinceData) ? count($provinceData) : 'not array') . "\n";
        if (is_array($provinceData) && count($provinceData) > 0) {
            echo "- First item: " . print_r($provinceData[0], true) . "\n";
        }
    } else {
        echo "- Method does not exist\n";
    }
} else {
    echo "- Class does not exist\n";
}
?>
