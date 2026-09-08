<?php

class BaseCosmoTwigController extends TwigBaseController {

    public function getContext(): array
    {
        $context = parent::getContext();
        
        if (!isset($_SESSION['history'])) {
            $_SESSION['history'] = [];
        }

        $current_url = urldecode($_SERVER['REQUEST_URI']);

        if (empty($_SESSION['history']) || end($_SESSION['history']) !== $current_url) {
            array_push($_SESSION['history'], $current_url);
        }

        if (count($_SESSION['history']) > 10) {
            array_shift($_SESSION['history']);
        }

        $context['history'] = array_reverse($_SESSION['history']);
        
        $query = $this->pdo->query("SELECT id, name FROM space_types ORDER BY name ASC");
        $context['types'] = $query->fetchAll();
        
        $authUser = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

        $isLogged = false;

        if (!empty($authUser) && !empty($authPassword)) {
            $q = $this->pdo->prepare("SELECT * FROM space_users WHERE username = :user");
            $q->execute(['user' => $authUser]);
            $userInDb = $q->fetch();
            
            if ($userInDb && $userInDb['password'] === $authPassword) {
                $isLogged = true;
            }
        }
        
        $context['is_logged'] = $isLogged;
        
        return $context;
    }
}