<div class="page-layout">
    <?php
    $id = $_GET['id'] ?? '';
    if ((!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3]))) {
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $resultViewContact = mysqli_query($link, "SELECT * FROM contacts WHERE id = $id");
        if ($resultViewContact) {
            $contactRow = mysqli_fetch_assoc($resultViewContact);
            if ($contactRow) {
                $userName        = $contactRow['userName'];
                $userEmail       = $contactRow['userEmail'];
                $userPhoneNumber = $contactRow['userPhoneNumber'];
                $supportType     = $contactRow['supportType'];
                $contentContact  = $contactRow['contentContact'];
                $createAt        = $contactRow['createAt'];
                $dateTime        = new DateTime($createdAt);
                $formattedDate   = $dateTime->format('H:i:s d/m/Y');
            }
        }
    ?>
        <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Xem Liên Hệ</h3>
                        <div class="box">
                            <a href="Admin/contacts/listcontact/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Liên Hệ</a>
                        </div>
                    </div>
                    <h5 class="title">Liên Hệ Của Khách Hàng</h5>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-2 ">
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="userName">Tên khách hàng:<span>*</span></label>
                                    <input type="text" id="userName" name="userName" value="<?= $userName ?>" class="form-control " disabled>
                                </div>
                                <div class="box">
                                    <div class="box-fieldset">
                                        <label for="userPhoneNumber">Số điện thoại<span>*</span></label>
                                        <input type="number" id="userPhoneNumber" name="userPhoneNumber" value="<?= $userPhoneNumber ?>" class="form-control " disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="userEmail">Email khách hàng<span>*</span></label>
                                    <input type="text" id="userEmail" name="userEmail" value="<?= $userEmail ?>" class="form-control" disabled>
                                </div>
                                <div class="box">
                                    <label for="userEmail">Vấn Đề</label>
                                    <?php $problems = ['Cần Hỗ Trợ', 'Thuê Trang', 'SEO Top', 'Khác']; ?>
                                    <input type="text" id="userEmail" name="userEmail" value="<?= $problems[$supportType - 1] ?>" class="form-control" disabled>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="box">
                            <div class="box-fieldset">
                                <label for="contentContact">Mô Tả Trang<span>*</span></label>
                                <textarea disabled class="form-control textareaFormControl" name="contentContact" rows="4" id="contentContact"><?= $contentContact ?></textarea>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
<?php
    } else { ?>
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