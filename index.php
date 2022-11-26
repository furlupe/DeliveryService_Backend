<?php
    include_once "routers/DishRouter.php";
    include_once "helpers/headers.php";
    include_once "exceptions/ExtendedExceptionInterface.php";
    include_once "exceptions/NonExistingURLException.php";

    const ip = "127.0.0.1";
    const username = "backend_food";
    const password = "password";
    const db = "backend_food";

    global $LINK;

    function getData($method) {
        $data = new stdClass();
        if ($method != "GET") {
            $data->body = json_decode(file_get_contents('php://input'));
        }

        $data->params = [];
        foreach($_GET as $key => $value) {
            if ($key == "q") continue;
            $data->params[$key] = $value;
        }

        return $data;
    }

    function determineRouter($key) : IRouter {
        switch($key) {
            case "dish":
                return new DishRouter();
            default:
                throw new NonExistingURLException();
        }
    }

    header('Content-type: application/json');

    $LINK = new mysqli(ip, username, password, db);
    $REQUESTS_CALLBACKS = array();

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

    try {
        $router = determineRouter($urlList[1]);
        $router->route(
            $method, 
            array_slice($urlList, 2), 
            $requestData
        );
    }
    catch (IExtendedException $e) {
        setHTPPStatus(
            $e->getCode(), 
            $e->getMessage(), 
            $e->getData()
        );
    }
?>
