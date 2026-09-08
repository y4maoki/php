<?php

class LoginController extends TwigBaseController {
    public $template = "login.twig";

    public function get(array $context) {
        $context['error'] = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        
        echo $this->twig->render($this->template, $context);
    }

    public function post(array $context) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); 
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            $query = $this->pdo->prepare("SELECT * FROM space_users WHERE username = :username");
            $query->execute(['username' => $username]);
            $user = $query->fetch();

            if ($user && $user['password'] === $password) {
                $_SESSION["is_logged"] = true;
                $_SESSION["username"] = $user['username'];
                
                header("Location: /"); 
                exit;
            }   
        }

        $_SESSION['login_error'] = "Неверный логин или пароль!";
        header("Location: /login");
        exit;
    }
}