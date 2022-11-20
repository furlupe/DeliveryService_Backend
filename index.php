<?php
    include "routers/AccountRouter.php";
    function getData($method) {
        $data = new stdClass();
        if ($method != "GET") {
            $data = json_decode(file_get_contents('php://input'));
        }

        $data->params = [];
        foreach($_GET as $key => $value) {
            if ($key == "q") continue;
            $data->params[$key] = $value;
        }

        return $data;
    }

    function determineRouter($key) {
        switch($key) {
            case "account":
                return new AccountRouter();
        }
    }

    header('Content-type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];

    $url = rtrim(
        isset($_GET['q']) ? $_GET['q'] : '',
        '/'
    );
    $urlList = explode('/', $url);

    if ($urlList[0] != "api") {
        echo "wrong endpoint";
        exit;
    }

    $requestData = getData($method);
    determineRouter($urlList[1])->route();
?>