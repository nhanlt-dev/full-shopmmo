<?php
session_start();

$otp             = $_SESSION['register']['otp']          ?? '';
$username        = $_SESSION['register']['username']     ?? '';
$useremail       = $_SESSION['register']['email']        ?? '';
$phonenumber     = $_SESSION['register']['phonenumber']  ?? '';
$hashed_password = $_SESSION['register']['password']     ?? '';

if (isset($_POST['verify'])) {

    $otp_code =
        ($_POST['otpkey1'] ?? '') .
        ($_POST['otpkey2'] ?? '') .
        ($_POST['otpkey3'] ?? '') .
        ($_POST['otpkey4'] ?? '') .
        ($_POST['otpkey5'] ?? '') .
        ($_POST['otpkey6'] ?? '');

    if ($otp_code !== $otp) {
        showErrorAlert('Xác Minh Thất Bại', 'Mã OTP không chính xác');
        exit;
    }

    /* ================== INSERT USER ================== */
    $sqlInsert = "
        INSERT INTO users
        (userName, userEmail, userNumberPhone, userPassword, userAvatar)
        VALUES
        ('$username', '$useremail', '$phonenumber', '$hashed_password', 'defaultAvatar.jpg')
    ";

    $result = mysqli_query($link, $sqlInsert);

    if ($result) {
        unset($_SESSION['register']);
        showSuccessAltertModalID(
            'Xác Minh Thành Công',
            'Tài khoản đã được kích hoạt, bạn có thể đăng nhập!',
            'modalLogin'
        );
    } else {
        showErrorAlert(
            'Xác Minh Thất Bại',
            'Lỗi SQL: ' . mysqli_error($link)
        );
    }
}
?>

<div class="modal modal-account fade" id="modalVerify">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account">
                <div class="banner-account">
                    <img src="src/docs/images/common/sendOTP.jpg" alt="banner">
                </div>
                <form class="form-account" method="POST">
                    <div class="title-box">
                        <h4>Xác thực OTP</h4>
                        <span class="close-modal icon-close" data-bs-dismiss="modal"></span>
                    </div>
                    <div class="box">
                        <fieldset class="box-fieldset">
                            <label for="nameAccount">Nhập mã OTP vào đây</label>
                            <div class="fxt-switcher-description1" id="masked-email"> Vui lòng nhập OTP để xác minh tài
                                khoản của bạn. Mã đã được gửi tới Zalo <?= $phonenumber ?></div>
                            <div class="otp-card-inputs">
                                <input name="otpkey1" type="text" maxlength="1" autofocus>
                                <input name="otpkey2" type="text" maxlength="1">
                                <input name="otpkey3" type="text" maxlength="1">
                                <input name="otpkey4" type="text" maxlength="1">
                                <input name="otpkey5" type="text" maxlength="1">
                                <input name="otpkey6" type="text" maxlength="1">
                            </div>
                        </fieldset>
                    </div>
                    <div class="box box-btn">
                        <button type="submit" name="verify" class="tf-btn bg-color-primary w-100">Xác Minh</button>
                    </div>
                    <div class="fxt-switcher-description3">Không nhận được mã xác minh?<a href="reSendOTP/"
                            class="fxt-switcher-text ms-1"><b>Gửi lại</b></a></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="src/public/js/customVerify.js"></script>
