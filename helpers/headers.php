<?php
    function setHTPPStatus($status = "HTTP/1.0 200 OK", $message = null, $extras = null) {
        switch ($status) {
            default:
            case "200":
                $status = "HTTP/1.0 200 OK";
                break;
            case "400":
                $status = "HTTP/1.0 400 Bad Request";
                break;
            case "404":
                $status = "HTTP/1.0 404 Not Found";
                break;
            case "401":
                    $status = "HTTP/1.0 401 Unauthorized";
                    break;
            case "500":
                $status = "HTTP/1.0 500 Internal Server Error";
                break;
        }
        
        header($status);
        $response = array();

        if(!is_null($message)) {
            $response["status"] = $status;
            $response["message"] = $message;
            if (!is_null($extras)) {
                foreach($extras as $key => $value) {
                    $response[$key] = $value;
                }
            }
        }

        if ($response) echo json_encode($response);
    }
?>