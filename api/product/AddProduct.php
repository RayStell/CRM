<?php session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $formData = $_POST;
    $fields = ['name', 'description', 'price', 'stock'];
    $errors = [];
    $_SESSION['products_errors'] = '';
    
    // 1. Проверить пришли ли данные 
    foreach($fields as $field){
        if(!isset($formData[$field]) || empty($_POST[$field])){
            $errors[$field][] = 'field is required';
        }
    }

    if(!empty($errors)){
        $error_list = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">';
        $error_list .= '<h4 style="margin: 0 0 10px 0; color: #842029;">Пожалуйста, исправьте следующие ошибки:</h4>';
        $error_list .= '<ul style="margin: 0; padding-left: 20px;">';
        foreach ($errors as $field => $error_array) {
            $error_messages = implode(', ', $error_array);
            $field_name = [
                'name' => 'Название',
                'description' => 'Описание',
                'price' => 'Цена',
                'stock' => 'Количество'
            ][$field] ?? $field;
            
            $error_list .= sprintf(
                '<li style="margin-bottom: 5px;"><strong>%s</strong>: %s</li>',
                $field_name,
                $error_messages
            );
        }
        $error_list .= '</ul></div>';
        
        $_SESSION['products_errors'] = $error_list;
        header('Location: ../../product.php');
        exit();
    }

    // 2. Фильтрация данных
    function cleanData($fields) {
        $fields = trim($fields);
        $fields = stripslashes($fields);
        $fields = strip_tags($fields);
        $fields = htmlspecialchars($fields);
        return $fields;
    }

    // Очистка всех полей формы
    foreach ($formData as $key => $value) {
        $formData[$key] = cleanData($value);
    }

    // 3. Проверить есть ли такой продукт
    $name = $formData['name'];
    require_once '../DB.php';
    
    $existingProduct = $DB->query(
        "SELECT id FROM products WHERE name = '$name'"
    )->fetchAll();

    if (!empty($existingProduct)) {
        $_SESSION['products_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
            <h4 style="margin: 0;">Продукт с таким названием уже существует</h4>
        </div>';
        header('Location: ../../product.php');
        exit();
    }
    
    // 4. Записать продукт
    $productId = $existingProduct[0]['id'] ?? null;
    
    if ($productId) {
        $_SESSION['products_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
            <h4 style="margin: 0;">Продукт уже существует в базе данных</h4>
        </div>';
        header('Location: ../../product.php');
        exit();
    }

    // Записать $formData в бд
    $sql = "INSERT INTO products (name, description, price, stock) 
            VALUES (:name, :description, :price, :stock)";
    
    $stmt = $DB->prepare($sql);
    $stmt->execute([
        ':name' => $formData['name'],
        ':description' => $formData['description'],
        ':price' => $formData['price'],
        ':stock' => $formData['stock']
    ]);

    header('Location: ../../product.php');
    exit();
} else {
    echo json_encode([
        'error' => 'Неверный запрос'
    ]);
}

?>