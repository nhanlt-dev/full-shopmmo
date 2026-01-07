<?php
$otp             = $_SESSION['register']['otp']         ?? '';
$username        = $_SESSION['register']['username']    ?? '';
$phonenumber     = $_SESSION['register']['phonenumber'] ?? '';
$userEmail       = $_SESSION['register']['email']       ?? ''; // ✅ thêm email
$hashed_password = $_SESSION['register']['password']    ?? '';

if (isset($_POST["verify"])) {

    $otp_code =
        ($_POST['otpkey1'] ?? '') .
        ($_POST['otpkey2'] ?? '') .
        ($_POST['otpkey3'] ?? '') .
        ($_POST['otpkey4'] ?? '') .
        ($_POST['otpkey5'] ?? '') .
        ($_POST['otpkey6'] ?? '');

    /* ================= Check OTP ================= */
    if ($otp != $otp_code) {
        showErrorAlert('Xác Minh Thất Bại', 'Mã OTP không hợp lệ');
        exit;
    }

    /* ================= Insert User ================= */
    $registerUser = "
        INSERT INTO users
        (`userName`, `userEmail`, `userNumberPhone`, `userPassword`, `userAvatar`)
        VALUES
        ('$username', '$userEmail', '$phonenumber', '$hashed_password', 'defaultAvatar.jpg')
    ";

    $resultRegister = mysqli_query($link, $registerUser);

    if ($resultRegister) {
        unset($_SESSION['register']);

        showSuccessAltertModalID(
            'Xác Minh Thành Công',
            'Xác minh tài khoản đã xong, bạn có thể đăng nhập ngay bây giờ!',
            'modalLogin'
        );
    } else {
        showErrorAlert(
            'Xác Minh Thất Bại',
            'Có lỗi trong quá trình truy vấn dữ liệu'
        );
    }
}
?>
<div class="modal modal-account fade" id="modalVerify" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account">
                <!-- Banner -->
                <div class="banner-account">
                    <img src="src/docs/images/common/sendOTP.jpg" alt="OTP Banner">
                </div>
                <!-- Form Verify -->
                <form class="form-account" method="POST" autocomplete="off">
                    <div class="title-box">
                        <h4>Xác Thực OTP</h4>
                        <span class="close-modal icon-close" data-bs-dismiss="modal"></span>
                    </div>
                    <div class="box">
                        <fieldset class="box-fieldset">
                            <label>Nhập mã OTP</label>
                            <div class="fxt-switcher-description1 text-center mb-2">
                                Vui lòng nhập mã OTP để xác minh tài khoản.<br>
                                Mã đã được gửi tới <b>Zalo <?= htmlspecialchars($phonenumber) ?></b>
                            </div>
                            <!-- OTP Inputs -->
                            <div class="otp-card-inputs">
                                <input type="text" name="otpkey1" maxlength="1" inputmode="numeric" required autofocus>
                                <input type="text" name="otpkey2" maxlength="1" inputmode="numeric" required>
                                <input type="text" name="otpkey3" maxlength="1" inputmode="numeric" required>
                                <input type="text" name="otpkey4" maxlength="1" inputmode="numeric" required>
                                <input type="text" name="otpkey5" maxlength="1" inputmode="numeric" required>
                                <input type="text" name="otpkey6" maxlength="1" inputmode="numeric" required>
                            </div>
                        </fieldset>
                    </div>
                    <!-- Button -->
                    <div class="box box-btn">
                        <button type="submit" name="verify" class="tf-btn bg-color-primary w-100">
                            Xác Minh
                        </button>
                    </div>
                    <!-- Resend -->
                    <div class="fxt-switcher-description3 text-center mt-2">
                        Không nhận được mã xác minh?
                        <a href="reSendOTP/" class="fxt-switcher-text ms-1">
                            <b>Gửi lại</b>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="src/public/js/customVerify.js"></script>
