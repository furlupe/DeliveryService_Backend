<?php
    include_once "ControllerInterface.php";
    class BasicController implements IController {
        public function getResponse($method, $urlList, $requestData) {
            $response = $this->setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }

        protected function setResponse($method, $urlList, $requestData) {
            return array();
        }
    }
?>
