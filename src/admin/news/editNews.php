<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $idNews = $_GET['id'] ?? '';
        $resultEditNews = mysqli_query($link, "SELECT * FROM news WHERE id = $idNews");
        if ($resultEditNews) {
            $newsRow = mysqli_fetch_assoc($resultEditNews);
            if ($newsRow) {
                $newsTitle          = $newsRow['newsTitle'];
                $newsKeyword1       = $newsRow['newsKeyword1'];
                $newsKeyword2       = $newsRow['newsKeyword2'];
                $newsImage          = $newsRow['newsImage'];
                $newsContent        = $newsRow['newsContent'];
                $newsDescription    = $newsRow['newsDescription'];
            }
        }
        if (isset($_POST['editNews'])) {
            validateCsrfToken($_POST['csrf_tokenEditNews']);
            $dataParam = [
                'newsTitle'       => $_POST['newsTitle']       ?? '',
                'newsKeyword1'    => $_POST['newsKeyword1']    ?? '',
                'newsKeyword2'    => $_POST['newsKeyword2']    ?? '',
                'newsDescription' => $_POST['newsDescription'] ?? '',
                'newsContent'     => $_POST['newsContent']     ?? '',
                'newsUrl'         => strtolower(noAccent(str_replace("'", "", $_POST['newsTitle'])))
            ];
            $updateQuery = "UPDATE news SET
                newsTitle       = ?,
                newsKeyword1    = ?,
                newsKeyword2    = ?,
                newsDescription = ?,
                newsContent     = ?,
                newsUrl         = ?
            WHERE id            = ?";
            $resultUpdate = executeQuery($link, $updateQuery, array_merge(array_values($dataParam), [$idNews]));
            if (!$resultUpdate) {
                handleError('Cập nhật không thành công!');
            }
            uploadImageAndUpdate('imageNews', $targetNewsDir, $link, 'news', $idNews, 'newsImage', $newsImage, ['defaultNews.jpg']);

            $actionHistories = 'Chỉnh sửa';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> bài viết \"<b>" . $dataParam['newsTitle'] . "</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            // showSuccessUpdateAlert('Chỉnh sửa bài viết thành công!', 'Admin/news/listnews/');
        } ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Chỉnh Sửa Bài Viết</h3>
                        <?php 
                        if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [1, 2, 3])) : ?>
                            <div class="box">
                                <a href="Admin/news/listnews/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Tin Tức</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-3 ">
                            <div class="box-house hover-img ">
                                <div class="image-wrap">
                                    <a href="javascript: void(0)">
                                        <img class="lazyload" id="imageNews-preview"
                                            data-src="src/docs/images/imageNews/<?= (!empty($newsImage)) ?   $newsImage : 'defaultNews.jpg' ?>"
                                            src="src/docs/images/imageNews/<?= (!empty($newsImage)) ? $newsImage : 'defaultNews.jpg' ?>" alt="imageNews">
                                    </a>
                                    <div class="list-btn flex gap-8">
                                        <label for="imageNews" class="btn-icon find hover-tooltip">
                                            <i class="fa-light fa-pen-to-square"></i>
                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                        </label>
                                        <input type="file" name="imageNews" id="imageNews" class="ec-image-upload imageNews hidden" accept=".png, .jpg, .jpeg" />
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="newsTitle">Tiêu Đề Bài Viết<span>*</span></label>
                                    <input type="text" id="newsTitle" name="newsTitle" value="<?= $newsTitle ?>" class="form-control ">
                                </div>
                                <div class="box box-fieldset">
                                    <label for="newsKeyword1">Từ Khoá Bài Viết 1<span>*</span></label>
                                    <input type="text" id="newsKeyword1" name="newsKeyword1" value="<?= $newsKeyword1 ?>" class="form-control ">
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="newsDescription">Mô Tả Bài Viết <i>(180 - 300 kí tự)</i><span>*</span></label>
                                    <input type="text" id="newsDescription" name="newsDescription" value="<?= $newsDescription ?>" class="form-control ">
                                </div>
                                <div class="box box-fieldset">
                                    <label for="newsKeyword2">Từ Khoá Bài Viết 2<span>*</span></label>
                                    <input type="text" id="newsKeyword2" name="newsKeyword2" value="<?= $newsKeyword2 ?>" class="form-control ">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="box ">
                            <div class="box box-fieldset">
                                <label for="newsContent">Nội Dung Bài Viết<span>*</span></label>
                                <textarea class="form-control" name="newsContent" rows="4" id="newsContent"><?= $newsContent ?></textarea>
                            </div>
                        </fieldset>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenEditNews" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="editNews" class="tf-btn style-border pd-10">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>

<script>
    document.getElementById("imageNews").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("imageNews-preview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var editornews = CKEDITOR.replace('newsContent', {
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