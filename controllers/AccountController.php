<?php
    include_once dirname(__DIR__, 1)."/services/AccountService.php";
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    class AccountController {
        public static function getResponse($method, $urlList, $requestData) {
            $response = null;
            
            /* implement error throwing */
            if (!$urlList) {
                throw new NonExistingURLException("URL doesn't exists");
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
                    throw new NonExistingURLException("URL doesn't exists".implode("/", $urlList));
                    break;
            }
            return json_encode($response);
        }
    }
?>