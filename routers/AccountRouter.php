<?php
    include "RouterInterface.php";
    include dirname(__DIR__, 1)."/controllers/AccountController.php";
    class AccountRouter implements IRouter {
        
        public function route($method, $urlList, $requestData) {
            $controller = new AccountController();
            echo $controller->getResponse(
                $method,
                $urlList,
                $requestData
            );
        }
    }
?>