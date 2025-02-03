<?php

session_start();

if (isset($_GET['do']) && $_GET['do'] === 'logout'){ 
    require_once 'api/auth/LogoutUser.php'; 
    require_once 'api/DB.php'; 
 
    LogoutUser('login.php',$DB, $_SESSION['token']);
    exit(); 
}  
 
require_once 'api/auth/AuthCheck.php'; 
AuthCheck('', 'login.php');

// 1. Фильтрация / сортировка 
// 2. Вывод продуктов 
// 3. добавление 
// 4. удаление


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/settings.css">
    <link rel="stylesheet" href="styles/pages/products.css">
    <link rel="stylesheet" href="styles/modules/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/modules/MicroModul.css">
    <title>CRM | Товары</title>
</head>
<body>
    <header class="header">
        <div class="container">
            <p class="header__admin"> <?php 
                 require 'api/DB.php'; 
                 require_once 'api/clients/AdminName.php';  
                  
                 echo AdminName($_SESSION['token'], $DB); 
                ?></p>
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
                    <label class="main__label" for="search">Поиск по названию</label>
                    <input class="main__input" type="text" id="search" name="search" placeholder="Товар">
                    <select class="main__select1" name="sort1" id="sort1">
                        <option value="0">Название</option>
                        <option value="1">Цена</option>
                        <option value="2">Количество</option>
                    </select>
                    <select class="main__select" name="sort" id="sort">
                        <option value="0">По возрастанию цены</option>
                        <option value="1">По убыванию цены</option>
                    </select>
                    <button class="main__button" type="submit">Поиск</button>
                    <a href="?" class="main__button main__button--reset">Сбросить</a>
                </form>
            </div>
        </section>
        <section class="main__products">
            <div class="container">
                <h2 class="main__products__title">Список товаров</h2>
                <button class="main__products__add" onclick="MicroModal.show('add-modal')"><i class="fa fa-plus-circle"></i></button>
                <table>
                    <thead>
                        <th>ИД</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>QR код</th>
                        <th>Редактировать</th>
                        <th>Удалить</th>
                    </thead>
                    <tbody>
                        <tr>
                        <?php
                        require 'api/DB.php';
                        require_once 'api/product/OutputProducts.php';
                        require_once 'api/product/ProductSearch.php';
                        $Products = ProductSearch($_GET, $DB);
                        OutputProducts($Products);
                        ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Модальное окно добавления товара -->
    <div class="modal micromodal-slide" id="add-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        Добавить товар
                    </h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <form action="api/product/AddProduct.php" method="POST" class="modal__form">
                        <div class="modal__form-group">
                            <label for="name">Название</label>
                            <input type="text" id="name" name="name" >
                        </div>
                        <div class="modal__form-group">
                            <label for="description">Описание</label>
                            <textarea id="description" name="description" ></textarea>
                        </div>
                        <div class="modal__form-group">
                            <label for="price">Цена</label>
                            <input type="number" step="0.01" id="price" name="price" >
                        </div>
                        <div class="modal__form-group">
                            <label for="stock">Количество</label>
                            <input type="number" id="stock" name="stock" >
                        </div>
                        <div class="modal__form-actions">
                            <button type="submit" class="modal__btn modal__btn-primary">Создать</button>
                            <button type="button" class="modal__btn modal__btn-secondary" data-micromodal-close>Отменить</button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>

    <!-- Модальное окно редактирования товара -->
    <div class="modal micromodal-slide" id="edit-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        Редактировать товар
                    </h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <form class="modal__form">
                        <div class="modal__form-group">
                            <label for="edit-name">Название</label>
                            <input type="text" id="edit-name" name="name" >
                        </div>
                        <div class="modal__form-group">
                            <label for="edit-description">Описание</label>
                            <textarea id="edit-description" name="description" ></textarea>
                        </div>
                        <div class="modal__form-group">
                            <label for="edit-price">Цена</label>
                            <input type="number" id="edit-price" name="price" >
                        </div>
                        <div class="modal__form-group">
                            <label for="edit-quantity">Количество</label>
                            <input type="number" id="edit-quantity" name="quantity" >
                        </div>
                        <div class="modal__form-actions">
                            <button type="submit" class="modal__btn modal__btn-primary">Сохранить</button>
                            <button type="button" class="modal__btn modal__btn-secondary" data-micromodal-close>Отменить</button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>

    <!-- Модальное окно удаления товара -->
    <div class="modal micromodal-slide" id="delete-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        Вы уверены, что хотите удалить товар?
                    </h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <button class="modal__btn modal__btn-primary">Удалить</button>
                    <button class="modal__btn modal__btn-secondary" onclick="MicroModal.close('delete-modal')">Отменить</button>
                </main>
            </div>
        </div>
    </div>

    <!-- Модальное окно QR кода -->
    <div class="modal micromodal-slide" id="qr-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        QR код товара
                    </h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <div class="qr-code-container">
                        <!-- Здесь будет QR код -->
                        <img src="path/to/qr-code.png" alt="QR код товара">
                    </div>
                    <button class="modal__btn modal__btn-primary">Скачать</button>
                </main>
            </div>
        </div>
    </div>

    <!-- Модальное окно с ошибками -->
    <div class="modal micromodal-slide <?php 
        if (isset($_SESSION['product_errors']) && !empty($_SESSION['product_errors'])) {
            echo 'open';
        }
    ?>" id="error-modal" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        Ошибка
                    </h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <?php 
                        if (isset($_SESSION['product_errors']) && !empty($_SESSION['product_errors'])) {
                            echo $_SESSION['product_errors'];
                            $_SESSION['product_errors'] = '';
                        }
                    ?>
                </main>
            </div>
        </div>
    </div>

    <script defer src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script defer src="scripts/initProductsModal.js"></script>
</body>
</html> 