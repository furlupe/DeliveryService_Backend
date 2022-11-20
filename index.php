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
    $link = mysqli_connect("127.0.0.1", "backend_food", "password", "backend_food");

    if(!$link) {
        echo "Connection error occured".PHP_EOL;
        echo "Errno: ".mysqli_connect_errno().PHP_EOL;
        echo "Error: ".mysqli_connect_error().PHP_EOL;
        exit;
    }

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