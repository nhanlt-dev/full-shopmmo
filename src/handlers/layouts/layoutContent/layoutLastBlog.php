<section class="section-featured-properties tf-spacing-1">
    <div class="tf-container">
        <div class="row">
            <div class="col-12">
                <div class="heading-section text-center mb-48">
                    <h2 class="title text-anime-wave">Tin Tức Về Các Doanh Nghiệp</h2>
                </div>
                <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="3" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                    <div class="swiper-wrapper mb-48">
                        <?php
                        $stmtLastBlog = $link->prepare("SELECT * FROM news ORDER BY id DESC LIMIT 6");
                        $stmtLastBlog->execute();
                        $resultLastBlog = $stmtLastBlog->get_result();

                        $LastBlog = [];
                        while ($row = $resultLastBlog->fetch_assoc()) {
                            $LastBlog[] = [
                                'id'              => $row['id'],
                                'newsTitle'       => $row['newsTitle'],
                                'newsDescription' => $row['newsDescription'],
                                'newsContent'     => $row['newsContent'],
                                'newsUrl'         => $row['newsUrl'],
                                'newsImage'       => $row['newsImage'] ?: 'defaultNews.jpg'
                            ];
                        }
                        $stmtLastBlog->close();
                        foreach ($LastBlog as $lastBlog) { ?>
                            <div class="swiper-slide">
                                <div class="box-house hover-img ">
                                    <div class="image-wrap image-wrap-last-blog">
                                        <a href="tintuc/<?= $lastBlog['newsUrl'] ?>/">
                                            <img class="lazyload" data-src="src/docs/images/imageNews/<?= $lastBlog['newsImage'] ?>" src="src/docs/images/imageNews/<?= $lastBlog['newsImage'] ?>" alt="newsImage">
                                        </a>
                                        <div class="list-btn flex gap-8 ">
                                            <a href="tintuc/<?= $lastBlog['newsUrl'] ?>/" class="btn-icon find hover-tooltip">
                                                <i class="icon-find-plus"></i>
                                                <span class="tooltip">Xem Thêm</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h5 class="title">
                                            <a href="tintuc/<?= $lastBlog['newsUrl'] ?>/"><?= $lastBlog['newsTitle'] ?></a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="sw-wrap-btn">
                        <div class="swiper-button-prev sw-button layout-3-prev">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                        <div class="swiper-button-next sw-button layout-3-next">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>