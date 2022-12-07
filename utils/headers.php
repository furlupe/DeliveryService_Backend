<?php
    function setHTPPStatus($status = "HTTP/1.0 200 OK", $message = null, $extras = null) {
        $isError = ($status != "200");
        $status = determineStatus($status);
        
        header($status);
        $response = array();

        if(!is_null($message)) {
            if ($isError) {
                $response["status"] = "Error";
            }
            $response["message"] = $message;
            if (!is_null($extras)) {
                foreach($extras as $key => $value) {
                    $response[$key] = $value;
                }
            }
        }

        if ($response) echo json_encode($response);
    }

    function determineStatus($code) {
        switch ($code) {
            default:
            case "200":
                return "HTTP/1.0 200 OK";
            case "400":
                return "HTTP/1.0 400 Bad Request";
            case "401":
                return "HTTP/1.0 401 Unauthorized";
            case "403":
                return "HTTP/1.0 403 Forbidden";
            case "404":
                return "HTTP/1.0 404 Not Found";
            case "500":
                return "HTTP/1.0 500 Internal Server Error";
        }
    }
?>