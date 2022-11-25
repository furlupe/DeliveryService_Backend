<?php
    include_once dirname(__DIR__, 1)."/helpers/Token.php";
    include_once dirname(__DIR__, 1)."/models/UserRegisterModel.php";
    class AccountService {
        public static function register($data) : array {
            $user = new UserRegisterModel($data);

            $user->store();
            
            /* move code on lines 12-25 to the login() func when it's implemented */
            $token = Token::generateJWT(
                array(
                    "email" => $data->email,
                    "exp" => ((new DateTime())->modify("+20 minutes")->format('Y-m-d H:i:s'))
                ));

            return array("token" => $token);
        }
    }
?>