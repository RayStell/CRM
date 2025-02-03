<?php session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;
    $errors = [];
    $_SESSION['orders_errors'] = '';

    // 1. Проверка данных
    if(!isset($formData['client_id']) || empty($formData['client_id'])) {
        $errors[] = 'Не выбран клиент';
    }

    if(!isset($formData['product_id']) || empty($formData['product_id'])) {
        $errors[] = 'Не выбраны товары';
    }

    if(!empty($errors)) {
        $error_list = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">';
        $error_list .= '<h4 style="margin: 0 0 10px 0; color: #842029;">Пожалуйста, исправьте следующие ошибки:</h4>';
        $error_list .= '<ul style="margin: 0; padding-left: 20px;">';
        foreach ($errors as $error) {
            $error_list .= sprintf('<li style="margin-bottom: 5px;">%s</li>', $error);
        }
        $error_list .= '</ul></div>';
        
        $_SESSION['orders_errors'] = $error_list;
        header('Location: ../../orders.php');
        exit();
    }

    require_once '../DB.php';

    try {
        // Начинаем транзакцию
        $DB->beginTransaction();

        // 1. Создаем заказ
        $sql = "INSERT INTO orders (client_id, order_date, status) VALUES (:client_id, NOW(), 'new')";
        $stmt = $DB->prepare($sql);
        $stmt->execute([
            ':client_id' => $formData['client_id']
        ]);
        
        // Получаем ID созданного заказа
        $order_id = $DB->lastInsertId();

        // 2. Добавляем товары к заказу
        $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
        $stmt = $DB->prepare($sql);

        foreach($formData['product_id'] as $product_id) {
            // Проверяем, есть ли количество для данного товара
            $quantity = isset($formData['quantity'][$product_id]) ? $formData['quantity'][$product_id] : 1;
            
            $stmt->execute([
                ':order_id' => $order_id,
                ':product_id' => $product_id,
                ':quantity' => $quantity
            ]);
        }

        // Если всё прошло успешно, фиксируем транзакцию
        $DB->commit();

        header('Location: ../../orders.php');
        exit();

    } catch (Exception $e) {
        // В случае ошибки откатываем все изменения
        $DB->rollBack();
        
        $_SESSION['orders_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
            <h4 style="margin: 0;">Произошла ошибка при создании заказа</h4>
        </div>';
        
        header('Location: ../../orders.php');
        exit();
    }

} else {
    echo json_encode([
        'error' => 'Неверный метод запроса'
    ]);
}
?>