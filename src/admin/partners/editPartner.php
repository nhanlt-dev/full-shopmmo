<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $idPartner = mysqli_real_escape_string($link, isset($_GET['id']) ? $_GET['id'] : '');
        $resultEditPartner = mysqli_query($link, "SELECT * FROM partners WHERE id = '$idPartner'");
        if ($resultEditPartner) {
            $partnerRow = mysqli_fetch_assoc($resultEditPartner);
            if ($partnerRow) {
                $namePartner  = $partnerRow['namePartner'];
                $imagePartner = $partnerRow['imagePartner'];
            }
        }
        if (isset($_POST['editPartner'])) {
            validateCsrfToken($_POST['csrf_tokenEditPartner']);
            $namePartner         = mysqli_real_escape_string($link, $_POST['namePartner'])  ?? '';

            $updatePartnerQuery  = "UPDATE partners SET namePartner = ? WHERE id = ?";
            $updateDataParams    = array_merge(array_values([$namePartner]), [$idPartner]);
            if (!executeQuery($link, $updatePartnerQuery, $updateDataParams)) {
                handleError('Cập nhật thông tin đối tác không thành công!');
            }
            uploadImageAndUpdate('imagePartner', $targetPartnersDir, $link, 'partners', $idPartner,  'imagePartner', $imagePartner, ['defaultPartner.jpg']);
            $actionHistories = 'Chỉnh sửa';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> đối tác \"<b>$namePartner</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlert('Cập nhật đối tác thành công!', 'Admin/partners/listpartner/');
        } ?>

        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Chỉnh Sửa Đối Tác</h3>
                        <div class="box">
                            <a href="Admin/partners/listpartner/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Đối Tác</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-3 ">
                            <div class="box-house hover-img ">
                                <div class="image-wrap">
                                    <a href="javascript: void(0)">
                                        <img class="lazyload" id="imagePartners-preview"
                                            data-src="src/docs/images/imagePartners/<?= (!empty($imagePartner)) ?   $imagePartner : 'defaultPartner.jpg' ?>"
                                            src="src/docs/images/imagePartners/<?= (!empty($imagePartner)) ? $imagePartner : 'defaultPartner.jpg' ?>" alt="imagePartners">
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
                                    <input type="text" id="namePartner" name="namePartner" value="<?= $namePartner ?>" class="form-control ">
                                </div>
                                <div class="box-btn mt4r">
                                    <input type="hidden" name="csrf_tokenEditPartner" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                    <button type="submit" name="editPartner" class="tf-btn style-border pd-10">Cập Nhật</button>
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