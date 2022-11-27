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
        $q = explode("&", $_SERVER["REDIRECT_QUERY_STRING"]);
        $parsedQ = array();
        foreach($q as $key => $value) {
            $p = explode("=", $value);
            if (!isset($parsedQ[$p[0]])) {
                $parsedQ[$p[0]] = array();
            }
            array_push($parsedQ[$p[0]], $p[1]);
        }

        foreach($parsedQ as $key => $value) {
            if ($key == "q") continue;
            if (count($value) == 1) {
                $data->params[$key] = $value[0];
                continue;
            }
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
