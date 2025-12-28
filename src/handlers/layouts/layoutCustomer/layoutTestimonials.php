<section id="our-testimonial">
    <div class="parallax page-header testimonial-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-6 col-md-12 text-center text-lg-end">
                    <div class="heading-title wow fadeInRight padding_testi" data-wow-delay="300ms">
                        <span class="whitecolor">Đánh Giá Của Khách Hàng</span>
                        <div class="font-normal darkcolor heading_space_half" style="color:#ffffff">KHÁCH HÀNG NÓI VỀ CHÚNG TÔI</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="owl-carousel wow fadeInUp" data-wow-delay="500ms" id="testimonial-slider">
            <?php for ($i = 0; $i <= 2; $i++): ?>
                <div class="item testi-box">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-md-12 text-center " >
                            <div class="testimonial-round d-inline-block">
                                <img src="../src/docs/images/imageReviews/<?= $imageReviews[$i] ?>" alt="imageReviews" class="image-area" >
                            </div>
                            <div class="defaultcolor font-light top15"><a href="javascript:void(0)" style="font-weight:700;"><?= $nameReviews[$i] ?></a></div>
                            <p><?= $jobReviews[$i] ?></p>
                        </div>
                        <div class="col-lg-6 offset-lg-2 col-md-10 offset-md-1 text-lg-start text-center">
                            <p class="bottom15 top100" class='author_name'>" <?= $contentReviews[$i] ?> "</p>
                            <span class="d-inline-block test-star">
                                <?php
                                for ($j = 1; $j <= 5; $j++) {
                                    if ($j <= $ratingReviews[$i]) { ?>
                                        <i class="fa-solid fa-star clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                    <?php } elseif ($j - 0.5 == $ratingReviews[$i]) { ?>
                                        <i class="fa-duotone fa-star-sharp-half-stroke clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                    <?php } else { ?>
                                        <i class="fa-regular fa-star rvstartEdit" data-value="<?= $j ?>"></i>
                                <?php }
                                } ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>