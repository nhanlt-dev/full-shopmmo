<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
    ?>
        <div class="main-content w-100">
            <div class="main-content-inner style-3">
                <div class="widget-box-2 wd-listing">
                    <div class="d-flex jcsb">
                        <h3 class="title">Danh Sách Người Dùng</h3>
                        <div class="d-flex jcsb">
                            <?php if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) : ?>
                                <div class="box">
                                    <a href="Admin/users/historyuser/" class="whitecolor tf-btn bg-color-primary pd-10 mr2rem"><i class="fa-regular fa-clock-rotate-left"></i> </span> Lịch Sử</a>
                                </div>
                            <?php endif; ?>
                            <div class="box">
                                <a href="Admin/users/adduser/" class="whitecolor tf-btn bg-color-primary pd-10"><span class="icon icon-plus"></span> Thêm Người Dùng</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="responsive-data-table" class="table">
                                    <thead>
                                        <tr>
                                            <th class=" fw-6">#</th>
                                            <th class=" fw-6">Tên Khách Hàng</th>
                                            <th class=" fw-6">Phân Quyền</th>
                                            <th class=" fw-6">Email</th>
                                            <th class=" fw-6">Số Điện Thoại</th>
                                            <th class=" text-center fw-6">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $currentDateTime = new DateTime();
                                        $userQuery = mysqli_query($link, "SELECT * FROM users ORDER BY id DESC ");
                                        if (mysqli_num_rows($userQuery) > 0) {
                                            $dataUser = [];
                                            $sttUser  =  0;
                                            while ($rowUser = mysqli_fetch_object($userQuery)) {
                                                $idUser             = $rowUser->id;
                                                $userName           = $rowUser->userName;
                                                $userRole           = $rowUser->userRole;
                                                $userEmail          = $rowUser->userEmail;
                                                $userNumberPhone    = $rowUser->userNumberPhone;
                                                $userStatus         = $rowUser->userStatus;
                                                $sttUser++;
                                                $dataUser[] = compact('sttUser', 'idUser', 'userName', 'userRole', 'userStatus', 'userEmail', 'userNumberPhone');
                                                $roles = ['Khách Hàng', 'Nhân Viên', 'Admin', 'Super Admin'];
                                            }
                                            foreach ($dataUser as $user) {
                                                if ($_SESSION['userData']['role'] == 2 && $user['userRole'] >= 2 && $user['idUser'] != $_SESSION['userData']['id']) {
                                                    continue;
                                                }
                                        ?>
                                                <tr class="datarow">
                                                    <td class="pl1-4rem">
                                                        <div class="h4rem"><?= $user['sttUser'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $user['userName'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $roles[$user['userRole']] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $user['userEmail'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $user['userNumberPhone'] ?></div>
                                                    </td>
                                                    <td>
                                                        <ul class="list-action">
                                                            <li class="d-flex jcsa">
                                                                <a href="Admin/users/edituser/<?= $user['idUser'] ?>/" class="edit-file item">
                                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M12.5 1.5L14.5 3.5M13.5 0.5L15.5 2.5M1 15H5L14.5 5.5L10.5 1.5L1 11V15Z" stroke="#A3ABB0" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                    <b>Sửa</b>
                                                                </a>
                                                                <a href="javascript:void(0);" class="remove-file item" onclick="showConfirmAlert('Bạn có chắc chắn muốn xóa người dùng này?', 'Admin/users/deleteuser/<?= $user['idUser'] ?>/');">
                                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M9.82667 6.00035L9.596 12.0003M6.404 12.0003L6.17333 6.00035M12.8187 3.86035C13.0467 3.89501 13.2733 3.93168 13.5 3.97101M12.8187 3.86035L12.1067 13.1157C12.0776 13.4925 11.9074 13.8445 11.63 14.1012C11.3527 14.3579 10.9886 14.5005 10.6107 14.5003H5.38933C5.0114 14.5005 4.64735 14.3579 4.36999 14.1012C4.09262 13.8445 3.92239 13.4925 3.89333 13.1157L3.18133 3.86035M12.8187 3.86035C12.0492 3.74403 11.2758 3.65574 10.5 3.59568M3.18133 3.86035C2.95333 3.89435 2.72667 3.93101 2.5 3.97035M3.18133 3.86035C3.95076 3.74403 4.72416 3.65575 5.5 3.59568M10.5 3.59568V2.98501C10.5 2.19835 9.89333 1.54235 9.10667 1.51768C8.36908 1.49411 7.63092 1.49411 6.89333 1.51768C6.10667 1.54235 5.5 2.19901 5.5 2.98501V3.59568M10.5 3.59568C8.83581 3.46707 7.16419 3.46707 5.5 3.59568" stroke="#A3ABB0" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                    <b>Xoá</b>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
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
</div>