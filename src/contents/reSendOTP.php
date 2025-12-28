<?php
$otp = rand(100000, 999999);
$_SESSION['register']['otp']  = $otp;
$_SESSION['register']['otp_created_at'] = time(); 
$phoneNumber = $_SESSION['register']['phonenumber'] ?? '';

$minTimeBetweenResend = 30;
$lastOtpTime = isset($_SESSION['register']['otp_created_at']) ? $_SESSION['register']['otp_created_at'] : 0;
$currentTime = time();

if ($currentTime - $lastOtpTime < $minTimeBetweenResend) {
    $remainingTime = $minTimeBetweenResend - ($currentTime - $lastOtpTime);
    showErrorAlert('Lỗi', "Vui lòng chờ thêm $remainingTime giây để gửi lại mã OTP.");
    return;
}
if(!empty($phoneNumber)){
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
}else{
     showErrorAlert('Gửi Lại OTP thất bại' . $errorMessage);
}
?>
