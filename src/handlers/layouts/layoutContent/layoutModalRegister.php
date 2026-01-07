<?php
require_once 'src/utils/Contact/phpMailer/PHPMailer.php';
require_once 'src/utils/Contact/phpMailer/Exception.php';
require_once 'src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['registerAccount'])) {

    $userName            = mysqli_real_escape_string($link, $_POST['userName'] ?? '');
    $phoneNumber         = mysqli_real_escape_string($link, $_POST['phoneNumber'] ?? '');
    $userEmail           = mysqli_real_escape_string($link, $_POST['userEmail'] ?? '');
    $userPassword        = mysqli_real_escape_string($link, $_POST['userPassword'] ?? '');
    $userPasswordConfirm = mysqli_real_escape_string($link, $_POST['userPasswordConfirm'] ?? '');
    $recaptcha_response  = $_POST['g-recaptcha-response'] ?? '';

    $errors = [];

    /* ================= reCAPTCHA ================= */
    $secret_key = '6Lc-jEEsAAAAAEygQbi3GDZbjugzOIfUOMeP0Mr9';
    $verify = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_response}"
    );
    $response = json_decode($verify);

    if (!$response || !$response->success) {
        showErrorAlert('Đăng Ký Thất Bại', 'reCAPTCHA xác thực không thành công. Vui lòng thử lại.');
        echo "<script>window.location.href='#modalRegister';</script>";
        exit;
    }

    /* ================= Validate ================= */
    if (!preg_match('/^\d{10}$/', $phoneNumber)) {
        showErrorAlertDirection(
            'Lỗi',
            'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 chữ số.',
            "#modalRegister"
        );
        exit;
    }
    if (empty($userEmail)) {
        $errors[] = "Email không được để trống";
    } elseif (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không đúng định dạng";
    }
    if (strlen($userPassword) < 8) {
        $errors[] = "Mật khẩu nên có ít nhất 8 ký tự";
    } elseif (strlen($userPassword) > 20) {
        $errors[] = "Mật khẩu tối đa 20 ký tự";
    } elseif (!preg_match('/[A-Z]/', $userPassword)) {
        $errors[] = "Mật khẩu cần ít nhất 1 ký tự in hoa";
    } elseif (!preg_match('/[0-9]/', $userPassword)) {
        $errors[] = "Mật khẩu cần ít nhất 1 chữ số";
    } elseif ($userPassword !== $userPasswordConfirm) {
        $errors[] = "Mật khẩu nhập lại không khớp";
    }
    if (!empty($errors)) {
        foreach ($errors as $error) {
            showErrorAlert('Đăng Ký Thất Bại', $error);
        }
        exit;
    }
    /* ================= Check tồn tại Phone / Email ================= */
    $checkExist = "SELECT id FROM users
                   WHERE userNumberPhone = '$phoneNumber'
                      OR userEmail = '$userEmail'";
    $resultExist = mysqli_query($link, $checkExist);

    if (mysqli_num_rows($resultExist) > 0) {
        showErrorAlert('Đăng Ký Thất Bại', 'Số điện thoại hoặc Email đã tồn tại!');
        exit;
    }

    /* ================= Hash Password ================= */
    $hashed_password = password_hash($userPassword, PASSWORD_DEFAULT);

    /* ================= OTP ================= */
    $otp = rand(100000, 999999);

    $_SESSION['register']['otp']         = $otp;
    $_SESSION['register']['password']    = $hashed_password;
    $_SESSION['register']['phonenumber'] = $phoneNumber;
    $_SESSION['register']['username']    = $userName;
    $_SESSION['register']['email']       = $userEmail; // ✅ thêm email

    $urlOTP = "https://api.abenla.com/api/SendSmsTemplate?" . http_build_query([
        'loginName'     => 'ABKGLLU',
        'sign'          => '18de4021e9e4db53b76b42ff3072ac18',
        'serviceTypeId' => 538,
        'templateGuid' => 'F1EB5108-2399-4CA2-BB8F-979ADB66F29C',
        'phoneNumber'  => $phoneNumber,
        'brandName'    => 'ATV DN',
        'param_1'      => $otp
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlOTP);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $responseOTP = curl_exec($ch);
    curl_close($ch);

    if ($responseOTP === false) {
        showErrorAlert('Đăng Ký Thất Bại', 'Không thể kết nối đến API OTP');
        exit;
    }

    $dataOTP = json_decode($responseOTP, true);

    if (isset($dataOTP['Code']) && $dataOTP['Code'] == 106) {
        showSuccessAltertModalID(
            'Đăng Ký Thành Công',
            'Mã OTP đã được gửi đến Zalo ' . $phoneNumber,
            'modalVerify'
        );
    } else {
        $errorMessage = $dataOTP['Message'] ?? 'Không xác định';
        showErrorAlert('Có lỗi khi gửi OTP', $errorMessage);
    }
}
?>
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

                        <!-- Tên người dùng -->
                        <fieldset class="box-fieldset">
                            <label for="usernameRegister">Tên Người Dùng</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-circle-user iconModal icon"></i>
                                <input type="text" class="form-control" id="usernameRegister" name="userName"
                                    placeholder="Hãy điền tên của bạn..." required>
                            </div>
                        </fieldset>

                        <!-- Email -->
                        <fieldset class="box-fieldset">
                            <label for="userEmail">Email</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-envelope iconModal icon"></i>
                                <input type="email" class="form-control" id="userEmail" name="userEmail"
                                    placeholder="Hãy điền email của bạn..." required>
                            </div>
                        </fieldset>

                        <!-- Số điện thoại -->
                        <fieldset class="box-fieldset">
                            <label for="phoneNumber">Số Điện Thoại</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-phone iconModal icon"></i>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                                    placeholder="Hãy điền số điện thoại của bạn..." required>
                            </div>
                        </fieldset>

                        <!-- Mật khẩu -->
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

                        <!-- Nhập lại mật khẩu -->
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

                        <!-- reCAPTCHA -->
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
