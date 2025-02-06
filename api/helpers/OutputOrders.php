<?php
function OutputOrders($orders) {
    foreach ($orders as $order) {
        $fullname = $order['name'] ?? 'Неизвестно';
        $order_date = $order['order_date'] ?? 'Неизвестно';
        $total_price = $order['total'] ?? '0';
        
        // Проверяем наличие данных о продуктах
        $order_items_str = 'Нет товаров';
        
        if (!empty($order['product_names'])) {
            // Разбиваем строки на массивы
            $product_names = explode(',', $order['product_names']);
            $product_quantities = explode(',', $order['product_quantities']);
            $product_prices = explode(',', $order['product_prices']);
            
            $order_items = [];
            for($i = 0; $i < count($product_names); $i++) {
                $item_total = floatval($product_prices[$i]) * intval($product_quantities[$i]);
                $order_items[] = $product_names[$i] . " (" . $product_prices[$i] . "₽) : x" . $product_quantities[$i] . " = " . $item_total . "₽";
            }
            $order_items_str = implode('<br>', $order_items);
        }

        echo "<tr>";
        echo "<td>{$order['id']}</td>";
        echo "<td>{$fullname}</td>";
        echo "<td>" . convertDate($order['order_date']) . "</td>";
        echo "<td>{$total_price}₽</td>";
        echo "<td>{$order_items_str}</td>";
        echo "<td onclick=\"MicroModal.show('edit-modal')\"><i class=\"fa fa-pencil\"></i></td>";
        echo "<td><button onclick='showDeleteModal({$order['id']})' class='delete-btn'><i class='fa fa-trash'></i></button></td>";
        echo "<td onclick=\"MicroModal.show('check-modal')\"><i class=\"fa fa-file-text-o\"></i></td>";
        echo "<td onclick=\"MicroModal.show('details-modal')\"><i class=\"fa fa-info-circle\"></i></td>";
        echo "</tr>";
    }
}
?>