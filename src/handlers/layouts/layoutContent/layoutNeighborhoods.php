<section class="section-neighborhoods mt-48" id="sectionCustomer">
    <div class="tf-container full">
        <div class="col-12">
            <div class="heading-section text-center mb-48">
                <h2 class="title text-anime-wave">Các Khách Hàng Tiêu Biểu</h2>
                <p class="text-1 wow animate__fadeInUp animate__animated" data-wow-duration="1.5s" data-wow-delay="0s">
                    Cảm ơn các doanh nghiệp đã đồng hành và phát triển cùng chúng tôi.</p>
            </div>
            <div class="wrap-neighborhoods">
                <?php
                $stmtLastPage = $link->prepare("SELECT * FROM pages WHERE pagePorpular = 1 ORDER BY id DESC LIMIT 8");
                if ($stmtLastPage) {
                    $stmtLastPage->execute();
                    $resultLastPage = $stmtLastPage->get_result();
                    $LastPage = [];

                    while ($rowPages = $resultLastPage->fetch_assoc()) {
                        $image = $rowPages['pageImageIntroduce'] ?: 'defaultIntroduce.jpg';
                        $LastPage[] = [
                            'id'                 => $rowPages['id'],
                            'pageName'           => $rowPages['pageName'],
                            'pageUrl'            => $rowPages['pageUrl'],
                            'pageImageIntroduce' => $image
                        ];
                    }

                    $stmtLastPage->close();
                } else {
                    die('Lỗi truy vấn: ' . $link->error);
                }

                foreach ($LastPage as $lastpage): ?>
                    <div class="box-location hover-img ">
                        <div class="image-wrap">
                            <a href="<?= $lastpage['pageUrl'] ?>/">
                                <img class="lazyload"
                                    data-src="src/docs/images/imageIntroduces/<?= $lastpage['pageImageIntroduce'] ?>"
                                    src="src/docs/images/imageIntroduces/<?= $lastpage['pageImageIntroduce'] ?>"
                                    alt="pageImageIntroduce">
                            </a>
                        </div>
                        <div class="content">
                            <h6 class="text-white"><?= $lastpage['pageName'] ?></h6>
                            <a href="<?= $lastpage['pageUrl'] ?>/" class="text-1 tf-btn style-border pd-23 text-white">Xem
                                Thêm <i class="icon-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
