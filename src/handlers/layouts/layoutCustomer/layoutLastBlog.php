<section class="bglight padding" id="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="heading-title darkcolor wow fadeInUp" data-wow-delay="100ms">
                    <div class="font-normal darkcolor heading_space_half" style="color: red; padding-bottom:20px"> TIN TỨC NỔI BẬT </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $delayBlog = 200;
            for ($i = 0; $i < 3; $i++) { ?>
                <div class="col-lg-4 col-md-6">
                    <div class="news_item shadow wow fadeInUp" data-wow-delay="<?= $delayBlog ?>ms">
                        <div class="blog-img">
                            <a class="image" href="/<?= $pageUrl ?>/">
                               <img src="../src/docs/images/imageBlogs/<?= $imageBlogs[$i] ?? 'defaultBlog.jpg' ?>" alt="<?= $titleBlogs[$i] ?>" class="img-responsive imageLastBlog" style="padding:5px;width: 100% !important;" >
                            </a>
                        </div>
                        <div class="news_desc">
                            
                            <a href="/<?= $pageUrl ?>/" style="color: #c00; font-size: 15px; overflow: hidden; font-weight: bold; line-height: 22px; margin-bottom: 9px; display: block;"><?= $titleBlogs[$i] ?></a>
                        
                            <p><?= $descriptionBlogs[$i] ?></p>
                            <ul class="meta-tags top20 bottom20">
                                <li><a href="/<?= $pageUrl ?>/"><i class="fa-regular fa-eye"></i>1.2 k</a></li>
                                <li><a href="/<?= $pageUrl ?>/"><i class="fa-regular fa-comment-dots"></i>114</a></li>
                                <li><a href="/<?= $pageUrl ?>/"><i class="fa-regular fa-star"></i></i>4.5/5</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php
                $delayBlog += 100;
            }
            ?>
        </div>
    </div>
</section>