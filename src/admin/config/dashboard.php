<div class="page-layout">
    <?php include('src/handlers/layouts/layoutContent/layoutSidebar.php');
    $uRole = isset($_SESSION['userData']['role']) ? $_SESSION['userData']['role'] : '';
    $count = isset($count) ? $count : '';
    $resultSumOrder = mysqli_query($link, "SELECT SUM(priceService) AS total FROM orders");
        $rowSumOrder = mysqli_fetch_assoc($resultSumOrder);
    ?>
    <div class="main-content w-100">
        <?php if (!empty($uRole) && in_array($uRole, [2, 3])) { ?>
            <div class="main-content-inner  ">
                <div class="button-show-hide show-mb">
                    <span class="body-1">Hiển Thị Bảng Điều Khiển</span>
                </div>
                <div class="flat-counter-v2 tf-counter">
                    <div class="counter-box">
                        <div class="box-icon">
                            <span class="icon "><i class="fa-regular fa-image-user iconDashboard"></i></span>
                        </div>
                        <div class="content-box">
                            <div class="title-count text-variant-1">Khách Hàng</div>
                            <div class="box-count d-flex align-items-end">
                                <div class="number"><?= @mysqli_num_rows(mysqli_query($link, "SELECT * FROM users Where userRole = 0")); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="counter-box">
                        <div class="box-icon">
                            <span class="icon "><i class="fa-regular fa-globe-pointer iconDashboard"></i></span>
                        </div>
                        <div class="content-box">
                            <div class="title-count text-variant-1">Tổng Thương Hiệu</div>
                            <div class="box-count d-flex align-items-end">
                                <div class="number"><?= @mysqli_num_rows(mysqli_query($link, "SELECT * FROM pages")); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="counter-box">
                        <div class="box-icon">
                            <span class="icon "><i class="fa-regular fa-file-signature iconDashboard"></i></span>
                        </div>
                        <div class="content-box">
                            <div class="title-count text-variant-1">Trang Đã Cho Thuê</div>
                            <div class="d-flex align-items-end">
                                <div class="number"><?= @mysqli_num_rows(mysqli_query($link, "SELECT * FROM orders")); ?></div>
                            </div>

                        </div>
                    </div>
                    <div class="counter-box">
                        <div class="box-icon">
                            <span class="icon"><i class="fa-regular fa-money-bills iconDashboard"></i></span>
                        </div>
                        <div class="content-box">
                            <div class="title-count text-variant-1">Doanh Thu</div>
                            <div class="d-flex align-items-end">
                                <div class="number"><?= number_format($rowSumOrder['total'] ?? 0, 0, ',', '.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 ">
                        <div class="widget-box-2 wd-listing mb-24">
                            <h3 class="title">Quản Lý Thương Hiệu</h3>
                            <div class="table-responsive">
                                <table id="responsive-data-table" class="table">
                                    <thead>
                                        <tr>
                                            <th class=" fw-6">#</th>
                                            <th class=" fw-6">Tên khách hàng</th>
                                            <th class=" fw-6">Tên Thương Hiệu</th>
                                            <th class=" fw-6">Ảnh Thương Hiệu</th>
                                            <th class=" fw-6">Thời Hạn</th>
                                            <th class=" fw-6">Chi Phí</th>
                                            <th class=" fw-6">Trạng Thái</th>
                                            <th class=" text-center fw-6">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $currentDateTime = new DateTime();
                                        $pageQuery = mysqli_query($link, "SELECT p.*, u.userName, o.priceService FROM pages AS p 
                                                                                                Inner Join users AS u ON u.id = p.idRepresentativePersion 
                                                                                                Inner Join orders AS o ON o.idPage = p.id 
                                                                                                Where u.userRole = 0 ORDER BY id DESC ");
                                        if (mysqli_num_rows($pageQuery) > 0) {
                                            $dataPage = [];
                                            $sttPage  =  0;
                                            $status = ['Đang Trống', 'Đã Cho Thuê', 'Đang Có Lỗi'];

                                            while ($rowPage = mysqli_fetch_object($pageQuery)) {
                                                $idPage         = $rowPage->id;
                                                $pageName       = $rowPage->pageName;
                                                $userName       = $rowPage->userName;
                                                $pageImageLogo  = $rowPage->pageImageLogo;
                                                $pageEndDate    = new DateTime($rowPage->pageEndDate);
                                                $pageStatus     = $rowPage->pageStatus;
                                                $priceService   = $rowPage->priceService;
                                                $pageUrl        = $rowPage->pageUrl;
                                                $sttPage++;

                                                $interval = $currentDateTime->diff($pageEndDate);
                                                $daysRemaining = $interval->days;
                                                if ($pageEndDate < $currentDateTime) {
                                                    $daysRemaining = 0;
                                                }

                                                $dataPage[] = compact('sttPage', 'idPage', 'pageName', 'userName', 'pageImageLogo', 'daysRemaining', 'pageStatus', 'priceService', 'pageUrl');
                                            }
                                            foreach ($dataPage as $pages) { ?>
                                                <tr class="datarow">
                                                    <td class="pl1-4rem">
                                                        <div class="h4rem"><?= $pages['sttPage'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div><?= $pages['userName'] ?></div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a href="<?= $pages['pageUrl'] ?>/"><?= $pages['pageName'] ?></a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a href="<?= $pages['pageUrl'] ?>/"><img src="src/docs/images/imagePages/<?= $pages['pageImageLogo'] ?>" alt="<?= $pages['pageName'] ?>" class="imagePageList"></a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div><?= ($pages['daysRemaining'] > 0) ? 'Còn ' .  $pages['daysRemaining'] . ' Ngày' : 'Hết Hạn' ?> </div>
                                                    </td>
                                                    <td>
                                                        <div><?= number_format($pages['priceService']) ?> VND</div>
                                                    </td>
                                                    <td>
                                                        <div><?= $status[$pages['pageStatus']] ?></div>
                                                    </td>
                                                    <td>
                                                        <ul class="list-action">
                                                            <li class="d-flex jcsa">
                                                                <a href="Admin/pages/editpage/<?= $pages['idPage'] ?>/" class="edit-file item">
                                                                    <i class="fa-regular fa-eye"></i> <b>Xem Thêm</b>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <p>Không có dữ liệu nào!</p>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } elseif ($uRole == 0) {
            if ($count > 1) { ?>
                <div class="main-content-inner style-3">
                    <div class="widget-box-2 wd-listing">
                        <div class="d-flex jcsb">
                            <h3 class="title">Danh Sách Thương Hiệu</h3>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="responsive-data-table" class="table">
                                        <thead>
                                            <tr>
                                                <th class="w5  fw-6">#</th>
                                                <th class="w40 fw-6">Tên Thương Hiệu</th>
                                                <th class="w15 fw-6">Danh Mục</th>
                                                <th class="w10 fw-6">Hình Ảnh</th>
                                                <th class="w15 text-center fw-6">Hành Động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $pageQuery = mysqli_query($link, "SELECT p.*, u.userName, cp.categoryPage FROM pages AS p
                                                                                                   Inner Join users AS u ON u.id = p.idRepresentativePersion
                                                                                                   Inner Join categorypages AS cp ON cp.id = p.idCategoryPage
                                                                                                   WHERE p.idRepresentativePersion = $idUser
                                                                                                   ORDER BY id DESC ");
                                            if (mysqli_num_rows($pageQuery) > 0) {
                                                $dataPages = [];
                                                while ($rowPage = mysqli_fetch_object($pageQuery)) {
                                                    $idPage          = $rowPage->id;
                                                    $pageName        = $rowPage->pageName;
                                                    $userName        = $rowPage->userName;
                                                    $categoryPage    = $rowPage->categoryPage;
                                                    $pageImageLogo   = $rowPage->pageImageLogo;
                                                    $pageUrl         = $rowPage->pageUrl;

                                                    $dataPages[] = compact('idPage', 'pageName', 'userName', 'categoryPage', 'pageImageLogo', 'pageUrl');
                                                }
                                                foreach ($dataPages as $page) { ?>
                                                    <tr class="datarow">
                                                        <td class="pl1-4rem">
                                                            <div class="">
                                                                <?= $page['idPage'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <a href="<?= $page['pageUrl'] ?>"><?= $page['pageName'] ?></a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div><?= $page['categoryPage'] ?></div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <img src="src/docs/images/imagePages/<?= $page['pageImageLogo'] ?>" alt="<?= $page['pageName'] ?>" class="imagePageList">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <ul class="list-action">
                                                                <li class="d-flex jcsa">
                                                                    <a href="Admin/previewCustomerPage/<?= $page['idPage'] ?>/" class="edit-file item btn btn-secondary p1rem">
                                                                        <b>Xem Trước</b>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <p>Không có dữ liệu nào!</p>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay-dashboard"></div>
            <?php } elseif ($count === 1) {
                $resultPageQuery = mysqli_query($link, "SELECT * FROM pages WHERE id = $pageId");
                if ($resultPageQuery) {
                    $pageRow = mysqli_fetch_assoc($resultPageQuery);
                    if ($pageRow) {
                        $pageUrl   = $pageRow['pageUrl'];
                    }
                } ?>
                <iframe class="iframeDashboard" src="<?= $pageUrl ?>/" width="100%" height="100%" frameborder="0"></iframe>
                <div class="overlay-dashboard"></div>?>
            <?php }else{ ?>
                <img src="src/docs/images/common/notifyRentPage.png" alt="notify" class="imageNotify"/>
            <?php } ?>
            <div class="overlay-dashboard"></div>
        <?php } else { ?>
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
    </div>
</div>