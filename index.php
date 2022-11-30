<?php
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
    global $UUID_REGEX;

    header('Content-type: application/json');

    $LINK = new ESQL(ip, username, password, db);
    $USER_TOKEN = explode(" ", getallheaders()["Authorization"])[1];
    $UUID_REGEX = "/[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}/";

    try {
        (new Request())->callRouter();
    }
    catch (IExtendedException $e) {
        $e->sendHTTP();
    }
?>
