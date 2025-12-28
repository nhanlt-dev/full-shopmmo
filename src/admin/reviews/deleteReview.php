<?php
if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) {
    $idUser   = isset($idUser)   ? $idUser   : (isset($_SESSION['userData']['id'])   ? $_SESSION['userData']['id']   : null);
    $nameUser = isset($nameUser) ? $nameUser : (isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : null);

    $stmt = mysqli_prepare($link, "SELECT * FROM testimonials WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $resultCheckImage = mysqli_stmt_get_result($stmt);
    $row_resuilt = mysqli_fetch_array($resultCheckImage);

    $nameTestimonial    = $row_resuilt['nameTestimonial'];
    $imageTestimonial   = $row_resuilt['imageTestimonial'];
    $targetDir = __DIR__ . "/../../../src/docs/images/imageTestimonials/";

    if (!empty($imageTestimonial) && $imageTestimonial !== 'defaultTestimonial.jpg') {
        $oldFile = $targetDir . $imageTestimonial;
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }

    $deleteQuery = "DELETE FROM testimonials WHERE id = ?";
    $stmtDelete = mysqli_prepare($link, $deleteQuery);
    mysqli_stmt_bind_param($stmtDelete, "s", $_GET['id']);
    mysqli_stmt_execute($stmtDelete);

    $actionHistories = 'Xoá bỏ';
    $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> đánh giá \"<b>$nameTestimonial</b>\" thành công.";
    logHistory($link, $idUser, $actionHistories, $detailHistories);
    showSuccessDeleteAlert('Đã xóa đánh giá thành công!', 'Admin/reviews/listreview/');
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