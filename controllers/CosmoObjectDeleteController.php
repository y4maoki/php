<?php

class CosmoObjectDeleteController extends BaseController {

    public function post(array $context)
    {
        $id = $this->params['id'] ?? null;

        if ($id) {
            $sql = "DELETE FROM planet_space WHERE id = :id";
            
            $query = $this->pdo->prepare($sql);
            $query->bindValue(":id", $id);
            $query->execute();
        }

        header("Location: /");
        exit;
    }
}