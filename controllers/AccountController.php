<?php
    include dirname(__DIR__, 1)."/models/UserRegisterModel.php";
    include dirname(__DIR__, 1)."/JWT.php";
    class AccountController {
        public function getResponse($method, $urlList, $requestData) {
            $response = null;
            
            if (!$urlList) {
                return "Error occured";
            }
            
            switch($method) {
                case "GET":
                    break;
                case "POST":
                    switch($urlList[0]) {
                        case "register":                             
                            $user = new UserRegisterModel($requestData->body);
                            if($user->store()) {
                                $response = (new JWT(
                                    array(
                                        "alg" => "HS256",
                                        "typ" => "JWT"
                                    ),
                                    array(
                                        "email" => $requestData->email,
                                    )
                                ))->getToken();
                            }
                            break;
                        }
                    break;
                default:
                    break;
            }
            return json_encode($response);
        }
    }
?>