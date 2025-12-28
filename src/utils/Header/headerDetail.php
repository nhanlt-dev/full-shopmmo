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
}
?>
<header id="header-main" class="header header-fixed">
    <div class="header-inner">
        <div class="tf-container xl">
            <div class="row">
                <div class="col-12">
                    <div class="header-inner-wrap">
                        <div class="header-logo">
                            <a href="trangchu" class="site-logo">
                                <img id="logo_header" alt="logo_header" src="src/docs/images/imageSystems/<?= $imageLogoPage ?>">
                            </a>
                        </div>
                        <nav class="main-menu">
                            <ul class="navigation ">
                                <li class="current-menu"><a href="trangchu">Trang Chủ</a></li>
                                <li><a href="gioithieu/">Giới Thiệu</a></li>
                                <li class="has-child "><a href="#">Quảng Bá Doanh Nghiệp</a>
                                   <!-- <ul class="submenu">
                                        <li><a href="quangba/doanhnghiep/">Doanh Nghiệp</a></li>
                                        <li><a href="quangba/cuahang/">Cửa Hàng</a></li>
                                        <li><a href="quangba/daily/">Đại Lý</a></li>
                                        <li><a href="quangba/nhaphanphoi/">Nhà Phân Phối</a></li>
                                        <li><a href="quangba/congty/">Công Ty</a></li>
                                        <li><a href="quangba/tapdoan/">Tập Đoàn</a></li>
                                    </ul> -->
                                </li>
                                <li> <a href="tintuc/"> Tin Tức</a> </li>
                                <li> <a href=" lienhe/">Liên Hệ</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">
                            <div class="phone-number">
                                <div class="icons">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.875 5.625C1.875 12.5283 7.47167 18.125 14.375 18.125H16.25C16.7473 18.125 17.2242 17.9275 17.5758 17.5758C17.9275 17.2242 18.125 16.7473 18.125 16.25V15.1067C18.125 14.6767 17.8325 14.3017 17.415 14.1975L13.7292 13.2758C13.3625 13.1842 12.9775 13.3217 12.7517 13.6233L11.9433 14.7008C11.7083 15.0142 11.3025 15.1525 10.935 15.0175C9.57073 14.5159 8.33179 13.7238 7.30398 12.696C6.27618 11.6682 5.48406 10.4293 4.9825 9.065C4.8475 8.6975 4.98583 8.29167 5.29917 8.05667L6.37667 7.24833C6.67917 7.0225 6.81583 6.63667 6.72417 6.27083L5.8025 2.585C5.75178 2.38225 5.63477 2.20225 5.47004 2.07361C5.30532 1.94498 5.10234 1.87507 4.89333 1.875H3.75C3.25272 1.875 2.77581 2.07254 2.42417 2.42417C2.07254 2.77581 1.875 3.25272 1.875 3.75V5.625Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <a href="tel:<?= $phoneNumberSystem ?>"><?= $phoneNumberSystem ?></a>
                            </div>
                            <div class="box-user tf-action-btns">
                                <div class="user ">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.749 6C15.749 6.99456 15.3539 7.94839 14.6507 8.65165C13.9474 9.35491 12.9936 9.75 11.999 9.75C11.0044 9.75 10.0506 9.35491 9.34735 8.65165C8.64409 7.94839 8.249 6.99456 8.249 6C8.249 5.00544 8.64409 4.05161 9.34735 3.34835C10.0506 2.64509 11.0044 2.25 11.999 2.25C12.9936 2.25 13.9474 2.64509 14.6507 3.34835C15.3539 4.05161 15.749 5.00544 15.749 6ZM4.5 20.118C4.53213 18.1504 5.33634 16.2742 6.73918 14.894C8.14202 13.5139 10.0311 12.7405 11.999 12.7405C13.9669 12.7405 15.856 13.5139 17.2588 14.894C18.6617 16.2742 19.4659 18.1504 19.498 20.118C17.1454 21.1968 14.5871 21.7535 11.999 21.75C9.323 21.75 6.783 21.166 4.5 20.118Z" stroke="#2C2E33" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="name">
                                    <?= isset($_SESSION['userData']['name']) ? $_SESSION['userData']['name'] : 'Tài Khoản' ?>
                                    <i class="icon-CaretDown"></i>
                                </div>
                                <div class=" menu-user">
                                    <?php if (!isset($_SESSION['userData'])) : ?>
                                        <div class="dropdown-item ">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.749 6C15.749 6.99456 15.3539 7.94839 14.6507 8.65165C13.9474 9.35491 12.9936 9.75 11.999 9.75C11.0044 9.75 10.0506 9.35491 9.34735 8.65165C8.64409 7.94839 8.249 6.99456 8.249 6C8.249 5.00544 8.64409 4.05161 9.34735 3.34835C10.0506 2.64509 11.0044 2.25 11.999 2.25C12.9936 2.25 13.9474 2.64509 14.6507 3.34835C15.3539 4.05161 15.749 5.00544 15.749 6ZM4.5 20.118C4.53213 18.1504 5.33634 16.2742 6.73918 14.894C8.14202 13.5139 10.0311 12.7405 11.999 12.7405C13.9669 12.7405 15.856 13.5139 17.2588 14.894C18.6617 16.2742 19.4659 18.1504 19.498 20.118C17.1454 21.1968 14.5871 21.7535 11.999 21.75C9.323 21.75 6.783 21.166 4.5 20.118Z" stroke="#A8ABAE" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="d-flex wrap-login">
                                                <a href="#modalLogin" data-bs-toggle="modal">Đăng Nhập</a>
                                                <span>/</span>
                                                <a href="#modalRegister" data-bs-toggle="modal">Đăng Ký </a>
                                            </div>
                                        </div>
                                    <?php else: 
                                        if ($_SESSION['userData']['role'] == 0) : ?>
                                            <a class="dropdown-item" href="Admin/dashboard/">           <i class="fa-regular fa-objects-column">     </i> Bảng Điều Khiển</a>
                                            <a class="dropdown-item" href="Admin/users/edituser/<?= $_SESSION['userData']['id'] ?>/"><i class="fa-regular fa-image-user"></i> Hồ Sơ</a>
                                             <?php if ($count >= 1) { ?>
                                                <a class="dropdown-item" href="Admin/<?= ($count > 1) ? "pages/listpage/" : "pages/editpage/$pageId/" ?>"><i class="fa-regular fa-file-signature"></i> Tuỳ Chỉnh Trang</a>
                                            <?php } ?>
                                        <?php elseif ($_SESSION['userData']['role'] == 1): ?>
                                            <a class="dropdown-item" href="Admin/users/edituser/<?= $_SESSION['userData']['id'] ?>/"><i class="fa-regular fa-image-user"></i> Hồ Sơ</a>
                                            <a class="dropdown-item" href="Admin/news/listnews/">       <i class="fa-regular fa-newspaper">          </i> Tin Tức</a>
                                            <a class="dropdown-item" href="Admin/pages/listpage/">      <i class="fa-regular fa-globe-pointer">      </i> Thương Hiệu</a>
                                        <?php else: ?>
                                            <a class="dropdown-item" href="Admin/dashboard/">           <i class="fa-regular fa-objects-column">     </i> Bảng Điều Khiển</a>
                                            <a class="dropdown-item" href="Admin/configuration/">       <i class="fa-regular fa-gears">              </i> Hệ Thống </a>
                                            <a class="dropdown-item" href="Admin/users/listuser/">      <i class="fa-regular fa-image-user">         </i> Người Dùng</a>
                                            <a class="dropdown-item" href="Admin/news/listnews/">       <i class="fa-regular fa-newspaper">          </i> Tin Tức</a>
                                            <a class="dropdown-item" href="Admin/pages/listpage/">      <i class="fa-regular fa-globe-pointer">      </i> Thương Hiệu</a>
                                            <a class="dropdown-item" href="Admin/services/listservice/"><i class="fa-regular fa-ballot-check">       </i> Dịch Vụ</a>
                                            <a class="dropdown-item" href="Admin/orders/listorder/">    <i class="fa-regular fa-cart-flatbed-boxes"> </i></i> Đơn Hàng</a>
                                            <a class="dropdown-item" href="Admin/partners/listpartner/"><i class="fa-regular fa-handshake-angle">    </i> Đối Tác </a>
                                            <a class="dropdown-item" href="Admin/reviews/listreview/">  <i class="fa-regular fa-messages">           </i> Đánh Giá </a>
                                            <a class="dropdown-item" href="Admin/comments/listcomment/"><i class="fa-regular fa-comments">           </i> Bình Luận </a>
                                            <a class="dropdown-item" href="Admin/contacts/listcontact/"><i class="fa-regular fa-envelopes-bulk">     </i> Liên Hệ </a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="logout/"><i class="fa-sharp fa-solid fa-right-from-bracket">                  </i> Đăng Xuất</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mobile-button" data-bs-toggle="offcanvas" data-bs-target="#menu-mobile" aria-controls="menu-mobile">
                                <i class="icon-menu"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>