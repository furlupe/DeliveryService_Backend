<?php
    function dateFormatting($str){
        $str = explode("T", $str);
        $date = $str[0];
        $time = $str[1];
        $time = substr($time, 0, strpos($time, "."));

        return $date . " " . $time;
    }
?>