<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $id = $_GET['id'] ?? '';
        $result = mysqli_query($link, "SELECT * FROM services WHERE id = $id");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $serviceName        = $row['serviceName'];
                $servicePropose     = $row['servicePropose'];
                $serviceContent     = $row['serviceContent'];
            }
        }
        if (isset($_POST['editService'])) {
            // validateCsrfToken($_POST['csrf_tokenEditService']);
            $dataParam  = [
                'serviceName'         => $_POST['serviceName']         ?? '',
                'servicePropose'      => $_POST['servicePropose']      ?? '',
                'serviceContent'      => $_POST['serviceContent']      ?? '',
                'serviceUrl'          => strtolower(noAccent(str_replace("'", "", $_POST['serviceName'])))
            ];

            $updateQuery = "UPDATE services SET
                serviceName         = ?,
                servicePropose      = ?,
                serviceContent      = ?,
                serviceUrl          = ?
            WHERE id                = ?";
            if (!executeQuery($link, $updateQuery, array_merge(array_values($dataParam), [$id]))) {
                showErrorAlert('Error', 'Database đã có vấn đề!');
            }
            $actionHistories = 'Chỉnh sửa';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> dịch vụ \"<b>" . $dataParam['serviceName'] . "</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlert('Chỉnh sửa dịch vụ thành công!', 'Admin/services/listservice/');
        } ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Thêm Mới Dịch Vụ</h3>
                        <div class="box">
                            <a href="Admin/services/listservice/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Dịch Vụ</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box">
                            <div class="box box-fieldset">
                                <fieldset class="box grid-layout-2">
                                    <div class="box box-fieldset">
                                        <label for="serviceName">Tiêu Đề Dịch Vụ<span>*</span></label>
                                        <input type="text" id="serviceName" name="serviceName" class="form-control " value="<?= $serviceName ?>">
                                    </div>
                                    <div class="box box-fieldset">
                                        <label for="servicePropose">Đề Xuất Dịch Vụ<span>*</span></label>
                                        <input type="text" id="servicePropose" name="servicePropose" class="form-control " value="<?= $servicePropose ?>">
                                    </div>
                                </fieldset>
                            </div>
                        </fieldset>
                        <fieldset class="box">
                            <div class="box box-fieldset">
                                <label for="serviceContent">Nội Dung Dịch Vụ<span>*</span></label>
                                <textarea class="form-control" name="serviceContent" rows="4" id="serviceContent"><?= $serviceContent ?></textarea>
                            </div>
                        </fieldset>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenEditService" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="editService" class="tf-btn style-border pd-10">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var editornews = CKEDITOR.replace('serviceContent', {
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