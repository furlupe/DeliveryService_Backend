<?php
    function joinRegex($r1, $r2) {
        $r1 = substr($r1, 0, -1);
        $r2 = substr($r2, 1);
        return $r1.$r2;
    }
?>
