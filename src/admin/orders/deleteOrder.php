<?php
$idUser   = isset($idUser)   ? $idUser   : (isset($_SESSION['userData']['id'])   ? $_SESSION['userData']['id']   : null);
$nameUser = isset($nameUser) ? $nameUser : (isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : null);

$stmt = mysqli_prepare($link, "SELECT o.*, u.userName, p.pageName FROM orders AS o
                                                        Inner Join users AS u ON u.id = o.idUser
                                                        Inner Join pages AS p ON p.id = o.idPage
                                                        WHERE o.id = ?");
mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
mysqli_stmt_execute($stmt);
$resultCheckImage = mysqli_stmt_get_result($stmt);
$row_resuilt = mysqli_fetch_array($resultCheckImage);

$pageName    = $row_resuilt['pageName'];
$userName    = $row_resuilt['userName'];

$deleteQuery = "DELETE FROM orders WHERE id = ?";
$stmtDelete = mysqli_prepare($link, $deleteQuery);
mysqli_stmt_bind_param($stmtDelete, "s", $_GET['id']);
mysqli_stmt_execute($stmtDelete);

$actionHistories = 'Xoá bỏ';
$detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> đơn hàng thương hiệu \"<b>" . $pageName . "</b>\" cho khách hàng \"<b>" . $userName . "</b>\" thành công.";
logHistory($link, $idUser, $actionHistories, $detailHistories);
showSuccessDeleteAlert('Đã xóa đơn hàng thành công!', 'Admin/orders/listorder/');
