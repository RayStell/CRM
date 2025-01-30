<?php

function OutputClients($Clients){
    //clients - массив с данными клиентов
    //вывести каждое значение массива в виде элемента 
    //таблицы (tr) через echo
    foreach($Clients as $Client){
        echo "<tr>";
        foreach($Client as $key => $value){
            echo "<td>$value</td>";
        }
        // Добавляем кнопки действий
        echo "<td onclick=\"MicroModal.show('history-modal')\"><i class=\"fa fa-history\"></i></td>";
        echo "<td onclick=\"MicroModal.show('edit-modal')\"><i class=\"fa fa-pencil\"></i></td>";
        echo "<td><a href='api/clients/DeleteClients.php?id=" . $Client['id'] . "'><i class=\"fa fa-trash\"></i></a></td>";
        echo "</tr>";
    }
}

?>