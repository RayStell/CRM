<?php

function OutputProducts($Products){
    //clients - массив с данными клиентов
    //вывести каждое значение массива в виде элемента 
    //таблицы (tr) через echo
    foreach($Products as $Product){
        echo "<tr>";
        foreach($Product as $key => $value){
            echo "<td>$value</td>";
        }
        // Добавляем кнопки действий
        echo "<td onclick=\"MicroModal.show('qr-modal')\"><i class=\"fa fa-qrcode\"></i></td>";
        echo "<td onclick=\"MicroModal.show('edit-modal')\"><i class=\"fa fa-pencil\"></i></td>";
        echo "<td><a href='api/product/DeleteProduct.php?id=" . $Product['id'] . "'><i class=\"fa fa-trash\"></i></a></td>";
        echo "</tr>";
    }
}

?>