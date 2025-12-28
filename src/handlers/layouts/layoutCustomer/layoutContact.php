<?php
require_once '../../../src/library/sweetalert/alert.php';
require_once '../../../src/utils/Contact/phpMailer/PHPMailer.php';
require_once '../../../src/utils/Contact/phpMailer/Exception.php';
require_once '../../../src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

$fullAddress = [];
function getApiData($url)
{
    $json = file_get_contents($url);
    return json_decode($json, true);
}

if ($pageWard) {
    $data = getApiData("https://esgoo.net/api-tinhthanh-new/5/$pageWard.htm");

    if ($data['error'] == 0 && !empty($data['data']['full_name'])) {
        $fullAddress[] = $data['data']['full_name'];
    }
}

$completeAddress = implode(", ", array_merge([$pageAddress], array_reverse($fullAddress)));
$encodedAddress     = urlencode($completeAddress);

$urlPage = "https://" . $_SERVER['SERVER_NAME'] . "/$pageUrl/";

$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($urlPage);

if (isset($_POST['customerContact'])) {
    $dataContact  = [
        'nameCustomer'             => mysqli_real_escape_string($link, $_POST['nameCustomer']             ?? ''),
        'phoneNumberCustomer'   => mysqli_real_escape_string($link, $_POST['phoneNumberCustomer']   ?? ''),
        'messageCustomer'       => mysqli_real_escape_string($link, $_POST['messageCustomer']       ?? '')
    ];
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Username = 'weborder4@gmail.com';
    $mail->Password = 'brrh tnbg uocf xtqs';
    $mail->setFrom('weborder4@gmail.com', 'Phản Hồi Liên Hệ');
    $mail->addAddress($userEmail);
    $mail->isHTML(true);
    $mail->Subject = "Phải Hồi Liên Hệ Của Khách Hàng";
    include('../../../src/utils/Contact/mailerReportCustomerContact.php');
    $mail->isHTML(true);
    $mail->Body = $htmlContents;
    if (!$mail->send()) {
        showErrorAlert('Gửi lại Phản Hồi Thất Bại', 'Không thể gửi phản hồi!' . mysqli_error($link));
    } else {
        showSuccessInsertAlert('Cảm ơn bạn đã phản hồi cho chúng tôi, chúng tôi sẽ phản hồi lại bạn sớm nhất.', "/#contact");
    }
}
?>
<!------------------------------------------------------------>


<section  id="contact" class="position-relative padding_bottom_half wow fadeInUp" data-wow-delay="300ms">
    <div class="wrap-form">
        <div class="wrap-content">
            <div class="cols-form">
                <div class="wow fadeInLeft" data-wow-delay="500ms">
                    <div class="text-form">
                        <p class="mb-0">Trải nghiệm dịch vụ chuyên nghiệp</p>
                        <span>"Hãy quét mã QR để trải nghiệm ngay dịch vụ của chúng tôi"</span>
                    </div>
                    <div class="info-header">
                        <div class="img-info-head">
                            <img src="<?= $qr_url ?>" alt="imageServices" class="qrCode_contact">
                        </div>
                    </div>
                </div>
                <div class="wow fadeInRight" data-wow-delay="500ms">
                    <div class="form">
                        <div class="title-form">
                            <div class="font-normal darkcolor heading_space_half" style="color:#ffffff">ĐĂNG KÝ NHẬN TIN</div>
                        </div>
                        <form class="getin_form" method="POST">
                            <div class="row px-2">
                                <div class="col-md-12 col-sm-12" id="result1"></div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="name1" class="d-none"></label>
                                        <input class="form-control" id="name1" name="nameCustomer" type="text" placeholder="Hãy điền tên của bạn..." required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="phoneNumberCustomer" class="d-none"></label>
                                        <input class="form-control" type="number" id="phoneNumberCustomer" name="phoneNumberCustomer" placeholder="Hãy điền địa chỉ số điện thoại của bạn..." required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="message1" class="d-none"></label>
                                        <textarea class="form-control" id="message1" name="messageCustomer" placeholder="Hãy điền yêu cầu của bạn..." required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" id="submit_btn1" name="customerContact" class="button gradient-btn w-100">Gửi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>