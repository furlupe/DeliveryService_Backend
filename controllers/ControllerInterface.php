<?php
    interface IController {
        public function getResponse($method, $urlList, $requestData);
    }
?>
