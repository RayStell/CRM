<?php session_start(); 
 
if (isset($_GET['do']) && $_GET['do'] === 'logout'){ 
    require_once 'api/auth/LogoutUser.php'; 
    require_once 'api/DB.php'; 
 
    LogoutUser('login.php',$DB, $_SESSION['token']);
    exit(); 
}  
 
require_once 'api/auth/AuthCheck.php'; 
AuthCheck('', 'login.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/settings.css">
    <link rel="stylesheet" href="styles/pages/orders.css">
    <link rel="stylesheet" href="styles/modules/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/modules/MicroModul.css">
    <title>CRM | Заказы</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <p> 
                <?php 
                 require 'api/DB.php'; 
                 require_once 'api/clients/AdminName.php';  
                  
                 echo AdminName($_SESSION['token'], $DB); 
                ?> 
            </p>
            <ul class="header__links">
                <li><a href="clients.php">Клиенты</a></li>
                <li><a href="product.php">Товары</a></li>
                <li><a href="orders.php">Заказы</a></li>
            </ul>
            <a href="?do=logout" class="header__logout">Выйти</a>
        </div>
    </header>
    <main class="main">
        <section class="main__filters">
            <div class="container">
                <form action="" class="main__form">
                    <label class="main__label" for="search">Поиск</label>
                    <input class="main__input" type="text" id="search" name="search" placeholder="Поиск...">
                    <select class="main__select" name="filter" id="filter">
                        <option value="client">Клиент</option>
                        <option value="id">ИД</option>
                        <option value="date">Дата</option>
                        <option value="price">По сумме</option>
                    </select>
                    <select class="main__select" name="sort" id="sort">
                        <option value="0">По умолчанию</option>
                        <option value="1">По возрастанию</option>
                        <option value="2">По убыванию</option>
                    </select>
                    <button class="main__button" type="submit">Поиск</button>
                    <a href="?" class="main__button main__button--reset">Сбросить</a>
                </form>
            </div>
        </section>
        <section class="main__clients">
            <div class="container">
                <h2 class="main__clients__title">Список заказов</h2>
                <button class="main__clients__add" onclick="MicroModal.show('add-modal')"><i class="fa fa-plus-circle"></i></button>
                <table>
                    <thead>
                        <th>ИД</th>
                        <th>ФИО клиента</th>
                        <th>Дата заказа</th>
                        <th>Цена</th>
                        <th>Элементы заказа</th>
                        <th>Редактировать</th>
                        <th>Удалить</th>
                        <th>Чек</th>
                        <th>Подробнее</th>
                    </thead>
                    <tbody>
                        <?php
                        require 'api/DB.php';
                        require_once 'api/helpers/convertDate.php';
                        require_once 'api/helpers/OutputOrders.php';
                        require_once 'api/helpers/OrdersSearch.php';

                        $orders = OrdersSearch($_GET, $DB);
                        OutputOrders($orders);
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Модальные окна -->
    <div class="modal micromodal-slide" id="add-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">Добавить заказ</h2>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <form class="modal__form">
                    <!-- Форма добавления заказа -->
                </form>
            </main>
          </div>
        </div>
    </div>

    <div class="modal micromodal-slide" id="delete-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
          <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
            <header class="modal__header">
              <h2 class="modal__title" id="modal-1-title">Вы уверены, что хотите удалить заказ?</h2>
              <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            </header>
            <main class="modal__content" id="modal-1-content">
                <button>Удалить</button>
                <button onclick="MicroModal.close('delete-modal')">Отменить</button>
            </main>
          </div>
        </div>
    </div>

    <div class="modal micromodal-slide" id="details-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">Подробная информация</h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <!-- Подробная информация о заказе -->
                </main>
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script defer src="scripts/initOrdersModal.js"></script>
</body>
</html>
