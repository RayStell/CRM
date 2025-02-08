<?php

function InputDefaultValue($name, $default = '') {
    if (isset($_GET[$name])) {
        echo 'value="' . htmlspecialchars($_GET[$name]) . '"';
    } else {
        echo 'value="' . htmlspecialchars($default) . '"';
    }
}

?>