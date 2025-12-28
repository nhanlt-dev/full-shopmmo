<?php
$link = @mysqli_connect('localhost', 'root', '', 'quangbathuonghie_data');

if (!$link) {
    die('Not connected: ' . mysqli_connect_error());
}

// make 'shoeshop' the current db
$db_selected = @mysqli_select_db($link, 'quangbathuonghie_data');

if (!$db_selected) {
    die('Can\'t use quangbathuonghie_data: ' . mysqli_error($link));
}

@mysqli_query($link, 'SET NAMES "UTF8"');
