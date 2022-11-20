<?php
    include dirname(__DIR__, 1)."/models/UserDto.php";
    class AccountController {
        public function getResponse($method, $urlList, $requestData) {
            $response = null;
            
            if (!$urlList) {
                return "Error occured";
            }
            
            $link = new mysqli("127.0.0.1", "backend_food", "password", "backend_food");
            
            $link->close();
            return json_encode($response);
        }
    }
?>