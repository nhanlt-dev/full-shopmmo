<?php
$stmtInfo = $link->prepare("SELECT * FROM site Where id = 1");
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();

if ($infoRow = $resultInfo->fetch_assoc()) {
    $titleSystem        = $infoRow['titleSystem'];
    $descriptionSystem  = $infoRow['descriptionSystem'];
    $imageLogoPage      = $infoRow['imageLogoPage'];
    $keywordSystem      = $infoRow['keywordSystem'];
    $phoneNumberSystem  = $infoRow['phoneNumberSystem'];
    $emailSystem        = $infoRow['emailSystem'];
    $customerSystem     = $infoRow['customerSystem'];
    $addressSystem      = $infoRow['addressSystem'];
    $companyName        = $infoRow['companyName'];
}
