<?php
    include_once dirname(__DIR__, 1)."/services/AccountService.php";
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    class AccountController {
        public static function getResponse($method, $urlList, $requestData) {
            $response = null;
            
            if (!$urlList) {
                throw new NonExistingURLException(
                    "URL doesn't exists", 
                    "404"
                );
            }
            
            switch($method) {
                case "GET":
                    break;
                case "POST":
                    switch($urlList[0]) {
                        case "register":                             
                            $response = AccountService::register($requestData->body);
                            break;
                        case "login":                             
                            $response = AccountService::login($requestData->body);
                            break;
                        default:
                            throw new NonExistingURLException(
                                "URL doesn't exists: /".implode("/", $urlList),
                                "404"
                            );
                        }
                    break;
                default:
                    throw new NonExistingURLException(
                        "URL doesn't exists: \n\t".implode("/", $urlList), 
                        "404"
                    );
            }
            return json_encode($response);
        }
    }
?>
