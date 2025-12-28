<section id="about" class="single-feature p3rem mt-n5" style="padding-bottom: 5rem;">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-lg-6 col-md-7 col-sm-7 text-sm-start text-center wow fadeInLeft" data-wow-delay="300ms">
                <div class="heading-title mb-4">
                    <h1 class="darkcolor font-normal bottom30 featureTitle"><a href="/<?= $pageUrl ?>/"><?= $pageName ?> </a></h1>
                </div>
                <div class="bottom35 featureContent"><?= $pageContentIntroduce ?></div>
            </div>
            <div class="col-lg-5 offset-lg-1 col-md-5 col-sm-5 wow fadeInRight" data-wow-delay="300ms">
                <div class="vienanh"></div>
                <div class="image">
                    <img alt="<?= $pageName ?>" class="imageFeatureContent" src="../src/docs/images/imageIntroduces/<?= (!empty($pageImageIntroduce)) ? $pageImageIntroduce : 'defaultIntroduce.jpg' ?>">
                </div>
            </div>
        </div>
    </div>
</section>