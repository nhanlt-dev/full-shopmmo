<header class="site-header" id="header">
    <div  class="header-top-area">
        <div class="container">
            <div class="col-lg-12">
                <div class="bwtween-area-header-top">
                    <div class="discount-area">
                        <p class="disc"><?= $pageHeaderTitle1 ?></p>
                    </div>
                    <div class="contact-number-area">
                        <p style='color: #ff0;'><?= $pageHeaderTitle2 ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg transparent-bg static-nav">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="../src/docs/images/imagePages/<?= $pageImageLogo ?>" alt="logo" class="logo-default">
                <img src="../src/docs/images/imagePages/<?= $pageImageLogo ?>" alt="logo" class="logo-scrolled">
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mx-auto ms-xl-auto me-xl-0 ">
                    <li class="nav-item">
                        <a class="nav-link active pagescroll" href="#home">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll scrollupto" href="#about">Giới Thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#our-process">Lĩnh Vực Kinh Doanh</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#portfolio">Dịch Vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#blog">Tin Tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#contact">Liên Hệ</a>
                    </li>
                </ul>
            </div>
        </div>
        <a href="javascript:void(0)" class="d-inline-block sidemenu_btn" id="sidemenu_toggle">
            <span></span> <span></span> <span></span>
        </a>
    </nav>
    <div class="side-menu opacity-0 gradient-bg">
        <div class="inner-wrapper">
            <span class="btn-close btn-close-no-padding" id="btn_sideNavClose"><i></i><i></i></span>
            <nav class="side-nav w-100">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link  pagescroll" href="#home">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll scrollupto" href="#about">Giới Thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#our-process">Lĩnh Vực Kinh Doanh</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#portfolio">Dịch Vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#blog">Tin Tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#contact">Liên Hệ</a>
                    </li>
                </ul>
            </nav>
            <div class="side-footer w-100">
                <ul class="social-icons-simple white top40">
                    <li><a href="<? isset($linkZalo)     ? $linkZalo     : 'javascript:void(0)' ?>" class="zalo"><i class="fab fa-facebook-f"></i> </a> </li>
                    <li><a href="<? isset($linkFacebook) ? $linkFacebook : 'javascript:void(0)' ?>" class="facebook"><i class="fab fa-facebook-f"></i> </a> </li>
                    <li><a href="<? isset($linkYoutube)  ? $linkYoutube  : 'javascript:void(0)' ?>" class="youtube"><i class="fa-brands fa-youtube"></i> </a> </li>
                    <li><a href="<? isset($linkTiktok)   ? $linkTiktok   : 'javascript:void(0)' ?>" class="tiktok"><i class="fa-brands fa-tiktok"></i> </a> </li>
                </ul>
                <p class="whitecolor">
                    Trang <span><b><?= htmlspecialchars($pageName, ENT_QUOTES, 'UTF-8') ?></b></span>
                    được làm ra bởi <a href="http://atvmedia.com">ATV Media</a>
                </p>
            </div>
        </div>
    </div>
    <div id="close_side_menu" class="tooltip"></div>
</header>