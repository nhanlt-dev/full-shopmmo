<section class="section-help tf-spacing-1" id="sectionServices">
    <div class="tf-container">
        <div class="row">
            <div class="col-12">
                <div class="heading-section text-center">
                    <h2 class="title text-anime-wave">Gói Dịch Vụ Của Chúng Tôi</h2>
                    <p class="text-1 wow animate__fadeInUp animate__animated" data-wow-duration="1.5s"
                        data-wow-delay="0s">Hàng nghìn doanh nghiệp đã tin tưởng và sử dụng dịch vụ của chúng tôi để mở rộng thị trường</p>
                </div>
                <div class="widget-tabs style-2 style-border-primary">
                    <ul class="widget-menu-tab ">
                        <li class="item-title active">
                            1 Năm
                        </li>
                        <li class="item-title">
                            2 Năm <span class="spanDiscountService"> -10%</span>
                        </li>
                    </ul>
                    <div class="widget-content-tab">
                        <?php
                        $services   = [];
                        $sttservice =  0;
                        $serviceIcon = ['fa-display-chart-up', 'fa-display-chart-up-circle-dollar'];
                        $serviceQuery = mysqli_query($link, "SELECT * FROM services ORDER BY id ASC");
                        if (mysqli_num_rows($serviceQuery) > 0) {
                            while ($row = mysqli_fetch_object($serviceQuery)) {
                                $serviceName    = $row->serviceName;
                                $servicePropose = $row->servicePropose;
                                $serviceContent = $row->serviceContent;
                                $serviceUrl     = $row->serviceUrl;
                                $services[] = compact('sttservice' ,'serviceName', 'servicePropose', 'serviceContent', 'serviceUrl');
                                $sttservice++;
                            }
                        }
                        ?>
                        <div class="widget-content-inner active">
                            <div class=" tf-grid-layout md-col-2 ">
                                <?php foreach ($services as $service){ ?>
                                <div class="icons-box default effec-icon animate__zoomIn wow animate__animated" data-wow-duration="1.5s">
                                    <div class="tf-icon text-center">
                                        <i class="fa-regular <?= $serviceIcon[$service['sttservice']] ?> iconService"></i>
                                    </div>
                                    <h4 class="title text-center"><a href="dichvu/<?= $service['serviceUrl'] ?>"><?= $service['serviceName'] ?></a></h4>
                                    <h6 class="text-center"><i>(<?= $service['servicePropose'] ?>)</i></h6>
                                    <div class="text-1 contentService"><?= $service['serviceContent'] ?></div>
                                    <div class="d-flex jcsb">
                                        <a href="https://zalo.me/<?= str_replace(" ", "", $phoneNumberSystem) ?>" class="tf-btn style-border pd-5 mx-auto">Liên Hệ</a>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="widget-content-inner ">
                            <div class="tf-grid-layout md-col-2">
                                <?php foreach ($services as $service){ ?>
                                <div class="icons-box default effec-icon">
                                    <div class="tf-icon text-center">
                                        <i class="fa-regular <?= $serviceIcon[$service['sttservice']] ?> iconService"></i>
                                    </div>
                                    <h4 class="title text-center"><a href="dichvu/<?= $service['serviceUrl'] ?>"><?= $service['serviceName'] ?></a></h4>
                                    <h6 class="text-center"><i>(<?= $service['servicePropose'] ?>)</i></h6>
                                    <div class="text-1 contentService"><?= $service['serviceContent'] ?></div>
                                    <div class="d-flex jcsb">
                                        <a href="https://zalo.me/<?= str_replace(" ", "", $phoneNumberSystem)?>" class="tf-btn style-border pd-5 mx-auto">Liên Hệ</a>
                                    </div>
                                </div>
                                 <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text text-center text-1 " data-wow-duration="2s"> Liên hệ ngay để nhận tư vấn miễn phí <a href="lienhe/" class="fw-7">Gọi ngay!</a></p>
            </div>
        </div>
    </div>

</section>