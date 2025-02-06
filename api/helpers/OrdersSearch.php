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

function OrdersSearch($get, $DB) {
    $sql = "SELECT 
                orders.*,
                clients.name,
                GROUP_CONCAT(products.name) as product_names,
                GROUP_CONCAT(order_items.quantity) as product_quantities,
                GROUP_CONCAT(products.price) as product_prices
            FROM orders 
            LEFT JOIN clients ON orders.client_id = clients.id 
            LEFT JOIN order_items ON orders.id = order_items.order_id
            LEFT JOIN products ON order_items.product_id = products.id
            WHERE 1=1";
    $params = [];

    // Фильтр по статусу (по умолчанию показываем только активные)
    if (!isset($get['show_inactive'])) {
        $sql .= " AND orders.status = '1'";
    }

    // Поиск
    if (isset($get['search']) && !empty($get['search'])) {
        $searchField = isset($get['search_name']) ? $get['search_name'] : 'clients.name';
        $sql .= " AND {$searchField} LIKE :search";
        $params[':search'] = '%' . $get['search'] . '%';
    }

    // Группировка
    $sql .= " GROUP BY orders.id";

    // Сортировка
    if (isset($get['sort'])) {
        $sortField = isset($get['search_name']) ? $get['search_name'] : 'orders.id';
        // Заменяем client.name на clients.name если необходимо
        $sortField = str_replace('client.name', 'clients.name', $sortField);
        
        switch ($get['sort']) {
            case '1': // По возрастанию
                $sql .= " ORDER BY {$sortField} ASC";
                break;
            case '2': // По убыванию
                $sql .= " ORDER BY {$sortField} DESC";
                break;
            default:
                $sql .= " ORDER BY orders.id DESC";
        }
    } else {
        $sql .= " ORDER BY orders.id DESC";
    }

    $stmt = $DB->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

?>