<section class="section-work-together " id="sectionPartner">
    <div class="wg-partner  tf-spacing-1">
        <div class="tf-container">
            <div class="row">
                <div class="col-12">
                    <div class="heading-section  text-center mb-48">
                        <h2 class="title text-white text-anime-wave">Đồng Hàng Cùng Chúng Tôi</h2>
                        <p class="text-1 text-white wow animate__fadeInUp animate__animated" data-wow-duration="1.5s" data-wow-delay="0s">Hàng trăm doanh nghiệp đã tin tưởng và sử dụng dịch vụ của chúng tôi.</p>
                    </div>
                    <div class="swiper brand-slide " data-preview="6" data-tablet="4" data-mobile-sm="3" data-mobile="1.8" data-space="15" data-space-md="30" data-space-lg="30">
                        <div class="swiper-wrapper">
                            <?php
                            $stmtPartner = $link->prepare("SELECT * FROM partners ORDER BY id DESC");
                            $stmtPartner->execute();
                            $resultPartner = $stmtPartner->get_result();

                            $Partners    = [];
                            while ($rowPartner = $resultPartner->fetch_assoc()) {
                                $Partners[] = [
                                    'idPartner'       => $rowPartner['id'],
                                    'namePartner'     => $rowPartner['namePartner'],
                                    'imagePartner'    => $rowPartner['imagePartner'] ?: 'defaultPartner.jpg'
                                ];
                            }
                            $stmtPartner->close();
                            foreach ($Partners as $partner) { ?>
                                <div class="swiper-slide">
                                    <div class="partner-item style-2 ">
                                        <img src="src/docs/images/imagePartners/<?= $partner['imagePartner'] ?>" alt="<?= $partner['namePartner'] ?>">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wg-appraisal ">
        <div class="tf-container">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <div class="heading-section mb-30">
                            <h2 class="title text-anime-wave">Bạn Đang Gặp Vấn Đề </br> Với Việc Gia Tăng Nhận Diện </br>Cho Thương Hiệu Của Mình?</h2>
                            <p class="text-1 wow animate__fadeInUp animate__animated" data-wow-duration="1.5s" data-wow-delay="0s">Hãy liên hệ với chúng tôi ngay để nhận được tư vấn tốt nhất và nhanh chóng.</p>
                        </div>
                        <a href="lienhe/" class="tf-btn bg-color-primary fw-7 pd-11">
                            Liên Hệ Ngay
                        </a>
                        <div class="person wow animate__fadeInRight animate__animated" data-wow-duration="2s">
                            <img src="src/public/admin/images/section/person-1.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>