<?php

class SearchController extends BaseCosmoTwigController {
    public $template = "search.twig"; 

    public function getContext(): array
    {
        $context = parent::getContext();
        $context['title'] = "Поиск объектов";

        $typeId = isset($_GET['type_id']) ? (int)$_GET['type_id'] : 0;
        $searchTitle = $_GET['title'] ?? '';
        $searchInfo = $_GET['info'] ?? '';

        $context['selected_type_id'] = $typeId;
        $context['search_title'] = $searchTitle;
        $context['search_info'] = $searchInfo;
        
        $sql = "SELECT p.*, t.name AS type_name 
                FROM planet_space p
                INNER JOIN space_types t ON p.type_id = t.id 
                WHERE 1=1";
        $params = [];

        if ($typeId > 0) {
            $sql .= " AND p.type_id = :type_id";
            $params['type_id'] = $typeId;
        }

        if (!empty($searchTitle)) {
            $sql .= " AND p.title LIKE :title";
            $params['title'] = '%' . $searchTitle . '%';
        }

        if (!empty($searchInfo)) {
            $sql .= " AND p.info LIKE :info";
            $params['info'] = '%' . $searchInfo . '%';
        }

        $sql .= " ORDER BY p.title ASC";

        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        $context['space_objects'] = $query->fetchAll();

        return $context;
    }
}