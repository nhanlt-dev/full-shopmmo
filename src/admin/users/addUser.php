<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) {
        include("src/handlers/layouts/layoutContent/layoutSidebar.php");

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        if (isset($_POST['addUser'])) {
            $confirmPassword      = mysqli_real_escape_string($link, $_POST['confirmUserPassword'])   ?? '';
            $newPassword          = mysqli_real_escape_string($link, $_POST['newUserPassword'])       ?? '';
            $dataUser = [
                'userName'        => mysqli_real_escape_string($link, $_POST['userName'])             ?? '',
                'userEmail'       => mysqli_real_escape_string($link, $_POST['userEmail'])            ?? '',
                'userNumberPhone' => mysqli_real_escape_string($link, $_POST['userNumberPhone'])      ?? '',
                'userRole'        => mysqli_real_escape_string($link, $_POST['userRole'])             ??  0,
                'userStatus'      => 1,
            ];
            $errors              = [];
            validateCsrfToken($_POST['csrf_tokenAddUser']);
            
            if ($newPassword !== $confirmPassword) {
                showErrorAlertDirection('Cập nhật thất bại', 'Mật khẩu mới và nhập lại không khớp!!', "Admin/users/adduser/");
            }
            if (strlen($newPassword) < 8) {
                $errors[] = "Mật khẩu phải có ít nhất 8 ký tự!";
            }
            if (strlen($newPassword) > 20) {
                $errors[] = "Mật khẩu tối đa là 20 ký tự!";
            }
            if (!preg_match('/[A-Z]/', $newPassword)) {
                $errors[] = "Mật khẩu phải chứa ít nhất 1 chữ cái viết hoa!";
            }
            if (!preg_match('/[0-9]/', $newPassword)) {
                $errors[] = "Mật khẩu phải chứa ít nhất 1 chữ số!";
            }
            if (!empty($errors)) {
                showErrorAlertDirection('Thêm Mới Tài Khoản Thất Bại', implode("<br>", $errors), "Admin/users/adduser/");
            }
            $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

            $insertQuery = "INSERT INTO users (`userName`,`userEmail`,`userNumberPhone`,`userRole`,`userStatus`,`userPassword`)VALUES (?, ?, ?, ?, ?, ?)";
            if (!executeQuery($link, $insertQuery, array_merge(array_values($dataUser), [$hashed_password]), true)) {
                showErrorAlertDirection('Thêm Mới Tài Khoản Thất Bại', 'Có Lỗi Trong Quá Trình Xử Lý Dữ Liệu!!', "Admin/users/adduser/");
            }
            uploadImageAndUpdate('imageUploadMain', $targetUsersDir, $link, 'users', $idUser, 'userAvatar', NULL, ['defaultAvatar.jpg']);
            $actionHistories = 'Thêm mới';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> tài khoản \"<b>" . $dataUser['userEmail'] . "</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessInsertAlert('Thêm mới tài khoản thành công!', 'Admin/users/listuser/');
        }
    ?> <div class="main-content style-2 w-100">
            <div class="main-content-inner wrap-dashboard-content-2">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Thêm Mới Người Dùng</h3>
                        <div class="box">
                            <a href="Admin/users/listuser/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Người Dùng</a>
                        </div>
                    </div>
                    <h5 class="title">Thông Tin Khách Hàng</h5>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <fieldset class="box grid-layout-3 ">
                            <div class="box">
                                <label for="box-avatar">Avatar</label>
                                <div class="box-agent-avt" id="box-avatar">
                                    <div class="avatar">
                                        <img id="avatar-preview" class="avatar-img" src="src/docs/images/imageUsers/defaultAvatar.jpg" alt="avatar" loading="lazy" width="128" height="128">
                                    </div>
                                    <div class="content uploadfile">
                                        <p>Tải ảnh đại diện mới</p>
                                        <div class="box-ip">
                                            <input type="file" class="ip-file" id="avatar-upload" name="imageUploadMain" accept=".png, .jpg, .jpeg">
                                        </div>
                                        <p class="file-requirements">JPEG 100x100</p>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="userName">Tên khách hàng:<span>*</span></label>
                                    <input type="text" id="userName" name="userName" class="form-control ">
                                </div>
                                <div class="box">
                                    <div class="box-fieldset">
                                        <label for="userNumberPhone">Số điện thoại<span>*</span></label>
                                        <input type="number" id="userNumberPhone" name="userNumberPhone" class="form-control ">
                                    </div>
                                </div>
                            </div>
                            <div class="box box-fieldset">
                                <div class="box">
                                    <label for="userEmail">Email khách hàng<span>*</span></label>
                                    <input type="text" id="userEmail" name="userEmail" class="form-control">
                                </div>
                                <div class="box-fieldset mb-30">
                                    <label for="confirm-pass">Phân Quyền</label>
                                    <?php $roles = ['Khách Hàng', 'Nhân Viên', 'Admin', 'Super Admin']; ?>
                                    <select id="userRole" name="userRole" title="--Phân Quyền--">
                                        <?php
                                        for ($i = 0; $i < count($roles); $i++):
                                            if ($_SESSION['userData']['role'] == 2 && $i > 1) continue; ?>
                                            <option value="<?= $i ?>">
                                                <?= $roles[$i] ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="box grid-layout-2 gap-30">
                            <div class="box-fieldset">
                                <label for="new-pass">Mật khẩu mới<span>*</span></label>
                                <div class="box-password">
                                    <input type="password" id="new-pass" name="newUserPassword" class="form-contact password-field2" placeholder="********">
                                    <span class="show-pass2">
                                        <i class="icon-pass icon-hide"></i>
                                        <i class="icon-pass icon-view"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="box-fieldset mb-30">
                                <label for="confirm-pass">Nhập lại mật khẩu<span>*</span></label>
                                <div class="box-password">
                                    <input type="password" id="confirm-pass" name="confirmUserPassword" class="form-contact password-field3" placeholder="********">
                                    <span class="show-pass3">
                                        <i class="icon-pass icon-hide"></i>
                                        <i class="icon-pass icon-view"></i>
                                    </span>
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenAddUser" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="addUser" class="tf-btn style-border pd-10">Thêm Mới</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
<script>
    document.getElementById("avatar-upload").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("avatar-preview").src = e.target.result;
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