<?php
     include '../includes/config.php';
     $array = array();

     $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
     $daycare = filter_input(INPUT_POST, 'daycare', FILTER_SANITIZE_SPECIAL_CHARS);
     $licencia = filter_input(INPUT_POST, 'licencia', FILTER_SANITIZE_SPECIAL_CHARS);
     $condado = filter_input(INPUT_POST, 'condado', FILTER_SANITIZE_SPECIAL_CHARS);
     $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
     $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);

     if (!$name || !$daycare || !$licencia || !$condado || !$email || !$phone) {
         $array['message'] = 0;
         $array['error'] = 'Invalid or missing parameters';
         echo json_encode($array);
         exit;
     }

     $response = $a->sendInvitationAdmin($name,$daycare,$licencia,$condado,$email,$phone);
     $array['message'] = 1;
     $array['response'] = $response;
     echo json_encode($array);

?>