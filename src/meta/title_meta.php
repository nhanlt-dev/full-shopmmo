<?php
antiVandal();
$thamso = isset($_GET['thamso']) ? $_GET['thamso'] : 'default';

$selectSystem = "SELECT * FROM site ORDER BY id LIMIT 1";
$resultSystem = mysqli_query($link, $selectSystem);

if ($resultSystem && mysqli_num_rows($resultSystem) > 0) {
    $systemRow = mysqli_fetch_array($resultSystem);
    $titleSystem        = $systemRow['titleSystem'];
    $titleMeta          = $titleSystem;
    $descriptionMeta    = $systemRow['descriptionSystem'];
    $keywordMeta        = $systemRow['keywordSystem'];
    $imageLogoPage      = $systemRow['imageLogoPage'];
    $imageMeta          = "src/docs/images/imageSystems/$imageLogoPage";
}

switch ($thamso) {
    case "contactDetails":
        $titleMeta      = "Liên Hệ Với $titleSystem";
        break;

    case "aboutUs":
        $titleMeta      = "Giới Thiệu Về $titleSystem";
        break;
        
    case "searchPages":
        $query          = $_GET['query'];
        $titleMeta      = "Tìm Kiếm Với Từ Khoá &ldquo; htmlspecialchars($query) &rdquo;";
        break;
        
    case "news":
        $titleMeta      = "Tin Tức Về $titleSystem";
        break;
        
    case "newsDetails":
        $urlNewsDetail    = $_GET['url'];
        $selectNewsDetail = "SELECT * FROM news Where newsUrl = '$urlNewsDetail'";
        $resultNewsDetail = mysqli_query($link, $selectNewsDetail);
        
        if ($resultNewsDetail && mysqli_num_rows($resultNewsDetail) > 0) {
            $newsRow            = mysqli_fetch_array($resultNewsDetail);
            $titleMeta          = $newsRow['newsTitle'];
            $descriptionMeta    = $newsRow['newsDescription'];
            $keywordMeta        = $newsRow['newsKeyword1'] . " " . $newsRow['newsKeyword1'];
            $newsImage          = $newsRow['newsImage'];
            $imageMeta          = "src/docs/images/imageNews/$newsImage";
        }
        break;

    case "pages":
        $titleMeta      = "Các Thương Hiệu Hàng Đầu Của $titleSystem";
        break;
    
    case "pageTypes":
        $urlPageType      = $_GET['url'];
        $selectPageType = "SELECT * FROM categorypages Where urlCategoryPage = '$urlPageType'";
        $resultPageType = mysqli_query($link, $selectPageType);
        
        if ($resultPageType && mysqli_num_rows($resultPageType) > 0) {
            $pageTypeRow        = mysqli_fetch_array($resultPageType);
            $categoryPage       = $pageTypeRow['categoryPage'];
            $titleMeta          = "Danh Mục $categoryPage";
        }
        break;

    default:
        break;
}

$config_url = $_SERVER["SERVER_NAME"];
?>