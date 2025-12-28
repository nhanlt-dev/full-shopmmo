<div class="page-layout">
    <?php
    include("src/handlers/layouts/layoutContent/layoutSidebar.php");
    if ((!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) || ($_SESSION['userData']['id'] == $id)) {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $resultGetInfomation = mysqli_query($link, "SELECT * FROM site WHERE id = 1");
        if ($resultGetInfomation) {
            $infoRow = mysqli_fetch_assoc($resultGetInfomation);
            if ($infoRow) {
                $titleSystem        = $infoRow['titleSystem'];
                $descriptionSystem  = $infoRow['descriptionSystem'];
                $imageLogoPage      = $infoRow['imageLogoPage'];
                $keywordSystem      = $infoRow['keywordSystem'];
                $phoneNumberSystem  = $infoRow['phoneNumberSystem'];
                $emailSystem        = $infoRow['emailSystem'];
                $customerSystem     = $infoRow['customerSystem'];
                $addressSystem      = $infoRow['addressSystem'];
                $companyName        = $infoRow['companyName'];
            }
        }

        if (isset($_POST['updateConfiguration'])) {
            validateCsrfToken($_POST['csrf_tokenConfiguration']);

            $dataConfiguration = [
                'titleSystem'       => $_POST['titleSystem']          ?? '',
                'descriptionSystem' => $_POST['descriptionSystem']    ?? '',
                'keywordSystem'     => $_POST['keywordSystem']        ?? '',
                'phoneNumberSystem' => $_POST['phoneNumberSystem']    ?? '',
                'emailSystem'       => $_POST['emailSystem']          ?? '',
                'customerSystem'    => $_POST['customerSystem']       ?? '',
                'addressSystem'     => $_POST['addressSystem']        ?? '',
                'companyName'       => $_POST['companyName']          ?? ''
            ];

            $updateQuery = "UPDATE site SET
                    titleSystem        = ?,
                    descriptionSystem  = ?,
                    keywordSystem      = ?,
                    phoneNumberSystem  = ?,
                    emailSystem        = ?,
                    customerSystem     = ?,
                    addressSystem      = ?,
                    companyName        = ?
                WHERE id               = 1";
            $resultUpdate = executeQuery($link, $updateQuery, array_values($dataConfiguration));
            if (!$resultUpdate) {
                handleError('Cập nhật không thành công: ' . mysqli_error($link));
            }

            uploadImageAndUpdate('imageLogoPage', $targetSystemsDir, $link, 'site', 1, 'imageLogoPage', $imageLogoPage, ['defaultLogo.jpg']);

            $actionHistories = 'Chỉnh sửa';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> hệ thống thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlert('Chỉnh sửa hệ thống thành công!', 'Admin/configuration/');
        }
    ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Chỉnh Sửa Thông Tin Hệ Thống</h3>
                        <div class="box">
                            <a href="Admin/configuration/historysystem/" class="whitecolor tf-btn bg-color-primary pd-10 mr2rem"><i class="fa-regular fa-clock-rotate-left"></i> </span> Lịch Sử</a>
                        </div>
                    </div>
                    <h5 class="title">Thông Tin Hệ Thống</h5>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-4 ">
                            <div class="box">
                                <label for="box-avatar">Logo Trang</label>
                                <div class="box-agent-avt" id="box-avatar">
                                    <div class="avatar borderR0">
                                        <img id="imageLogoPagePreview" class="avatar-img" src="src/docs/images/imageSystems/<?= (!empty($imageLogoPage)) ? $imageLogoPage : 'defaultLogo.jpg' ?>" alt="avatar" loading="lazy" width="128" height="128">
                                    </div>
                                    <div class="content uploadfile">
                                        <div class="box-ip">
                                            <input type="file" class="ip-file" id="imageLogoPage" name="imageLogoPage" accept=".png, .jpg, .jpeg">
                                        </div>
                                        <p class="file-requirements"><i>Logo Trang nên có kích thước 120x54 và ở định dạng .jpg hoặc .png</i></p>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="titleSystem">Tên Trang<span>*</span></label>
                                    <input type="text" id="titleSystem" name="titleSystem" value="<?= $titleSystem ?>" class="form-control ">
                                </div>
                                <div class="box">
                                    <div class="box-fieldset">
                                        <label for="customerSystem">Người Đại Diện<span>*</span></label>
                                        <input type="text" id="customerSystem" name="customerSystem" value="<?= $customerSystem ?>" class="form-control ">
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="emailSystem">Email Trang<span>*</span></label>
                                    <input type="text" id="emailSystem" name="emailSystem" value="<?= $emailSystem ?>" class="form-control">
                                </div>
                                <div class="box">
                                    <div class="box-fieldset">
                                        <label for="companyName">Tên Công Ty<span>*</span></label>
                                        <input type="text" id="companyName" name="companyName" value="<?= $companyName ?>" class="form-control ">
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="phoneNumberSystem">Số Điện Thoại<span>*</span></label>
                                    <input type="text" id="phoneNumberSystem" name="phoneNumberSystem" value="<?= $phoneNumberSystem ?>" class="form-control">
                                </div>
                                <div class="box">
                                    <div class="box-fieldset">
                                        <label for="addressSystem">Trụ Sở Công Ty<span>*</span></label>
                                        <input type="text" id="addressSystem" name="addressSystem" value="<?= $addressSystem ?>" class="form-control ">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="box grid-layout-2 ">
                            <div class="box-fieldset">
                                <label for="descriptionSystem">Mô Tả Trang<span>*</span></label>
                                <textarea class="form-control textareaFormControl" name="descriptionSystem" rows="4" id="descriptionSystem"><?= $descriptionSystem ?></textarea>
                            </div>
                            <div class="box-fieldset">
                                <label for="keywordSystem">Từ Khoá Trang<span>*</span></label>
                                <textarea class="form-control textareaFormControl" name="keywordSystem" rows="4" id="keywordSystem"><?= $keywordSystem ?></textarea>
                            </div>
                        </fieldset>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenConfiguration" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="updateConfiguration" class="tf-btn bg-color-primary pd-10">Lưu Thông Tin</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
<script>
    document.getElementById("imageLogoPage").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("imageLogoPagePreview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
<?php } else { ?>
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
<?php } ?>