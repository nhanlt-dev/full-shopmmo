<div class="section-testimonials style-1 tf-spacing-1" id="sectionReview">
    <div class="tf-container">
        <div class="row">
            <div class="col-12">
                <div class="heading-section text-center mb-48">
                    <h2 class="title text-anime-wave">Đánh Giá Của Khách Hàng</h2>
                    <p class="text-1 wow animate__fadeInUp animate__animated" data-wow-duration="1.5s" data-wow-delay="0s">Hàng trăm khách hàng đã để lại đánh giá sau khi sử dụng dịch vụ của chúng tôi.</p>
                </div>
                <div class="tf-grid-layout md-col-3 loadmore-item-8">
                    <?php
                    $stmtTestimonial = $link->prepare("SELECT * FROM testimonials ORDER BY id DESC LIMIT 9");
                    $stmtTestimonial->execute();
                    $resultTestimonial = $stmtTestimonial->get_result();

                    $Testimonials = [];
                    while ($rowTestimonial = $resultTestimonial->fetch_assoc()) {
                        $Testimonials[] = [
                            'idTestimonial'       => $rowTestimonial['id'],
                            'nameTestimonial'     => $rowTestimonial['nameTestimonial'],
                            'jobTestimonial'      => $rowTestimonial['jobTestimonial'],
                            'contentTestimonial'  => $rowTestimonial['contentTestimonial'],
                            'ratingTestimonial'   => (float)$rowTestimonial['ratingTestimonial'],
                            'imageTestimonial'    => $rowTestimonial['imageTestimonial'] ?: 'defaultTestimonial.jpg'
                        ];
                    }
                    $stmtTestimonial->close();
                    for ($i = 0; $i < count($Testimonials); $i += 3) { ?>
                        <div class="box-testimonials">
                            <?php for ($j = $i; $j < $i + 3 && $j < count($Testimonials); $j++) {
                                $testimonial = $Testimonials[$j]; ?>
                                <div class="wg-testimonial style-2">
                                    <div class="ratings ">
                                        <?php for ($k = 0; $k < 5; $k++) {
                                            if ($k < floor($testimonial['ratingTestimonial'])) { ?>
                                                <i class="fa-solid fa-star clstart rvstartEdit" data-value="<?= $k + 1 ?>"></i>
                                            <?php } elseif ($k == floor($testimonial['ratingTestimonial']) && $testimonial['ratingTestimonial'] - floor($testimonial['ratingTestimonial']) === 0.5) { ?>
                                                <i class="fa-duotone fa-star-sharp-half clstart rvstartEdit" data-value="<?= $k + 1 ?>"></i>
                                            <?php } else { ?>
                                                <i class="fa-duotone fa-star rvstartEdit" data-value="<?= $k + 1 ?>"></i>
                                        <?php }
                                        } ?>
                                    </div>
                                    <p class="text-1 description"><?= $testimonial['contentTestimonial'] ?></p>
                                    <div class="author">
                                        <div class="avatar">
                                            <img src="src/docs/images/imageTestimonials/<?= $testimonial['imageTestimonial'] ?>" alt="imageTestimonials">
                                        </div>
                                        <div class="content">
                                            <h6 class="name"><a href="javascript: void(0)"><?= $testimonial['nameTestimonial'] ?></a></h6>
                                            <p class="text-2"><?= $testimonial['jobTestimonial'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <button class="tf-btn bg-color-primary fw-7 mx-auto btn-loadmore view-more-button">Xem Thêm</button>
                </div>
            </div>
        </div>
    </div>
</div>