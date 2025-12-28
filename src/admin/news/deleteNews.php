<?php
if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) :
    $idUser   = isset($idUser)   ? $idUser   : (isset($_SESSION['userData']['id'])   ? $_SESSION['userData']['id']   : null);
    $nameUser = isset($nameUser) ? $nameUser : (isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : null);

    $stmt = mysqli_prepare($link, "SELECT * FROM news WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $resultCheckImage = mysqli_stmt_get_result($stmt);
    $row_resuilt = mysqli_fetch_array($resultCheckImage);

    $newsTitle = $row_resuilt['newsTitle'];
    $imageNews = $row_resuilt['imageNews'];
    $targetDir = __DIR__ . "/../../../src/docs/images/imageNews/";

    if (!empty($imageNews) && $imageNews !== 'defaultNews.jpg') {
        $oldFile = $targetDir . $imageNews;
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }

    $deleteNewsQuery = "DELETE FROM news WHERE id = ?";
    $stmtDelete = mysqli_prepare($link, $deleteNewsQuery);
    mysqli_stmt_bind_param($stmtDelete, "s", $_GET['id']);
    mysqli_stmt_execute($stmtDelete);

    $actionHistories = 'Xoá bỏ';
    $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> bài viết \"<b>$newsTitle</b>\" thành công.";
    logHistory($link, $idUser, $actionHistories, $detailHistories);
    showSuccessDeleteAlert('Đã xóa bài viết thành công!', 'Admin/news/listnews/');
else: ?>
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
<?php endif; ?>