<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        if (isset($_POST['addReview'])) {
            validateCsrfToken($_POST['csrf_tokenAddReview']);
            $defaultName = 'DemoReview_' . date('Ymd_His');
            $dataParam  = [
                'nameTestimonial'    => $_POST['nameTestimonial']    ?: $defaultName,
                'jobTestimonial'     => $_POST['jobTestimonial']     ?? '',
                'ratingTestimonial'  => $_POST['ratingTestimonial']  ?:  5,
                'contentTestimonial' => $_POST['contentTestimonial'] ?? ''
            ];
            $insertQuery    = "INSERT INTO testimonials (`nameTestimonial`, `jobTestimonial`, `ratingTestimonial`, `contentTestimonial`) VALUES (?,?,?,?)";
            $idTestimonial  = executeQuery($link, $insertQuery, array_values($dataParam), true);
            uploadImageAndUpdate('imageTestimonial', $targetTestimonialsDir, $link, 'testimonials', $idTestimonial, 'imageTestimonial', NULL, ['defaultTestimonial.jpg']);

            $actionHistories = 'Thêm mới';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> đánh giá \"<b>" . $dataParam['nameTestimonial'] . "</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessInsertAlert('Thêm mới đánh giá thành công!', 'Admin/reviews/listreview/');
        } ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Thêm Mới Đánh Giá</h3>
                        <div class="box">
                            <a href="Admin/reviews/listreview/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Đánh Giá</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-3 ">
                            <div class="box-house hover-img ">
                                <div class="image-wrap">
                                    <a href="javascript: void(0)">
                                        <img class="lazyload" id="imageTestimonials-preview" data-src="src/docs/images/imageTestimonials/defaultTestimonial.jpg" src="src/docs/images/imageTestimonials/defaultTestimonial.jpg" alt="imageTestimonial">
                                    </a>
                                    <div class="list-btn flex gap-8">
                                        <label for="imageTestimonials" class="btn-icon find hover-tooltip">
                                            <i class="fa-light fa-pen-to-square"></i>
                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                        </label>
                                        <input type="file" name="imageTestimonial" id="imageTestimonials" class="ec-image-upload imageTestimonial hidden" accept=".png, .jpg, .jpeg" />
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="nameTestimonial">Người Đánh Giá<span>*</span></label>
                                    <input type="text" id="nameTestimonial" name="nameTestimonial" class="form-control ">
                                </div>

                                <div class="box box-fieldset">
                                    <label for="jobTestimonial">Đánh Giá<span>*</span></label>
                                    <div class="ec-t-review-rating d-flex jcc" id="TestimonialRating">
                                        <?php
                                        for ($j = 1; $j <= 5; $j++) { ?>
                                            <i class="fa-solid fa-star clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" name="ratingTestimonial" id="selected-rating">
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="jobTestimonial">Công Việc<span>*</span></label>
                                    <input type="text" id="jobTestimonial" name="jobTestimonial" class="form-control ">
                                </div>
                            </div>
                        </fieldset>
                        <div class="box box-fieldset">
                            <label for="contentTestimonial">Nội Dung Đánh Giá<span>*</span></label>
                            <textarea class="form-control textareaFormControl" name="contentTestimonial" rows="4" id="contentTestimonial"></textarea>
                        </div>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenAddReview" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="addReview" class="tf-btn style-border pd-10">Thêm Mới</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const stars = document.querySelectorAll(`#TestimonialRating .rvstartEdit`);
        const selectedRatingInput = document.getElementById(`selected-rating`);
        let lastClickTime = 0;
        let lastClickedStar = null;
        let lastHalfStar = null;

        stars.forEach(star => {
            star.addEventListener("click", function() {
                const currentTime = new Date().getTime();
                const ratingValue = parseInt(star.getAttribute("data-value"));
                if (lastClickedStar === star && currentTime - lastClickTime < 300) {
                    selectedRatingInput.value = ratingValue - 0.5;
                    star.classList.remove("fa-star");
                    star.classList.add("fa-duotone", "fa-star-sharp-half");
                    lastHalfStar = star;
                } else {
                    selectedRatingInput.value = ratingValue;
                    stars.forEach(s => s.classList.remove("fa-duotone", "fa-star-sharp-half", "fa-solid", "clstart"));
                    stars.forEach((s, index) => {
                        if (index < ratingValue) {
                            s.classList.add("fa-solid", "clstart", "fa-star");
                        } else {
                            s.classList.add("fa-duotone", "fa-solid", "clstart", "fa-star");
                        }
                    });
                    if (lastHalfStar) {
                        lastHalfStar.classList.remove("fa-star-sharp-half");
                        lastHalfStar.classList.add("fa-star");
                        lastHalfStar = null;
                    }
                }

                lastClickTime = currentTime;
                lastClickedStar = star;
            });
        });
    });
</script>
<script>
    document.getElementById("imageTestimonials").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("imageTestimonials-preview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
<?php else: ?>
    <script>
        Swal.fire({
            title: '403 Forbidden!',
            text: 'Bạn không có quyền hạn để truy cập trang này!',
            icon: 'error',
            confirmButtonText: 'Thử lại',
            timer: 5000,
            timerProgressBar: true,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '#modalLogin';
            }
        });
        setTimeout(() => {
            window.location.href = '#modalLogin';
        }, 5000);
    </script>
<?php endif; ?>