<section class="bglight padding" id="related">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <!--<div class="heading-title darkcolor wow fadeInUp" data-wow-delay="100ms">
                    <div class="font-normal darkcolor heading_space_half" style="color: red; padding-bottom:20px"> Thương Hiệu Tương Tự </div>
                </div>  -->
            </div>
        </div>
        <div class="row g-2">
            <?php
            $relatedPagesQuery = mysqli_query($link, "SELECT * FROM pages Where id <> $idPage ORDER BY Rand() LIMIT 9");
            if (mysqli_num_rows($relatedPagesQuery) > 0) {
                $dataPages = [];
                while ($rowPages = mysqli_fetch_object($relatedPagesQuery)) {
                    $idPageRelated          = $rowPages->id;
                    $pageDescriptionRelated = $rowPages->pageDescriptionBanner;
                    $pageNameRelated        = $rowPages->pageName;
                    $pageUrlRelated         = $rowPages->pageUrl;
                    $basePathRelated        = "src/docs/images/imageIntroduces/";
                    $imageNameRelated       = !empty($rowPages->pageImageIntroduce) ? $rowPages->pageImageIntroduce : 'defaultIntroduce.jpg';
                    $imagePageRelated       = $basePathRelated . $imageNameRelated;
    
                    $dataPages[] = compact('idPageRelated', 'pageDescriptionRelated', 'pageNameRelated', 'pageUrlRelated', 'imagePageRelated');
                }
            $delayBlog = 200;
            foreach ($dataPages as $page) { ?>
                <div class="col-lg-4 col-md-6">
                    <div class="news_item shadow wow fadeInUp" data-wow-delay="<?= $delayBlog ?>ms">
                       <!-- <div class="blog-img">
                            <a class="image" href="/<?= $page['pageUrlRelated'] ?>/">
                               <img src="../<?= $page['imagePageRelated'] ?>" alt="<?= $page['pageNameRelated'] ?>" class="img-responsive imageLastBlog" style="padding:5px;width: 100% !important;" >
                            </a>
                        </div> -->
                   
                            <span style='font-size: 15px;line-height: 26px;    padding: 5px;'>
                                <a href="/<?= $page['pageUrlRelated'] ?>/"><?= $page['pageNameRelated'] ?></a>
                            </span>
                        
                    </div>
                </div>
            <?php
                $delayBlog += 100;
                }
            }
            ?>
        </div>
    </div>
</section>