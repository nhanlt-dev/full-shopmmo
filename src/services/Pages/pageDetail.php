<?php
session_start();
include_once("../../database/db.php");
include_once("../../func/main.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pageUrl    = mysqli_real_escape_string($link, $_GET['url'] ?? '');

$stmtInfo = $link->prepare("SELECT * FROM site Where id = 1");
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();

if ($infoRow = $resultInfo->fetch_assoc()) {
    $imageLogoPage    = $infoRow['imageLogoPage'];
    $titleSystem      = $infoRow['titleSystem'];
}

$queryInfoPage = "SELECT p.*, u.userName,
   (SELECT GROUP_CONCAT(CONCAT_WS('|', s.id, s.imageSlide) SEPARATOR '#') FROM slides AS s WHERE s.idPage = p.id) AS slides,
        (SELECT GROUP_CONCAT(CONCAT_WS('|', f.id, f.fieldsTitle) SEPARATOR '#') FROM fields AS f WHERE f.idPage = p.id) AS fields,
        (SELECT GROUP_CONCAT(CONCAT_WS('|', pr.id, pr.titleProduct, pr.imageProduct) SEPARATOR '#') FROM products AS pr WHERE pr.idPage = p.id) AS products,
        (SELECT GROUP_CONCAT(CONCAT_WS('|', r.id, r.nameReview, r.jobReview, r.contentReview, r.imageReview, r.ratingReview) SEPARATOR '#') FROM reviews AS r WHERE r.idPage = p.id) AS reviews,
        (SELECT GROUP_CONCAT(CONCAT_WS('|', b.id, b.titleBlog, b.imageBlog, b.descriptionBlog) SEPARATOR '#')  FROM blogs AS b  WHERE b.idPage = p.id) AS blogs
    FROM pages AS p
    INNER JOIN users AS u ON u.id = p.idRepresentativePersion
    WHERE p.pageUrl = ?";

if ($stmt = mysqli_prepare($link, $queryInfoPage)) {
    $bindValue = empty($pageId) ? $pageUrl : $pageId;
    mysqli_stmt_bind_param($stmt, 's', $bindValue);
    mysqli_stmt_execute($stmt);
    $resultInfoPage = mysqli_stmt_get_result($stmt);

    if ($resultInfoPage && mysqli_num_rows($resultInfoPage) > 0) {
        $pageInfoRow = mysqli_fetch_assoc($resultInfoPage);

        $idPage                     = $pageInfoRow["id"];
        $pageImageLogo              = $pageInfoRow["pageImageLogo"];
        $idRepresentativePersion    = $pageInfoRow["idRepresentativePersion"];
        $idCategoryPage             = $pageInfoRow["idCategoryPage"];
        $pageName                   = $pageInfoRow["pageName"];
        $userName                   = $pageInfoRow["userName"];
        $pageBusinessField          = $pageInfoRow["pageBusinessField"];
        $pageHeaderTitle1           = $pageInfoRow["pageHeaderTitle1"];
        $pageHeaderTitle2           = $pageInfoRow["pageHeaderTitle2"];
        $pageDescriptionBanner      = $pageInfoRow["pageDescriptionBanner"];
        $pageDescriptionSEO         = $pageInfoRow["pageDescriptionSEO"];
        $pageImageIntroduce         = $pageInfoRow["pageImageIntroduce"];
        $pageContentIntroduce       = $pageInfoRow["pageContentIntroduce"];
        $pageProvince               = $pageInfoRow["pageProvince"];
        $pageWard                   = $pageInfoRow["pageWard"];
        $pageAddress                = $pageInfoRow["pageAddress"];
        $pageStartDate              = $pageInfoRow["pageStartDate"];
        $pageEndDate                = $pageInfoRow["pageEndDate"];
        $pageStatus                 = $pageInfoRow["pageStatus"];

        $querySocialPage = " SELECT linkZalo, linkFacebook, linkYoutube, linkTiktok FROM socialmedia WHERE idPage = ?";
        if ($stmtSocial = mysqli_prepare($link, $querySocialPage)) {
            mysqli_stmt_bind_param($stmtSocial, 'i', $idPage);
            mysqli_stmt_execute($stmtSocial);
            $resultSocialPage = mysqli_stmt_get_result($stmtSocial);

            if ($resultSocialPage && mysqli_num_rows($resultSocialPage) > 0) {
                $pageSocialRow  = mysqli_fetch_assoc($resultSocialPage);
                $linkZalo       = $pageSocialRow["linkZalo"]     ?: '';
                $linkFacebook   = $pageSocialRow["linkFacebook"] ?: '';
                $linkYoutube    = $pageSocialRow["linkYoutube"]  ?: '';
                $linkTiktok     = $pageSocialRow["linkTiktok"]   ?: '';
            } else {
                $linkZalo = $linkFacebook = $linkYoutube = $linkTiktok = '';
            }
        }
        $queryUserInfo = " SELECT * FROM users WHERE id = ?";
        if ($stmtUser = mysqli_prepare($link, $queryUserInfo)) {
            mysqli_stmt_bind_param($stmtUser, 'i', $idRepresentativePersion);
            mysqli_stmt_execute($stmtUser);
            $resultUserPage = mysqli_stmt_get_result($stmtUser);

            if ($resultUserPage && mysqli_num_rows($resultUserPage) > 0) {
                $pageUserRow  = mysqli_fetch_assoc($resultUserPage);
                $userNumberPhone    = $pageUserRow["userNumberPhone"]   ?: '';
                $userEmail          = $pageUserRow["userEmail"]   ?: '';
            }
        }

        $slideData   = explode('#', $pageInfoRow['slides']);
        $idSlides = $imageSlides = [];
        foreach ($slideData as $slide) {
            $slideparts           = explode('|', $slide);
            if ($slideparts[0]    !== null) {
                $idSlides[]       = $slideparts[0] ?: null;
                $imageSlides[]    = $slideparts[1] ?: '';
            }
        }
        $fieldData = explode('#', $pageInfoRow['fields']);
        $idFields = $fieldTitles = [];
        foreach ($fieldData as $field) {
            $fieldparts = explode('|', $field);
            if ($slideparts[0] !== null) {
                $idFields[]     = $fieldparts[0] ?: null;
                $fieldTitles[]  = $fieldparts[1] ?: '';;
            }
        }
        $productData    = explode('#', $pageInfoRow['products']);
        $idProducts     = $titleProducts = $imageProducts = [];
        foreach ($productData as $product) {
            $productparts          = explode('|', $product);
            if ($productparts[0]   !== null) {
                $idProducts[]      = $productparts[0] ?: null;
                $titleProducts[]   = $productparts[1] ?: '';
                $imageProducts[]   = $productparts[2] ?: '';
            }
        }
        $reviewsData    = explode('#', $pageInfoRow['reviews']);
        $idReviews = $imageReviews = $nameReviews = $jobReviews = $contentReviews = [];
        foreach ($reviewsData as $review) {
            $reviewparts          = explode('|', $review);
            if ($reviewparts[0]   !== null) {
                $idReviews[]      = $reviewparts[0] ?: null;
                $nameReviews[]    = $reviewparts[1] ?: '';
                $jobReviews[]     = $reviewparts[2] ?: '';
                $contentReviews[] = $reviewparts[3] ?: '';
                $imageReviews[]   = $reviewparts[4] ?: '';
                $ratingReviews[]  = $reviewparts[5] ?: '';
            }
        }
        $blogsData = explode('#', $pageInfoRow['blogs']);
        $idBlogs = $titleBlogs = $imageBlogs = $descriptionBlogs = [];
        foreach ($blogsData as $blog) {
            $blogparts          = explode('|', $blog);
            if ($blogparts[0]   !== null) {
                $idBlogs[]          = $blogparts[0] ?: null;
                $titleBlogs[]       = $blogparts[1] ?: '';
                $imageBlogs[]       = $blogparts[2] ?: '';
                $descriptionBlogs[] = $blogparts[3] ?: '';
            }
        }
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?= $pageName; ?></title>
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="canonical" href="https://<?= $_SERVER['SERVER_NAME']; ?><?= $_SERVER['REQUEST_URI']; ?>" />

            <meta name="twitter:card" content="summary" />
            <meta name="twitter:title" content="<?= $pageName ?>" />
            <meta name="twitter:description" content="<?= htmlspecialchars(strip_tags($pageDescriptionBanner), ENT_QUOTES, 'UTF-8'); ?>" />
            <meta name="twitter:image" content="https://<?= $_SERVER['SERVER_NAME']; ?>/src/docs/images/imagePages/<?= $pageImageLogo; ?>" />
            <meta name="twitter:url" content="https://<?= $_SERVER['SERVER_NAME']; ?><?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?>" />
            <meta name="keywords" content="<?= $fieldTitles[1] . ', ' . $fieldTitles[2] .', ' . $fieldTitles[3] ?>" />
            <meta name="description" content="<?= $pageDescriptionBanner; ?>" />
            <meta property="og:url" content="https://<?= $_SERVER['SERVER_NAME']; ?><?= $_SERVER['REQUEST_URI']; ?>" />
            <meta property="og:type" content="website" />
            <meta property="og:updated_time" content="1578214368" />
            <meta property="og:image" content="https://<?= $_SERVER['SERVER_NAME']; ?>/src/docs/images/imageIntroduces/<?= $pageImageIntroduce;?>" />
            <meta property="og:description" content="<?= htmlspecialchars(strip_tags($pageDescriptionBanner), ENT_QUOTES, 'UTF-8'); ?>" />
            <meta property="og:keywords" content="<?= $fieldTitles[1] . ', ' . $fieldTitles[2] .', ' . $fieldTitles[3] ?>" />

            <link rel="shortcut icon" href="../src/docs/images/imageSystems/<?= $imageLogoPage ?>" />
            <link rel="stylesheet" href="../src/public/themes/css/bootstrap.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/all.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/animate.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/owl.carousel.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/jquery.fancybox.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/tooltipster.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/cubeportfolio.min.css">
            <link rel="stylesheet" href="../src/public/themes/css/revolution/navigation.css">
            <link rel="stylesheet" href="../src/public/themes/css/revolution/settings.css">
            <link rel="stylesheet" href="../src/public/themes/css/style.css">
            <link rel="stylesheet" href="../src/public/themes/css/customstyle.css">
 
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <?php include("../../handlers/layouts/layoutIndex/layoutFontawesome.php"); 
        
            $fullAddress  = [];
            $wardName     = null;
            $provinceName = null;
            
            if (!empty($pageWard)) {
                $json = @file_get_contents("https://esgoo.net/api-tinhthanh-new/5/$pageWard.htm");
            
                if ($json !== false) {
                    $data = json_decode($json, true);
            
                    if (
                        is_array($data)
                        && isset($data['error'])
                        && $data['error'] === 0
                        && !empty($data['data']['full_name'])
                    ) {
                        $full = $data['data']['full_name'];
            
                        $parts = array_map('trim', explode(',', $full));
            
                        $wardName     = $parts[0] ?? null;
                        $provinceName = $parts[1] ?? null;
            
                        $fullAddress[] = $full;
                    }
                }
            }

    ?>
            

<script type="application/ld+json">
<?= json_encode([
  "@context" => "https://schema.org",
  "@type" => "LocalBusiness",
  "@id" => "https://quangbathuonghieu.com.vn/".$idPage,
  "name" => $pageName,
  "url" => "https://quangbathuonghieu.com.vn/".$pageUrl."/",
  "logo" => "https://quangbathuonghieu.com.vn/src/docs/images/imagePages/".$pageImageLogo,
  "image" => "https://quangbathuonghieu.com.vn/src/docs/images/imageIntroduces/".$pageImageIntroduce,
  "telephone" => $userNumberPhone,
  "description" => trim(html_entity_decode(strip_tags($pageDescriptionBanner))),
  "priceRange" => "VNĐ",
  "address" => array_filter([
  "@type" => "PostalAddress",
    "streetAddress" => trim(strip_tags($pageAddress)),
    "addressLocality" => trim(strip_tags($wardName)),
    "addressRegion" => trim(strip_tags($provinceName)),
    "postalCode" => !empty($pagePostalCode) ? $pagePostalCode : null,
    "addressCountry" => "VN"
]),

  "openingHoursSpecification" => [[
    "@type" => "OpeningHoursSpecification",
    "dayOfWeek" => [
      "Monday","Tuesday","Wednesday",
      "Thursday","Friday","Saturday","Sunday"
    ],
    "opens" => "07:00",
    "closes" => "22:00"
  ]],
  "areaServed" => [
    "@type" => "Country",
    "name" => "Việt Nam"
  ],
  "sameAs" => [
    "https://zalo.me/".$userNumberPhone
  ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>


            
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-EQX5D15PRG"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'G-EQX5D15PRG');
            </script>
        </head>

        <body data-bs-spy="scroll" data-bs-target=".navbar-nav" data-bs-offset="75" class="offset-nav">
            <?php
            include_once("../../library/sweetalert/alert.php");
            include("../../handlers/layouts/layoutCustomer/layoutCheckStatusPopup.php");
            include("../../handlers/layouts/layoutCustomer/layoutLoader.php");
            include("../../utils/Header/headerCustomer.php");
            include("../../handlers/layouts/layoutCustomer/layoutSlider.php");
            include("../../handlers/layouts/layoutCustomer/layoutService.php");
            include("../../handlers/layouts/layoutCustomer/layoutFeature.php");
            include("../../handlers/layouts/layoutCustomer/layoutWorkProcess.php");
            include("../../handlers/layouts/layoutCustomer/layoutBanner.php");
            include("../../handlers/layouts/layoutCustomer/layoutGallery.php");
            include("../../handlers/layouts/layoutCustomer/layoutTestimonials.php");
            include("../../handlers/layouts/layoutCustomer/layoutLastBlog.php");
            include("../../handlers/layouts/layoutCustomer/layoutContact.php");
            include("../../handlers/layouts/layoutCustomer/layoutRelatedPage.php");
            include("../../handlers/layouts/layoutCustomer/layoutMap.php");
	        include("../../handlers/layouts/layoutCustomer/layoutModalPopup.php");
            include("../../utils/Footer/footerCustomer.php");
            ?>
            
            <script src="../src/public/themes/js/jquery-3.6.0.min.js"></script>
            <script src="../src/public/themes/js/propper.min.js"></script>
            <script src="../src/public/themes/js/bootstrap.min.js"></script>
            <script src="../src/public/themes/js/jquery.appear.js"></script>
            <script src="../src/public/themes/js/owl.carousel.min.js"></script>
            <script src="../src/public/themes/js/jquery-countTo.js"></script>
            <script src="../src/public/themes/js/parallaxie.js"></script>
            <script src="../src/public/themes/js/jquery.cubeportfolio.min.js"></script>
            <script src="../src/public/themes/js/jquery.fancybox.min.js"></script>
            <script src="../src/public/themes/js/tooltipster.min.js"></script>
            <script src="../src/public/themes/js/wow.js"></script>
            <script src="../src/public/themes/js/revolution/jquery.themepunch.tools.min.js"></script>
            <script src="../src/public/themes/js/revolution/jquery.themepunch.revolution.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.actions.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.carousel.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.kenburn.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.layeranimation.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.migration.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.navigation.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.parallax.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.slideanims.min.js"></script>
            <script src="../src/public/themes/js/revolution/extensions/revolution.extension.video.min.js"></script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgIfLQi8KTxTJahilcem6qHusV-V6XXjw"></script>
            <script src="../src/public/themes/js/functions.js"></script>
            
            <a href="https://zalo.me/<?= $userNumberPhone ?>" class="suntory-alo-phone suntory-alo-green" id="suntory-alo-phoneIcon" style="left: 1px; bottom: 60px;">
                <div class="suntory-alo-ph-circle"></div>
                <div class="suntory-alo-ph-circle-fill"></div>
                <div class="suntory-alo-ph-img-circle"><img src="../src/docs/images/common/icon-zalo.gif" style="width: 100%; height: auto;" /> </div>
            </a>

            <a href="tel:<?= $userNumberPhone ?>" class="suntory-alo-phone suntory-alo-green" id="suntory-alo-phoneIcon" style="left: 0px; bottom: 0px;">
                <div class="callmeText">
                    <span class="phone_text"><?= $userNumberPhone ?></span>
                </div>
                <div class="suntory-alo-ph-circle"></div>
                <div class="suntory-alo-ph-circle-fill"></div>
                <div class="suntory-alo-ph-img-circle"><i class="fa fa-phone"></i></div>
            </a>
                    
        </body>

        </html>
<?php
    } else {   
        redirectToNotFound();
    }
    mysqli_stmt_close($stmt);
} else { 
    redirectToNotFound();
}

