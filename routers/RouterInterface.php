<?php
    interface IRouter {
        public function route($method, $urlList, $requestData);
    }
?>