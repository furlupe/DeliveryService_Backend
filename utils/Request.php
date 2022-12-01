<?php
    include_once "exceptions/NonExistingUrlException.php";
    include_once dirname(__DIR__, 1)."/routers/DishRouter.php";
    class Request {
        private $method;
        private $uri;
        private $data;
        private $router;

        public function __construct() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $uri = rtrim(
                isset($_GET['q']) ? $_GET['q'] : '',
                '/'
            );
            $this->uri = explode('/', $uri);
            if ($this->uri[0] != "api") {
                throw new NonExistingURLException("All requests must start with 'api/'");
            }
            $this->data = $this->getData();
            $this->router = $this->determineRouter();
        }

        public function callRouter() {
            $this->router->route(
                $this->method,
                array_slice($this->uri, 2),
                $this->data
            );
        }

        function getData() {
            $data = new stdClass();
            if ($this->method != "GET") {
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

        private function determineRouter() {
            switch($this->uri[1]) {
                case "dish":
                    return new DishRouter();
            }
        }
    }
?>