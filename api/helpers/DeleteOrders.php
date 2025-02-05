<?php
session_start();
require_once '../DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    try {
        // Начинаем транзакцию
        $DB->beginTransaction();

        // Проверяем, существует ли заказ
        $checkSql = "SELECT id FROM orders WHERE id = :order_id";
        $checkStmt = $DB->prepare($checkSql);
        $checkStmt->execute([':order_id' => $_POST['order_id']]);
        
        if ($checkStmt->rowCount() > 0) {
            // Обновляем статус в основной таблице заказов
            $orderSql = "UPDATE orders SET status = '0' WHERE id = :order_id";
            $orderStmt = $DB->prepare($orderSql);
            $orderStmt->execute([':order_id' => $_POST['order_id']]);

            // Если все успешно - подтверждаем транзакцию
            $DB->commit();
            
            header('Location: ../../orders.php');
            exit();
        } else {
            $_SESSION['orders_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
                <h4 style="margin: 0;">Заказ не найден</h4>
            </div>';
            header('Location: ../../orders.php');
            exit();
        }
    } catch (PDOException $e) {
        // В случае ошибки откатываем все изменения
        $DB->rollBack();
        
        $_SESSION['orders_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
            <h4 style="margin: 0;">Произошла ошибка при удалении заказа: ' . $e->getMessage() . '</h4>
        </div>';
        header('Location: ../../orders.php');
        exit();
    }
} else {
    $_SESSION['orders_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
        <h4 style="margin: 0;">Некорректный запрос на удаление</h4>
    </div>';
    header('Location: ../../orders.php');
    exit();
}
?> 