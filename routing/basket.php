<?php
    function route($method, $urlList, $requestData) {
        echo "basket request. info:".PHP_EOL;
        echo json_encode($method).PHP_EOL;
        echo json_encode($urlList).PHP_EOL;
        echo json_encode($requestData).PHP_EOL;
    }