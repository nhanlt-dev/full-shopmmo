<?php
$idUser   = isset($idUser)   ? $idUser   : (isset($_SESSION['userData']['id'])   ? $_SESSION['userData']['id']   : null);
$nameUser = isset($nameUser) ? $nameUser : (isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : null);

$stmt = mysqli_prepare($link, "SELECT * FROM comments WHERE id = ?");
mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
mysqli_stmt_execute($stmt);
$resultCheckImage = mysqli_stmt_get_result($stmt);
$row_resuilt = mysqli_fetch_array($resultCheckImage);

$idNews    = $row_resuilt['idNews'];

$deleteQuery = "DELETE FROM comments WHERE id = ?";
$stmtDelete = mysqli_prepare($link, $deleteQuery);
mysqli_stmt_bind_param($stmtDelete, "s", $_GET['id']);
mysqli_stmt_execute($stmtDelete);

$actionHistories = 'Xoá bỏ';
$detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> bình luận bài viết id \"<b>$idNews</b>\" thành công.";
logHistory($link, $idUser, $actionHistories, $detailHistories);
showSuccessDeleteAlert('Đã xóa đánh giá thành công!', 'Admin/comments/listcomment/');
