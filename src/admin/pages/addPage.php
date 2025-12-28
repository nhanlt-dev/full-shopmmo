<div class="page-layout">
    <?php
    if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [1, 2, 3])) {
        include('src/handlers/layouts/layoutContent/layoutSidebar.php');
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $id = $_SESSION['userData']['id'] ?? '';

        if (isset($_POST['addPage'])) {
            validateCsrfToken($_POST['csrf_tokenAddPage']);
            $defaultName = 'Demo_' . date('Ymd_His');

            $dataInfo = [
                'pageName'                  => $_POST['pageName']               ?: $defaultName,
                'idRepresentativePersion'   => $_POST['idPersion']              ?: '',
                'idCategoryPage'            => $_POST['idCategory']             ?: '',
                'pageHeaderTitle1'          => $_POST['pageHeaderTitle1']       ?: '',
                'pageHeaderTitle2'          => $_POST['pageHeaderTitle2']       ?: '',
                'pageBusinessField'         => $_POST['pageBusinessField']      ?: '',
                'pageDescriptionBanner'     => $_POST['pageDescriptionBanner']  ?: '',
                'pageDescriptionSEO'        => $_POST['pageDescriptionSEO']     ?: '',
                'pageContentIntroduce'      => $_POST['pageContentIntroduce']   ?: '',
                'pageProvince'              => $_POST['pageProvince']           ?: '',
                'pageWard'                  => $_POST['pageWard']               ?: '',
                'pageAddress'               => $_POST['pageAddress']            ?: '',
                'pageStartDate'             => $_POST['pageStartDate']          ?? '',
                'pageEndDate'               => $_POST['pageEndDate']            ?? '',
                'pageStatus'                => $_POST['pageStatus']             ?? '',
                'pagePorpular'              => isset($_POST['pagePorpular'])    ?1 : 0,
                'pageUrl'                   => strtolower(noAccent(str_replace("'", "", $_POST['pageName'] ?: $defaultName)))
            ];

            $dataSocial = [
                'linkZalo'                  => $_POST['linkZalo']      ?: '',
                'linkFacebook'              => $_POST['linkFacebook']  ?: '',
                'linkYoutube'               => $_POST['linkYoutube']   ?: '',
                'linkTiktok'                => $_POST['linkTiktok']    ?: ''
            ];

            $insertPageQuery = "INSERT INTO pages (pageName, idRepresentativePersion, idCategoryPage ,pageHeaderTitle1, pageHeaderTitle2, pageBusinessField, pageDescriptionBanner, pageDescriptionSEO, pageContentIntroduce, pageProvince, pageWard, pageAddress, pageStartDate, pageEndDate, pageStatus,  pagePorpular, pageUrl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $idPage = executeQuery($link, $insertPageQuery,  array_values($dataInfo), true);

            $insertDataParams = array_merge([$idPage], array_values($dataSocial));
            $insertSocialQuery = "INSERT INTO socialmedia (idPage, linkZalo, linkFacebook, linkYoutube, linkTiktok) VALUES (?, ?, ?, ?, ?)";
            executeQuery($link, $insertSocialQuery, $insertDataParams, true);

            uploadImageAndUpdate('pageImageLogo',       $targetPagesDir,       $link, 'pages', $idPage, 'pageImageLogo',      null, ['defaultLogo.jpg']);
            uploadImageAndUpdate('pageImageIntroduce',  $targetIntroduceDir,   $link, 'pages', $idPage, 'pageImageIntroduce', null, ['defaultIntroduce.jpg']);

            $slides = ['imageSlide1', 'imageSlide2', 'imageSlide3'];
            foreach ($slides as $slide) {
                $slideImage = handleFileUpload($_FILES[$slide], $targetSlidesDir, ['defaultSlide.jpg']);
                $insertSlideQuery = "INSERT INTO slides (imageSlide, idPage) VALUES (?, ?)";
                executeQuery($link, $insertSlideQuery, [$slideImage, $idPage], true);
            }

            $fields = [];
            for ($i = 0; $i < 5; $i++) {
                $fieldKey = 'field' . ($i + 1);
                if (isset($_POST[$fieldKey]) && !empty($_POST[$fieldKey])) {
                    $fields[] = mysqli_real_escape_string($link, $_POST[$fieldKey]);
                } else {
                    $fields[] = '';
                }
            }
            foreach ($fields as $businessField) {
                $insertBusinessFieldQuery = "INSERT INTO fields (fieldsTitle, idPage) VALUES (?, ?)";
                executeQuery($link, $insertBusinessFieldQuery, [$businessField, $idPage], true);
            }

            if (!empty($_FILES['imageIntroducePage']['name'])) {
                $imageIntroduce = handleFileUpload($file, $targetIntroducesDir, ['defaultIntroduce.jpg']);
                $insertImageIntroduceQuery = "INSERT INTO introduces (imageIntroduce, idPage) VALUES (?, ?)";
                executeQuery($link, $insertImageIntroduceQuery, [$imageIntroduce, $idPage], true);
            }

            $products = [];
            for ($i = 0; $i < 6; $i++) {
                $titleKey = 'titleProducts' . ($i + 1);
                $imageKey = 'imageProducts' . ($i + 1);
            
                $products[] = [
                    'title' => mysqli_real_escape_string($link, $_POST[$titleKey] ?? ''),
                    'file' => $_FILES[$imageKey] ?? null,
                ];
            }
            
            foreach ($products as $index => $product) {
                $titleProductInfo = $product['title'];
                $newFileNameProduct = handleFileUpload(
                    $product['file'],
                    $targetProductsDir,
                    ($index % 6 < 3) ? ['defaultProductSmall.jpg'] : ['defaultProductBig.jpg']
                );
            
                $insertProductQuery = "INSERT INTO products (titleProduct, imageProduct, idPage) VALUES (?, ?, ?)";
                executeQuery($link, $insertProductQuery, [$titleProductInfo, $newFileNameProduct, $idPage], true);
            }


            $reviews = [];
            for ($i = 0; $i < 3; $i++) {
                $nameKey    = 'nameReviews'     . ($i + 1);
                $jobKey     = 'jobReviews'      . ($i + 1);
                $contentKey = 'contentReviews'  . ($i + 1);
                $imageKey   = 'imageReviews'    . ($i + 1);
                $ratingKey  = 'ratingReviews'   . ($i + 1);

                $reviews[] = [
                    'user'      => mysqli_real_escape_string($link, $_POST[$nameKey]    ?: ''),
                    'job'       => mysqli_real_escape_string($link, $_POST[$jobKey]     ?: ''),
                    'content'   => mysqli_real_escape_string($link, $_POST[$contentKey] ?: ''),
                    'rating'    => mysqli_real_escape_string($link, $_POST[$ratingKey]  ?:  5),
                    'file'      => $_FILES[$imageKey] ?? null,
                ];
            }
            foreach ($reviews as $review) {
                $nameReviewInfo    = $review['user'];
                $jobReviewInfo     = $review['job'];
                $contentReviewInfo = $review['content'];
                $ratingReviewInfo  = $review['rating'];
                $newFileNameReview = handleFileUpload($review['file'], $targetReviewsDir, ['defaultReview.jpg']);
        
                $insertReviewQuery = "INSERT INTO reviews (nameReview, jobReview, contentReview, ratingReview, imageReview, idPage) VALUES (?, ?, ?, ?, ?, ?)";
                executeQuery($link, $insertReviewQuery, [$nameReviewInfo, $jobReviewInfo, $contentReviewInfo, $ratingReviewInfo, $newFileNameReview, $idPage], true);
            }

            $blogs = [];
            for ($i = 0; $i < 3; $i++) {
                $titleKey       = 'titleBlogs' . ($i + 1);
                $descriptionKey = 'descriptionBlogs' . ($i + 1);
                $imageKey       = 'imageBlogs' . ($i + 1);

                $blogs[] = [
                    'title'       => mysqli_real_escape_string($link, $_POST[$titleKey]       ?? ''),
                    'description' => mysqli_real_escape_string($link, $_POST[$descriptionKey] ?? ''),
                    'file'        => $_FILES[$imageKey] ?? null,
                ];
            }
            foreach ($blogs as $blog) {
                $titleBlogInfo          = $blog['title'];
                $descriptionBlogInfo    = $blog['description'];
                $newFileNameBlog = handleFileUpload($blog['file'], $targetBlogsDir, ['defaultBlog.jpg']);
        
                $insertProductQuery = "INSERT INTO blogs (titleBlog, imageBlog, descriptionBlog, idPage) VALUES (?, ?, ?, ?)";
                executeQuery($link, $insertProductQuery, [$titleBlogInfo, $newFileNameBlog, $descriptionBlogInfo, $idPage], true);
            }
            $actionHistories =  'Thêm mới';
            $namePage        = $dataInfo['pageName'];
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> thương hiệu \"<b>$namePage</b>\" thành công.";

            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessInsertAlert('Thêm mới thương hiệu thành công!', 'Admin/pages/listpage/');
        }
    ?>
        <div class="main-content w-100">
            <div class="main-content-inner">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Thêm Mới Trang</h3>
                        <div class="box">
                            <a href="Admin/pages/listpage/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Thương Hiệu</a>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <div class="widget-box-2 mt4r" id="infopage">
                            <h5 class="title">Thông Tin Trang</h5>
                            <fieldset class="box grid-layout-4 mb-0">
                                <div class="box">
                                    <label for="box-avatar">Logo Trang</label>
                                    <div class="box-agent-avt" id="box-avatar">
                                        <div class="avatar borderR0">
                                            <img id="pageImageLogoPreview" class="avatar-img pageImageLogo" src="src/docs/images/imagePages/defaultLogo.jpg" alt="avatar" loading="lazy" width="128" height="128">
                                        </div>
                                        <div class="content uploadfile">
                                            <div class="box-ip">
                                                <input type="file" class="ip-file" id="pageImageLogo" name="pageImageLogo" accept=".png, .jpg, .jpeg">
                                            </div>
                                            <p class="file-requirements"><i>Logo Trang nên có kích thước 120x54 và ở định dạng .jpg hoặc .png</i></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-fieldset">
                                    <label for="pageName">Tên Doanh Ngiệp<span>*</span></label>
                                    <input type="text" id="pageName" name="pageName" class="form-control">
                                </div>
                                <div class="box box-fieldset">
                                    <label for="idPersion">Người đại diện</label>
                                    <div class="box-fieldset">
                                        <select name="idPersion" id="idPersion" class="form-control formSelect">
                                            <?php $pertionQuery = mysqli_query($link, "SELECT * FROM users ORDER BY id ASC");
                                            while ($rowPersion = mysqli_fetch_array($pertionQuery)) {
                                                $persionId      = $rowPersion['id'];
                                                $persionName    = $rowPersion['userName'];
                                                $persionRole    = $rowPersion['userRole'];
                                                // if (in_array($roleUser, [1, 2]) && ($persionRole == 3 || ($roleUser == 1 && $persionRole == 0))) {
                                                if (in_array($roleUser, [1, 2]) && $persionRole == 3) {
                                                    continue;
                                                } else { ?>
                                                    <option value="<?= $persionId ?>"><?= $persionName ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="box box-fieldset">
                                    <label for="idCategory">Danh Mục Thương Hiệu</label>
                                    <div class="box-fieldset">
                                        <select name="idCategory" id="idCategory" class="form-control formSelect">
                                            <?php $categoryQuery = mysqli_query($link, "SELECT * FROM categorypages ORDER BY id ASC");
                                            while ($rowCategory = mysqli_fetch_array($categoryQuery)) {
                                                $categoryID      = $rowCategory['id'];
                                                $categoryPage    = $rowCategory['categoryPage']; ?>
                                                <option value="<?= $categoryID ?>"><?= $categoryPage ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="box grid-layout-4 gap-30 ">
                                <div class="box-fieldset">
                                    <label for="linkZalo">Zalo<span>*</span></label>
                                    <input type="text" id="linkZalo" name="linkZalo" placeholder="zalo.me/" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="linkFacebook">Facebook<span>*</span></label>
                                    <input type="text" id="linkFacebook" name="linkFacebook" placeholder="facebook.com/" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="linkYoutube">Youtube</label>
                                    <input type="text" id="linkYoutube" name="linkYoutube" placeholder="youtube.com/" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="linkTiktok">TikTok</label>
                                    <input type="text" id="linkTiktok" name="linkTiktok" placeholder="tiktok.com/" class="form-control ">
                                </div>
                            </fieldset>
                            <fieldset class="box grid-layout-3 gap-30">
                            <div class="box-fieldset">
                                <label for="pageProvince">Tỉnh/ Thành phố<span></span></label>
                                <select id="pageProvince" name="pageProvince" title="Chọn Tỉnh Thành">
                                    <option value="0">Tỉnh Thành</option>
                                </select>
                            </div>
                            <div class="box-fieldset">
                                <label for="pageWard">Phường/ Xã<span></span></label>
                                <select id="pageWard" name="pageWard" title="Chọn Phường Xã">
                                    <option value="0">Phường Xã</option>
                                </select>
                            </div>
                            <div class="box-fieldset">
                                <label for="pageAddress">Địa Chỉ</label>
                                <input type="text" id="pageAddress" name="pageAddress" class="form-control ">
                            </div>
                        </fieldset>
                        </div>
                        <div class="widget-box-2 mt4r" id="timepage">
                            <h5 class="title">Thời Hạn Kích Hoạt</h5>
                            <fieldset class="box grid-layout-3 gap-30 mb-0">
                                <div class="box-fieldset">
                                    <label for="pageStartDate">Ngày bắt đầu hoạt động (Tháng/ Ngày/ Năm)<span></span></label>
                                    <input type="datetime-local" name="pageStartDate" id="pageStartDate" class="form-control">
                                </div>
                                <div class="box-fieldset">
                                    <label for="pageEndDate">Ngày kết thúc hoạt động (Tháng/ Ngày/ Năm)<span></span></label>
                                    <input type="datetime-local" name="pageEndDate" id="pageEndDate" class="form-control">
                                </div>
                                <div class="box-fieldset">
                                    <fieldset class="box grid-layout-2 gap-30 mb-0">
                                        <div class="box-fieldset">
                                            <label for="pageStatus">Trạng Thái</label>
                                            <?php $status = ['Đang Trống', 'Đã Cho Thuê', 'Đang Có Lỗi']; ?>
                                            <select id="pageStatus" name="pageStatus" title="--Trạng Thái--">
                                                <?php for ($s = 0; $s < count($status); $s++): ?>
                                                    <option value="<?= $s ?>">
                                                        <?= $status[$s] ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <fieldset class="checkbox-item  style-1  ">
                                            <label for="pagePorpular"> Nổi Bật
                                                <input type="checkbox" id="pagePorpular" name="pagePorpular">
                                                <span class="btn-checkbox"></span>
                                            </label>
                                        </fieldset>
                                    </fieldset>
                                </div>
                            </fieldset>
                        </div>
                          <div class="widget-box-2 mt4r" id="keywordpage">
                            <h5 class="title">Tiêu Đề Trang </h5>
                            <fieldset class="box grid-layout-2 gap-30 mb-0">
                                <div class="box-fieldset">
                                    <label for="pageHeaderTitle1">Tiêu đề trái<span>*</span></label>
                                    <input type="text" id="pageHeaderTitle1" name="pageHeaderTitle1" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="pageHeaderTitle2">Tiêu đề phải<span>*</span></label>
                                    <input type="text" id="pageHeaderTitle2" name="pageHeaderTitle2" class="form-control ">
                                </div>
                            </fieldset>
                        </div>
                        <div class="widget-box-2 mt4r" id="slidepage">
                            <h5 class="title">Slide </h5>
                            <span><i>(Nên có kích thước 1920x900 và ở định dạng .jpg hoặc .png để có hiệu quả tốt nhất)</i></span>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i <= 2; $i++): ?>
                                        <div class="swiper-slide mt4r">
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload imageSlidePreview" id="imageSlide<?= $i + 1 ?>-preview" data-src="src/docs/images/imageSlides/defaultSlide.jpg" src="src/docs/images/imageSlides/defaultSlide.jpg" alt="imageSlide">
                                                    </a>
                                                    <div class="list-btn flex gap-8">
                                                        <label for="imageSlide<?= $i + 1 ?>" class="btn-icon find hover-tooltip">
                                                            <i class="fa-light fa-pen-to-square"></i>
                                                            <span class="tooltip">Chỉnh Sửa Slide</span>
                                                        </label>
                                                        <input type="file" name="imageSlide<?= $i + 1 ?>" id="imageSlide<?= $i + 1 ?>" class="ec-image-upload imageSlides hidden" accept=".png, .jpg, .jpeg" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="sw-wrap-btn">
                                    <div class="swiper-button-prev sw-button layout-3-prev">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                                    <div class="swiper-button-next sw-button layout-3-next">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="widget-box-2 mt4r" id="introducepage">
                            <h5 class="title">Giới Thiệu Trang </h5>
                            <fieldset class="box grid-layout-2-5 mb-0">
                                <div class="box box-fieldset">
                                    <label for="pageContentIntroduce">Mô Tả Ngắn (160 - 300 kí tự)<span>*</span></label>
                                    <textarea class="form-control" name="pageContentIntroduce" rows="4" id="pageContentIntroduce"></textarea>
                                </div>
                                <div class="box">
                                    <label for="box-introduce">Ảnh Giới Thiệu Trang</label>
                                    <div class="box-agent-introduce" id="box-introduce">
                                        <div class="introduce">
                                            <img id="pageImageIntroducePreview" class="avatar-img pageImageIntroduce" src="src/docs/images/imageIntroduces/defaultIntroduce.jpg" alt="avatar" loading="lazy" width="128" height="128">
                                        </div>
                                        <div class="content uploadfile">
                                            <p class="file-requirements"><i>Ảnh giới thiệu trang nên có kích thước 600x490 và ở định dạng .jpg hoặc .png</i></p>
                                            <div class="box-ip">
                                                <input type="file" class="ip-file" id="pageImageIntroduce" name="pageImageIntroduce" accept=".png, .jpg, .jpeg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="widget-box-2 mt4r" id="fieldpage">
                            <h5 class="title">Lĩnh Vực Kinh Doanh của Trang</h5>
                            <fieldset class="box ">
                                <div class="box box-fieldset">
                                    <label for="pageBusinessField">Mô tả ngắn lĩnh vực kinh doanh<span>*</span></label>
                                    <input type="text" id="pageBusinessField" name="pageBusinessField" class="form-control ">
                                </div>
                            </fieldset>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="2" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <div class="swiper-slide">
                                            <fieldset class="box mb-0">
                                                <div class="box box-fieldset">
                                                    <label for="field">Lĩnh Vực Kinh Doanh <?= $i + 1 ?></label>
                                                    <input type="text" id="field<?= $i + 1 ?>" name="field<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="sw-wrap-btn">
                                    <div class="swiper-button-prev sw-button layout-3-prev">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                                    <div class="swiper-button-next sw-button layout-3-next">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="bannerpage">
                            <fieldset class="box ">
                                <div class="box box-fieldset">
                                    <label for="pageDescriptionBanner">Mô Tả Chi Tiết Để Seo (160 - 300 kí tự)<span>*</span></label>
                                    <textarea class="form-control" name="pageDescriptionBanner" rows="4" id="pageDescriptionBanner"></textarea>
                                </div>
                            </fieldset>
                        </div>
                        <div class="widget-box-2 mt4r" id="seopage">
                            <h5 class="title">Bài SEO của Trang</h5>
                            <fieldset class="box ">
                                <div class="box box-fieldset">
                                    <label for="pageDescriptionSEO">Mô Tả Chi Tiết bài SEO<span>*</span></label>
                                    <textarea class="form-control" name="pageDescriptionSEO" rows="4" id="pageDescriptionSEO"></textarea>
                                </div>
                            </fieldset>
                        </div>
                        <div class="widget-box-2 mt4r" id="productpage">
                            <h5 class="title">Sản Phẩm của Trang </h5>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i <= 2; $i++): ?>
                                        <div class="swiper-slide mt4r">
                                            <fieldset class="box ">
                                                <div class="box box-fieldset">
                                                    <label for="titleProducts<?= $i + 1 ?>">Tiêu Đề Sản Phẩm <?= $i + 1 ?></label>
                                                    <input type="text" id="titleProducts<?= $i + 1 ?>" name="titleProducts<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload imageProduct" id="imageProducts<?= $i + 1 ?>-preview"
                                                            data-src="src/docs/images/imageProducts/defaultProductSmall.jpg"
                                                            src="src/docs/images/imageProducts/defaultProductSmall.jpg" alt="imageProducts">
                                                    </a>
                                                    <div class="list-btn flex gap-8">
                                                        <label for="imageProducts<?= $i + 1 ?>" class="btn-icon find hover-tooltip">
                                                            <i class="fa-light fa-pen-to-square"></i>
                                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                                        </label>
                                                        <input type="file" name="imageProducts<?= $i + 1 ?>" id="imageProducts<?= $i + 1 ?>" class="ec-image-upload imageProducts hidden" accept=".png, .jpg, .jpeg" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="sw-wrap-btn">
                                    <div class="swiper-button-prev sw-button layout-3-prev">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                                    <div class="swiper-button-next sw-button layout-3-next">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <span>Hình ảnh sản phẩm nhỏ 1300 x 750 <i>(Nên ở định dạng .jpg hoặc .png để có hiệu quả tốt nhất)</i></span>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 3; $i <= 5; $i++): ?>
                                        <div class="swiper-slide mt4r">
                                            <fieldset class="box ">
                                                <div class="box box-fieldset">
                                                    <label for="titleProducts<?= $i + 1 ?>">Tiêu Đề Sản Phẩm <?= $i + 1 ?></label>
                                                    <input type="text" id="titleProducts<?= $i + 1 ?>" name="titleProducts<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload" id="imageProducts<?= $i + 1 ?>-preview"
                                                            data-src="src/docs/images/imageProducts/defaultProductBig.jpg"
                                                            src="src/docs/images/imageProducts/defaultProductBig.jpg" alt="imageProducts">
                                                    </a>
                                                    <div class="list-btn flex gap-8">
                                                        <label for="imageProducts<?= $i + 1 ?>" class="btn-icon find hover-tooltip">
                                                            <i class="fa-light fa-pen-to-square"></i>
                                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                                        </label>
                                                        <input type="file" name="imageProducts<?= $i + 1 ?>" id="imageProducts<?= $i + 1 ?>" class="ec-image-upload imageProducts hidden" accept=".png, .jpg, .jpeg" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="sw-wrap-btn">
                                    <div class="swiper-button-prev sw-button layout-3-prev">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                                    <div class="swiper-button-next sw-button layout-3-next">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <span>Hình ảnh sản phẩm lớn 1300 x 1500 <i>(Nên ở định dạng .jpg hoặc .png để có hiệu quả tốt nhất)</i></span>
                        </div>
                        <div class="widget-box-2 mt4r" id="reviewpage">
                            <h5 class="title">Đánh giá của Trang </h5>
                            <span>Hình ảnh đánh giá 300 x 300 <i>(Nên ở định dạng .jpg hoặc .png để có hiệu quả tốt nhất)</i></span>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i <= 2; $i++): ?>
                                        <div class="swiper-slide mt4r">
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload imageReview" id="imageReviews<?= $i + 1 ?>-preview"
                                                            data-src="src/docs/images/imageReviews/defaultReview.jpg"
                                                            src="src/docs/images/imageReviews/defaultReview.jpg" alt="imageReviews">
                                                    </a>
                                                    <div class="list-btn flex gap-8">
                                                        <label for="imageReviews<?= $i + 1 ?>" class="btn-icon find hover-tooltip">
                                                            <i class="fa-light fa-pen-to-square"></i>
                                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                                        </label>
                                                        <input type="file" name="imageReviews<?= $i + 1 ?>" id="imageReviews<?= $i + 1 ?>" class="ec-image-upload imageReviews hidden" accept=".png, .jpg, .jpeg" />
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset class="box mt4r">
                                                <div class="box box-fieldset">
                                                    <label for="nameReviews<?= $i + 1 ?>">Họ Tên Người <?= $i + 1 ?></label>
                                                    <input type="text" id="nameReviews<?= $i + 1 ?>" name="nameReviews<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box ">
                                                <div class="box box-fieldset">
                                                    <label for="jobReviews<?= $i + 1 ?>">Chức Vụ</label>
                                                    <input type="text" id="jobReviews<?= $i + 1 ?>" name="jobReviews<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box ">
                                                <div class="box box-fieldset">
                                                    <label for="contentReviews<?= $i + 1 ?>">Nội Dung</label>
                                                    <input type="text" id="contentReviews<?= $i + 1 ?>" name="contentReviews<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box">
                                                <div class="box box-fieldset">
                                                    <label for="ratingReview<?= $i + 1 ?>">Đánh giá</label>
                                                    <div class="ec-t-review-rating d-flex jcc" id="review-rating-<?= $i + 1 ?>">
                                                        <?php
                                                        for ($j = 1; $j <= 5; $j++) { ?>
                                                                <i class="fa-duotone fa-solid clstart fa-star rvstartEdit" data-value="<?= $j ?>"></i>
                                                        <?php } ?>
                                                    </div>
                                                    <input type="hidden" name="ratingReviews<?= $i + 1 ?>" id="selected-rating<?= $i + 1 ?>" >
                                                </div>
                                            </fieldset>
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                for (let i = 1; i <= 3; i++) {
                                                    const stars = document.querySelectorAll(`#review-rating-${i} .rvstartEdit`);
                                                    const selectedRatingInput = document.getElementById(`selected-rating${i}`);
                                                    let lastClickTime = 0;
                                                    let lastClickedStar = null;
                                                    let lastHalfStar = null;

                                                    stars.forEach(star => {
                                                        star.addEventListener("click", function() {
                                                            const currentTime = new Date().getTime();
                                                            const ratingValue = parseInt(star.getAttribute("data-value"));
                                                            if (lastClickedStar === star && currentTime - lastClickTime < 300) {
                                                                selectedRatingInput.value = ratingValue - 0.5;
                                                                star.classList.remove("fa-star");
                                                                star.classList.add("fa-duotone", "fa-star-sharp-half");
                                                                lastHalfStar = star;
                                                            } else {
                                                                selectedRatingInput.value = ratingValue;
                                                                stars.forEach(s => s.classList.remove("fa-duotone", "fa-star-sharp-half", "fa-solid", "clstart"));
                                                                stars.forEach((s, index) => {
                                                                    if (index < ratingValue) {
                                                                        s.classList.add("fa-solid", "clstart", "fa-star");
                                                                    } else {
                                                                        s.classList.add("fa-duotone", "fa-solid", "clstart", "fa-star");
                                                                    }
                                                                });
                                                                if (lastHalfStar) {
                                                                    lastHalfStar.classList.remove("fa-star-sharp-half");
                                                                    lastHalfStar.classList.add("fa-star");
                                                                    lastHalfStar = null;
                                                                }
                                                            }

                                                            lastClickTime = currentTime;
                                                            lastClickedStar = star;
                                                        });
                                                    });
                                                }
                                            });
                                        </script>
                                    <?php endfor; ?>
                                </div>
                                <div class="sw-wrap-btn">
                                    <div class="swiper-button-prev sw-button layout-3-prev">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                                    <div class="swiper-button-next sw-button layout-3-next">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="blogpage">
                            <h5 class="title">Tin Tức của Trang </h5>
                            <span>Hình ảnh đánh giá 640 x 420 <i>(Nên ở định dạng .jpg hoặc .png để có hiệu quả tốt nhất)</i></span>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i <= 2; $i++): ?>
                                        <div class="swiper-slide mt4r">
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload imageBlog" id="imageBlogs<?= $i + 1 ?>-preview"
                                                            data-src="src/docs/images/imageBlogs/defaultBlog.jpg"
                                                            src="src/docs/images/imageBlogs/defaultBlog.jpg" alt="imageBlogs">
                                                    </a>
                                                    <div class="list-btn flex gap-8">
                                                        <label for="imageBlogs<?= $i + 1 ?>" class="btn-icon find hover-tooltip">
                                                            <i class="fa-light fa-pen-to-square"></i>
                                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                                        </label>
                                                        <input type="file" name="imageBlogs<?= $i + 1 ?>" id="imageBlogs<?= $i + 1 ?>" class="ec-image-upload imageBlogs hidden" accept=".png, .jpg, .jpeg" />
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset class="box mt4r">
                                                <div class="box box-fieldset">
                                                    <label for="titleBlogs<?= $i + 1 ?>">Tiêu Đề Bài Viết <?= $i + 1 ?></label>
                                                    <input type="text" id="titleBlogs<?= $i + 1 ?>" name="titleBlogs<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box mt4r">
                                                <div class="box box-fieldset">
                                                    <label for="descriptionBlogs<?= $i + 1 ?>">Mô Tả Bài Viết <?= $i + 1 ?></label>
                                                    <input type="text" id="descriptionBlogs<?= $i + 1 ?>" name="descriptionBlogs<?= $i + 1 ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="sw-wrap-btn">
                                    <div class="swiper-button-prev sw-button layout-3-prev">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L5 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="sw-pagination sw-pagination-layout-3 text-center"></div>
                                    <div class="swiper-button-next sw-button layout-3-next">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5L19 12L12 19" stroke="#5C5E61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenAddPage" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="addPage" class="tf-btn style-border pd-10">Lưu & Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overlay-dashboard"></div>
        </div>
</div>
<script>
    function setupImagePreviewSelecttor(inputSelector) {
        document.querySelectorAll(inputSelector).forEach(input => {
            input.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewId = event.target.id + "-preview";
                        const previewImg = document.getElementById(previewId);
                        if (previewImg) {
                            previewImg.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    function setupImagePreviewID(inputSelector, previewSelector) {
        document.getElementById(inputSelector).addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewSelector).src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    setupImagePreviewID("pageImageLogo", "pageImageLogoPreview");
    setupImagePreviewID("pageImageIntroduce", "pageImageIntroducePreview");

    setupImagePreviewSelecttor(".imageSlides");
    setupImagePreviewSelecttor(".imageProducts");
    setupImagePreviewSelecttor(".imageReviews");
    setupImagePreviewSelecttor(".imageBlogs");
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        $(document).ready(function() {
            $.getJSON('https://esgoo.net/api-tinhthanh-new/1/0.htm', function(data_tinh) {
                if (data_tinh.error == 0) {
                    $.each(data_tinh.data, function(key_tinh, val_tinh) {
                        $("#pageProvince").append('<option value="' + val_tinh.id + '">' + val_tinh.full_name + '</option>');
                    });
                    $("#pageProvince").val().change();
                }
            });
            $("#pageProvince").change(function() {
                var idtinh = $(this).val();
                $.getJSON('https://esgoo.net/api-tinhthanh-new/2/' + idtinh + '.htm', function(data_phuong) {
                    if (data_phuong.error == 0) {
                        $("#pageWard").html('<option value="0">Phường Xã</option>');

                        $.each(data_phuong.data, function(key_phuong, val_phuong) {
                            $("#pageWard").append('<option value="' + val_phuong.id + '">' + val_phuong.full_name + '</option>');
                        });
                        $("#pageWard").val();
                    }
                });
            });
        });
    });
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        function initializeCKEditor(elementId) {
            return CKEDITOR.replace(elementId, {
                uiColor: '#e7e7e7',
                language: 'en',
                skin: 'moono',
                width: 'auto',
                height: 350,
                filebrowserImageBrowseUrl: '/src/library/ckfinder/ckfinder.html?Type=Images',
                filebrowserFlashBrowseUrl: '/src/library/ckfinder/ckfinder.html?Type=Flash',
                filebrowserImageUploadUrl: '/src/library/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserFlashUploadUrl: '/src/library/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
            });
        }
        initializeCKEditor('pageContentIntroduce');
        initializeCKEditor('pageDescriptionBanner');
        initializeCKEditor('pageDescriptionSEO');
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const scrollTo = urlParams.get("scrollTo");

        if (scrollTo) {
            let targetElement = document.getElementById(scrollTo);
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({
                        behavior: "smooth"
                    });
                }, 500);
            }
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    });
</script>

<?php     } else { ?>
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