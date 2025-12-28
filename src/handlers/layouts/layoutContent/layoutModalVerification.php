<?php
$otp             = isset($_SESSION['register']['otp'])          ? $_SESSION['register']['otp']          : '';
$username        = isset($_SESSION['register']['username'])     ? $_SESSION['register']['username']     : '';
$phonenumber     = isset($_SESSION['register']['phonenumber'])  ? $_SESSION['register']['phonenumber']  : '';
$hashed_password = isset($_SESSION['register']['password'])     ? $_SESSION['register']['password']     : '';
if (isset($_POST["verify"])) {
    $otp1 = $_POST['otpkey1'];
    $otp2 = $_POST['otpkey2'];
    $otp3 = $_POST['otpkey3'];
    $otp4 = $_POST['otpkey4'];
    $otp5 = $_POST['otpkey5'];
    $otp6 = $_POST['otpkey6'];

    $otp_code = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;
    if ($otp != $otp_code) {
        showErrorAlert('Xác Minh thất bại', 'Mã OTP không hợp lệ');
    } else {
        $registerUser = "INSERT INTO users (`userName`,`userNumberPhone`,`userPassword`,`userAvatar`)
                             VALUES ( '$username', '$phonenumber', '$hashed_password','defaultAvatar.jpg')";
        $resultRegister = mysqli_query($link, $registerUser);
        if ($resultRegister) {
            unset($_SESSION['register']);
            showSuccessAltertModalID('Xác Minh Thành Công', 'Xác minh tài khoản đã xong, bạn có thể đăng nhập ngay bây giờ!', 'modalLogin');
        } else {
            showErrorAlert('Xác Minh thất bại', 'Có Lỗi Trong Quá Trình Truy Vấn Dữ Liệu');
        }
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
                            <div class="fxt-switcher-description1" id="masked-email"> Vui lòng nhập OTP để xác minh tài khoản của bạn. Mã đã được gửi tới Zalo <?= $phonenumber ?></div>
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
                    <div class="fxt-switcher-description3">Không nhận được mã xác minh?<a href="reSendOTP/" class="fxt-switcher-text ms-1"><b>Gửi lại</b></a></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="src/public/js/customVerify.js"></script>