<?php
    include_once dirname(__DIR__, 1)."/services/AccountService.php";
    class AccountController {
        public static function getResponse($method, $urlList, $requestData) {
            $response = null;
            
            /* implement error throwing */
            if (!$urlList) {
                return "Error occured";
            }
            
            switch($method) {
                case "GET":
                    break;
                case "POST":
                    switch($urlList[0]) {
                        case "register":                             
                            $response = AccountService::register($requestData->body);
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