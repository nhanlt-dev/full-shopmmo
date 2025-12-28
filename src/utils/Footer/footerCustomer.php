<?php include('../../../src/utils/Footer/iconlienheCustomer.php') ?>
<footer id="site-footer" class="footer">
    <div class="container">
        <div  class="row">
             <div class="footer-top wow fadeInUp" data-wow-delay="300ms">
 <div class="wrap-content flex flex-wrap items-start justify-between">
 <div class="items-footer">
 <img src="https://quangbathuonghieu.com.vn/src/docs/images/common/icon1.png" alt="">
 <div class="info-items-footer">
 <span class="mb-0"><?= $completeAddress ?></span>
 </div>
 </div>
 <div class="items-footer">
 <img src="https://quangbathuonghieu.com.vn/src/docs/images/common/icon2.png" alt="">
 <div class="info-items-footer">
 <p class="mb-0">Điện thoại:</p>
 <span class="mb-0"><?= $userNumberPhone ?></span>
 </div>
 </div>
 <div class="items-footer">
 <img src="https://quangbathuonghieu.com.vn/src/docs/images/common/icon3.png" alt="">
 <div class="info-items-footer">
 <p class="mb-0">Email:</p>
 <span class="mb-0"><?= $userEmail ?></span>
 </div>
 </div>
 <div class="items-footer">
 <img src="https://quangbathuonghieu.com.vn/src/docs/images/common/icon4.png" alt="">
 <div class="info-items-footer">
 <p class="mb-0">Người đại diện:</p>
 <span class="mb-0"><?= $userName ?></span>
 </div>
 </div>
 </div>
 </div>
        </div>
        <div class="row pt-5 wow fadeInDown" data-wow-delay="500ms">
            <div class="col-lg-6 col-md-6 col-sm-6 ">
                <div class="footer_panel padding_bottom_half bottom20 ">
                    <!--<div class="whitecolor bottom25">Liên kết với chúng tôi</div>-->
                    <p class="pagename" style="text-transform: uppercase;"> <?= $pageName ?></p>
                    <div class="d-table w-100 address-item whitecolor bottom25">
                        <p class="d-table-cell align-middle bottom0" style="line-height: 30px;">
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= $encodedAddress ?>" target="_blank" class=" bottom25"><i class="fa-solid fa-map-location-dot" style="margin-right:10px" ></i> <?= $completeAddress ?></a>
                            <a class="d-block" href="mailto:<?= $userEmail ?>"><i class="fa-solid fa-envelope" style="margin-right:10px" ></i> <?= $userEmail ?></a>
                            <a href="tel:+<?= $userNumberPhone ?>"><i class="fa-solid fa-phone-office" style="margin-right:10px" ></i> <?= $userNumberPhone ?></a>
                        </p>
                    </div>
                    <ul class="social-icons white ">
                        <li><a href="<? isset($linkFacebook) ? $linkFacebook : 'javascript:void(0)' ?>" class="facebook"><i class="fab fa-facebook-f"></i> </a> </li>
                        <li><a href="<? isset($linkYoutube)  ? $linkYoutube  : 'javascript:void(0)' ?>" class="youtube"><i class="fa-brands fa-youtube"></i> </a> </li>
                        <li><a href="<? isset($linkTiktok)   ? $linkTiktok   : 'javascript:void(0)' ?>" class="tiktok"><i class="fa-brands fa-tiktok"></i> </a> </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer_panel padding_bottom_half bottom20 ps-0 ps-lg-5">
                    <!--<div class="whitecolor bottom25">Điều Hướng</div>-->
                    <ul class="links">
                        <li><a href="#home" class="pagescroll">Trang Chủ</a></li>
                        <li><a href="#about" class="pagescroll scrollupto">Giới Thiệu</a></li>
                        <li><a href="#our-process" class="pagescroll">Dịch Vụ</a></li>
                        <li><a href="#portfolio" class="pagescroll">Sản Phẩm</a></li>
                        <li><a href="#blog" class="pagescroll">Tin Tức</a></li>
                        <li><a href="#contact" class="pagescroll">Liên Hệ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer_panel pb2rem">
                    <img src="../src/docs/images/common/quangbaADS.png" alt="imageServices" class="qrCode_footer">
                </div>
            </div>
        </div>
        
        
         <div class="footer-powered bg-[#5172FD]">
 <div class="wrap-content py-[15px] wow fadeInUp" data-wow-delay="1s"">
     <p class="copyright mb-0">Website này <b>©</b> được tạo bởi <b> ATV Media</b> - Bạn muốn có website tương tự, hãy liên hệ <b>Hotline: 0348 45 43 48</b> để được tư vấn miễn phí. </p> 
 </div>
    </div>
</footer>

