<section id="portfolio" class="position-relative pt3rem" style="padding-bottom:3rem;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center wow fadeIn" data-wow-delay="300ms">
                <div class="heading-title darkcolor wow fadeInUp" data-wow-delay="300ms">
                    <div class="font-normal darkcolor heading_space_half" style="color:#ff0000">DỊCH VỤ NỔI BẬT</div>
                </div>
                <div class="col-md-6 offset-md-3 heading_space_half wow fadeIn" data-wow-delay="700ms">
                    <div>Top các sản phẩm, dịch vụ hàng đầu của chúng tôi.</div>
                </div>
            </div>
            <div class="col-lg-12 wow fadeIn" data-wow-delay="1s">
                <div id="grid-mosaic" class="cbp cbp-l-grid-mosaic-flat">
                    <?php
                    $items = [
                        ['index' => 0, 'classes' => 'brand graphics'],
                        ['index' => 3, 'classes' => 'brand graphics design'],
                        ['index' => 1, 'classes' => 'design digital graphics'],
                        ['index' => 4, 'classes' => 'brand graphics'],
                        ['index' => 5, 'classes' => 'graphics design'],
                        ['index' => 2, 'classes' => 'brand digital design'],
                    ];

                    foreach ($items as $item) {
                        $imgSrc = "../src/docs/images/imageProducts/{$imageProducts[$item['index']]}";
                        $title = $titleProducts[$item['index']];
                    ?>
                        <div class="cbp-item <?= $item['classes'] ?> item-<?= $item['index'] ?>">
                            <img src="<?= $imgSrc ?>" alt="<?= $title ?>">
                            <div class="gallery-hvr whitecolor">
                                <div class="center-box">
                                    <a href="<?= $imgSrc ?>" class="opens" data-fancybox="gallery" title="Zoom In">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                    <p class="w-100"><?= $title ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</section>