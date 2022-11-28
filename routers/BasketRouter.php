<?php
    include_once dirname(__DIR__)."/controllers/BasketController.php";
    include_once "RouterInterface.php";

    class BasketRouter implements IRouter {
        public function route($method, $urlList, $requestData) {
            echo BasketController::getResponse(
                $method,
                $urlList,
                $requestData
            );
        }
    }
?>