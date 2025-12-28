<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        if (isset($_POST['addPartner'])) {
            validateCsrfToken($_POST['csrf_tokenAddPartner']);
            $defaultName  = 'DemoPartner_' . date('Ymd_His');
            $namePartner  = mysqli_real_escape_string($link, $_POST['namePartner'])        ?: $defaultName;

            $insertQuery = "INSERT INTO partners (`namePartner`) VALUES (?)";
            $idPartner = executeQuery($link, $insertQuery, [$namePartner], true);
            uploadImageAndUpdate('imagePartner', $targetPartnersDir, $link, 'partners', $idPartner, 'imagePartner', NULL, ['imagePartner']);

            $actionHistories =  'Thêm mới';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> đối tác \"<b>$namePartner</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessInsertAlert('Thêm mới đối tác thành công!', 'Admin/partners/listpartner/');
        } ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Thêm Mới Đối Tác</h3>
                        <div class="box">
                            <a href="Admin/partners/listpartner/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Đối Tác</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-3 ">
                            <div class="box-house hover-img ">
                                <div class="image-wrap">
                                    <a href="javascript: void(0)">
                                        <img class="lazyload" id="imagePartners-preview" data-src="src/docs/images/imagePartners/defaultPartner.jpg" src="src/docs/images/imagePartners/defaultPartner.jpg" alt="imagePartners">
                                    </a>
                                    <div class="list-btn flex gap-8">
                                        <label for="imagePartners" class="btn-icon find hover-tooltip">
                                            <i class="fa-light fa-pen-to-square"></i>
                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                        </label>
                                        <input type="file" name="imagePartner" id="imagePartners" class="ec-image-upload imagePartner hidden" accept=".png, .jpg, .jpeg" />
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="namePartner">Tên Đối Tác<span>*</span></label>
                                    <input type="text" id="namePartner" name="namePartner" class="form-control ">
                                </div>
                                <div class="box-btn mt4r">
                                    <input type="hidden" name="csrf_tokenAddPartner" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                    <button type="submit" name="addPartner" class="tf-btn style-border pd-10">Thêm Mới</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>

<script>
    document.getElementById("imagePartners").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("imagePartners-preview").src = e.target.result;
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