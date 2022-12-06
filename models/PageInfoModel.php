<?php
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    class PageInfoModel {
        private $size;
        private $count;
        private $current;

        const MAX_PAGE_SIZE = 5;

        public function __construct($size, $page) {
            $this->count = $this->getCount($size);

            $page = intval($page);
            if ($size == 0) {
                $page = 0;
            }

            if($page > $this->count || $page < 1) {
                throw new URLParametersException(
                    extras: array("Page" => "Page index is greater than amount of pages")
                );
            }

            $this->current = intval($page);

            $this->size = self::MAX_PAGE_SIZE;
            $d = $size - self::MAX_PAGE_SIZE * ($this->current - 1);
            if($d < self::MAX_PAGE_SIZE) {
                $this->size = $d;   
            }
        }
        private function getCount($size) {
            return floor($size / self::MAX_PAGE_SIZE) 
            + ($size % self::MAX_PAGE_SIZE > 0);
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