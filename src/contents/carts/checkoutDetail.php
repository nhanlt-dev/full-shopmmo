<?php 
require_once  'src/utils/Contact/phpMailer/PHPMailer.php';
require_once  'src/utils/Contact/phpMailer/Exception.php';
require_once  'src/utils/Contact/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if(!empty($_SESSION['userData']['id'])){

$services = isset($_GET['service']) ? $_GET['service'] : '';
$hasTwoYears = strpos($services, '2-nam') !== false;
$baseService = str_replace('-2-nam', '', $services);

$stmt = $link->prepare("SELECT * FROM services WHERE serviceUrl = ?");
$stmt->bind_param("s", $baseService);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $idService    = $row['id'];
    $serviceName  = $row['serviceName'];
    $servicePrice = $row['servicePrice'];

    if ($hasTwoYears) {
        $serviceName = $serviceName . ' 2 Năm';
        $servicePrice *= 2;
        $servicePrice *= 0.9;
    }
}

    $idOrder = time() . "";
    $MY_BANK = [
        'BANK_ID' => 'MB',
        'ACCOUNT_NO' => '0348454348',
        'AMOUNT' => $servicePrice,
        'DESCRIPTION' => "Thanh toán đơn hàng {$idOrder}",
        'ACCOUNT_NAME' => 'THAI THI THU TRANG'
    ];
    $QR = "https://img.vietqr.io/image/{$MY_BANK['BANK_ID']}-{$MY_BANK['ACCOUNT_NO']}-compact2.jpg?amount={$MY_BANK['AMOUNT']}&addInfo={$MY_BANK['DESCRIPTION']}&accountName={$MY_BANK['ACCOUNT_NAME']}";
?>
    <div id="toast" class="toast-copied">
        <span class="message">Số tài khoản đã được copy!</span>
        <button class="close-btn-toast" id="close-toast">&times;</button>
        <div class="progress" id="progress"></div>
    </div>
    <div class="modal-overlay" id="overlayQR"></div>
    <div class="isQRcode">
        <div class="container">
            <div class="fw600 text-center titleCheckout">Thanh Toán Dịch Vụ Cho <?= $serviceName ?></div>
            <div class="infoQrCheckOut d-flex jcc row">
                <div class="imgQr col-md-7">
                    <img class="qrCheckout" src="<?= $QR ?>" alt="qr-thanhtoan" />
                </div>
                <div class="infoBanking col-md-5">
                    <div class="nameBanking">
                        <div class="titleNameBanking ">Tên Ngân Hàng:</div>
                        <div class="fw600">Ngân hàng TMCP Quân đội (MB Bank)</div>
                    </div>
                    <div class="nameBanking">
                        <div class="titleNameBanking ">Chủ Tài Khoản:</div>
                        <div class="fw600"><?= $MY_BANK['ACCOUNT_NAME'] ?></div>
                    </div>
                    <div class="numberBanking">
                        <div class="titleNumberBanking ">Số tài khoản:</div>
                        <div class="fw600" id="accountNumber"><?= $MY_BANK['ACCOUNT_NO'] ?><span class="copyNumberBanking" onclick="copyToClipboard()"><i class="fa-solid fa-copy"></i></span></div>
                    </div>
                    <div class="amountBanking">
                        <div class="titleAmountBanking ">Số tiền:</div>
                        <div class="fw600"><?= number_format($MY_BANK['AMOUNT']) ?> VND</div>
                    </div>
                    <div class="contentBanking">
                        <div class="titleContentBanking ">Nội Dung:</div>
                        <div class="fw600"><?= $MY_BANK['DESCRIPTION'] ?></div>
                    </div>
                    <div class="notificationBanking">
                        <div class="titleNotificationBanking ">Khách hàng sau khi thanh toán hãy nhấn xác nhận để chúng tôi kiểm tra đơn hàng!</div>
                    </div>
                    <div class="buttonBanking d-flex">
                        <div class="cancelBanking">
                            <button class="btn btn-danger" id="cancelBanking">Huỷ đơn</button>
                        </div>
                        <div class="agreeBanking">
                            <form method="POST" id="formConfirmBanking">
                                <input type="hidden" name="formType" value="banking">
                                <button class="btn btn-primary" type="submit" id="agreeBanking" name="agreeBanking">Xác nhận</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    const overlay = document.getElementById('overlayQR');
    overlay.style.display = 'block';

    const form = document.getElementById("formConfirmBanking");
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        Swal.fire({
            title: "Xác Nhận Thanh Toán",
            text: "Bạn đã thanh toán dịch vụ rồi chứ?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Huỷ Bỏ!",
            confirmButtonText: "Xác Nhận",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else {
            Swal.fire({
                title: "Xác nhận thất bại",
                text: "Bạn đã huỷ quá trình xác nhận thanh toán!",
                icon: "error",
                confirmButtonText: "Về trang chủ",
                timer: 5000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = "/";
            });
        }
        });
    });
    
    $("body").on("click", "#cancelBanking", function () {
        Swal.fire({
            title: "Huỷ Xác Nhận?",
            text: "Bạn muốn huỷ xác nhận thanh toán dịch vụ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Đồng ý!",
            cancelButtonText: "Không",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "Xác Nhận Huỷ Thành Công",
                text: "Bạn đã huỷ quá trình xác nhận thanh toán!",
                icon: "success",
                confirmButtonText: "Về trang chủ",
                timer: 5000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false
                }).then(() => {
                    window.location.href = "/";
                });
            }
        });
    });

    function showToast() {
        const toast = document.getElementById('toast');
        const progress = document.getElementById('progress');
        const closeBtn = document.getElementById('close-toast');

        toast.classList.add('show');
        let width = 100;
        const interval = setInterval(() => {
            if (width <= 0) {
                clearInterval(interval);
                toast.classList.remove('show');
            } else {
                width--;
                progress.style.width = width + '%';
            }
        }, 30);

        closeBtn.onclick = () => {
            clearInterval(interval);
            toast.classList.remove('show');
        };
    }

    function copyToClipboard() {
        const accountNumber = document.getElementById('accountNumber').innerText;

        const textarea = document.createElement('textarea');
        textarea.value = accountNumber;
        document.body.appendChild(textarea);

        textarea.select();
        textarea.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(textarea);

        showToast();
    }
</script>
<?php
if (isset($_POST['formType']) && $_POST['formType'] === 'banking') {
    $dataOrder = [
            'idUser'             => $_SESSION['userData']['id']   ?? "",
            'titleService'       => $serviceName                  ?? "",
            'priceService'       => $servicePrice                 ?:  0,
            'statusOrder'        => 0
        ];

    $insertQuery    = "INSERT INTO orders (`idUser`, `titleService`, `priceService`, `statusOrder`) VALUES (?,?,?,?)";
    if(!executeQuery($link, $insertQuery, array_values($dataOrder), true)){
        showErrorAlert('Thanh Toán Thất Bại', 'Có lỗi trong quá trình nhập liệu, liên hệ với chúng tôi ngay!');
    }
    $mail = new PHPMailer;
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'weborder4@gmail.com';
        $mail->Password = 'brrh tnbg uocf xtqs';
        $mail->setFrom($emailSystem , 'Xác Nhận Đơn Hàng của ' . $_SESSION['userData']['name']);
        $mail->addAddress($emailSystem);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Xác Nhận Đơn Hàng";
        $message = "
            <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;'>
                        <div style='background-color: #4CAF50; padding: 10px 0; color: white; text-align: center; border-radius: 10px 10px 0 0;'>
                            <div class='fw600'>Thông Báo Đơn Hàng Mới</div>
                        </div>
                        <div style='padding: 20px;'>
                            <p>Kính gửi <span style='font-weight: bold;'>$customerSystem</span>,</p>
                            <p>Bạn đã nhận được một đơn hàng mới từ <span style='font-weight: bold;'>$userName</span>.</p>
                            <p>Thông tin chi tiết đơn hàng:</p>
                            <div style='margin-bottom: 15px;'>
                                <span style='font-weight: bold;'>Mã Đơn Hàng:</span>
                                <span style='margin-left: 10px; color: #555;'>$idOrder</span>
                            </div>
                            <div style='margin-bottom: 15px;'>
                                <span style='font-weight: bold;'>Email Khách Hàng:</span>
                                <span style='margin-left: 10px; color: #555;'>$email</span>
                            </div>
                            <div style='margin-bottom: 15px;'>
                                <span style='font-weight: bold;'>Số Điện Thoại Khách Hàng:</span>
                                <span style='margin-left: 10px; color: #555;'>$phoneNumber</span>
                            </div>
                            <div style='margin-bottom: 15px;'>
                                <span style='font-weight: bold;'>Tổng Đơn Hàng:</span>
                                <span style='margin-left: 10px; color: #555;'>$totalOrder</span>
                            </div>
                        </div>
                    </div>
                </body>
            </html>
        ";
        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        showErrorAlert('Lỗi Gửi Email', 'Không thể gửi email. Lỗi: ' . $mail->ErrorInfo);
    }
     showSuccessAltert('Thanh Toán Thành Công', 'Chúng tôi sẽ kiểm tra thông tin và liên hệ lại với bạn sớm nhất!', 'trangchu');
}
}else{ ?>
    <script>
        Swal.fire({
            title: 'Error !!',
            text: 'Bạn chưa đăng nhập? Hãy đăng nhập rồi hẳn đến đây!',
            icon: 'error',
            confirmButtonText: 'Thử lại',
            timer: 5000,
            timerProgressBar: true,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '#modalLogin';
            }
        });
        setTimeout(() => {
            window.location.href = '#modalLogin';
        }, 5000);
    </script>
<?php } ?>