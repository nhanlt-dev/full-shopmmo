<?php
require_once  'src/utils/Contact/phpMailer/PHPMailer.php';
require_once  'src/utils/Contact/phpMailer/Exception.php';
require_once  'src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['registerAccount'])) {
    $userName               = mysqli_real_escape_string($link, $_POST['userName']             ?? '');
    $phoneNumber            = mysqli_real_escape_string($link, $_POST['phoneNumber']          ?? '');
    $userPassword           = mysqli_real_escape_string($link, $_POST['userPassword']         ?? '');
    $userPasswordConfirm    = mysqli_real_escape_string($link, $_POST['userPasswordConfirm']) ?? '';
    $recaptcha_response     = $_POST['g-recaptcha-response'];
    
    $secret_key = '6LcCjRsrAAAAAAhmGcm9KHFFw8fu6ufU51H6-1fq';
    
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_response}");
    $response = json_decode($verify);

    if ($response->success) {
        if (!preg_match('/^\d{10}$/', $phoneNumber)) {
            showErrorAlertDirection('Lỗi', 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 chữ số và không chứa chữ cái.', "#modalRegister");
            exit;
        }
        if (strlen($userPassword) < 8) {
            $errors[] = "Mật khẩu nên có ít nhất 8 kí tự ";
        } elseif (strlen($userPassword) > 20) {
            $errors[] = "Mật khẩu tối đa chỉ 20 kí tự";
        } elseif (!preg_match('/[A-Z]/', $userPassword)) {
            $errors[] = "Mật khẩu nên có ít nhất 1 ký tự in hoa";
        } elseif (!preg_match('/[0-9]/', $userPassword)) {
            $errors[] = "Mật khẩu nên có ít nhất 1 chữ số";
        } elseif ($userPassword !== $userPasswordConfirm) {
            $errors[] = "Mật khẩu và mật khẩu nhập lại không khớp.";
        }
        if (empty($errors)) {
            $hashed_password = password_hash($userPassword, PASSWORD_DEFAULT);
    
            $checkPhoneNumber = "SELECT * From users Where userNumberPhone = '$phoneNumber'";
            $resultcheckPhoneNumber = mysqli_query($link, $checkPhoneNumber);
            if (mysqli_num_rows($resultcheckPhoneNumber) > 0) {
                showErrorAlert('Đăng Ký Thất Bại', 'Số Điện Thoại Đã Tồn Tại!');
            } else {
                $otp = rand(100000, 999999);
    			$_SESSION['register']['otp']         = $otp;
    			$_SESSION['register']['password']    = $hashed_password;
    			$_SESSION['register']['phonenumber'] = $phoneNumber;
    			$_SESSION['register']['username']    = $userName;
    
    			$urlOTP = "https://api.abenla.com/api/SendSmsTemplate?loginName=ABKGLLU&sign=18de4021e9e4db53b76b42ff3072ac18&serviceTypeId=538&templateGuid=F1EB5108-2399-4CA2-BB8F-979ADB66F29C&phoneNumber=" . urlencode($phoneNumber) . "&brandName=" . urlencode("ATV DN") . "&param_1=" . urlencode($otp) . "";
    
    			$ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL, $urlOTP);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    			$responseOTP = curl_exec($ch);
    			curl_close($ch);
    
    			if ($responseOTP === false) {
    				showErrorAlert('Đăng Ký Thất Bại', 'Không thể kết nối đến API OTP');
    				return;
    			}
    
    			$dataOTP = json_decode($responseOTP, true);
    
    			if (isset($dataOTP['Code']) && $dataOTP['Code'] == 106) {
    				showSuccessAltertModalID('Đăng Ký Thành Công', 'Đăng Ký Thành Công, Mã OTP đã được gửi đến Zalo ' . $phoneNumber, 'modalVerify');
    			} else {
    				$errorMessage = isset($dataOTP['Message']) ? $dataOTP['Message'] : 'Không xác định';
    				showErrorAlert('Có lỗi khi gửi OTP: ' . $errorMessage);
    			}
            }
        } else {
            foreach ($errors as $error) {
                showErrorAlert('Đăng Ký Thất Bại', 'Đăng ký thất bại', '' . $error);
            }
        }
    }else{
        showErrorAlert('Đăng Ký Thất Bại', 'reCAPTCHA xác thực không thành công. Vui lòng thử lại.');?>
        window.location.href = '#modalRegister';
    <?php 
    }
} ?>
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
                                <input type="text" class="form-control" id="usernameRegister" name="userName" placeholder="Hãy điền tên của bạn...">
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                            <label for="phoneNumber">Số Điện Thoại</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-phone iconModal icon"></i>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Hãy điền số điện thoại của bạn...">
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                            <label for="passRegister">Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="passRegister" name="userPassword" placeholder="Hãy điền mật khẩu của bạn...">
                                <i class="fa-regular fa-eye iconEye" onclick="togglePasswordVisibility('passRegister', this)"></i>
                            </div>
                        </fieldset>

                        <fieldset class="box-fieldset">
                            <label for="confirm">Nhập Lại Mật Khẩu</label>
                            <div class="ip-field">
                                <i class="fa-regular fa-lock iconModal icon"></i>
                                <input type="password" class="form-control" id="confirm" name="userPasswordConfirm" placeholder="Hãy điền lại mật khẩu của bạn...">
                                <i class="fa-regular fa-eye iconEye" onclick="togglePasswordVisibility('confirm', this)"></i>
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                            <div class="g-recaptcha" data-sitekey="6LcCjRsrAAAAALOWSbLh5wcwMkBylbKwohkUQd5R"></div>
                        </fieldset>
                    </div>
                    <div class="box box-btn">
                        <button type="submit" name="registerAccount" class="tf-btn bg-color-primary w-full">Đăng Ký</button>
                        <div class="text text-center">Bạn Đã Có Tài Khoản? <a href="#modalLogin" data-bs-toggle="modal" class="text-color-primary">Đăng Nhập Ngay</a></div>
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
