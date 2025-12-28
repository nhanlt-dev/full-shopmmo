<section id="home" class="position-relative mainSlide">
    <div id="carouselExampleControls" class="carousel slide" data-mdb-ride="carousel">
        <div class="carousel-inner">
            <?php for ($i = 0; $i <= 2; $i++): ?>
                <div class="carousel-item <?= ($i == 0)? 'active' : '' ?>">
                    <img src="../src/docs/images/imageSlides/<?= (!empty($imageSlides[$i])) ? $imageSlides[$i] : 'defaultSlide.jpg' ?>" class="d-block w-100" alt="quảng bá thương hiệu việt" />
                </div>
            <?php endfor; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
  </section> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mdbootstrap@5.1.0/dist/js/mdb.min.js"></script>

<script>
    const carousel = new bootstrap.Carousel('#carouselExampleControls', {
        interval: 5000,  
        ride: 'carousel' 
    });
</script>