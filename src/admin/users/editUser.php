<?php
ob_start();

$id = isset($_GET['id']) ? mysqli_real_escape_string($link, $_GET['id']) : '';
if ((!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) || ($_SESSION['userData']['id'] == $id)) {
    include("src/handlers/layouts/layoutContent/layoutSidebar.php");

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Truy vấn an toàn với prepared statement
    $stmt = $link->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultEditUser = $stmt->get_result();
    if ($resultEditUser && $resultEditUser->num_rows > 0) {
        $userRow = $resultEditUser->fetch_assoc();
        $userName        = $userRow['userName'];
        $userEmail       = $userRow['userEmail'];
        $userNumberPhone = $userRow['userNumberPhone'];
        $userRole        = $userRow['userRole'];
        $userPassword    = $userRow['userPassword'];
        $userAvatarOld   = $userRow['userAvatar'];
    } else {
        showErrorAlertDirection('Lỗi', 'Tài khoản không tồn tại!', 'Admin/users/listuser/');
        exit;
    }
    $stmt->close();

    if (isset($_POST['updateInfomation'])) {
        validateCsrfToken($_POST['csrf_tokenEditUser']);
        $errors = [];
        if (in_array($_SESSION['userData']['role'], [2, 3])) {
            $dataUser = [
                'userName'        => mysqli_real_escape_string($link, $_POST['userName'] ?? ''),
                'userNumberPhone' => mysqli_real_escape_string($link, $_POST['userNumberPhone'] ?? ''),
                'userEmail'       => mysqli_real_escape_string($link, $_POST['userEmail'] ?? ''),
                'userRole'        => mysqli_real_escape_string($link, $_POST['userRole'] ?? 0)
            ];               
            if (strlen($dataUser['userName']) < 1) {
                $errors[] = "Tên khách hàng phải có ít nhất 1 ký tự!";
            }
            if (strlen($dataUser['userName']) > 20) {
                $errors[] = "Tên khách hàng tối đa là 20 ký tự!";
            }
            if (!empty($errors)) {
                showErrorAlertDirection('Cập nhật thất bại', implode("<br>", $errors), "Admin/users/edituser/$id/");
            } else {
                if (filter_var($dataUser['userEmail'], FILTER_VALIDATE_EMAIL) || ($_SESSION['userData']['role'] == 3)) {
                    $updateQuery = "UPDATE users SET
                                    userName        = ?,
                                    userNumberPhone = ?,
                                    userEmail       = ?,
                                    userRole        = ?
                                WHERE id            = ?";
                    $resultUpdate = executeQuery($link, $updateQuery, array_merge(array_values($dataUser), [$id]));
                } else {
                    showErrorAlertDirection('Lỗi', 'Email không hợp lệ. Vui lòng nhập định dạng.', "Admin/users/edituser/$id/");
                }
                if ($dataUser['userNumberPhone']) {
                    $phone = $dataUser['userNumberPhone'];
                    if (!preg_match('/^\d{10}$/', $phone)) {
                        showErrorAlertDirection('Lỗi', 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 chữ số và không chứa chữ cái.', "Admin/users/edituser/$id/");
                    }
                }
            }
        } else {
            $dataUser = [
                'userName'      => mysqli_real_escape_string($link, $_POST['userName'] ?? ''),
                'userEmail'     => mysqli_real_escape_string($link, $_POST['userEmail'] ?? ''),
            ];
            if (strlen($dataUser['userName']) < 1) {
                $errors[] = "Tên khách hàng phải có ít nhất 1 ký tự!";
            }
            if (strlen($dataUser['userName']) > 20) {
                $errors[] = "Tên khách hàng tối đa là 20 ký tự!";
            }
            if (!empty($errors)) {
                showErrorAlertDirection('Cập nhật thất bại', implode("<br>", $errors), "Admin/users/edituser/$id/");
            } else {
                if (filter_var($dataUser['userEmail'], FILTER_VALIDATE_EMAIL)) {
                    $updateQuery = "UPDATE users SET
                                    userName        = ?,
                                    userEmail       = ?
                                WHERE id            = ?";
                    $resultUpdate = executeQuery($link, $updateQuery, array_merge(array_values($dataUser), [$id]));
                } else {
                    showErrorAlertDirection('Lỗi', 'Email không hợp lệ. Vui lòng nhập định dạng.', "Admin/users/edituser/$id/");
                }
            }
        }

        if (isset($resultUpdate) && !$resultUpdate) {
            handleError('Cập nhật không thành công!');
        }

        uploadImageAndUpdate('imageUploadMain', $targetUsersDir, $link, 'users', $id, 'userAvatar', $userAvatarOld, ['defaultAvatar.jpg']);

        $actionHistories = 'Chỉnh sửa thông tin';
        $nameUser = $_SESSION['userData']['name'] ?? 'Unknown';
        if ($userName !== $dataUser['userName']) {
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> tài khoản \"<b>$userName</b>\" thành \"<b>{$dataUser['userName']}</b>\" thành công.";
        } else {
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> tài khoản \"<b>$userName</b>\" thành công.";
        }

        $redirectUrl = (in_array($_SESSION['userData']['role'], [2, 3])) ? 'Admin/users/listuser/' : "Admin/users/edituser/$id/";
        logHistory($link, $_SESSION['userData']['id'], $actionHistories, $detailHistories);
        showSuccessUpdateAlert('Chỉnh sửa tài khoản thành công!', $redirectUrl);
    }

    if (isset($_POST['updatePassword'])) {
        validateCsrfToken($_POST['csrf_tokenEditPassword']);
        $dataPassword = [
            'oldPassword'     => $_POST['oldUserPassword'] ?? '',
            'newPassword'     => $_POST['newUserPassword'] ?? '',
            'confirmPassword' => $_POST['confirmUserPassword'] ?? ''
        ];
        $errors = [];
        if (!in_array($_SESSION['userData']['role'], [2, 3])) {
            if (!password_verify($dataPassword['oldPassword'], $userPassword)) {
                showErrorAlertDirection('Cập nhật thất bại', 'Mật khẩu cũ không khớp!!', "Admin/users/edituser/$id/");
            }
        }
        if ($dataPassword['newPassword'] !== $dataPassword['confirmPassword']) {
            showErrorAlertDirection('Cập nhật thất bại', 'Mật khẩu mới và nhập lại không khớp!!', "Admin/users/edituser/$id/");
        }
        if (strlen($dataPassword['newPassword']) < 8) {
            $errors[] = "Mật khẩu phải có ít nhất 8 ký tự!";
        }
        if (strlen($dataPassword['newPassword']) > 20) {
            $errors[] = "Mật khẩu tối đa là 20 ký tự!";
        }
        if (!preg_match('/[A-Z]/', $dataPassword['newPassword'])) {
            $errors[] = "Mật khẩu phải chứa ít nhất 1 chữ cái viết hoa!";
        }
        if (!preg_match('/[0-9]/', $dataPassword['newPassword'])) {
            $errors[] = "Mật khẩu phải chứa ít nhất 1 chữ số!";
        }
        if (!empty($errors)) {
            showErrorAlertDirection('Cập nhật thất bại', implode("<br>", $errors), "Admin/users/edituser/$id/");
        } else {
            $hashed_password = password_hash($dataPassword['newPassword'], PASSWORD_DEFAULT);

            $updateQuery    = "UPDATE users SET userPassword = ? WHERE id = ?";
            $resultUpdate   = executeQuery($link, $updateQuery, [$hashed_password, $id]);
            if (!$resultUpdate) {
                handleError('Cập nhật không thành công!');
            }
            $actionHistories = 'Chỉnh sửa mật khẩu';
            $nameUser = $_SESSION['userData']['name'] ?? 'Unknown';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> tài khoản \"<b>$userName</b>\" thành công.";
            $redirectUrl = (in_array($_SESSION['userData']['role'], [2, 3])) ? 'Admin/users/listuser/' : "Admin/users/edituser/$id/";
            logHistory($link, $_SESSION['userData']['id'], $actionHistories, $detailHistories);
            showSuccessUpdateAlert('Chỉnh sửa người dùng thành công!', $redirectUrl);
        }
    }
?>

<div class="page-layout">
    <?php include("src/handlers/layouts/layoutContent/layoutSidebar.php"); ?>
    <div class="main-content style-2 w-100">
        <div class="main-content-inner wrap-dashboard-content-2">
            <div class="widget-box-2">
                <div class="box d-flex jcsb">
                    <h3 class="title">Chỉnh Sửa Tài Khoản</h3>
                    <?php 
                        if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) : ?>
                    <div class="box">
                        <a href="Admin/users/listuser/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Người Dùng</a>
                    </div>
                    <?php endif; ?>
                </div>
                <h5 class="title">Thông Tin Khách Hàng</h5>
                <form class="gap-30" method="post" enctype="multipart/form-data">
                    <fieldset class="box grid-layout-3">
                        <div class="box">
                            <label for="box-avatar">Avatar</label>
                            <div class="box-agent-avt" id="box-avatar">
                                <div class="avatar">
                                    <img id="avatar-preview" class="avatar-img" src="src/docs/images/imageUsers/<?= (!empty($userAvatarOld)) ? $userAvatarOld : 'defaultAvatar.jpg' ?>" alt="avatar" loading="lazy" width="128" height="128">
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
                                <input type="text" id="userName" name="userName" value="<?= htmlspecialchars($userName) ?>" class="form-control">
                            </div>
                            <div class="box">
                                <div class="box-fieldset">
                                    <label for="userNumberPhone">Số điện thoại<span>*</span></label>
                                    <input type="text" id="userNumberPhone" name="userNumberPhone" value="<?= htmlspecialchars($userNumberPhone) ?>" class="form-control" <?= (in_array($_SESSION['userData']['role'], [2, 3])) ? '' : 'disabled' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="box box-fieldset">
                            <div class="box">
                                <label for="userEmail">Email khách hàng<span>*</span></label>
                                <input type="text" id="userEmail" name="userEmail" value="<?= htmlspecialchars($userEmail) ?>" class="form-control">
                            </div>
                            <?php if (in_array($_SESSION['userData']['role'], [2, 3])) { ?>
                                <div class="box-fieldset">
                                    <label for="userRole">Phân Quyền</label>
                                    <?php 
                                    $roles = ['Khách Hàng', 'Nhân Viên', 'Admin', 'Super Admin'];
                                    // Xác định maxRoleIndex
                                    $maxRoleIndex = ($_SESSION['userData']['role'] == 2 && $_SESSION['userData']['id'] == $id) ? 2 : 1;
                                    if ($_SESSION['userData']['role'] == 3) {
                                        $maxRoleIndex = 3; // Super Admin thấy tất cả
                                    }
                                    ?>
                                    <select id="userRole" name="userRole" title="--Phân Quyền--">
                                        <?php for ($i = 0; $i <= $maxRoleIndex; $i++): ?>
                                            <option value="<?= $i ?>" <?= ($userRole == $i) ? 'selected' : '' ?>>
                                                <?= $roles[$i] ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </fieldset>
                    <input type="hidden" name="csrf_tokenEditUser" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="box">
                        <button type="submit" name="updateInfomation" class="tf-btn bg-color-primary pd-10">Lưu Thông Tin</button>
                    </div>
                </form>
            </div>
            <div class="widget-box-2 mt4r">
                <form method="post">
                    <h5 class="title">Thay đổi mật khẩu</h5>
                    <div class="box grid-layout-<?= (in_array($_SESSION['userData']['role'], [2, 3])) ? '2' : '3' ?> gap-30">
                        <?php if (!in_array($_SESSION['userData']['role'], [2, 3])) { ?>
                            <div class="box-fieldset">
                                <label for="old-pass">Mật khẩu cũ:<span>*</span></label>
                                <div class="box-password">
                                    <input type="password" id="old-pass" name="oldUserPassword" class="form-contact password-field" placeholder="********">
                                    <span class="show-pass">
                                        <i class="icon-pass icon-hide"></i>
                                        <i class="icon-pass icon-view"></i>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
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
                    </div>
                    <input type="hidden" name="csrf_tokenEditPassword" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="box">
                        <button type="submit" name="updatePassword" class="tf-btn bg-color-primary pd-20">Cập Nhật Mật Khẩu</button>
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

    // Toggle password visibility
    $(document).ready(function() {
        $('.show-pass, .show-pass2, .show-pass3').on('click', function() {
            const input = $(this).siblings('input');
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            $(this).find('.icon-hide, .icon-view').toggle();
        });
    });
</script>

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
<?php } 

// Xóa buffer
ob_end_flush();
?>