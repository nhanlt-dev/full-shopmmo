<?php
if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [2, 3])) {
    $idUser   = isset($idUser)   ? $idUser   : (isset($_SESSION['userData']['id'])   ? $_SESSION['userData']['id']   : null);
    $nameUser = isset($nameUser) ? $nameUser : (isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : null);
    
    $idPage = $_GET['id'];
    $stmt = mysqli_prepare($link, "SELECT * FROM pages WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "s", $idPage);
    mysqli_stmt_execute($stmt);
    $resultCheckPage = mysqli_stmt_get_result($stmt);
    $row_resuilt = mysqli_fetch_array($resultCheckPage);
    $pageName = $row_resuilt['pageName'];

    function deleteImageFiles($link, $query, $idPage, $targetDir, $defaultImage)
    {
        if (!is_array($defaultImage)) {
            $defaultImage = [$defaultImage];
        }
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $idPage);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $image = $row['image'];
            if (!empty($image) && $image !== $defaultImage) {
                $oldFile = $targetDir . $image;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }
    }

    deleteImageFiles($link, "SELECT pageImageLogo       AS image FROM pages      WHERE id = ?",     $_GET['id'], __DIR__ . "/../../docs/images/imagePages/",      'defaultLogo.jpg');
    deleteImageFiles($link, "SELECT pageImageIntroduce  AS image FROM pages      WHERE id = ?",     $_GET['id'], __DIR__ . "/../../docs/images/imageIntroduces/", 'defaultIntroduce.jpg');
    deleteImageFiles($link, "SELECT imageSlide          AS image FROM slides     WHERE idPage = ?", $_GET['id'], __DIR__ . "/../../docs/images/imageSlides/",     'defaultSlide.jpg');
    deleteImageFiles($link, "SELECT imageProduct        AS image FROM products   WHERE idPage = ?", $_GET['id'], __DIR__ . "/../../docs/images/imageProducts/",   ['defaultProductBig.jpg', 'defaultProductSmall.jpg']);
    deleteImageFiles($link, "SELECT imageReview         AS image FROM reviews    WHERE idPage = ?", $_GET['id'], __DIR__ . "/../../docs/images/imageReviews/",    'defaultReview.jpg');
    deleteImageFiles($link, "SELECT imageBlog           AS image FROM blogs      WHERE idPage = ?", $_GET['id'], __DIR__ . "/../../docs/images/imageBlogs/",      'defaultBlog.jpg');

    function deleteRecords($link, $query, $idPage)
    {
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $idPage);
        mysqli_stmt_execute($stmt);
    }

    deleteRecords($link, "DELETE FROM pages       WHERE id     = ?", $_GET['id']);
    deleteRecords($link, "DELETE FROM socialmedia WHERE idPage = ?", $_GET['id']);
    deleteRecords($link, "DELETE FROM slides      WHERE idPage = ?", $_GET['id']);
    deleteRecords($link, "DELETE FROM fields      WHERE idPage = ?", $_GET['id']);
    deleteRecords($link, "DELETE FROM products    WHERE idPage = ?", $_GET['id']);
    deleteRecords($link, "DELETE FROM reviews     WHERE idPage = ?", $_GET['id']);
    deleteRecords($link, "DELETE FROM blogs       WHERE idPage = ?", $_GET['id']);

    $actionHistories = 'Xoá bỏ';
    $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> thương hiệu \"<b>$pageName</b>\" thành công.";
    logHistory($link, $idUser, $actionHistories, $detailHistories);

    showSuccessDeleteAlert('Đã xóa thương hiệu thành công!', 'Admin/pages/listpage/');
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