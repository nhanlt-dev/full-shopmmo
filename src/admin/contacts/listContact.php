<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");
    ?>
        <div class="main-content w-100">
            <div class="main-content-inner style-3">
                <div class="widget-box-2 wd-listing">
                    <div class="d-flex jcsb">
                        <h3 class="title">Danh Sách Liên Hệ</h3>
                        <div class="d-flex jcsb">
                            <?php if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) : ?>
                                <div class="box">
                                    <a href="Admin/contacts/historycontact/" class="whitecolor tf-btn bg-color-primary pd-10 mr2rem"><i class="fa-regular fa-clock-rotate-left"></i> </span> Lịch Sử</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="responsive-data-table" class="table">
                                    <thead>
                                        <tr>
                                            <th class=" fw-6">#</th>
                                            <th class=" fw-6">Tên khách hàng</th>
                                            <th class=" fw-6">Email Khách Hàng</th>
                                            <th class=" fw-6">Số Liên Lạc</th>
                                            <th class=" fw-6">Loại Hỗ Trợ</th>
                                            <th class=" fw-6">Ngày Nhận</th>
                                            <th class=" text-center fw-6">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contactQuery = mysqli_query($link, "SELECT * FROM contacts ORDER BY id DESC ");
                                        if (mysqli_num_rows($contactQuery) > 0) {
                                            $dataContact = [];
                                            $sttContact  =  0;
                                            while ($rowContact = mysqli_fetch_object($contactQuery)) {
                                                $idContact       = $rowContact->id;
                                                $userName        = $rowContact->userName;
                                                $userEmail       = $rowContact->userEmail;
                                                $userPhoneNumber = $rowContact->userPhoneNumber;
                                                $supportType     = $rowContact->supportType;
                                                $contentContact  = $rowContact->contentContact;
                                                $createdAt       = $rowContact->createAt;
                                                $dateTime = new DateTime($createdAt, new DateTimeZone('Asia/Saigon'));
                                                $formattedDate = $dateTime->format('H:i:s d/m/Y');
                                                $sttContact++;
                                                $dataContact[] = compact('sttContact', 'idContact', 'userName', 'userEmail', 'userPhoneNumber', 'supportType', 'contentContact', 'formattedDate');
                                                $services = ['Khách Hàng', 'Nhân Viên', 'Admin', 'Super Admin'];
                                            }
                                            foreach ($dataContact as $contacts) {
                                        ?>
                                                <tr class="datarow">
                                                    <td class="pl1-4rem">
                                                        <div class="h4rem"><?= $contacts['sttContact'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $contacts['userName'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $contacts['userEmail'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $contacts['userPhoneNumber'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $contacts['supportType'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $contacts['formattedDate'] ?></div>
                                                    </td>
                                                    <td>
                                                        <ul class="list-action">
                                                            <li class="d-flex jcsa">
                                                                <a href="Admin/contacts/viewcontact/<?= $contacts['idContact'] ?>/" class="edit-file item"><i class="fa-regular fa-eye"></i> <b>Xem</b></a>
                                                                <a href="javascript:void(0);" class="remove-file item" onclick="showConfirmAlert('Bạn có chắc chắn muốn xóa liên hệ này?', 'Admin/contacts/deletecontact/<?= $contacts['idContact'] ?>/');">
                                                                    <i class="fa-regular fa-trash-can"></i> <b>Xoá</b>
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