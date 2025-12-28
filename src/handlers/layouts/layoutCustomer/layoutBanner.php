<section id="banner" class="pt3rem wow fadeInLeft" style="padding:3rem;" data-wow-delay="300ms">
    <div class="container">
        <div class="tab-SEO-Details">
            <div class="SEODetailWrapper" id="SEOContentWrapper">
                <div class="SEODetail" id="SEOContent">
                    <?= $pageDescriptionSEO ?>
                </div>
            </div>
            <div class="toggle-more-info d-flex jcc pt3rem">
                <button class="toggle-button text-center w15" id="toggleButton">Xem thêm</button>
            </div>
        </div>
        <img class="pt3rem w100 pageImageBanner wow fadeInRight" data-wow-delay="300ms" src="../src/docs/images/imageSlides/<?= (!empty($imageSlides[2])) ? $imageSlides[2] : 'defaultSlide.jpg' ?>" alt="quảng bá thương hiệu việt"/>
    </div>
</section>
<script>
    const SEOContentWrapper = document.getElementById('SEOContentWrapper');
    const SEOContent = document.getElementById('SEOContent');
    const toggleButton = document.getElementById('toggleButton');

    const originalHeight = SEOContentWrapper.clientHeight;

    toggleButton.addEventListener('click', () => {
        const isExpanded = SEOContentWrapper.classList.contains('expanded');

        if (isExpanded) {
            SEOContentWrapper.style.height = `${SEOContent.scrollHeight}px`;
            requestAnimationFrame(() => {
                SEOContentWrapper.addEventListener('transitionend', () => {
                    toggleButton.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, {
                    once: true
                });
                SEOContentWrapper.style.height = `${originalHeight}px`;
            });
            toggleButton.textContent = 'Xem thêm';
        } else {
            const fullHeight = SEOContent.scrollHeight;
            SEOContentWrapper.style.height = `${fullHeight}px`;
            SEOContentWrapper.addEventListener('transitionend', () => {
                SEOContentWrapper.style.height = 'auto';
            }, {
                once: true
            });

            toggleButton.textContent = 'Thu gọn';
        }
        SEOContentWrapper.classList.toggle('expanded');
    });
</script>