<?php
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

    header('Content-type: application/json');
    $link = mysqli_connect("127.0.0.1", "backend_food", "password", "backend_food");

    if(!$link) {
        echo "еррор какой-та".PHP_EOL;
        echo "ашипка: ".mysqli_connect_error().PHP_EOL;
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

    $controllerpath = 'routing/'.$urlList[1].'.php';
    $requestData = getData($method);

    if(file_exists(realpath(dirname(__FILE__)).'/'.$controllerpath)) {
        include_once $controllerpath;
        route($method, $urlList, $requestData);
    } else {
        echo 'No such request as '.$url;
    }
?>