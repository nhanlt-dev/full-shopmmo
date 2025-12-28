<?php
session_start();
header("Content-Type: application/json");
require_once('../database/db.php');

$titlePages = [];

$resultNamePage = mysqli_query($link, "SELECT id, pageName, pageUrl FROM pages");

if ($resultNamePage) {
    while ($rowNamePage = mysqli_fetch_object($resultNamePage)) {
        $titlePages[] = [
            'pageName' => $rowNamePage->pageName,
            'pageUrl'  => $rowNamePage->pageUrl
        ];
    }
}
echo json_encode($titlePages);
