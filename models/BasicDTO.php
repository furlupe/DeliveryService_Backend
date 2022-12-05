<?php
    include_once "DTOInterface.php";
    abstract class BasicDTO implements IDTO {
        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }

    }
?>