<?php

class LogoutController extends BaseController {
    public function get(array $context) {
        $_SESSION["is_logged"] = false;
        unset($_SESSION["username"]);
        
        header("Location: /login");
        exit;
    }
}