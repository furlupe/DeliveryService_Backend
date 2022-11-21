<?php
    include dirname(__DIR__, 1)."/models/UserRegisterModel.php";
    class AccountController {
        public function getResponse($method, $urlList, $requestData) {
            $response = null;
            
            if (!$urlList) {
                return "Error occured";
            }
            
            $link = new mysqli("127.0.0.1", "backend_food", "password", "backend_food");
            switch($method) {
                case "GET":
                    break;
                case "POST":
                    $email = $requestData->body->email;
                    $userExists = $link->query("SELECT id FROM USERS WHERE email='$email'")->fetch_assoc();

                    if(is_null($userExists)) {
                        $body = $requestData->body;
                        $body->password = hash("sha1", $body->password);
                        $body->birthDate = date('y-m-d',strtotime($body->birthDate));
                        
                        $user = new UserRegisterModel($body);
                        $insert = $user->store();

                        if(!$insert) echo "error occured";

                    } else {
                        echo $userExists;
                    }
                    break;
                default:
                    break;
            }
            
            $link->close();
            return json_encode($response);
        }
    }
?>