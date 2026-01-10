<?php
    include '../includes/config.php';
    function queryStrapi($url, $body = "", $method="GET") {

        $endpoint = "https://acuarelacore.com/api/" . $url;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'token: '. $_SESSION["userLogged"]->user->token),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    $array = array();

    // Explicit inputs
    $license = filter_input(INPUT_POST, 'license', FILTER_SANITIZE_SPECIAL_CHARS);
    $daycareInput = filter_input(INPUT_POST, 'daycare', FILTER_SANITIZE_SPECIAL_CHARS);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_SPECIAL_CHARS);
    $phonedaycare = filter_input(INPUT_POST, 'phonedaycare', FILTER_SANITIZE_SPECIAL_CHARS);
    $emaildaycare = filter_input(INPUT_POST, 'emaildaycare', FILTER_VALIDATE_EMAIL);
    $expiration = filter_input(INPUT_POST, 'expiration', FILTER_SANITIZE_SPECIAL_CHARS);
    // Password should be read raw but handled safely in JSON
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$license || !$daycareInput || !$state || !$phonedaycare || !$emaildaycare || !$expiration || !$password || !$name || !$lastname || !$email || !$phone) {
        $array['message'] = 0;
        $array['error'] = 'Invalid or missing parameters';
        echo json_encode($array);
        exit;
    }

    $curl = curl_init();

    $daycareData = [
        "license" => $license,
        "name" => $daycareInput,
        "state" => $state,
        "phone" => $phonedaycare,
        "mail" => $emaildaycare,
        "license_expiration_date" => date("Y-m-d", strtotime($expiration)),
        "active" => true
    ];

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://acuarelacore.com/api/daycares',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($daycareData),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
    ));
    $daycare = curl_exec($curl);
    $finalDaycare = json_decode($daycare);
    curl_close($curl);

    $userData = [
        "pass" => $password,
        "name" => $name,
        "lastname" => $lastname,
        "mail" => $email,
        "phone" => $phone,
        "daycare" => [$finalDaycare->id ?? null],
        "status" => true,
        "rols" => ["5ff78feb5d6f2e272cfd7393"],
        "country" => "",
        "photo" => "",
        "state" => $state,
        "new_account" => true
    ];

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://acuarelacore.com/api/acuarelausers/register',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($userData),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $array['message'] = 1;
    $array['user'] = json_decode($response);
    $array['daycare'] = json_decode($daycare);
    echo json_encode($array);
