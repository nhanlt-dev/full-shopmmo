<section id="our-process" class="padding bgdark">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-center">
                <div class="heading-title whitecolor wow fadeInUp" data-wow-delay="300ms">
                <div class="font-normal darkcolor heading_space_half" style="color:#ffffff">LĨNH VỰC KINH DOANH</div>
                </div>
                <div class="heading-title whitecolor wow fadeInUp mt2rem" data-wow-delay="500ms">
                    <div><?= $pageBusinessField ?> </div>
                </div>
            </div>
        </div>
        <div class="row">
            <ul class="process-wrapp">
                <?php
                $delay = 300;
                for ($i = 0; $i < 5; $i++) { ?>
                    <li class="whitecolor wow fadeIn" data-wow-delay="<?= $delay ?>ms">
                        <span class="pro-step bottom20">0<?= $i + 1 ?></span>
                        <p class="mt-n2 mt-sm-0"><a href="/<?= $pageUrl ?>/"><?= htmlspecialchars($fieldTitles[$i], ENT_QUOTES, 'UTF-8') ?></a></p>
                    </li>
                <?php
                    $delay += 250;
                } ?>
            </ul>
        </div>
    </div>
</section>