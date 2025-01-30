<?php

//Если ID существует и не пустой
if(isset($_GET['id']) && !empty($_GET['id'])){
    $id = $_GET['id'];

    require_once '../DB.php';
    
    try {
        // Начинаем транзакцию
        $DB->beginTransaction();

        // Сначала удаляем связанные записи из order_items
        $stmt = $DB->prepare("DELETE FROM order_items WHERE product_id = ?");
        $stmt->execute([$id]);

        // Затем удаляем сам продукт
        $stmt = $DB->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        // Если всё прошло успешно, фиксируем транзакцию
        $DB->commit();

        header('Location: ../../product.php');
        
    } catch(PDOException $e) {
        // В случае ошибки откатываем все изменения
        $DB->rollBack();
        
        $_SESSION['products_errors'] = '<div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; border-radius: 5px; padding: 15px; margin: 10px 0;">
            <h4 style="margin: 0;">Ошибка при удалении продукта</h4>
        </div>';
        
        header('Location: ../../product.php');
    }
    
}else{
    header('Location: ../../product.php');
}

?>