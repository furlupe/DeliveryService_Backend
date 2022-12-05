<?php
    class AccountQueries {
        public static function addUser(...$data) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO USERS(id, name, birthdate, gender, phone, email, adress, password) 
                VALUES(UUID(), ?, ?, ?, ?, ?, ?, ?)",
                ...$data
            );
        }
        public static function getUser($email) {
            return $GLOBALS["LINK"]->query(
                "SELECT * 
                FROM USERS 
                WHERE email=?",
                $email)->fetch_assoc();
        }
        public static function editUser($email, ...$data) {
            $GLOBALS["LINK"]->query(
                "UPDATE USERS 
                SET name=?, birthdate=?, gender=?, phone=?, adress=?
                WHERE email=?",
                ...$data, ...[$email]
            );
        }
    }
?>