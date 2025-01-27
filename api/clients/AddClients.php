<?php session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $formData = $_POST;
    $fields = ['fullname', 'email', 'phone','birthday'];
    $errors = [];
    $_SESSION['clients_errors'] = '';
    // 1. Проверить пришли ли данные 
    foreach($fields as $field){
        if(isset($formData[$field]) || !empty($_POST[$field])){
            $errors[$field][] = 'field is required';
        }
    }
    if(!empty($errors)){
        $_SESSION['clients_errors'] = json_encode($errors);
        header('Location: /clients.php');
        exit;
    }
    // 2. Фильтрация данных
    // 3. Проверить есть ли такой клиент
    // 4. Записать клиента
} else {
    echo json_encode([
        'error' => 'Неверный запрос'
    ]);
}

?>