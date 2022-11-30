<?php
    include_once "exceptions/NonExistingUrlException.php";
    include_once dirname(__DIR__, 1)."/routers/OrderRouter.php";
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

        private function getData() {
            $data = new stdClass();
            if ($this->method != "GET") {
                $data->body = json_decode(file_get_contents('php://input'));
            }

            $data->params = [];
            foreach($_GET as $key => $value) {
                if ($key == "q") continue;
                $data->params[$key] = $value;
            }

            return $data;
        }

        private function determineRouter() {
            switch($this->uri[1]) {
                case "order":
                    return new OrderRouter();
            }
        }
    }
?>