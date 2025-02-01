<?php

function ClientsSearch($params, $DB){
   //Получить данные из базы данных
   $search = isset($params['search']) ? $params['search'] : '';
   $sort = isset($params['sort']) ? $params['sort'] : '0';

   //Добавить сортировку (order by)
   // 0 - ордер не добавляется
   // 1 - ордер по возрастанию
   // 2 - ордер по убыванию
   $order = "";
   if($sort == 1){
      $order = " ORDER BY name ASC";
   }elseif($sort == 2){
      $order = " ORDER BY name DESC";
   }

   $search = trim(strtolower($search));

   $clients = $DB->query(
      "SELECT * FROM clients WHERE LOWER(name) LIKE '%$search%'" . $order
   )->fetchAll();
   
   //Вывести данные в таблицу
   return $clients;
}

function OrdersSearch($params, $DB) {
    $search = isset($params['search']) ? trim($params['search']) : '';
    $sort = isset($params['sort']) ? $params['sort'] : '0';
    $filter = isset($params['filter']) ? $params['filter'] : 'client';

    $query = "
        SELECT
            orders.id,
            clients.name,
            orders.order_date,
            SUM(products.price * order_items.quantity) as total,
            orders.status,
            GROUP_CONCAT(products.name SEPARATOR ', ') AS product_names,
            GROUP_CONCAT(order_items.quantity SEPARATOR ', ') AS product_quantities,
            GROUP_CONCAT(products.price SEPARATOR ', ') AS product_prices
        FROM
            orders
        JOIN
            clients ON orders.client_id = clients.id
        JOIN
            order_items ON orders.id = order_items.order_id
        JOIN
            products ON order_items.product_id = products.id
    ";

    // Добавляем WHERE условие для поиска
    if (!empty($search)) {
        switch ($filter) {
            case 'client':
                $query .= " WHERE LOWER(clients.name) LIKE LOWER('%" . $search . "%')";
                break;
            case 'id':
                $query .= " WHERE orders.id = '" . $search . "'";
                break;
            case 'date':
                $query .= " WHERE DATE(orders.order_date) = '" . $search . "'";
                break;
        }
    }

    $query .= " GROUP BY orders.id, clients.name, orders.order_date, orders.status";

    // Добавляем HAVING для поиска по цене после GROUP BY
    if (!empty($search) && $filter === 'price') {
        $query .= " HAVING total = '" . $search . "'";
    }

    // Добавляем сортировку
    if ($sort != '0') {
        $orderDirection = ($sort == '1') ? 'ASC' : 'DESC';
        switch ($filter) {
            case 'client':
                $query .= " ORDER BY clients.name " . $orderDirection;
                break;
            case 'id':
                $query .= " ORDER BY orders.id " . $orderDirection . " ";
                break;
            case 'date':
                $query .= " ORDER BY orders.order_date " . $orderDirection . " ";
                break;
            case 'price':
                $query .= " ORDER BY total " . $orderDirection . " ";
                break;
            default:
                $query .= " ORDER BY orders.id " . $orderDirection . " ";
        }
    }

    // Для отладки
    // echo "<!-- SQL Query: " . $query . " -->";

    return $DB->query($query)->fetchAll();
}

?>