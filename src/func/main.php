<?php
global $link;
function antiVandal() {}

$baseDir = __DIR__ . "/../../src/docs/images/";
$subDirs = [
    'imageNews'         => $baseDir . 'imageNews/',
    'imagePages'        => $baseDir . 'imagePages/',
    'imageUsers'        => $baseDir . 'imageUsers/',
    'imageBlogs'        => $baseDir . 'imageBlogs/',
    'imageSlides'       => $baseDir . 'imageSlides/',
    'imageSystems'      => $baseDir . 'imageSystems/',
    'imageBanners'      => $baseDir . 'imageBanners/',
    'imageReviews'      => $baseDir . 'imageReviews/',
    'imagePartners'     => $baseDir . 'imagePartners/',
    'imageProducts'     => $baseDir . 'imageProducts/',
    'imageIntroduces'   => $baseDir . 'imageIntroduces/',
    'imageTestimonials' => $baseDir . 'imageTestimonials/',
];
foreach ($subDirs as $key => $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$targetNewsDir          = $subDirs['imageNews'];
$targetPagesDir         = $subDirs['imagePages'];
$targetUsersDir         = $subDirs['imageUsers'];
$targetBlogsDir         = $subDirs['imageBlogs'];
$targetSlidesDir        = $subDirs['imageSlides'];
$targetSystemsDir       = $subDirs['imageSystems'];
$targetBannerDir        = $subDirs['imageBanners'];
$targetReviewsDir       = $subDirs['imageReviews'];
$targetPartnersDir      = $subDirs['imagePartners'];
$targetProductsDir      = $subDirs['imageProducts'];
$targetIntroduceDir     = $subDirs['imageIntroduces'];
$targetTestimonialsDir  = $subDirs['imageTestimonials'];

function browserDetection(){
    $browserInformation = $_SERVER['HTTP_USER_AGENT'];
    $browserName = "otherBrowser";

    if (preg_match("/Firefox/i", $browserInformation)) {
        $browserName = "firefox";
    } elseif (preg_match("/MSIE 8\.0/i", $browserInformation)) {
        $browserName = "ie8";
    } elseif (preg_match("/MSIE 7\.0/i", $browserInformation)) {
        $browserName = "ie7";
    } elseif (preg_match("/MSIE 6\.0/i", $browserInformation)) {
        $browserName = "ie6";
    } elseif (preg_match("/Chrome/i", $browserInformation)) {
        $browserName = "google";
    } elseif (preg_match("/Safari/i", $browserInformation) && !preg_match("/Chrome/i", $browserInformation)) {
        $browserName = "safari";
    } elseif (preg_match("/Opera/i", $browserInformation)) {
        $browserName = "opera";
    }
    return $browserName;
}
function replaceFckeditor($nd){
    $replace_map = [
        '&eacute;' => 'é',
        '&egrave;' => 'è',
        '&yacute;' => 'ý',
        '&uacute;' => 'ú',
        '&ugrave;' => 'ù',
        '&iacute;' => 'í',
        '&igrave;' => 'ì',
        '&oacute;' => 'ó',
        '&ograve;' => 'ò',
        '&ocirc;'  => 'ô',
        '&aacute;' => 'á',
        '&agrave;' => 'à',
        '&atilde;' => 'ã',
        '&acirc;'  => 'â',
        '&ecirc;'  => 'ê',
        '&Eacute;' => 'É',
        '&Egrave;' => 'È',
        '&Yacute;' => 'Ý',
        '&Uacute;' => 'Ú',
        '&Ugrave;' => 'Ù',
        '&Iacute;' => 'Í',
        '&Igrave;' => 'Ì',
        '&Oacute;' => 'Ó',
        '&Ograve;' => 'Ò',
        '&Ocirc;'  => 'Ô',
        '&Aacute;' => 'Á',
        '&Agrave;' => 'À',
        '&Atilde;' => 'Ã',
        '&Acirc;'  => 'Â',
        '&Ecirc;'  => 'Ê'
    ];
    return strtr($nd, $replace_map);
}
function getSubstringUnicode($str, $firstCharacter, $lastCharacter){
    $str = strip_tags($str);
    $str = replaceFckeditor($str);
    if ($firstCharacter < 0 || $lastCharacter <= $firstCharacter || $lastCharacter > mb_strlen($str, 'UTF-8')) {
        return '';
    }
    return mb_substr($str, $firstCharacter, $lastCharacter - $firstCharacter, 'UTF-8');
}
function noAccent($str){
    $map = [
        'à' => 'a',
        'á' => 'a',
        'ạ' => 'a',
        'ả' => 'a',
        'ã' => 'a',
        'â' => 'a',
        'ầ' => 'a',
        'ấ' => 'a',
        'ậ' => 'a',
        'ẩ' => 'a',
        'ẫ' => 'a',
        'ă' => 'a',
        'ằ' => 'a',
        'ắ' => 'a',
        'ặ' => 'a',
        'ẳ' => 'a',
        'ẵ' => 'a',
        'è' => 'e',
        'é' => 'e',
        'ẹ' => 'e',
        'ẻ' => 'e',
        'ẽ' => 'e',
        'ê' => 'e',
        'ề' => 'e',
        'ế' => 'e',
        'ệ' => 'e',
        'ể' => 'e',
        'ễ' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'ị' => 'i',
        'ỉ' => 'i',
        'ĩ' => 'i',
        'ò' => 'o',
        'ó' => 'o',
        'ọ' => 'o',
        'ỏ' => 'o',
        'õ' => 'o',
        'ô' => 'o',
        'ồ' => 'o',
        'ố' => 'o',
        'ộ' => 'o',
        'ổ' => 'o',
        'ỗ' => 'o',
        'ơ' => 'o',
        'ờ' => 'o',
        'ớ' => 'o',
        'ợ' => 'o',
        'ở' => 'o',
        'ỡ' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'ụ' => 'u',
        'ủ' => 'u',
        'ũ' => 'u',
        'ư' => 'u',
        'ừ' => 'u',
        'ứ' => 'u',
        'ự' => 'u',
        'ử' => 'u',
        'ữ' => 'u',
        'ỳ' => 'y',
        'ý' => 'y',
        'ỵ' => 'y',
        'ỷ' => 'y',
        'ỹ' => 'y',
        'đ' => 'd',
        'À' => 'A',
        'Á' => 'A',
        'Ạ' => 'A',
        'Ả' => 'A',
        'Ã' => 'A',
        'Â' => 'A',
        'Ầ' => 'A',
        'Ấ' => 'A',
        'Ậ' => 'A',
        'Ẩ' => 'A',
        'Ẫ' => 'A',
        'Ă' => 'A',
        'Ằ' => 'A',
        'Ắ' => 'A',
        'Ặ' => 'A',
        'Ẳ' => 'A',
        'Ẵ' => 'A',
        'È' => 'E',
        'É' => 'E',
        'Ẹ' => 'E',
        'Ẻ' => 'E',
        'Ẽ' => 'E',
        'Ê' => 'E',
        'Ề' => 'E',
        'Ế' => 'E',
        'Ệ' => 'E',
        'Ể' => 'E',
        'Ễ' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Ị' => 'I',
        'Ỉ' => 'I',
        'Ĩ' => 'I',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ọ' => 'O',
        'Ỏ' => 'O',
        'Õ' => 'O',
        'Ô' => 'O',
        'Ồ' => 'O',
        'Ố' => 'O',
        'Ộ' => 'O',
        'Ổ' => 'O',
        'Ỗ' => 'O',
        'Ơ' => 'O',
        'Ờ' => 'O',
        'Ớ' => 'O',
        'Ợ' => 'O',
        'Ở' => 'O',
        'Ỡ' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Ụ' => 'U',
        'Ủ' => 'U',
        'Ũ' => 'U',
        'Ư' => 'U',
        'Ừ' => 'U',
        'Ứ' => 'U',
        'Ự' => 'U',
        'Ử' => 'U',
        'Ữ' => 'U',
        'Ỳ' => 'Y',
        'Ý' => 'Y',
        'Ỵ' => 'Y',
        'Ỷ' => 'Y',
        'Ỹ' => 'Y',
        'Đ' => 'D'
    ];

    $str = strtr($str, $map);

    $str = str_replace(" ", "-", $str);

    $str = preg_replace('/[^A-Za-z0-9-]/', '', $str);

    return $str;
}
function trans($str){
    $trans = array(
        "As" => "Á",
        "Ax" => "Ã",
        "Aj" => "Ạ",
        "Af" => "À",
        "Ar" => "Ả",
        "Es" => "É",
        "Ex" => "Ẽ",
        "Ej" => "Ẹ",
        "Ef" => "È",
        "Er" => "Ẻ",
        "Ys" => "Ý",
        "Yx" => "Ỹ",
        "Yj" => "Ỵ",
        "Yf" => "Ỳ",
        "Yr" => "Ỷ",
        "Us" => "Ú",
        "Ux" => "Ũ",
        "Uj" => "Ụ",
        "Uf" => "Ù",
        "Ur" => "Ủ",
        "Os" => "Ó",
        "Ox" => "Õ",
        "Oj" => "Ọ",
        "Of" => "Ò",
        "Or" => "Ỏ",
        "Is" => "Í",
        "Ix" => "Ĩ",
        "Ij" => "Ị",
        "If" => "Ì",
        "Ir" => "Ỉ",

        "Aas" => "Ấ",
        "Aax" => "Ẫ",
        "Aaj" => "Ậ",
        "Aaf" => "Ầ",
        "Aar" => "Ẩ",
        "Ees" => "Ế",
        "Eex" => "Ễ",
        "Eej" => "Ệ",
        "Eef" => "Ề",
        "Eer" => "Ể",
        "Oos" => "Ố",
        "Oox" => "Ỗ",
        "Ooj" => "Ộ",
        "Oof" => "Ồ",
        "Oor" => "Ổ",

        "aw" => "ă",
        "aa" => "â",
        "oo" => "ô",
        "ee" => "ê",
        "uw" => "ư",
        "ow" => "ơ",
        "dd" => "đ"
    );
    return strtr($str, $trans);
}
function characterConversion($txt){
    $replace_pairs = array(
        "&rsquo;" => "'",
        "&sbquo;" => ",",
        "&acute;" => "´",
        "&quot;" => '"',
        "&amp;" => '&',
        "&lt;" => '<',
        "&gt;" => '>',
        "&euro;" => '€',
        "&brvbar;" => '¦',
        "&cedil;" => '¸',
        "&laquo;" => '«',
        "&raquo;" => '»',
        "&reg;" => '®',
        "&tilde;" => '˜',
        "&lsquo;" => '‘',
        "&rsquo;" => '’',
        "&ldquo;" => '“',
        "&rdquo;" => '”',
        "&bdquo;" => '„',
        "&frasl;" => '⁄',
    );
    foreach ($replace_pairs as $search => $replace) {
        $txt = str_replace($search, $replace, $txt);
    }
    return $txt;
}
function logHistory($link, $idUser, $action, $details){
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $link->prepare("INSERT INTO histories (idUser, action, details, ipAddress, userAgent) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $idUser, $action, $details, $ipAddress, $userAgent);
    $stmt->execute();
}
function validateCsrfToken($tokenKeyword){
    if (empty($tokenKeyword) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $tokenKeyword)) {
        showErrorAlert('Error', 'CSRF Token không hợp lệ!');
        die();
    }
    unset($_SESSION['csrf_token']);
}
function handleError($message){
    error_log($message);
    showErrorAlert('Error', $message);
    exit();
}
function handleFileUpload($file, $targetDir, $preservedDefaults = []){
    if (!empty($file['name']) && !empty($file['tmp_name'])) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            return $preservedDefaults[0];
        }
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = time() . '_' . uniqid() . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $targetDir . $newFileName)) {
            return $preservedDefaults[0];
        }
        return $newFileName;
    }
    return $preservedDefaults[0];
}
function handleFileUploadAndUnlink($file, $targetDir, $oldFileName, $preservedDefaults = []){
    if (!empty($file['name']) && !empty($file['tmp_name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            return $oldFileName;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = time() . '_' . uniqid() . '.' . $extension;

        if (move_uploaded_file($file['tmp_name'], $targetDir . $newFileName)) {
            if ($oldFileName && !in_array($oldFileName, $preservedDefaults)) {
                $oldFilePath = $targetDir . $oldFileName;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            return $newFileName;
        }
        return $oldFileName;
    }
    return $oldFileName;
}
function executeQuery($link, $query, $params, $isInsert = false){
    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        handleError('Lỗi chuẩn bị truy vấn: ' . mysqli_error($link));
        return false;
    }
    $types = array_map(function ($param) {
        return (is_numeric($param) && !preg_match('/^0\d+$/', $param)) ? 'd' : 's';
    }, $params);
    $typeData = implode('', $types);

    mysqli_stmt_bind_param($stmt, $typeData, ...$params);
    if (!mysqli_stmt_execute($stmt)) {
        handleError('Lỗi thực thi truy vấn: ' . mysqli_stmt_error($stmt));
        return false;
    }
    mysqli_stmt_close($stmt);
    if ($isInsert) {
        return mysqli_insert_id($link);
    }
    return true;
}
function uploadImageAndUpdate($fileInputName, $targetDir, $link, $table, $id, $columnName, $oldImage, $preservedDefaults = []){
    if (!empty($oldImage)) {
        $imagePath = handleFileUploadAndUnlink($_FILES[$fileInputName], $targetDir, $oldImage, $preservedDefaults);
    } else {
        $imagePath = handleFileUpload($_FILES[$fileInputName],  $targetDir, $preservedDefaults);
    }
    $updateImageQuery = "UPDATE $table SET $columnName = ? WHERE id = ?";
    executeQuery($link, $updateImageQuery, [$imagePath, $id]);
}
function redirectToNotFound() { ?>
    <script>window.location.href = '/404/';</script>";
    <?php exit();
}

