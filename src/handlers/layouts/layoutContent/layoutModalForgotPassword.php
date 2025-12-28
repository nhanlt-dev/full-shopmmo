<?php
require_once 'src/utils/Contact/phpMailer/PHPMailer.php';
require_once 'src/utils/Contact/phpMailer/Exception.php';
require_once 'src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

function generateRandomPassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

if (isset($_POST["recover"])) {
    $errors = [];
    $email  = isset($_POST["email"])  ? trim($_POST["email"])  : '';
    $number = isset($_POST["number"]) ? trim($_POST["number"]) : '';

    if (empty($number)) {
        $errors[] = 'Số điện thoại không được để trống.';
    }

    if (empty($email)) {
        $errors[] = 'Email không được để trống.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }

    if (empty($errors)) {
        $stmt = $link->prepare("SELECT * FROM users WHERE userNumberPhone = ?");
        $stmt->bind_param("s", $number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            showErrorAlert('Khôi phục mật khẩu thất bại', 'Số điện thoại không đúng!');
        } else {
            $user = $result->fetch_assoc();
            $emailExist = !empty($user['userEmail']);

            if (!$emailExist) {
                $updateEmail = $link->prepare("UPDATE users SET userEmail = ? WHERE userNumberPhone = ?");
                $updateEmail->bind_param("ss", $email, $number);
                $updateEmail->execute();
            }

            $password = generateRandomPassword();
            $mail = new PHPMailer(true);

            try {
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'weborder4@gmail.com';
                $mail->Password = 'brrh tnbg uocf xtqs';

                $mail->setFrom($emailSystem, 'Khôi Phục Mật Khẩu');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Khôi Phục Mật Khẩu";

                include('src/utils/Contact/mailerForgotPassword.php');
                $mail->Body = $htmlForgotPassword;

                $mail->send();
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $update = $link->prepare("UPDATE users SET userPassword = ? WHERE userNumberPhone = ?");
                $update->bind_param("ss", $hash, $number);
                $update->execute();

                showSuccessAltertModalID('Khôi Phục Thành Công', 'Chúng tôi đã gửi mật khẩu mới của bạn, vui lòng kiểm tra email của bạn!', 'modalLogin');

            } catch (Exception $e) {
                showErrorAlert('Khôi Phục Mật Khẩu Thất Bại', 'Không thể gửi email. Vui lòng thử lại sau!');
            }
        }
    } else {
        showErrorAlert('Lỗi', $errors[0]);
    }
}?>
<div class="modal modal-account fade" id="modalForgotPassword">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account">
                <div class="banner-account">
                    <img src="src/docs/images/common/forgot-email-2.jpg" alt="banner">
                </div>
                <form class="form-account" method="POST">
                    <div class="title-box">
                        <h4>Quên Mật Khẩu</h4>
                        <span class="close-modal icon-close" data-bs-dismiss="modal"></span>
                    </div>
                    <div class="box">
                        <fieldset class="box-fieldset">
                            <div class="fxt-switcher-description1" id="masked-email"> Vui lòng nhập số điện thoại đã đăng ký tài khoản của bạn và email của bạn để chúng tôi cấp lại mật khẩu.</div>
                            <label for="nameAccount" class="mt2r">Số Điện Thoại</label>
                            <div class="form-group">
                                <input type="text" id="number" class="form-control" name="number" placeholder="Nhập Số điện thoại đã tạo tài khoản..." required="required">
                            </div>
                        </fieldset>
                        <fieldset class="box-fieldset">
                             <label for="nameAccount">Email</label>
                            <div class="form-group">
                                <input type="email" id="email" class="form-control" name="email" placeholder="Nhập Email để nhận mật khẩu mới..." required="required">
                            </div>
                            
                        </fieldset>
                    </div>
                    <div class="box box-btn">
                        <button type="submit" name="recover" class="tf-btn bg-color-primary w-100">Đặt Lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="src/public/js/customVerify.js"></script>