<?php
if (!empty($_SESSION['userData']['role']) && ($_SESSION['userData']['role'] == 3)) {
    $type = $_GET['type'];
    $url  = $_GET['url'];
    $key  = $_GET['key'];
    $converted = str_replace("_", " ", $key);
    $deleteQuery = "DELETE FROM histories WHERE details LIKE ?";
    $stmtDelete = mysqli_prepare($link, $deleteQuery);
    $searchTerm = "%$converted%";
    mysqli_stmt_bind_param($stmtDelete, "s", $searchTerm);
    mysqli_stmt_execute($stmtDelete);
    showSuccessDeleteAlert('Đã xóa toàn bộ lịch sử thành công!', "Admin/$type/$url/");
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