<?php
    include_once dirname(__DIR__)."/controllers/OrderController.php";
    include_once "RouterInterface.php";

    class OrderRouter implements IRouter {
        public function route($method, $urlList, $requestData) {
            echo (new OrderController())->getResponse(
                $method,
                $urlList,
                $requestData
            );
        }
    }
?>
