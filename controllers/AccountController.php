<?php
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
                        
                        }
                    break;
                default:
                    break;
            }
            return json_encode($response);
        }
    }

?>