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
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $context['is_logged'] = $_SESSION['is_logged'] ?? false;
        $context['username'] = $_SESSION['username'] ?? '';
        
        return $context;
    }
}