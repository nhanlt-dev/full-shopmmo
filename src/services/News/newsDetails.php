<?php
$urlNews = isset($_GET['url']) ? $_GET['url'] : '';

$stmt = $link->prepare("SELECT * FROM news WHERE newsUrl = ?");
$stmt->bind_param("s", $urlNews);
$stmt->execute();
$result = $stmt->get_result();

if ($newsRow = $result->fetch_assoc()) {
    $idNews          = $newsRow['id'];
    $newsTitle       = $newsRow['newsTitle'];
    $newsDescription = $newsRow['newsDescription'];
    $newsContent     = $newsRow['newsContent'];
    $newsImage       = $newsRow['newsImage'];
    
    $count = @mysqli_num_rows(mysqli_query($link, "SELECT * FROM comments Where idNews = $idNews"));

    $stmt->close();

if (isset($_POST['comments'])) {
    if(!empty($_POST['idUserComment'])){
    $dataComment  = [
        'idUserComment'     => mysqli_real_escape_string($link, $_POST['idUserComment']     ?? ''),
        'idNewsComment'     => mysqli_real_escape_string($link, $_POST['idNewsComment']     ?? ''),
        'ratingComment'     => mysqli_real_escape_string($link, $_POST['ratingComment']     ?:  5),
        'contentComment'    => mysqli_real_escape_string($link, $_POST['message']           ?? '')
    ];
    $insertQuery    = "INSERT INTO comments (`idUser`, `idNews`, `ratingComment`, `statusComment`, `contentComment`) VALUES (?,?,?,0,?)";
    $idComment      = executeQuery($link, $insertQuery, array_values($dataComment), true);

    showSuccessInsertAlert('Thêm mới bình luận thành công!', "tintuc/$urlNews/");
    }else{
        showErrorAlert('Bình Luận Thất Bại', 'Bạn cần đăng nhập trước khi bình luận');
    }
}
?>
<section class="flat-title ">
    <div class="tf-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-inner ">
                    <ul class="breadcrumb">
                        <li><a class="home fw-6 text-color-3" href="trangchu">Trang Chủ</a></li>
                        <li><a class="home fw-6 text-color-3" href="tintuc/">Tin Tức</a></li>
                        <li><?= $newsTitle ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="page-content">
    <section class="section-blog-details ">
        <div class="tf-container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="heading">
                        <h2 class="title-heading "><?= $newsTitle ?></h2>
                    </div>
                    <p class="fw-5 text-color-heading mb-30"><?= $newsDescription ?></p>
                    <div class="image-wrap mb-30">
                        <img class="lazyload" data-src="src/docs/images/imageNews/<?= $newsImage ?>" src="src/docs/images/imageNews/<?= $newsImage ?>" alt="">
                    </div>
                    <div class="wrap-content mb-20">
                        <?= $newsContent ?>
                    </div>
                    <div class="wrap-comment">
                        <h4 class="title">Đánh giá bài viết (<?= $count ?>)</h4>
                        <ul class="comment-list">
                            <?php
                            $commentQuery = mysqli_query($link, "SELECT c.*, u.userName, u.userAvatar FROM comments AS c
                                                                                          Inner Join users AS u ON u.id = c.idUser
                                                                                          Where idNews = $idNews AND statusComment = 1 ORDER BY id DESC");
                            if (mysqli_num_rows($commentQuery) > 0) {
                                $dataComment = [];
                                while ($rowComment  = mysqli_fetch_object($commentQuery)) {
                                    $idComment          = $rowComment->id;
                                    $userName           = $rowComment->userName;
                                    $userAvatar         = $rowComment->userAvatar;
                                    $ratingComment      = $rowComment->ratingComment;
                                    $contentComment     = $rowComment->contentComment;
                                    $dataComment[] = compact('idComment', 'userName', 'userAvatar', 'ratingComment', 'contentComment');
                                }
                                foreach ($dataComment as $comments) { ?>
                                    <li>
                                        <div class="comment-item">
                                            <div class="image-wrap">
                                                <img src="src/docs/images/imageUsers/<?= $comments['userAvatar'] ?>" alt="userAvatar">
                                            </div>
                                            <div class="content w100">
                                                <div class="user w100 d-flex jcsb">
                                                    <div class="author ">
                                                        <h6 class="name"><?= $comments['userName'] ?></h6>
                                                    </div>
                                                    <div class="ratings">
                                                        <?php
                                                        for ($j = 1; $j <= 5; $j++) {
                                                            if ($j <= $comments['ratingComment']) { ?>
                                                                <i class="fa-solid fa-star clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                                            <?php } elseif ($j - 0.5 == $comments['ratingComment']) { ?>
                                                                <i class="fa-duotone fa-star-sharp-half clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                                            <?php } else { ?>
                                                                <i class="fa-duotone fa-star rvstartEdit" data-value="<?= $j ?>"></i>
                                                        <?php }
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="comment">
                                                    <p><?= $comments['contentComment'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                        <a href="#" class="tf-btn style-border fw-7 pd-1">
                            <span>Xem tất cả đánh giá </span>
                        </a>
                    </div>
                    <div class="box-send ">
                        <div class="heading-box">
                            <h4 class="title fw-7">Để lại đánh giá của bạn</h4>
                            <p>Địa chỉ email của bạn sẽ không được công bố. Các trường bắt buộc được đánh dấu *</p>
                        </div>
                        <form class="form-add-review" method="post">
                            <div class="cols">
                                <fieldset class="box box-fieldset">
                                    <label for="jobTestimonial">Đánh Giá<span>*</span></label>
                                    <div class="ec-t-review-rating d-flex jcc" id="TestimonialRating">
                                        <?php
                                        for ($j = 1; $j <= 5; $j++) { ?>
                                            <i class="fa-duotone fa-solid fa-star clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" name="ratingComment" id="selected-rating">
                                </fieldset>
                            </div>
                            <fieldset class="message">
                                <label class="text-1 fw-6" for="message-comment">Đánh giá của bạn</label>
                                <textarea id="message-comment" class="tf-input" name="message" rows="4" placeholder="Hãy điền đánh giá của bạn..." tabindex="4" aria-required="true" required></textarea>
                            </fieldset>
                            <input type="hidden" name="idUserComment" value="<?= $_SESSION['userData']['id'] ?? '' ?>">
                            <input type="hidden" name="idNewsComment" value="<?= $idNews ?>">
                            <button class="tf-btn bg-color-primary pd-2 fw-7" name="comments" type="submit">Đánh giá</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class=" tf-sidebar">
                        <div class="sidebar-search sidebar-item">
                            <h4 class="sidebar-title">Tìm Kiếm Tin Tức</h4>
                            <form action="#" class="form-search">
                                <fieldset>
                                    <input class="" type="text" placeholder="Search" name="text" tabindex="2" value="" aria-required="true" required="">
                                </fieldset>
                                <div class="button-submit">
                                    <button class="" type="submit"><i class="icon-MagnifyingGlass"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="sidebar-item sidebar-featured  pb-36">
                            <h4 class="sidebar-title">Tin Tức Mới Nhất</h4>
                            <ul>
                                <?php
                                $lastNewsQuery = mysqli_query($link, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
                                if (mysqli_num_rows($lastNewsQuery) > 0) {
                                    while ($lastNewsPages = mysqli_fetch_object($lastNewsQuery)) {
                                        $idNews     = $lastNewsPages->id;
                                        $newsTitle  = $lastNewsPages->newsTitle;
                                        $newsImage  = $lastNewsPages->newsImage;
                                        $newsUrl    = $lastNewsPages->newsUrl; ?>
                                        <li class="box-listings hover-img">
                                            <div class="image-wrap">
                                                <img class="lazyload" data-src="src/docs/images/imageNews/<?= $newsImage ?>"
                                                    src="src/docs/images/imageNews/<?= $newsImage ?>" alt="<?= $newsTitle ?>">
                                            </div>
                                            <div class="content">
                                                <div class="text-1 title fw-5">
                                                    <a href="tintuc/<?= $newsUrl ?>/"><?= $newsTitle ?></a>
                                                </div>
                                            </div>
                                        </li>
                                <?php }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="sidebar-newslatter sidebar-item">
                            <h4 class="sidebar-title">Nhận Thông Báo Ngay!</h4>
                            <p>Đăng ký để nhận thông báo những tin tức mới nhất từ chúng tôi chỉ với 1 click để có nhưng tin tức mới nhất!</p>
                            <form action="#" class="form-search">
                                <fieldset>
                                    <input class="" type="text" placeholder="Email@gmail.com..." name="text" tabindex="2" value="" aria-required="true" required="">
                                </fieldset>
                                <div class="button-submit">
                                    <button class="" type="submit"><i class="icon-send-message"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class=" sidebar-ads">
                            <div class="image-wrap">
                                <img class="lazyload" data-src="src/docs/images/common/ads.jpg" src="src/docs/images/common/ads.jpg" alt="banner">
                            </div>
                            <div class="logo relative z-5">
                                <img src="src/docs/images/imageSystems/<?= $imageLogoPage ?>" alt="">
                            </div>
                            <div class="box-ads relative z-5">
                                <div class="content ">
                                    <h4 class="title"><a href="lienhe/">Tăng độ nhận diện thương hiệu ngay.</a> </h4>
                                    <div class="text-addres ">
                                        <p>Kết nối với chúng tôi ngay để được hướng dẫn tăng độ nhận diện thương hiệu của bản thân, giúp nhanh chóng được khách hàng biết đến.</p>
                                    </div>
                                </div>
                                <a href="lienhe/" class="tf-btn fw-6 bg-color-primary w-full">
                                    Liên Hệ Với Chúng Tôi Ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-related-posts">
        <div class="tf-container">
            <div class="row">
                <div class="col-12">
                    <h4 class="heading">Tin Tức Liên Quan</h4>
                    <div class="swiper style-pagination tf-sw-latest" data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space-lg="40" data-space-md="20" data-space="15">
                        <div class="swiper-wrapper ">
                            <?php
                            $stmtRelated = $link->prepare("SELECT * FROM news WHERE id <> ? ORDER BY RAND() LIMIT 3");
                            $stmtRelated->bind_param("i", $idNews);
                            $stmtRelated->execute();
                            $resultRelated = $stmtRelated->get_result();

                            $relatedNews = [];
                            $dataDelayRelatedNews = 1;
                            while ($row = $resultRelated->fetch_assoc()) {
                                $relatedNews[] = [
                                    'idRelated'              => $row['id'],
                                    'newsTitleRelated'       => $row['newsTitle'],
                                    'newsDescriptionRelated' => $row['newsDescription'],
                                    'newsContentRelated'     => $row['newsContent'],
                                    'newsUrlRelated'         => $row['newsUrl'],
                                    'newsImageRelated'       => $row['newsImage'] ?: 'defaultNews.jpg'
                                ];
                            }
                            $stmtRelated->close();
                            foreach ($relatedNews as $related) {
                                $dataDelayRelatedNews += 1 ?>
                                <div class="swiper-slide">
                                    <div class="blog-article-item style-2 hover-img wow animate__fadeInUp animate__animated" data-wow-duration="<?= $dataDelayRelatedNews ?>s" data-wow-delay="0s">
                                        <div class=" image-wrap ">
                                            <a href="tintuc/<?= $related['newsUrlRelated'] ?>/">
                                                <img class="lazyload" data-src="src/docs/images/imageNews/<?= $related['newsImageRelated'] ?>" src="src/docs/images/imageNews/<?= $related['newsImageRelated'] ?>" alt="<?= $related['newsTitleRelated'] ?>">
                                            </a>
                                        </div>
                                        <div class="article-content">
                                            <h4 class="title ">
                                                <a href="tintuc/<?= $related['newsUrlRelated'] ?>/" class="line-clamp-2"><?= $related['newsTitleRelated'] ?></a>
                                            </h4>
                                            <a href="tintuc/<?= $related['newsUrlRelated'] ?>/" class="tf-btn-link">
                                                <span> Xem Thêm </span>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_2450_13860)">
                                                        <path d="M10.0013 18.3334C14.6037 18.3334 18.3346 14.6024 18.3346 10C18.3346 5.39765 14.6037 1.66669 10.0013 1.66669C5.39893 1.66669 1.66797 5.39765 1.66797 10C1.66797 14.6024 5.39893 18.3334 10.0013 18.3334Z" stroke="#F1913D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M6.66797 10H13.3346" stroke="#F1913D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10 13.3334L13.3333 10L10 6.66669" stroke="#F1913D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_2450_13860">
                                                            <rect width="20" height="20" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="sw-pagination sw-pagination-latest text-center d-lg-none d-block mt-20">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-CTA">
        <div class="tf-container">
            <div class="row">
                <div class="col-12">
                    <div class="content-inner">
                        <img src="src/public/admin/images/section/cta.png" alt="">
                        <div class="content">
                            <h4 class="text-white mb-8 ">SEO top thương hiệu của bạn ngay hôm nay</h4>
                            <p class="text-white text-1"> Bạn đang tìm cách để thương hiệu mình trên top tìm kiếm? Chúng tôi có thể giúp bạn.</p>
                        </div>
                        <a href="lienhe/#contactnow" class="tf-btn style-2 fw-6 ">Liên hệ với chúng tôi ngay
                            <i class="icon-MagnifyingGlass fw-6"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const stars = document.querySelectorAll(`#TestimonialRating .rvstartEdit`);
        const selectedRatingInput = document.getElementById(`selected-rating`);
        let lastClickTime = 0;
        let lastClickedStar = null;
        let lastHalfStar = null;

        stars.forEach(star => {
            star.addEventListener("click", function() {
                const currentTime = new Date().getTime();
                const ratingValue = parseInt(star.getAttribute("data-value"));
                if (lastClickedStar === star && currentTime - lastClickTime < 300) {
                    selectedRatingInput.value = ratingValue - 0.5;
                    star.classList.remove("fa-star");
                    star.classList.add("fa-duotone", "fa-star-sharp-half");
                    lastHalfStar = star;
                } else {
                    selectedRatingInput.value = ratingValue;
                    stars.forEach(s => s.classList.remove("fa-duotone", "fa-star-sharp-half", "fa-solid", "clstart"));
                    stars.forEach((s, index) => {
                        if (index < ratingValue) {
                            s.classList.add("fa-solid", "clstart", "fa-star");
                        } else {
                            s.classList.add("fa-duotone", "fa-solid", "clstart", "fa-star");
                        }
                    });
                    if (lastHalfStar) {
                        lastHalfStar.classList.remove("fa-star-sharp-half");
                        lastHalfStar.classList.add("fa-star");
                        lastHalfStar = null;
                    }
                }

                lastClickTime = currentTime;
                lastClickedStar = star;
            });
        });
    });
</script>
<?php }else{
    include('src/contents/404.php');
    exit();
}