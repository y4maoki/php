<?php

class MainController extends BaseCosmoTwigController {
    public $template = "main.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        
        $typeId = isset($_GET['type_id']) ? (int)$_GET['type_id'] : 0;

        $sql = "SELECT p.*, t.name AS type_name 
                FROM planet_space p
                INNER JOIN space_types t ON p.type_id = t.id";

        $params = [];

        if ($typeId > 0) {
            $sql .= " WHERE p.type_id = :type_id";
            $params['type_id'] = $typeId;
        }

        $sql .= " ORDER BY p.id DESC";

        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        $context['space_objects'] = $query->fetchAll();
        $context['title'] = "Главная страница";
        $context['selected_type_id'] = $typeId; 

        return $context;
    }
}