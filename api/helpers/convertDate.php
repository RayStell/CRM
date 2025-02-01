<?php
function convertDate($date) {
    $timestamp = strtotime($date);
    return date('H:i | d.m.y', $timestamp);
}
?> 