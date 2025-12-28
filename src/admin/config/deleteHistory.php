<?php
if (!empty($_SESSION['userData']['role']) && ($_SESSION['userData']['role'] == 3)) {
    $deleteQuery = "DELETE FROM histories WHERE id = ?";
    $stmtDelete = mysqli_prepare($link, $deleteQuery);
    mysqli_stmt_bind_param($stmtDelete, "s", $_GET['id']);
    mysqli_stmt_execute($stmtDelete);
    $type = $_GET['type'];
    $url  = $_GET['url'];
    showSuccessDeleteAlert('Đã xóa lịch sử thành công!', "Admin/$type/$url/");
} else { ?>
    <script>
        Swal.fire({
            title: '403 Forbidden!',
            text: 'Bạn không có quyền hạn để truy cập trang này!',
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