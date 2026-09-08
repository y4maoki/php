<?php

class CosmoController extends BaseCosmoTwigController {
    public $template = "object.twig"; 

    public function getContext(): array
    {
        $context = parent::getContext();
        
        $id = $this->params['id'] ?? 1;

        $query = $this->pdo->prepare("SELECT * FROM planet_space WHERE id = :my_id");
        $query->bindValue("my_id", $id);
        $query->execute();
        $data = $query->fetch();
        
        if (!$data) {
            return $context;
        }

        $context['id'] = $id;
        $context['title'] = $data['title'];
        $context['info'] = $data['info']; 
        $context['image'] = $data['image'];

        $show = $_GET['show'] ?? '';
        
        if ($show === 'info') {
            $context['show'] = 'info';
        } elseif ($show === 'image') {
            $context['show'] = 'image';
        } else {
            $context['show'] = ''; 
        }

        return $context;
    }
}