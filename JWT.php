<?php
    function generateJWT($headers, $payload, $secret="morbius") {
        $headers_enc = base64url_encode(json_encode($headers));
        $payload_enc = base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$headers_enc.$payload_enc", $secret, true);
        $signature_enc = base64url_encode($signature);

        return "$headers_enc.$payload_enc.$signature_enc";
    }
    function base64url_encode($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
?>