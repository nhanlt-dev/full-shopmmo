<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['id']) && isset($_SESSION['userData']['id'])) {
        if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) {
            include("src/handlers/layouts/layoutContent/layoutSidebar.php"); ?>
            <div class="main-content w-100">
                <div class="main-content-inner style-3">
                    <div class="widget-box-2 wd-listing">
                        <div class="d-flex jcsb">
                            <h3 class="title">Lịch Sử Người Dùng</h3>
                            <div class="d-flex jcsb">
                                <?php if ($_SESSION['userData']['role'] == 3) { ?>
                                    <div class="box mr2rem">
                                        <a href="javascript:void(0);" class="whitecolor tf-btn bg-color-primary pd-10 " onclick="showConfirmAlert('Bạn có chắc chắn muốn xóa toàn bộ lịch sử này?', 'Admin/deleteall/users/historyuser/tai_khoan/');">
                                            <i class="fa-solid fa-delete-right"></i> Xoá Toàn Bộ
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="box">
                                    <a href="Admin/users/listuser/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Người Dùng</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="responsive-data-table" class="table">
                                        <thead>
                                            <tr>
                                                <th class="fw-6">#</th>
                                                <th class="fw-6">Người Thao Tác</th>
                                                <th class="fw-6">Hành Động</th>
                                                <th class="fw-6">Nội Dung</th>
                                                <th class="fw-6">Thời Gian</th>
                                                <?php if ($_SESSION['userData']['role'] == 3) { ?>
                                                    <th class="text-center fw-6">Hành Động</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $historyQuery = mysqli_query($link, "SELECT h.*, u.userName FROM histories AS h
                                                                                                   Inner Join users AS u ON u.id = h.idUser
                                                                                                   Where h.details LIKE '%tài khoản%'
                                                                                                   ORDER BY id DESC ");
                                            if (mysqli_num_rows($historyQuery) > 0) {
                                                $dataHistory = [];
                                                $sttHistory  =  0;
                                                while ($rowHistory = mysqli_fetch_object($historyQuery)) {
                                                    $idHistory     = $rowHistory->id;
                                                    $userName      = $rowHistory->userName;
                                                    $action        = $rowHistory->action;
                                                    $details       = $rowHistory->details;
                                                    $ipAddress     = $rowHistory->ipAddress;
                                                    $userAgent     = $rowHistory->userAgent;
                                                    $createdAt     = $rowHistory->createdAt;
                                                    $dateTime = new DateTime($createdAt);
                                                    $formattedDate = $dateTime->format('H:i:s d/m/Y');
                                                    $sttHistory++;
                                                    $dataHistory[] = compact('sttHistory', 'idHistory', 'userName', 'action', 'details', 'ipAddress', 'userAgent', 'formattedDate');
                                                }
                                                foreach ($dataHistory as $histories) { ?>
                                                    <tr class="datarow">
                                                        <td class="pl1-4rem">
                                                            <div class="h4rem">
                                                                <?= $histories['sttHistory'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= $histories['userName'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div><?= $histories['action'] ?></div>
                                                        </td>
                                                        <td>
                                                            <div><?= $histories['details'] ?></div>
                                                        </td>
                                                        <td>
                                                            <div><?= $histories['formattedDate'] ?></div>
                                                        </td>
                                                        <?php if ($_SESSION['userData']['role'] == 3) { ?>
                                                            <td>
                                                                <ul class="list-action">
                                                                    <li class="d-flex jcsa">
                                                                        <a href="javascript:void(0);" class="remove-file item" onclick="showConfirmAlert('Bạn có chắc chắn muốn xóa lịch sử này?', 'Admin/deletehistory/users/historyuser/<?= $histories['idHistory'] ?>/');">
                                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M9.82667 6.00035L9.596 12.0003M6.404 12.0003L6.17333 6.00035M12.8187 3.86035C13.0467 3.89501 13.2733 3.93168 13.5 3.97101M12.8187 3.86035L12.1067 13.1157C12.0776 13.4925 11.9074 13.8445 11.63 14.1012C11.3527 14.3579 10.9886 14.5005 10.6107 14.5003H5.38933C5.0114 14.5005 4.64735 14.3579 4.36999 14.1012C4.09262 13.8445 3.92239 13.4925 3.89333 13.1157L3.18133 3.86035M12.8187 3.86035C12.0492 3.74403 11.2758 3.65574 10.5 3.59568M3.18133 3.86035C2.95333 3.89435 2.72667 3.93101 2.5 3.97035M3.18133 3.86035C3.95076 3.74403 4.72416 3.65575 5.5 3.59568M10.5 3.59568V2.98501C10.5 2.19835 9.89333 1.54235 9.10667 1.51768C8.36908 1.49411 7.63092 1.49411 6.89333 1.51768C6.10667 1.54235 5.5 2.19901 5.5 2.98501V3.59568M10.5 3.59568C8.83581 3.46707 7.16419 3.46707 5.5 3.59568" stroke="#A3ABB0" stroke-linecap="round" stroke-linejoin="round" />
                                                                            </svg>
                                                                            <b>Xoá</b>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <p>Không có dữ liệu nào!</p>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay-dashboard"></div>
            </div>
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
        <?php }
    } else { ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Bạn chưa đăng nhập tài khoản!',
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
</div>