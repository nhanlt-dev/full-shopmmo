<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $id = $_GET['id'] ?? '';
        $resultOrder = mysqli_query($link, "SELECT o.*, u.userName, p.pageName FROM orders AS o
                                                        Inner Join users AS u ON u.id = o.idUser
                                                        Inner Join pages AS p ON p.id = o.idPage
                                                        WHERE o.id = $id");
        if ($resultOrder) {
            $orderRow = mysqli_fetch_assoc($resultOrder);
            if ($orderRow) {
                $idUserQuery    = $orderRow['idUser'];
                $idPageQuery    = $orderRow['idPage'];
                $idServiceQuery = $orderRow['idService'];
                $timeService    = $orderRow['timeService'];
                $priceService   = $orderRow['priceService'];
                $timeOrder      = $orderRow['timeOrder'];
                $noteOrder      = $orderRow['noteOrder'];
                $userName       = $orderRow['userName'];
                $pageName       = $orderRow['pageName'];
            }
        }
        if (isset($_POST['editOrder'])) {
            validateCsrfToken($_POST['csrf_tokenEditOrder']);
            $dataParam   = [
                'idUserOrder'       => $_POST['idUserOrder']        ?? '',
                'idServiceOrder'    => $_POST['idServiceOrder']     ?? '',
                'timeService'       => $_POST['timeService']        ??  1,
                'idPageOrder'       => $_POST['idPageOrder']        ?? '',
                'priceService'      => $_POST['priceService']       ??  0,
                'timeOrder'         => $_POST['timeOrder']          ?? '',
                'noteOrder'         => $_POST['noteOrder']          ?? ''
            ];
            $updateQuery        = "UPDATE orders SET
                idUser          = ?,
                idService       = ?,
                timeService     = ?,
                idPage          = ?,
                priceService    = ?,
                timeOrder       = ?,
                noteOrder       = ?
            WHERE id            = ?";
            if(!executeQuery($link, $updateQuery, array_merge(array_values($dataParam), [$id]))){
                showErrorAlertDirection('Chỉnh Sửa Đơn Hàng Thất Bại', 'Có lỗi trong quá trình đẩy dữ liệu!', "Admin/orders/editorder/$id");
            }
            $actionHistories = 'Chỉnh sửa';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> đơn hàng thương hiệu \"<b>" . $pageName . "</b>\" cho khách hàng \"<b>" . $userName . "</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessInsertAlert('Chỉnh sửa đơn hàng thành công!', 'Admin/orders/listorder/');
        } ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Chỉnh Sửa Đơn Hàng</h3>
                        <div class="box">
                            <a href="Admin/orders/listorder/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Đơn Hàng</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-2 ">
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="idUserOrder">Tên Khách Hàng<span>*</span></label>
                                    <select name="idUserOrder" id="idUserOrder" class="form-control formSelect">
                                        <?php $userQuery = mysqli_query($link, "SELECT * FROM users Where userRole = 0 ORDER BY id ASC");
                                        while ($rowUser = mysqli_fetch_array($userQuery)) {
                                            $idUserOrder      = $rowUser['id'];
                                            $nameUserOrder    = $rowUser['userName']; ?>
                                            <option value="<?= $idUserOrder ?>" <?= ($idUserQuery == $idUserOrder)? 'selected' : '' ?>><?= $nameUserOrder ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <fieldset class="box box-fieldset grid-layout-2">
                                    <div class="box box-fieldset">
                                        <label for="idServiceOrder">Gói Dịch Vụ<span>*</span></label>
                                        <select name="idServiceOrder" id="idServiceOrder" class="form-control formSelect">
                                            <?php $serviceQuery = mysqli_query($link, "SELECT * FROM services ORDER BY id ASC");
                                            while ($rowService  = mysqli_fetch_array($serviceQuery)) {
                                                $idServiceOrder      = $rowService['id'];
                                                $nameServiceOrder    = $rowService['serviceName']; ?>
                                                <option value="<?= $idServiceOrder ?>"<?= ($idServiceQuery == $idServiceOrder)? 'selected' : '' ?>><?= $nameServiceOrder ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="box box-fieldset">
                                        <label for="timeService">Thời Hạn<span>*</span></label>
                                        <select name="timeService" id="timeService" class="form-control formSelect">
                                           <option value="1" <?= ($timeService == 1)? 'selected' : '' ?>>1 Năm</option>
                                           <option value="2" <?= ($timeService == 2)? 'selected' : '' ?>>2 Năm</option>
                                           <option value="3" <?= ($timeService == 3)? 'selected' : '' ?>>3 Năm</option>
                                           <option value="4" <?= ($timeService == 4)? 'selected' : '' ?>>4 Năm</option>
                                           <option value="5" <?= ($timeService == 5)? 'selected' : '' ?>>5 Năm</option>
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box box-fieldset">
                                    <label for="idPageOrder">Tên Thương Hiệu<span>*</span></label>
                                    <select name="idPageOrder" id="idPageOrder" class="form-control formSelect">
                                        <?php $pageQuery = mysqli_query($link, "SELECT * FROM pages ORDER BY id ASC");
                                        while ($rowPage  = mysqli_fetch_array($pageQuery)) {
                                            $idPageOrder      = $rowPage['id'];
                                            $namePageOrder    = $rowPage['pageName']; ?>
                                            <option value="<?= $idPageOrder ?>"<?= ($idPageQuery == $idPageOrder)? 'selected' : '' ?>><?= $namePageOrder ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <fieldset class="box box-fieldset grid-layout-2">
                                    <div class="box box-fieldset">
                                        <label for="priceService">Chi Phí Dịch Vụ<span>*</span></label>
                                        <input type="number" id="priceService" name="priceService" class="form-control" value="<?= $priceService ?>">
                                    </div>
                                    <div class="box box-fieldset">
                                        <div class="box-fieldset">
                                            <label for="timeOrder">Ngày Đặt Đơn<span></span></label>
                                            <input type="datetime-local" name="timeOrder" id="timeOrder" class="form-control" value="<?= $timeOrder ?>">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </fieldset>
                        <div class="box box-fieldset">
                            <label for="noteOrder">Ghi Chú Đơn Hàng<span>*</span></label>
                            <textarea class="form-control textareaFormControl" name="noteOrder" rows="4" id="noteOrder"><?= $noteOrder ?></textarea>
                        </div>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenEditOrder" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="editOrder" class="tf-btn style-border pd-10">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
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