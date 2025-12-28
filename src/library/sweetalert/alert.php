<?php
function showSuccessUpdateAlert($message, $redirectUrl)
{
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Cập Nhật Thành Công!',
            text: '$message',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '$redirectUrl';
        });
    </script>";
}
function showSuccessInsertAlert($message, $redirectUrl)
{
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Thêm Mới Thành Công!',
            text: '$message',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '$redirectUrl';
        });
    </script>";
}
function showSuccessDeleteAlert($message, $redirectUrl)
{
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Xoá Thành Công!',
            text: '$message',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '$redirectUrl';
        });
    </script>";
}
function showSuccessUpdateAlertProperty($message, $redirectUrl, $targetId = '')
{
    $redirectWithHash = $redirectUrl . (!empty($targetId) ? "?scrollTo=" . $targetId : "");

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Cập Nhật Thành Công!',
            text: '$message',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '$redirectWithHash';
        });
    </script>";
}
function showErrorAlert($title, $message)
{
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: '$title',
            text: '$message'
        });
    </script>";
}
function showErrorAlertDirection($title, $message, $redirectUrl)
{
    echo "<script>
        Swal.fire({
            title: '$title',
            html: '$message',
            icon: 'error',
            confirmButtonText: 'Thử lại',
            timer: 2000,
            timerProgressBar: true,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '$redirectUrl';
            }
        });
        setTimeout(() => {
            window.location.href = '$redirectUrl';
        }, 5000);
        </script>";
        exit(); 
}

function showSuccessAltert($title, $message, $redirectUrl)
{
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '$title',
            text: '$message',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '$redirectUrl';
        });
    </script>";
}
function showSuccessAltertModalID($title, $message, $modalId)
{
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '$title',
            text: '$message',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            var modalElement = document.getElementById('$modalId');
            if (modalElement) {
                var myModal = new bootstrap.Modal(modalElement);
                myModal.show();
            }
        });
    </script>";
}

function showSuccessContact($message, $redirectUrl)
{
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Liên Hệ Thành Công!',
            text: '$message',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '$redirectUrl';
        });
    </script>";
}

?>
<script>
    function showConfirmAlert(message, confirmUrl) {
        Swal.fire({
            title: 'Xác Nhận!',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xác Nhận',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = confirmUrl;
            }
        });
    }
</script>