<?php
    include_once "DTOInterface.php";
    class BasicDTO implements IDTO {
        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }

    }
?>