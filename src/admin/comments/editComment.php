<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $idComment = $_GET['id'] ?? '';
        $resultComment = mysqli_query($link, "SELECT * FROM comments WHERE id = $idComment");
        if ($resultComment) {
            $commentRow = mysqli_fetch_assoc($resultComment);
            if ($commentRow) {
                $idUserCmt     = $commentRow['idUser'];
                $idNewsCmt     = $commentRow['idNews'];
                $ratingCmt     = $commentRow['ratingComment'];
                $statusCmt     = $commentRow['statusComment'];
                $contentCmt    = $commentRow['contentComment'];
            }
        }
        if (isset($_POST['editComment'])) {
            validateCsrfToken($_POST['csrf_tokenEditComment']);
            $dataParam  = [
                'idUserComment'     => $_POST['idUserComment']     ?? '',
                'idNewsComment'     => $_POST['idNewsComment']     ?? '',
                'ratingComment'     => $_POST['ratingComment']     ?:  5,
                'statusComment'     => $_POST['statusComment']     ?:  0,
                'contentComment'    => $_POST['contentComment']    ?? ''
            ];
            $updateQuery    = "UPDATE testimonials SET
                idUser          = ?,
                idNews          = ?,
                ratingComment   = ?,
                statusComment   = ?,
                contentComment  = ?
            WHERE id            = ?";
            executeQuery($link, $insertQuery, array_values($dataParam));
            $actionHistories = 'Chỉnh sửa';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> bình luận bài viết id \"<b>" . $dataParam['idNewsComment'] . "</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlert('Chỉnh sửa bình luận thành công!', 'Admin/comments/listcomment/');
        } ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Chỉnh Sửa Bình Luận</h3>
                        <div class="box">
                            <a href="Admin/reviews/listreview/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Bình Luận</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-4 ">
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="idUserComment">Người Bình Luận<span>*</span></label>
                                    <select name="idUserComment" id="idUserComment" class="form-control formSelect">
                                        <?php $userQuery = mysqli_query($link, "SELECT * FROM users Where userRole NOT IN (2,3) ORDER BY id ASC");
                                        while ($rowUser = mysqli_fetch_array($userQuery)) {
                                            $idUserComment      = $rowUser['id'];
                                            $nameUserComment    = $rowUser['userName']; ?>
                                            <option value="<?= $idUserComment ?>" <?= ($idUserComment == $idUserCmt) ? 'selected' : '' ?>><?= $nameUserComment ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="idNewsComment">Bài Viết Bình Luận<span>*</span></label>
                                    <select name="idNewsComment" id="idNewsComment" class="form-control formSelect">
                                        <?php $newsQuery = mysqli_query($link, "SELECT * FROM news ORDER BY id ASC");
                                        while ($rowNews = mysqli_fetch_array($newsQuery)) {
                                            $idNewsComment    = $rowNews['id'];
                                            $titleNewsComment = $rowNews['newsTitle']; ?>
                                            <option value="<?= $idNewsComment ?>" <?= ($idNewsComment == $idNewsCmt) ? 'selected' : '' ?>><?= $titleNewsComment ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="TestimonialRating">Đánh Giá<span>*</span></label>
                                    <div class="ec-t-review-rating d-flex jcc" id="TestimonialRating">
                                        <?php
                                        for ($j = 1; $j <= 5; $j++) {
                                            if ($j <= $ratingCmt) { ?>
                                                <i class="fa-solid fa-star clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                            <?php } elseif ($j - 0.5 == $ratingCmt) { ?>
                                                <i class="fa-duotone fa-star-sharp-half clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                            <?php } else { ?>
                                                <i class="fa-duotone fa-star rvstartEdit" data-value="<?= $j ?>"></i>
                                        <?php }
                                        } ?>
                                    </div>
                                    <input type="hidden" name="ratingComment" id="selected-rating" value="<?= $ratingCmt ?>">
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="statusComment">Trạng Thái<span>*</span></label>
                                    <select name="statusComment" id="statusComment" class="form-control formSelect">
                                        <option value="0" <?= ($statusCmt == 0) ? 'selected' : '' ?>>Ẩn</option>
                                        <option value="1" <?= ($statusCmt == 1) ? 'selected' : '' ?>>Hiện</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <div class="box box-fieldset">
                            <label for="contentComment">Nội Dung Bài Viết<span>*</span></label>
                            <textarea class="form-control" name="contentComment" rows="4" id="contentComment"><?= $contentCmt ?></textarea>
                        </div>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenEditComment" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="editComment" class="tf-btn style-border pd-10">Cập Nhật</button>
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
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var editornews = CKEDITOR.replace('contentComment', {
            uiColor: '#e7e7e7',
            language: 'en',
            skin: 'moono',
            width: 'auto',
            height: 350,
            filebrowserImageBrowseUrl: '/src/library/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl: '/src/library/ckfinder/ckfinder.html?Type=Flash',
            filebrowserImageUploadUrl: '/src/library/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl: '/src/library/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
        });
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