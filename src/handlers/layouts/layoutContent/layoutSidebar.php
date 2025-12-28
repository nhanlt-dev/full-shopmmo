<?php
if (!empty($_SESSION['userData']['id'])) {
    $idUser                 = isset($idUser)   ? $idUser   : (isset($_SESSION['userData']['id'])   ? $_SESSION['userData']['id']   : null);
    $roleUser               = isset($roleUser) ? $roleUser : (isset($_SESSION['userData']['role']) ? $_SESSION['userData']['role'] : null);
    $nameUser               = isset($nameUser) ? $nameUser : (isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : null);
    $pageId                 = isset($pageId)   ? $pageId   : (isset($_SESSION['userData']['page']) ? $_SESSION['userData']['page'] : null);
    $resultCheckCountPages  = mysqli_query($link, "SELECT id FROM pages WHERE idRepresentativePersion = $idUser");
    $count                  = mysqli_num_rows($resultCheckCountPages);
    if ($count === 1) {
        $rowPages = mysqli_fetch_assoc($resultCheckCountPages);
        $pageId = $rowPages['id'];
    }

?>
    <div class="wrap-sidebar">
        <div class="sidebar-menu-dashboard">
            <div class="menu-box left-menu">
                <ul class="box-menu-dashboard navigationLeft">
                    <?php if ($_SESSION['userData']['role'] == 0) { ?>
                        <li class="nav-menu-item ">
                            <a class="nav-menu-link" href="Admin/dashboard/"><i class="fa-regular fa-objects-column"></i> Bảng Điều Khiển</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/users/edituser/<?= $idUser ?>/"><i class="fa-regular fa-image-user"></i> Hồ Sơ</a>
                        </li>
                        <?php if ($count >= 1) { ?>
                            <li class="nav-menu-item ">
                                <a class="nav-menu-link" href="Admin/<?= ($count > 1) ? "pages/listpage/" : "pages/editpage/$pageId/" ?>"><i class="fa-regular fa-file-signature"></i> Tuỳ Chỉnh Trang</a>
                            </li>
                        <?php } ?>
                    <?php } elseif ($_SESSION['userData']['role'] == 1) { ?>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/users/edituser/<?= $idUser ?>/"><i class="fa-regular fa-image-user"></i> Hồ Sơ</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/news/listnews/"><i class="fa-regular fa-newspaper"></i> Tin Tức</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/pages/listpage/"><i class="fa-regular fa-globe-pointer"></i> Thương Hiệu</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-menu-item ">
                            <a class="nav-menu-link" href="Admin/dashboard/"><i class="fa-regular fa-objects-column"></i> Bảng Điều Khiển</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/configuration/"><i class="fa-regular fa-gears"></i> Hệ Thống </a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/users/listuser/"><i class="fa-regular fa-image-user"></i> Người Dùng</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/news/listnews/"><i class="fa-regular fa-newspaper"></i> Tin Tức</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/pages/listpage/"><i class="fa-regular fa-globe-pointer"></i> Thương Hiệu</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/services/listservice/"><i class="fa-regular fa-ballot-check"></i> Dịch Vụ</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/orders/listorder/"><i class="fa-regular fa-cart-flatbed-boxes"></i></i> Đơn Hàng</a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/partners/listpartner/"><i class="fa-regular fa-handshake-angle"></i> Đối Tác </a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/reviews/listreview/"><i class="fa-regular fa-messages"></i> Đánh Giá </a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/comments/listcomment/"><i class="fa-regular fa-comments"></i> Bình Luận </a>
                        </li>
                        <li class="nav-menu-item">
                            <a class="nav-menu-link" href="Admin/contacts/listcontact/"><i class="fa-regular fa-envelopes-bulk"></i> Liên Hệ </a>
                        </li>
                    <?php } ?>
                    <li class="nav-menu-item">
                        <a class="nav-menu-link" href="javascript:void(0)" onclick="showConfirmAlert('Bạn có chắc chắn muốn đăng xuất?', 'logout/');">
                            <i class="fa-sharp fa-solid fa-right-from-bracket"></i> Đăng Xuất
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            var currentPath = window.location.pathname;
            var urlGroups = ["/dashboard/", "/news/", "/users/", "/pages/", "/services/", "/partners/", "/reviews/", "/comments/", "/contacts/", "/configuration/"];

            $(".nav-menu-item").each(function() {
                var link = $(this).find("a").attr("href");
                var isActive = urlGroups.some(function(urlGroup) {
                    return currentPath.includes(urlGroup) && link.includes(urlGroup);
                });

                if (isActive) {
                    $(this).addClass("active");
                } else {
                    $(this).removeClass("active");
                }
            });

            if ($(".nav-menu-item").length > 0) {
                $("#footer").hide();
            }
            if ($(".nav-menu-item").length > 0) {
                $("#header-main").removeClass("header-fixed").addClass("dashboard");
            } else {
                $("#header-main").removeClass("dashboard").addClass("header-fixed");
            }
        });
    </script>
<?php } else { ?>
    <script>
        Swal.fire({
            title: 'Error',
            text: 'Bạn chưa đăng nhập để truy cập trang này!',
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