<?php
    enum Gender : string {
        case Male = "Male";
        case Female = "Female";
        public static function checkIfExists($gender) {
            $genders = array_column(Gender::cases(), "value");
            if (array_search($gender, $genders) !== false) {
                return true;
            }
            return false;
        }
    }
?>