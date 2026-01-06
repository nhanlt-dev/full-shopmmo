<?php
session_start();
require_once 'src/utils/Contact/phpMailer/PHPMailer.php';
require_once 'src/utils/Contact/phpMailer/Exception.php';
require_once 'src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

/* ===================== XỬ LÝ FORM ===================== */

if (isset($_POST['registerAccount'])) {

    $errors = [];

    $userName            = trim($_POST['userName'] ?? '');
    $phoneNumber         = trim($_POST['phoneNumber'] ?? '');
    $userPassword        = $_POST['userPassword'] ?? '';
    $userPasswordConfirm = $_POST['userPasswordConfirm'] ?? '';
    $captchaResponse     = $_POST['g-recaptcha-response'] ?? '';

    /* ===== CAPTCHA CHECK ===== */
    if (empty($captchaResponse)) {
        showErrorAlertDirection('Lỗi', 'Vui lòng xác nhận reCAPTCHA', '#modalRegister');
        exit;
    }

    $secretKey = '6Lc-jEEsAAAAAEygQbi3GDZbjugzOIfUOMeP0Mr9';

    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret'   => $secretKey,
            'response' => $captchaResponse
        ]),
        CURLOPT_RETURNTRANSFER => true
    ]);
    $verifyResponse = curl_exec($ch);
    curl_close($ch);

    $captchaResult = json_decode($verifyResponse, true);

    if (empty($captchaResult['success'])) {
        showErrorAlertDirection('Lỗi', 'reCAPTCHA không hợp lệ, vui lòng thử lại', '#modalRegister');
        exit;
    }

    /* ===== VALIDATE INPUT ===== */
    if (!preg_match('/^\d{10}$/', $phoneNumber)) {
        $errors[] = 'Số điện thoại phải đủ 10 chữ số';
    }

    if (strlen($userPassword) < 8 || strlen($userPassword) > 20) {
        $errors[] = 'Mật khẩu phải từ 8–20 ký tự';
    }
    if (!preg_match('/[A-Z]/', $userPassword)) {
        $errors[] = 'Mật khẩu phải có ít nhất 1 chữ in hoa';
    }
    if (!preg_match('/[0-9]/', $userPassword)) {
        $errors[] = 'Mật khẩu phải có ít nhất 1 chữ số';
    }
    if ($userPassword !== $userPasswordConfirm) {
        $errors[] = 'Mật khẩu nhập lại không khớp';
    }

    if (!empty($errors)) {
        showErrorAlert('Đăng Ký Thất Bại', implode('<br>', $errors));
        exit;
    }

    /* ===== CHECK TRÙNG SĐT ===== */
    $stmt = $link->prepare("SELECT id FROM users WHERE userNumberPhone = ?");
    $stmt->bind_param("s", $phoneNumber);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        showErrorAlert('Đăng Ký Thất Bại', 'Số điện thoại đã tồn tại');
        exit;
    }

    /* ===== OTP ===== */
    $otp = random_int(100000, 999999);

    $_SESSION['register'] = [
        'otp'         => $otp,
        'password'    => password_hash($userPassword, PASSWORD_DEFAULT),
        'phonenumber' => $phoneNumber,
        'username'    => $userName
    ];

    $urlOTP = "https://api.abenla.com/api/SendSmsTemplate?" . http_build_query([
        'loginName'      => 'ABKGLLU',
        'sign'           => '18de4021e9e4db53b76b42ff3072ac18',
        'serviceTypeId'  => 538,
        'templateGuid'  => 'F1EB5108-2399-4CA2-BB8F-979ADB66F29C',
        'phoneNumber'   => $phoneNumber,
        'brandName'     => 'ATV DN',
        'param_1'       => $otp
    ]);

    $otpResponse = file_get_contents($urlOTP);
    $otpData = json_decode($otpResponse, true);

    if (isset($otpData['Code']) && $otpData['Code'] == 106) {
        showSuccessAltertModalID(
            'Đăng Ký Thành Công',
            'Mã OTP đã được gửi đến số ' . $phoneNumber,
            'modalVerify'
        );
    } else {
        showErrorAlert('Lỗi gửi OTP', $otpData['Message'] ?? 'Không xác định');
    }
}
?>

<!-- ===================== HTML ===================== -->
<div class="modal modal-account fade" id="modalRegister">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account">
                <div class="banner-account">
                    <img src="src/public/admin/images/section/banner-register.jpg" alt="banner">
                </div>
                <form class="form-account" method="POST" id="registerform">
                    <div class="title-box">
                        <h4>Đăng Ký</h4>
                        <span class="close-modal icon-close" data-bs-dismiss="modal"></span>
                    </div>
                    <div class="box">
                        <fieldset class="box-fieldset">
                            <label for="username">Tên Người Dùng</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-circle-user iconModal icon"></i>
                                <input type="text" class="form-control" id="usernameRegister" name="userName"
                                    placeholder="Hãy điền tên của bạn...">
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                            <label for="phoneNumber">Số Điện Thoại</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-phone iconModal icon"></i>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                                    placeholder="Hãy điền số điện thoại của bạn...">
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                            <label for="passRegister">Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="passRegister" name="userPassword"
                                    placeholder="Hãy điền mật khẩu của bạn...">
                                <i class="fa-regular fa-eye iconEye"
                                    onclick="togglePasswordVisibility('passRegister', this)"></i>
                            </div>
                        </fieldset>

                        <fieldset class="box-fieldset">
                            <label for="confirm">Nhập Lại Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="confirm" name="userPasswordConfirm"
                                    placeholder="Hãy điền lại mật khẩu của bạn...">
                                <i class="fa-regular fa-eye iconEye"
                                    onclick="togglePasswordVisibility('confirm', this)"></i>
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                            <div class="g-recaptcha" data-sitekey="6Lc-jEEsAAAAAF2268nlvCJhJc_g6YkoiRLa3wYK"></div>
                        </fieldset>
                    </div>
                    <div class="box box-btn">
                        <button type="submit" name="registerAccount" class="tf-btn bg-color-primary w-full">Đăng
                            Ký</button>
                        <div class="text text-center">Bạn Đã Có Tài Khoản? <a href="#modalLogin" data-bs-toggle="modal"
                                class="text-color-primary">Đăng Nhập Ngay</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function togglePasswordVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
