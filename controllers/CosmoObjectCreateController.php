<?php

class CosmoObjectCreateController extends BaseCosmoTwigController {
    public $template = "space_object_create.twig";

    public function get(array $context)
    {
        parent::get($context);
    }

    public function post(array $context)
    {
        $typeId = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $info = $_POST['info'] ?? '';

        $imageUrl = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $name =  $_FILES['image']['name'];
            $imageUrl = "/media/" . $name;
            move_uploaded_file($tmpName, "../public" . $imageUrl);
        }

        $sql = "INSERT INTO planet_space (title, description, info, image, type_id) 
                VALUES (:title, :description, :info, :image, :type_id)";

        $query = $this->pdo->prepare($sql);
        
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("info", $info);
        $query->bindValue("image", $imageUrl);
        $query->bindValue("type_id", $typeId, PDO::PARAM_INT);
        
        $query->execute();

        header("Location: /");
        exit;
    }
}