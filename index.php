<?php
    include_once "routers/AccountRouter.php";
    include_once "utils/headers.php";
    include_once "utils/ESQL.php";
    include_once "exceptions/ExtendedExceptionInterface.php";
    include_once "utils/Request.php";

    const ip = "127.0.0.1";
    const username = "backend_food";
    const password = "password";
    const db = "backend_food";

    global $LINK;
    global $USER_TOKEN;

    header('Content-type: application/json');

    $LINK = new ESQL(ip, username, password, db);
    $USER_TOKEN = explode(" ", getallheaders()["Authorization"])[1];

    try {
        $request = new Request();
        $request->callRouter();
    }
    catch (IExtendedException $e) {
        setHTPPStatus(
            $e->getCode(), 
            $e->getMessage(), 
            $e->getData()
        );
    }
?>
