<?php
    class Query {
        //$link = new mysqli("127.0.0.1", "backend_food", "password", "backend_food");
        const ip = "127.0.0.1";
        const username = "backend_food";
        const password = "password";
        const db = "backend_food";
        public static function connect() {
            return new mysqli(self::ip, self::username, self::password, self::db);
        }
    }
?>