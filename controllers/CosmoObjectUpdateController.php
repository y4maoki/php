<?php

class CosmoObjectUpdateController extends BaseCosmoTwigController {
    public $template = "space_object_edit.twig";

    public function get(array $context)
    {
        $id = $this->params['id'] ?? null;

        $query = $this->pdo->prepare("SELECT * FROM planet_space WHERE id = :id");
        $query->execute(['id' => $id]);
        $object = $query->fetch();

        if (!$object) {
            header("Location: /404");
            exit;
        }

        $context['object'] = $object;
        $context['title'] = "Редактирование: " . $object['title'];

        parent::get($context);
    }

    public function post(array $context)
    {
        // ИСПРАВИЛИ ЗДЕСЬ: Берем ID из массива params, как это принято в твоем фреймворке
        $id = $this->params['id'] ?? null; 
        
        $typeId = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $info = $_POST['info'] ?? '';

        $imageUrl = $_POST['old_image'] ?? ''; 
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $name =  $_FILES['image']['name'];
            $imageUrl = "/media/" . $name;
            move_uploaded_file($tmpName, "../public" . $imageUrl);
        }

        $sql = "UPDATE planet_space 
                SET title = :title, description = :description, type_id = :type_id, info = :info, image = :image 
                WHERE id = :id";

        $query = $this->pdo->prepare($sql);
        
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("info", $info);
        $query->bindValue("image", $imageUrl);
        $query->bindValue("type_id", $typeId, PDO::PARAM_INT);
        $query->bindValue("id", $id, PDO::PARAM_INT);
        
        $query->execute();

        header("Location: /");
        exit;
    }
}