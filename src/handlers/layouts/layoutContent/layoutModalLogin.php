<?php
$passwordUrl = $_GET['password'] ?? '';

if (isset($_POST['login'])) {

    $accountLogin  = trim($_POST['phoneLogin'] ?? '');
    $passwordLogin = trim($_POST['passwordLogin'] ?? '');

    if (empty($accountLogin) || empty($passwordLogin)) {
        showErrorAlert('Đăng Nhập Thất Bại', 'Vui lòng nhập đầy đủ thông tin');
        return;
    }

    $allowed_users = ["superadmin", "admin"];

    /* ===== Xác định kiểu đăng nhập ===== */
    if (in_array($accountLogin, $allowed_users)) {

        // Login bằng username đặc biệt
        $stmt = $link->prepare("SELECT * FROM users WHERE userName = ?");
        $stmt->bind_param("s", $accountLogin);
    } elseif (filter_var($accountLogin, FILTER_VALIDATE_EMAIL)) {

        // Login bằng EMAIL
        $stmt = $link->prepare("SELECT * FROM users WHERE userEmail = ?");
        $stmt->bind_param("s", $accountLogin);
    } elseif (preg_match('/^[0-9]{9,11}$/', $accountLogin)) {

        // Login bằng SĐT
        $stmt = $link->prepare("SELECT * FROM users WHERE userNumberPhone = ?");
        $stmt->bind_param("s", $accountLogin);
    } else {
        showErrorAlert('Đăng Nhập Thất Bại', 'Tài khoản không hợp lệ (Email / Số điện thoại)');
        return;
    }

    /* ===== Execute ===== */
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($passwordLogin, $user['userPassword'])) {

            $_SESSION['userData'] = [
                'id'   => $user['id'],
                'role' => $user['userRole'],
                'name' => $user['userName'],
                'email' => $user['userEmail']
            ];

            if ($user['userRole'] == 1) {
                showSuccessAltert(
                    'Đăng Nhập Thành Công',
                    'Chào Admin!',
                    "Admin/users/edituser/" . $user['id'] . "/"
                );
            } else {
                showSuccessAltert(
                    'Đăng Nhập Thành Công',
                    'Đăng nhập thành công!',
                    'Admin/dashboard/'
                );
            }
        } else {
            showErrorAlert('Đăng Nhập Thất Bại', 'Mật khẩu không chính xác!');
        }
    } else {
        showErrorAlert('Đăng Nhập Thất Bại', 'Tài khoản không tồn tại!');
    }

    $stmt->close();
}
?>

<div class="modal modal-account fade" id="modalLogin">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account">

                <!-- Banner -->
                <div class="banner-account">
                    <img src="src/public/admin/images/section/banner-login.jpg" alt="banner">
                </div>

                <!-- Form Login -->
                <form class="form-account" method="POST" autocomplete="off">
                    <div class="title-box">
                        <h4>Đăng Nhập</h4>
                        <span class="close-modal icon-close" data-bs-dismiss="modal"></span>
                    </div>

                    <div class="box">

                        <!-- Email / Phone -->
                        <fieldset class="box-fieldset">
                            <label for="nameAccount">Email hoặc Số Điện Thoại</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-circle-user iconModal icon"></i>
                                <input type="text" autofocus class="form-control" id="nameAccount" name="phoneLogin"
                                    placeholder="Nhập email hoặc số điện thoại" required>
                            </div>
                        </fieldset>

                        <!-- Password -->
                        <fieldset class="box-fieldset">
                            <label for="passLogin">Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="passLogin" name="passwordLogin"
                                    placeholder="*********" value="<?= htmlspecialchars($passwordUrl ?? '') ?>"
                                    required>
                                <i class="fa-regular fa-eye iconEye"
                                    onclick="togglePasswordVisibility('passLogin', this)"></i>
                            </div>

                            <div class="text-forgot text-end">
                                <a href="#modalForgotPassword" data-bs-toggle="modal">
                                    Quên Mật Khẩu?
                                </a>
                            </div>
                        </fieldset>

                    </div>

                    <div class="box box-btn">
                        <button name="login" type="submit" class="tf-btn bg-color-primary w-100">
                            Đăng Nhập
                        </button>

                        <div class="text text-center">
                            Bạn Chưa Có Tài Khoản?
                            <a href="#modalRegister" data-bs-toggle="modal" class="text-color-primary">
                                Đăng Ký Ngay
                            </a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
