<?php

class LoginRequiredMiddleware extends BaseMiddleware {
    
    public function apply(BaseController $controller, array $context)
    {
        $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

        if (empty($user) || empty($password)) {
            $this->askForPassword();
        }

        $sql = "SELECT * FROM space_users WHERE username = :username";
        $query = $controller->pdo->prepare($sql);
        $query->execute(['username' => $user]);
        $dbUser = $query->fetch();

        if (!$dbUser || $dbUser['password'] !== $password) {
            $this->askForPassword();
        }
        
    }

    private function askForPassword() {
        header('WWW-Authenticate: Basic realm="Space objects"');
        http_response_code(401);
        exit;
    }
}