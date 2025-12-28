<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Saigon');

include("src/database/db.php");
include("src/func/main.php");
include("src/func/cutString.php");
include("src/meta/title_meta.php");
?>
<!DOCTYPE html>
<html lang="en">
<base href="http://localhost/code-dvshop/">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= $titleMeta; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="canonical" href="https://<?= $_SERVER['SERVER_NAME']; ?>/<?= $_SERVER['REQUEST_URI']; ?>" />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?= $titleMeta ?>" />
	<meta name="twitter:description" content="<?= htmlspecialchars($descriptionMeta, ENT_QUOTES, 'UTF-8'); ?>" />
	<meta name="twitter:image" content="https://<?= $_SERVER['SERVER_NAME']; ?>/<?= $imageMeta; ?>" />
	<meta name="twitter:url" content="https://<?= $_SERVER['SERVER_NAME']; ?>/<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?>" />
	<meta name="keywords" content="<?= $keywordMeta; ?>" />
	<meta name="description" content="<?= $descriptionMeta; ?>" />
	<meta property="og:url" content="https://<?= $_SERVER['SERVER_NAME']; ?>/<?= $_SERVER['REQUEST_URI']; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:updated_time" content="1578214368" />
	<meta property="og:image" content="https://<?= $_SERVER['SERVER_NAME']; ?>/<?= $imageMeta; ?>" />
	<meta property="og:description" content="<?= $descriptionMeta; ?>" />
	<meta property="og:keywords" content="<?= $keywordMeta; ?>" />
	<?php include("src/handlers/layouts/layoutIndex/layoutFontawesome.php"); ?>

	<link rel="stylesheet" type="text/css" href="src/public/admin/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/animate.min.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/jqueryui.min.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/map.min.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/odometer.min.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/swiper-bundle.min.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/sib-styles.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/styles.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/customVerify.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/customstyles.css" />
	<link rel="stylesheet" type="text/css" href="src/public/admin/css/responsivetable.css" />

	<link href='src/public/plugins/data-tables/datatables.bootstrap5.min.css' rel='stylesheet'>
	<link href='src/public/plugins/data-tables/responsive.datatables.min.css' rel='stylesheet'>

	<link rel="stylesheet" type="text/css" href="src/public/admin/icons/icomoon/style.css" />
	<link rel="shortcut icon" href="src/docs/images/imageSystems/<?= $imageLogoPage ?>" />
	<link rel="apple-touch-icon-precomposed" href="src/docs/images/imageSystems/<?= $imageLogoPage ?>" />

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script type="text/javascript" src="src/public/admin/js/jquery.min.js"></script>
	<script src="https://esgoo.net/scripts/jquery.js"></script>
	<script src="https://www.google.com/recaptcha/api.js?render=6LcPXxsrAAAAAIcVCqL86N2iPr9JNQ4qJJVWIKiu"></script>

	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "LocalBusiness",
			"@id": "https://quangbathuonghieu.com.vn/#localbusiness",
			"name": "Quảng Bá Thương Hiệu Việt",
			"url": "https://quangbathuonghieu.com.vn/",
			"telephone": "+84905454348",
			"logo": "https://quangbathuonghieu.com.vn/src/docs/images/imageSystems/1744190400_67f63bc020209.png",
			"image": "https://quangbathuonghieu.com.vn/src/docs/images/imageSystems/1744190400_67f63bc020209.png",
			"description": "Bạn đang gặp khó khăn trong việc quảng bá thương hiệu doanh nghiệp vì nguồn ngân sách quá ít ỏi và hạn chế? Hãy đến với chúng tôi để được tư vấn miễn phí. Hotline/zalo: 0348 45 43 48.",
			"priceRange": "VNĐ",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "26 Nguyễn Duy",
				"addressLocality": "Cẩm Lệ",
				"addressRegion": "Đà Nẵng",
				"postalCode": "550000",
				"addressCountry": "VN"
			},
			"openingHoursSpecification": [{
				"@type": "OpeningHoursSpecification",
				"dayOfWeek": [
					"Monday", "Tuesday", "Wednesday",
					"Thursday", "Friday", "Saturday", "Sunday"
				],
				"opens": "07:00",
				"closes": "22:00"
			}],
			"areaServed": {
				"@type": "Country",
				"name": "Việt Nam"
			},
			"sameAs": [
				"https://zalo.me/0905454348",
				"https://www.google.com/maps/place/26+Nguy%E1%BB%85n+Duy,+Khu%C3%AA+Trung,+C%E1%BA%A9m+L%E1%BB%87,+%C4%90%C3%A0+N%E1%BA%B5ng+550000,+Vietnam/"
			]
		}
	</script>


	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-EQX5D15PRG"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-EQX5D15PRG');
	</script>
	<script type="text/javascript">
		window.dataLayer = window.dataLayer || [];
	</script>
</head>

<body class="popup-loader">
	<div id="wrapper">
		<?php
		include("src/library/sweetalert/alert.php");
		include("src/handlers/layouts/layoutContent/layoutLoading.php");
		include("src/handlers/layouts/layoutIndex/layoutInfomation.php");
		include("src/utils/Header/headerDetail.php");
		include('src/routers/router.php');
		include("src/utils/Footer/footerDetail.php") ?>
	</div>
	<?php
	include("src/handlers/layouts/layoutContent/layoutModalLogin.php");
	include("src/handlers/layouts/layoutContent/layoutModalRegister.php");
	include("src/handlers/layouts/layoutContent/layoutModalVerification.php");
	include("src/handlers/layouts/layoutContent/layoutModalForgotPassword.php");
	include("src/handlers/layouts/layoutContent/layoutMobileNav.php");
	include("src/handlers/layouts/layoutContent/layoutProgressWrap.php");
	?>

	<script src="src/library/ckeditor/ckeditor.js"></script>
	<script src="src/library/ckfinder/ckfinder.js"></script>

	<script src='src/public/plugins/data-tables/jquery.datatables.min.js'></script>
	<script src='src/public/plugins/data-tables/datatables.bootstrap5.min.js'></script>
	<script src='src/public/plugins/data-tables/datatables.responsive.min.js'></script>

	<script type="text/javascript" src="src/public/admin/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/lazysize.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/wow.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/jqueryui.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/jquery.nice-select.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/chart.js"></script>
	<script type="text/javascript" src="src/public/admin/js/chart-init.js"></script>
	<script type="text/javascript" src="src/public/admin/js/odometer.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/counter.js"></script>
	<script type="text/javascript" src="src/public/admin/js/swiper-bundle.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/swiper.js"></script>
	<script type="text/javascript" src="src/public/admin/js/simpleParallaxVanilla.umd.js"></script>
	<script type="text/javascript" src="src/public/admin/js/gsap.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/rangle-slider.js"></script>
	<script type="text/javascript" src="src/public/admin/js/Splitetext.js"></script>
	<script type="text/javascript" src="src/public/admin/js/ScrollTrigger.min.js"></script>
	<script type="text/javascript" src="src/public/admin/js/main.js"></script>
	<script type="text/javascript" src="src/public/js/customjs.js"></script>
	<script type="text/javascript" src="src/public/admin/js/responsivetable.js"></script>
	<script defer src="src/public/admin/js/sibforms/forms/end-form/build/main.js"></script>
</body>

</html>