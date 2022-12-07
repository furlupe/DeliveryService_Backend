<?php
    include_once dirname(__DIR__)."/controllers/DishController.php";
    include_once "RouterInterface.php";
    class DishRouter implements IRouter {
        public function route($method, $urlList, $requestData) {
            echo (new DishController())->getResponse(
                $method, 
                $urlList, 
                $requestData
            );
        }
    }
?>