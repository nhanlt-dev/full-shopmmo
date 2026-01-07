<?php
session_start();
require_once 'src/utils/Contact/phpMailer/PHPMailer.php';
require_once 'src/utils/Contact/phpMailer/Exception.php';
require_once 'src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['registerAccount'])) {

    $userName            = trim($_POST['userName'] ?? '');
    $phoneNumber         = trim($_POST['phoneNumber'] ?? '');
    $userEmail           = trim($_POST['userEmail'] ?? '');
    $userPassword        = $_POST['userPassword'] ?? '';
    $userPasswordConfirm = $_POST['userPasswordConfirm'] ?? '';
    $recaptcha_response  = $_POST['g-recaptcha-response'] ?? '';

    $errors = [];

    /* ================== reCAPTCHA ================== */
    $secret_key = '6Lc-jEEsAAAAAEygQbi3GDZbjugzOIfUOMeP0Mr9';
    $verify = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_response}"
    );
    $response = json_decode($verify);

    if (!$response || !$response->success) {
        showErrorAlert('Đăng Ký Thất Bại', 'reCAPTCHA không hợp lệ');
        exit;
    }

    /* ================== VALIDATE ================== */
    if ($userName == '') {
        $errors[] = 'Tên người dùng không được để trống';
    }

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ';
    }

    if (!preg_match('/^\d{10}$/', $phoneNumber)) {
        $errors[] = 'Số điện thoại phải đủ 10 chữ số';
    }

    if (strlen($userPassword) < 8 || strlen($userPassword) > 20) {
        $errors[] = 'Mật khẩu từ 8–20 ký tự';
    }

    if (!preg_match('/[A-Z]/', $userPassword)) {
        $errors[] = 'Mật khẩu cần ít nhất 1 chữ in hoa';
    }

    if (!preg_match('/[0-9]/', $userPassword)) {
        $errors[] = 'Mật khẩu cần ít nhất 1 chữ số';
    }

    if ($userPassword !== $userPasswordConfirm) {
        $errors[] = 'Mật khẩu nhập lại không khớp';
    }

    if (!empty($errors)) {
        showErrorAlert('Đăng Ký Thất Bại', implode('<br>', $errors));
        exit;
    }

    /* ================== CHECK DB ================== */
    $sqlCheck = "
        SELECT id FROM users
        WHERE userNumberPhone = '$phoneNumber'
        OR userEmail = '$userEmail'
    ";
    $check = mysqli_query($link, $sqlCheck);

    if (mysqli_num_rows($check) > 0) {
        showErrorAlert('Đăng Ký Thất Bại', 'Email hoặc số điện thoại đã tồn tại');
        exit;
    }

    /* ================== OTP ================== */
    $otp = rand(100000, 999999);

    $_SESSION['register'] = [
        'otp'              => $otp,
        'otp_created_at'   => time(),
        'username'         => $userName,
        'email'            => $userEmail,
        'phonenumber'      => $phoneNumber,
        'password'         => password_hash($userPassword, PASSWORD_DEFAULT)
    ];

    $urlOTP = "https://api.abenla.com/api/SendSmsTemplate?" . http_build_query([
        'loginName'     => 'ABKGLLU',
        'sign'          => '18de4021e9e4db53b76b42ff3072ac18',
        'serviceTypeId' => 538,
        'templateGuid' => 'F1EB5108-2399-4CA2-BB8F-979ADB66F29C',
        'phoneNumber'  => $phoneNumber,
        'brandName'    => 'ATV DN',
        'param_1'      => $otp
    ]);

    $ch = curl_init($urlOTP);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $responseOTP = curl_exec($ch);
    curl_close($ch);

    $dataOTP = json_decode($responseOTP, true);

    if (isset($dataOTP['Code']) && $dataOTP['Code'] == 106) {
        showSuccessAltertModalID(
            'Đăng Ký Thành Công',
            'Mã OTP đã được gửi tới Zalo ' . $phoneNumber,
            'modalVerify'
        );
    } else {
        showErrorAlert('Gửi OTP thất bại', $dataOTP['Message'] ?? 'Không xác định');
    }
}
?>

<!-- ===================== HTML ===================== -->
<div class="modal modal-account fade" id="modalRegister" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account">

                <div class="banner-account">
                    <img src="src/public/admin/images/section/banner-register.jpg" alt="banner">
                </div>

                <form class="form-account" method="POST" id="registerform" autocomplete="off">
                    <div class="title-box">
                        <h4>Đăng Ký</h4>
                        <span class="close-modal icon-close" data-bs-dismiss="modal"></span>
                    </div>

                    <div class="box">

                        <!-- USERNAME -->
                        <fieldset class="box-fieldset">
                            <label for="usernameRegister">Tên Người Dùng</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-circle-user iconModal icon"></i>
                                <input type="text" class="form-control" id="usernameRegister" name="userName"
                                    placeholder="Hãy điền tên của bạn..." required>
                            </div>
                        </fieldset>

                        <!-- PHONE -->
                        <fieldset class="box-fieldset">
                            <label for="phoneNumber">Số Điện Thoại</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-phone iconModal icon"></i>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                                    placeholder="Hãy điền số điện thoại của bạn..." required>
                            </div>
                        </fieldset>

                        <!-- EMAIL (CỰC KỲ QUAN TRỌNG) -->
                        <fieldset class="box-fieldset">
                            <label for="userEmail">Email</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-envelope iconModal icon"></i>
                                <input type="email" class="form-control" id="userEmail" name="userEmail"
                                    placeholder="Nhập email của bạn..." required autocomplete="email">
                            </div>
                        </fieldset>

                        <!-- PASSWORD -->
                        <fieldset class="box-fieldset">
                            <label for="passRegister">Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="passRegister" name="userPassword"
                                    placeholder="Hãy điền mật khẩu của bạn..." required>
                                <i class="fa-regular fa-eye iconEye"
                                    onclick="togglePasswordVisibility('passRegister', this)"></i>
                            </div>
                        </fieldset>

                        <!-- CONFIRM PASSWORD -->
                        <fieldset class="box-fieldset">
                            <label for="confirm">Nhập Lại Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="confirm" name="userPasswordConfirm"
                                    placeholder="Hãy điền lại mật khẩu của bạn..." required>
                                <i class="fa-regular fa-eye iconEye"
                                    onclick="togglePasswordVisibility('confirm', this)"></i>
                            </div>
                        </fieldset>

                        <!-- RECAPTCHA -->
                        <fieldset class="box-fieldset">
                            <div class="g-recaptcha" data-sitekey="6Lc-jEEsAAAAAF2268nlvCJhJc_g6YkoiRLa3wYK"></div>
                        </fieldset>

                    </div>

                    <div class="box box-btn">
                        <button type="submit" name="registerAccount" class="tf-btn bg-color-primary w-full">
                            Đăng Ký
                        </button>

                        <div class="text text-center">
                            Bạn Đã Có Tài Khoản?
                            <a href="#modalLogin" data-bs-toggle="modal" class="text-color-primary">
                                Đăng Nhập Ngay
                            </a>
                        </div>
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
