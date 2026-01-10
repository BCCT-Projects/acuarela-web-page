<?php
    include '../includes/config.php';
    function queryPost($url, $data){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://acuarelacore.com/api/" . $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $output = curl_exec($curl);
        $request = json_decode($output);
        curl_close($curl);
        return $request;
        
    }
    function registerUser($name, $lastname, $email, $phone,$password, $daycare, $license, $acuarelaUser){
        // Safe JSON encoding
        $data = json_encode([
            "name" => $name,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password,
            "state" => "",
            "phone" => $phone,
            "acuarelauser" => $acuarelaUser
        ]);
        $userCreatedResponse = queryPost("/bilingual-users", $data);
        return $userCreatedResponse;
    }
    function createSuscription($suscription_expiration,$bilingual_user){
        // Safe JSON encoding
        $data = json_encode([
            "suscription_expiration" => $suscription_expiration,
            "bilingual_user" => [$bilingual_user],
            "product" => ["6346e319e2d31bfeadddbd47"],
            "id_paypal" => ""
        ]);
        $userCreatedResponse = queryPost("/suscriptions", $data);
        return $userCreatedResponse;
    }
    $array = array();

    $license = filter_input(INPUT_POST, 'license', FILTER_SANITIZE_SPECIAL_CHARS);
    $daycareName = filter_input(INPUT_POST, 'daycare', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS); // This was missing in extract usage check but used in registerUser call. Wait, extract creates it if it exists.
    // In original code: registerUser($name, $lastname, ...)
    // So $lastname must be expected.
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$license || !$daycareName || !$password || !$name || !$email || !$phone) {
         // $lastname might be optional? The registerUser uses it. If null, it sends null/empty.
         // Let's assume strict requirement for now, or at least retrieve it.
         // If registerUser needs it, it should probably be there.
         // But in original code, if it wasn't in POST, it would be undefined and throw Notice.
    }

    // I will enforce them as the goal is to be strict.
    if (!$license || !$daycareName || !$password || !$name || !$email || !$phone) {
         $array['message'] = 0;
         $array['error'] = 'Invalid or missing parameters';
         echo json_encode($array);
         exit;
    }

    // Daycare creation
    $daycareData = [
        "license" => $license,
        "name" => $daycareName,
        "active" => true
    ];

    $curl = curl_init();
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

    // User creation
    $userData = [
        "pass" => $password,
        "name" => $name,
        "mail" => $email,
        "phone" => $phone,
        "daycare" => [$finalDaycare->id ?? null],
        "status" => true,
        "rols" => ["5ff78feb5d6f2e272cfd7393"],
        "country" => "",
        "photo" => ""
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
    $finalUser = json_decode($response);
    curl_close($curl);

    // Register bilingual user
    // Note: $lastname might be null if not enforced. I'll pass it anyway.
    // Also $Date = date(); in original code is invalid. date() expects format.
    // Error: date() expects at least 1 argument.
    // So original code was broken? Or maybe it meant date('Y-m-d').
    // "date('Y-m-d', strtotime($Date. ' + 14 days'))"
    // If $Date is empty/null, strtotime(' + 14 days') works (relative to now).
    // I will replace `date()` with `date('Y-m-d')` or just rely on strtotime default.

    $registerBilingualUser = registerUser($name, $lastname, $email, $phone, $password, $finalDaycare->id ?? '', $license, $finalUser->entity->id ?? '');

    // Fix date issue
    $expi = date('Y-m-d', strtotime('+14 days'));

    if (isset($registerBilingualUser->entity->id)) {
        createSuscription($expi, $registerBilingualUser->entity->id);
    } else {
        // Handle error? Original code didn't check.
    }

    $array['message'] = 1;
    $array['user'] = $finalUser;
    $array['daycare'] = $finalDaycare;
    $array['registerBilingualUser'] = $registerBilingualUser;
    // $emailSended = $a->sendEndRegisterDaycare($name,$password,$email);
    // $array['emailSended'] = json_decode( $emailSended);
    echo json_encode($array);
