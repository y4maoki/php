<?php

class CosmoTypeCreateController extends BaseCosmoTwigController {
    public $template = "cosmo_type_create.twig";

    public function get(array $context) {
        parent::get($context);
    }

    public function post(array $context) {
        $name = $_POST['name'] ?? '';
        
        if (empty($name)) {
            $context['error'] = 'Название типа обязательно!';
            $this->get($context);
            return;
        }

        $imageUrl = '/images/default_type.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            if (move_uploaded_file($tmpName, "../public/media/$fileName")) {
                $imageUrl = "/media/$fileName";
            }
        }

        $sql = "INSERT INTO space_types (name, image) VALUES (:name, :image)";
        $query = $this->pdo->prepare($sql);
        $query->bindValue("name", strtolower($name));
        $query->bindValue("image", $imageUrl);
        $query->execute();

        $context['message'] = 'Новый тип объектов успешно добавлен!';
        $this->get($context);
    }
}