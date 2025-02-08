<?php

function ProductSearch($params, $DB){
   //Получить данные из базы данных
   $search = isset($params['search']) ? $params['search'] : '';
   $sort = isset($params['sort']) ? $params['sort'] : '0';
   $sort1 = isset($params['sort1']) ? $params['sort1'] : '0';

   //Добавить сортировку (order by)
   $orderBy = "";
   
   // Первая сортировка по полю
   $sortField = "";
   if($sort1 == '0') {
      $sortField = "name";
   } elseif($sort1 == '1') {
      $sortField = "price";
   } elseif($sort1 == '2') {
      $sortField = "stock";
   }

   // Вторая сортировка по направлению
   $sortDirection = "";
   if($sort == '0') {
      $sortDirection = "ASC";
   } elseif($sort == '1') {
      $sortDirection = "DESC";
   }

   // Если выбрано поле сортировки, добавляем ORDER BY
   if($sort1 != '0' || $sort != '0') {
      $orderBy = " ORDER BY $sortField $sortDirection";
   }

   $search = trim(strtolower($search));

   $products = $DB->query(
      "SELECT * FROM products WHERE LOWER(name) LIKE '%$search%'$orderBy"
   )->fetchAll();
   
   //Вывести данные в таблицу
   return $products;
}

?>