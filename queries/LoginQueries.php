<?php
    class LoginQueries {
        public static function checkUser($email, $password) {
            return $GLOBALS["LINK"]->query(
                "SELECT *
                    FROM USERS
                    WHERE email=? AND password=?",
                $email,
                $password
            )->num_rows() > 0;
        }
    }
?>