<?php
    class PageInfoModel {
        private $size;
        private $count;
        private $current;

        public function __construct($size, $page) {
            $this->size = 3;
            $this->count = $this->getCount($size);
            $this->current = intval($page);
        }

        private function getCount($size) {
            return floor($size / $this->size) 
            + ($size % $this->size > 0);
        }

        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }
    }
?>