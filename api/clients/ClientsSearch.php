<?php

function ClientsSearch($params, $DB){
   //Получить данные из базы данных
   $search = isset($params['search']) ? $params['search'] : '';
   $sort = isset($params['sort']) ? $params['sort'] : '0';
   $search_name = isset($params['search_name']) ? $params['search_name'] : 'name';

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
   
   // Выбираем поле для поиска в зависимости от search_name
   $search_field = $search_name === 'email' ? 'email' : 'name';
   
   $clients = $DB->query(
      "SELECT * FROM clients WHERE LOWER($search_field) LIKE '%$search%'" . $order
   )->fetchAll();
   
   //Вывести данные в таблицу
   return $clients;
}

?>