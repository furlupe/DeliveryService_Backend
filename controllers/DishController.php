<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/DishService.php";

    class DishController extends BasicController{
        private static function setResponse($method, $urlList, $requestData) {
            if(isset(getallheaders()["Authorization"])) {
                $token = explode(" ", getallheaders()["Authorization"])[1];
            }

            switch($method) {
                case "GET":
                    return array();
                case "POST":
                    return array();
            }
        }
    }
?>