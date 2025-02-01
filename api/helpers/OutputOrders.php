<?php
function OutputOrders($orders) {
    foreach ($orders as $order) {
        $fullname = $order['name'] ?? 'Неизвестно';
        $order_date = $order['order_date'] ?? 'Неизвестно';
        $total_price = $order['total'] ?? '0';
        
        // Разбиваем строки на массивы
        $product_names = explode(', ', $order['product_names']);
        $product_quantities = explode(', ', $order['product_quantities']);
        $product_prices = explode(', ', $order['product_prices']);
        
        $order_items = [];
        for($i = 0; $i < count($product_names); $i++) {
            $item_total = $product_prices[$i] * $product_quantities[$i];
            $order_items[] = $product_names[$i] . " (" . $product_prices[$i] . "₽) : x" . $product_quantities[$i] . " = " . $item_total . "₽";
        }
        $order_items_str = implode('<br>', $order_items);

        echo "<tr>";
        echo "<td>{$order['id']}</td>";
        echo "<td>{$fullname}</td>";
        echo "<td>" . convertDate($order['order_date']) . "</td>";
        echo "<td>{$total_price}₽</td>";
        echo "<td>{$order_items_str}</td>";
        echo "<td onclick=\"MicroModal.show('edit-modal')\"><i class=\"fa fa-pencil\"></i></td>";
        echo "<td><a href='api/orders/DeleteOrder.php?id=" . $order['id'] . "'><i class=\"fa fa-trash\"></i></a></td>";
        echo "<td onclick=\"MicroModal.show('check-modal')\"><i class=\"fa fa-file-text-o\"></i></td>";
        echo "<td onclick=\"MicroModal.show('details-modal')\"><i class=\"fa fa-info-circle\"></i></td>";
        echo "</tr>";
    }
}
?>