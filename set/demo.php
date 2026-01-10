<?php
include '../includes/config.php';
$array = array();
$array['message'] = 1;

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
$daycare_name = filter_input(INPUT_POST, 'daycare_name', FILTER_SANITIZE_SPECIAL_CHARS);
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$name || !$email || !$phone || !$daycare_name || !$country || !$city) {
    $array['message'] = 0;
    $array['error'] = 'Invalid or missing parameters';
    echo json_encode($array);
    exit;
}

$array['info']['name'] = $name;
$array['info']['email'] = $email;
$array['info']['phone'] = $phone;
$array['info']['daycare'] = $daycare_name;
$array['info']['country'] = $country;
$array['info']['city'] = $city;

$a->sendDemoEmail($name, $email, $phone, $daycare_name, $country, $city);
echo json_encode($array);
