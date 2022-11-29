<?php
    class ESQL {
        private $link;
        private $stmt;
        public function __construct($id, $username, $password, $db) {
            $this->link = new mysqli($id, $username, $password, $db);
        }

        public function query($request, $params) {
            $this->stmt = $this->link->prepare($request);
            foreach($params as $key => $value) {
                $type = "s";
                if(is_int($value)) $type = "i";
                $this->stmt->bind_param($type, $value);
            }
            $this->stmt->execute();
        }

        public function fetch() {
            $this->stmt->bind_result($r);
            $this->stmt->fetch();

            return $r;
        }
        
    }
?>