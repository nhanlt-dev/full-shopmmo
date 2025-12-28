<div class="page-layout">
    <?php include('src/handlers/layouts/layoutContent/layoutSidebar.php');
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $idPage = $_GET['id'] ?? '';
    $idUser = $_SESSION['userData']['id'] ?? '';
    if (!empty($idUser)) {
        $queryInfoPage = "SELECT p.*, u.userName,
            (SELECT GROUP_CONCAT(CONCAT_WS('|', s.id, s.imageSlide) SEPARATOR '#') FROM slides AS s WHERE s.idPage = p.id) AS slides,
            (SELECT GROUP_CONCAT(CONCAT_WS('|', f.id, f.fieldsTitle) SEPARATOR '#') FROM fields AS f WHERE f.idPage = p.id) AS fields,
            (SELECT GROUP_CONCAT(CONCAT_WS('|', pr.id, pr.titleProduct, pr.imageProduct) SEPARATOR '#') FROM products AS pr WHERE pr.idPage = p.id) AS products,
            (SELECT GROUP_CONCAT(CONCAT_WS('|', r.id, r.nameReview, r.jobReview, r.contentReview, r.imageReview, r.ratingReview) SEPARATOR '#') FROM reviews AS r WHERE r.idPage = p.id) AS reviews,
            (SELECT GROUP_CONCAT(CONCAT_WS('|', b.id, b.titleBlog, b.imageBlog, b.descriptionBlog) SEPARATOR '#')  FROM blogs AS b  WHERE b.idPage = p.id) AS blogs
        FROM pages AS p
        INNER JOIN users AS u ON u.id = p.idRepresentativePersion
        WHERE p.id = ?";
    if ($stmt = mysqli_prepare($link, $queryInfoPage)) {
        mysqli_stmt_bind_param($stmt, 'i', $idPage);
        mysqli_stmt_execute($stmt);
        $resultInfoPage = mysqli_stmt_get_result($stmt);

        if ($resultInfoPage && mysqli_num_rows($resultInfoPage) > 0) {
            $pageInfoRow = mysqli_fetch_assoc($resultInfoPage);

            $pageImageLogo              = $pageInfoRow["pageImageLogo"];
            $idRepresentativePersion    = $pageInfoRow["idRepresentativePersion"];
            $idCategoryPage             = $pageInfoRow["idCategoryPage"];
            $pageName                   = $pageInfoRow["pageName"];
            $userName                   = $pageInfoRow["userName"];
            $pageBusinessField          = $pageInfoRow["pageBusinessField"];
            $pageHeaderTitle1           = $pageInfoRow["pageHeaderTitle1"];
            $pageHeaderTitle2           = $pageInfoRow["pageHeaderTitle2"];
            $pageDescriptionBanner      = $pageInfoRow["pageDescriptionBanner"];
            $pageDescriptionSEO         = $pageInfoRow["pageDescriptionSEO"];
            $pageImageIntroduce         = $pageInfoRow["pageImageIntroduce"];
            $pageContentIntroduce       = $pageInfoRow["pageContentIntroduce"];
            $pageProvince               = $pageInfoRow["pageProvince"];
            $pageWard                   = $pageInfoRow["pageWard"];
            $pageAddress                = $pageInfoRow["pageAddress"];
            $pageStartDate              = $pageInfoRow["pageStartDate"];
            $pageEndDate                = $pageInfoRow["pageEndDate"];
            $pageStatus                 = $pageInfoRow["pageStatus"];
            $pagePorpular               = $pageInfoRow["pagePorpular"];
            $pageUrl                    = $pageInfoRow["pageUrl"];

            $querySocialPage = " SELECT linkZalo, linkFacebook, linkYoutube, linkTiktok FROM socialmedia WHERE idPage = ?";
            if ($stmtSocial = mysqli_prepare($link, $querySocialPage)) {
                mysqli_stmt_bind_param($stmtSocial, 'i', $idPage);
                mysqli_stmt_execute($stmtSocial);
                $resultSocialPage = mysqli_stmt_get_result($stmtSocial);

                if ($resultSocialPage && mysqli_num_rows($resultSocialPage) > 0) {
                    $pageSocialRow  = mysqli_fetch_assoc($resultSocialPage);
                    $linkZalo       = $pageSocialRow["linkZalo"]     ?: '';
                    $linkFacebook   = $pageSocialRow["linkFacebook"] ?: '';
                    $linkYoutube    = $pageSocialRow["linkYoutube"]  ?: '';
                    $linkTiktok     = $pageSocialRow["linkTiktok"]   ?: '';
                } else {
                    $linkZalo = $linkFacebook = $linkYoutube = $linkTiktok = '';
                }
            }

            $slideData   = explode('#', $pageInfoRow['slides']);
            $idSlides = $imageSlides = [];
            foreach ($slideData as $slide) {
                $slideparts           = explode('|', $slide);
                if ($slideparts[0]    !== null) {
                    $idSlides[]       = $slideparts[0] ?: null;
                    $imageSlides[]    = $slideparts[1] ?: '';
                }
            }
            $fieldData = explode('#', $pageInfoRow['fields']);
            $idFields = $fieldTitles = [];
            foreach ($fieldData as $field) {
                $fieldparts = explode('|', $field);
                if ($fieldparts[0] !== null) {
                    $idFields[]     = $fieldparts[0] ?: null;
                    $fieldTitles[]  = $fieldparts[1] ?: '';;
                }
            }
            $productData    = explode('#', $pageInfoRow['products']);
            $idProducts     = $titleProducts = $imageProducts = [];
            foreach ($productData as $product) {
                $productparts          = explode('|', $product);
                if ($productparts[0]   !== null) {
                    $idProducts[]      = $productparts[0] ?: null;
                    $titleProducts[]   = $productparts[1] ?: '';
                    $imageProducts[]   = $productparts[2] ?: '';
                }
                $reviewsData    = explode('#', $pageInfoRow['reviews']);
                $idReviews = $imageReviews = $nameReviews = $jobReviews = $contentReviews = $ratingReview = [];
                foreach ($reviewsData as $review) {
                    $reviewparts = explode('|', $review);
                    if (!empty($reviewparts[0])) {
                        $idReviews[]      = $reviewparts[0];
                        $nameReviews[]    = $reviewparts[1] ?? '';
                        $jobReviews[]     = $reviewparts[2] ?? '';
                        $contentReviews[] = $reviewparts[3] ?? '';
                        $imageReviews[]   = $reviewparts[4] ?? '';
                        $ratingReview[]   = $reviewparts[5] ?? '';
                    }
                }

                $blogsData = explode('#', $pageInfoRow['blogs']);
                $idBlogs = $titleBlogs = $imageBlogs = $descriptionBlogs = [];
                foreach ($blogsData as $blog) {
                    $blogparts = explode('|', $blog);
                    if (!empty($blogparts[0])) {
                        $idBlogs[]          = $blogparts[0];
                        $titleBlogs[]       = $blogparts[1] ?? '';
                        $imageBlogs[]       = $blogparts[2] ?? '';
                        $descriptionBlogs[] = $blogparts[3] ?? '';
                    }
                }
            }
        }

        if (isset($_POST['updateInfomation'])) {
            validateCsrfToken($_POST['csrf_tokenInfomation']);
            $dataInfo = [
                'pageName'                  => $_POST['pageName']           ?: '',
                'idRepresentativePersion'   => $_POST['idPersion']          ?? $idRepresentativePersion,
                'idCategoryPage'            => $_POST['idCategory']         ?? $idCategoryPage,
                'pageProvince'              => $_POST['pageProvince']       ?? '',
                'pageWard'                  => $_POST['pageWard']           ?? '',
                'pageAddress'               => $_POST['pageAddress']        ?? ''
            ];
            $dataSocial = [
                'linkZalo'      => $_POST['linkZalo']      ?? '',
                'linkFacebook'  => $_POST['linkFacebook']  ?? '',
                'linkYoutube'   => $_POST['linkYoutube']   ?? '',
                'linkTiktok'    => $_POST['linkTiktok']    ?? '',
            ];
            if (!empty($dataInfo['pageName'])) {
                $updatePageQuery = "UPDATE pages  SET
                pageName                = ?,
                idRepresentativePersion = ?,
                idCategoryPage          = ?,
                pageProvince            = ?,
                pageWard                = ?,
                pageAddress             = ?
                WHERE id                = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge(array_values($dataInfo), [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
                uploadImageAndUpdate('pageImageLogo', $targetPagesDir,  $link, 'pages', $idPage, 'pageImageLogo', $pageImageLogo, ['defaultLogo.jpg']);
            } else {
                handleError('Tên Thương Hiệu không hợp lệ!!');
            }
            if (!empty($idPage)) {
                $updateSocialQuery  = "UPDATE socialmedia SET
                    linkZalo        = ?,
                    linkFacebook    = ?,
                    linkYoutube     = ?,
                    linkTiktok      = ?
                WHERE idPage        = ?";
                $updateDataParams = array_merge(array_values($dataSocial), [$idPage]);

                if (!executeQuery($link, $updateSocialQuery, $updateDataParams)) {
                    handleError('Cập nhật thông tin mạng xã hội không thành công!');
                }
            } else {
                handleError('ID trang không hợp lệ!');
            }
            $actionHistories = 'Chỉnh sửa thông tin';
            
            if ($pageName !== $dataInfo['pageName']) {
                $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành \"<b>{$dataInfo['pageName']}</b>\" thành công.";
            } else {
                $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            }
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật thông tin trang thành công!', "Admin/pages/editpage/$idPage/", 'infopage');
        }
        if (isset($_POST['updateDateTime'])) {
            validateCsrfToken($_POST['csrf_tokenDateTime']);
            $datadatetime = [
                'pageStartDate'  => $_POST['pageStartDate'] ?: '',
                'pageEndDate'    => $_POST['pageEndDate']   ?: '',
                'pageStatus'     => $_POST['pageStatus']    ?: '',
                'pagePorpular'   => isset($_POST['pagePorpular']) ? 1 : 0
            ];

            if (!empty($idPage)) {
                $updateDateTimeQuery = "UPDATE pages SET
                pageStartDate           = ?,
                pageEndDate             = ?,
                pageStatus              = ?,
                pagePorpular            = ?
                WHERE id                = ?";
                $result = executeQuery($link, $updateDateTimeQuery, array_merge(array_values($datadatetime), [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
            } else {
                handleError('ID thương hiệu không hợp lệ!!');
            }

            $actionHistories = 'Chỉnh sửa thời hạn';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật thông tin trang thành công!', "Admin/pages/editpage/$idPage/", 'timepage');
        }
        if (isset($_POST['updateSlide'])) {
            validateCsrfToken($_POST['csrf_tokenSlide']);

            $slides = [];
            for ($i = 1; $i <= count($imageSlides); $i++) {
                $slides[] = 'imageSlide' . $i;
            }
            foreach ($slides as $index => $slide) {
                $oldImage   = $imageSlides[$index]  ?: null;
                $idSlide    = $idSlides[$index]      ?: null;
                uploadImageAndUpdate($slide, $targetSlidesDir,  $link, 'slides', $idSlide, 'imageSlide', $oldImage, ['defaultSlide.jpg']);
            }
            $actionHistories =  'Chỉnh sửa Slide';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật Slide thành công!', "Admin/pages/editpage/$idPage/", 'slidepage');
        }
        if (isset($_POST['updateHeaderTitle'])) {
            validateCsrfToken($_POST['csrf_tokenHeaderTitle']);
            $datakeyword = [
                'pageHeaderTitle1'  => $_POST['pageHeaderTitle1'] ?: '',
                'pageHeaderTitle2'  => $_POST['pageHeaderTitle2'] ?: ''
            ];

            if (!empty($idPage)) {
                $updatePageQuery = "UPDATE pages SET
                pageHeaderTitle1        = ?,
                pageHeaderTitle2        = ?
                WHERE id                = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge(array_values($datakeyword), [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
            } else {
                handleError('ID thương hiệu không hợp lệ!!');
            }

            $actionHistories =  'Chỉnh sửa tiêu đề';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật thông tin trang thành công!', "Admin/pages/editpage/$idPage/", 'keywordpage');
        }
        if (isset($_POST['updateIntroduce'])) {
            validateCsrfToken($_POST['csrf_tokenIntroduce']);
            $pageContentIntroduce = $_POST['pageContentIntroduce']  ?: '';

            if (!empty($idPage)) {
                $updatePageQuery = "UPDATE pages SET pageContentIntroduce = ? WHERE id = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge([$pageContentIntroduce], [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
                uploadImageAndUpdate('pageImageIntroduce', $targetIntroduceDir, $link, 'pages', $idPage, 'pageImageIntroduce', $pageImageIntroduce, ['defaultIntroduce.jpg']);
            } else {
                handleError('ID trang không hợp lệ!');
            }

            $actionHistories =  'Chỉnh sửa giới thiệu';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật giới thiệu thành công!', "Admin/pages/editpage/$idPage/", 'introducepage');
        }
        if (isset($_POST['updateField'])) {
            validateCsrfToken($_POST['csrf_tokenField']);
            $pageBusinessField  = mysqli_real_escape_string($link, $_POST['pageBusinessField'] ?: '');

            if (!empty($idPage)) {
                $updatePageQuery = "UPDATE pages SET pageBusinessField = ? WHERE id = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge([$pageBusinessField], [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
            } else {
                handleError('ID trang không hợp lệ!');
            }
            $fields = [];
            for ($i = 1; $i <= count($idFields); $i++) {
                $fields[] = 'field' . $i;
            }
            foreach ($fields as $index => $field) {
                $idField        = $idFields[$index]     ?: null;
                $titleField     = $_POST[$fields[$index]] ?: '';
                if (!empty($idField)) {
                    $updateFieldQuery = "UPDATE fields SET fieldsTitle = ? WHERE id = ?";
                    $result = executeQuery($link, $updateFieldQuery, array_merge([$titleField], [$idField]));
                    if (!$result) {
                        handleError('Cập nhật không thành công!');
                    }
                } else {
                    handleError('ID lĩnh vực không hợp lệ!');
                }
            }
            $actionHistories =  'Chỉnh sửa lĩnh vực kinh doanh';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật lĩnh vực kinh doanh thành công!', "Admin/pages/editpage/$idPage/", 'fieldpage');
        }
        if (isset($_POST['updateBanner'])) {
            validateCsrfToken($_POST['csrf_tokenBanner']);
            $pageDescriptionBanner = $_POST['pageDescriptionBanner'] ?: '';

            if (!empty($idPage)) {
                $updatePageQuery = "UPDATE pages SET pageDescriptionBanner = ? WHERE id = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge([$pageDescriptionBanner], [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
            } else {
                handleError('ID trang không hợp lệ!');
            }
            $actionHistories =  'Chỉnh sửa Banner';
            $detailHistories =  "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật banner trang thành công!', "Admin/pages/editpage/$idPage/", 'bannerpage');
        }
        if (isset($_POST['updateSEO'])) {
            validateCsrfToken($_POST['csrf_tokenSEO']);
            $pageDescriptionSEO = $_POST['pageDescriptionSEO'] ?: '';

            if (!empty($idPage)) {
                $updatePageQuery = "UPDATE pages SET pageDescriptionSEO = ? WHERE id = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge([$pageDescriptionSEO], [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
            } else {
                handleError('ID trang không hợp lệ!');
            }
            $actionHistories =  'Chỉnh sửa Bài SEO';
            $detailHistories =  "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật bài viết trang thành công!', "Admin/pages/editpage/$idPage/", 'seopage');
        }
        if (isset($_POST['updateProduct'])) {
            validateCsrfToken($_POST['csrf_tokenProduct']);

            $products = [];
            for ($i = 1; $i <= count($idProducts); $i++) {
                $products[] = 'imageProducts' . $i;
            }
            foreach ($products as $index => $product) {
                $idProduct      = $idProducts[$index]       ?: '';
                $imageProduct   = $imageProducts[$index]    ?: null;
                $titleProduct   = mysqli_real_escape_string($link, $_POST['titleProducts' . ($index + 1)] ?: '');
                if (!empty($idProduct)) {
                    $updateProductQuery = "UPDATE products SET titleProduct = ? WHERE id = ?";
                    $result = executeQuery($link, $updateProductQuery, array_merge([$titleProduct], [$idProduct]));
                    if (!$result) {
                        handleError('Cập nhật không thành công!');
                    }
                } else {
                    handleError('ID sản phẩm không hợp lệ!');
                }
                $defaultImage  = ($index <= 3) ? 'defaultProductBig.jpg' : 'defaultProductSmall.jpg';
                uploadImageAndUpdate($product, $targetProductsDir,  $link, 'products', $idProduct, 'imageProduct', $imageProduct, ['defaultProductBig.jpg', 'defaultProductSmall.jpg']);
            }
            $actionHistories =  'Chỉnh sửa sản phẩm';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật hình ảnh sản phẩm thành công!', "Admin/pages/editpage/$idPage/", 'productpage');
        }
        if (isset($_POST['updateReview'])) {
            validateCsrfToken($_POST['csrf_tokenReview']);
            $reviews = [];
            for ($i = 1; $i <= count($idReviews); $i++) {
                $reviews[] = 'imageReview' . $i;
            }

            foreach ($reviews as $index => $review) {
                $idReview         = $idReviews[$index]       ?: '';
                $imageReviewold   = $imageReviews[$index]    ?: null;
                $dataReview = [
                    'nameReview'    =>  mysqli_real_escape_string($link, $_POST['nameReview'      . ($index + 1)] ?? ''),
                    'jobReview'     =>  mysqli_real_escape_string($link, $_POST['jobReview'       . ($index + 1)] ?: ''),
                    'contentReview' =>  mysqli_real_escape_string($link, $_POST['contentReview'   . ($index + 1)] ?: ''),
                    'ratingReview'  =>  mysqli_real_escape_string($link, $_POST['ratingReview'    . ($index + 1)] ?:  5)
                ];
                if (!empty($idReview)) {
                    $updateReviewQuery  = "UPDATE reviews SET
                        nameReview      = ?,
                        jobReview       = ?,
                        contentReview   = ?,
                        ratingReview    = ?
                    WHERE id            = ?";
                    $updateDataParams = array_merge(array_values($dataReview), [$idReview]);
                    if (!executeQuery($link, $updateReviewQuery, $updateDataParams)) {
                        handleError('Cập nhật thông tin đánh giá không thành công!');
                    }
                } else {
                    handleError('ID đánh giá không hợp lệ!');
                }
                uploadImageAndUpdate($review, $targetReviewsDir,  $link, 'reviews', $idReview, 'imageReview', $imageReviewold, ['defaultReview.jpg']);
            }
            $actionHistories =  'Chỉnh sửa đánh giá';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật đánh giá thành công!', "Admin/pages/editpage/$idPage/", 'reviewpage');
        }
        if (isset($_POST['updateBlog'])) {
            validateCsrfToken($_POST['csrf_tokenBlog']);
            $blogs = [];
            for ($i = 1; $i <= count($idBlogs); $i++) {
                $blogs[] = 'imageBlogs' . $i;
            }
            foreach ($blogs as $index => $blog) {
                $idBlog             = $idBlogs[$index]       ?: '';
                $imageBlog          = $imageBlogs[$index]    ?: null;
                $titleBlog          = mysqli_real_escape_string($link, $_POST['titleBlogs'       . ($index + 1)] ?? '');
                $descriptionBlog    = mysqli_real_escape_string($link, $_POST['descriptionBlogs' . ($index + 1)] ?? '');

                if (!empty($idBlog)) {
                    $updateBlogQuery    = "UPDATE blogs SET 
                        titleBlog       = ?, 
                        descriptionBlog = ? 
                    WHERE id            = ?";
                    $updateDataParams = array_merge([$titleBlog, $descriptionBlog], [$idBlog]);
                    if (!executeQuery($link, $updateBlogQuery, $updateDataParams)) {
                        handleError('Cập nhật thông tin bài viết không thành công!');
                    }
                } else {
                    handleError('ID bài viết không hợp lệ!');
                }
                uploadImageAndUpdate($blog, $targetBlogsDir, $link, 'blogs', $idBlog, 'imageBlog', $imageBlog, ['defaultBlog.jpg']);
            }
            $actionHistories = 'Chỉnh sửa bài viết';
            $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlertProperty('Đã cập nhật tin tức thành công!', "Admin/pages/editpage/$idPage/", 'blogpage');
        }
        if (isset($_POST['updateProperty'])) {
            validateCsrfToken($_POST['csrf_tokenProperty']);
            $dataInfo = [
                'pageName'                  => $_POST['pageName']               ?? '',
                'idRepresentativePersion'   => $_POST['idPersion']              ?? $idRepresentativePersion,
                'idCategoryPage'            => $_POST['idCategory']             ?? $idCategoryPage,
                'pageHeaderTitle1'          => $_POST['pageHeaderTitle1']       ?? '',
                'pageHeaderTitle2'          => $_POST['pageHeaderTitle2']       ?? '',
                'pageBusinessField'         => $_POST['pageBusinessField']      ?? '',
                'pageDescriptionBanner'     => $_POST['pageDescriptionBanner']  ?? '',
                'pageDescriptionSEO'        => $_POST['pageDescriptionSEO']     ?? '',
                'pageContentIntroduce'      => $_POST['pageContentIntroduce']   ?? '',
                'pageProvince'              => $_POST['pageProvince']           ?? '',
                'pageWard'                  => $_POST['pageWard']               ?? '',
                'pageAddress'               => $_POST['pageAddress']            ?? '',
                'pageStartDate'             => $_POST['pageStartDate']          ?? '',
                'pageEndDate'               => $_POST['pageEndDate']            ?? '',
                'pageStatus'                => $_POST['pageStatus']             ?? '',
                'pagePorpular'              => isset($_POST['pagePorpular']) ?   1 :  0
            ];

            $dataSocial = [
                'linkZalo'      => $_POST['linkZalo']      ?? '',
                'linkFacebook'  => $_POST['linkFacebook']  ?? '',
                'linkYoutube'   => $_POST['linkYoutube']   ?? '',
                'linkTiktok'    => $_POST['linkTiktok']    ?? '',
            ];
            $datadatetime = [];

            if (!empty($dataInfo['pageName'])) {
                $updatePageQuery = "UPDATE pages  SET
                pageName                = ?,
                idRepresentativePersion = ?,
                idCategoryPage          = ?,
                pageHeaderTitle1        = ?,
                pageHeaderTitle2        = ?,
                pageBusinessField       = ?,
                pageDescriptionBanner   = ?,
                pageDescriptionSEO      = ?,
                pageContentIntroduce    = ?,
                pageProvince            = ?,
                pageWard                = ?,
                pageAddress             = ?,
                pageStartDate           = ?,
                pageEndDate             = ?,
                pageStatus              = ?,
                pagePorpular            = ?
                WHERE id                = ?";
                $result = executeQuery($link, $updatePageQuery, array_merge(array_values($dataInfo), [$idPage]));
                if (!$result) {
                    handleError('Cập nhật không thành công!');
                }
                uploadImageAndUpdate('pageImageLogo',       $targetPagesDir,      $link, 'pages', $idPage, 'pageImageLogo', $pageImageLogo, ['defaultLogo.jpg']);
                uploadImageAndUpdate('pageImageIntroduce',  $targetIntroduceDir,  $link, 'pages', $idPage, 'pageImageIntroduce', $pageImageIntroduce, ['defaultIntroduce.jpg']);
            } else {
                handleError('Tên Thương Hiệu không hợp lệ!!');
            }
            if (!empty($idPage)) {
                $updateSocialQuery  = "UPDATE socialmedia SET
                    linkZalo        = ?,
                    linkFacebook    = ?,
                    linkYoutube     = ?,
                    linkTiktok      = ?
                WHERE idPage        = ?";
                $updateDataParams = array_merge(array_values($dataSocial), [$idPage]);

                if (!executeQuery($link, $updateSocialQuery, $updateDataParams)) {
                    handleError('Cập nhật thông tin mạng xã hội không thành công!');
                }
            } else {
                handleError('ID trang không hợp lệ!');
            }

            $slides = [];
            for ($i = 1; $i <= count($imageSlides); $i++) {
                $slides[] = 'imageSlide' . $i;
            }
            foreach ($slides as $index => $slide) {
                $oldImage   = $imageSlides[$index]   ?: null;
                $idSlide    = $idSlides[$index]      ?: null;
                uploadImageAndUpdate($slide, $targetSlidesDir,  $link, 'slides', $idSlide, 'imageSlide', $oldImage, ['defaultSlide.jpg']);
            }

            $fields = [];
            for ($i = 1; $i <= count($idFields); $i++) {
                $fields[] = 'field' . $i;
            }
            foreach ($fields as $index => $field) {
                $idField        = $idFields[$index]     ?: null;
                $titleField     = mysqli_real_escape_string($link, $_POST[$fields[$index]] ?: '');
                if (!empty($idField)) {
                    $updateFieldQuery = "UPDATE fields SET fieldsTitle = ? WHERE id = ?";
                    $result = executeQuery($link, $updateFieldQuery, array_merge([$titleField], [$idField]));
                    if (!$result) {
                        handleError('Cập nhật không thành công!');
                    }
                } else {
                    handleError('ID lĩnh vực không hợp lệ!');
                }
            }

            $products = [];
            for ($i = 1; $i <= count($idProducts); $i++) {
                $products[] = 'imageProducts' . $i;
            }
            foreach ($products as $index => $product) {
                $idProduct      = $idProducts[$index]       ?: '';
                $imageProduct   = $imageProducts[$index]    ?: null;
                $titleProduct   = mysqli_real_escape_string($link, $_POST['titleProducts' . ($index + 1)] ?: '');
                if (!empty($idProduct)) {
                    $updateProductQuery = "UPDATE products SET titleProduct = ? WHERE id = ?";
                    $result = executeQuery($link, $updateProductQuery, array_merge([$titleProduct], [$idProduct]));
                    if (!$result) {
                        handleError('Cập nhật không thành công!');
                    }
                } else {
                    handleError('ID sản phẩm không hợp lệ!');
                }
                $defaultImage  = ($index <= 3) ? 'defaultProductBig.jpg' : 'defaultProductSmall.jpg';
                uploadImageAndUpdate($product, $targetProductsDir,  $link, 'products', $idProduct, 'imageProduct', $imageProduct, ['defaultProductBig.jpg', 'defaultProductSmall.jpg']);
            }

            $reviews = [];
            for ($i = 1; $i <= count($idReviews); $i++) {
                $reviews[] = 'imageReview' . $i;
            }
            foreach ($reviews as $index => $review) {
                $idReview      = $idReviews[$index]       ?: '';
                $imageReview   = $imageReviews[$index]    ?: null;
                $dataReview = [
                    'nameReview'    =>  mysqli_real_escape_string($link, $_POST['nameReview'      . ($index + 1)] ?? ''),
                    'jobReview'     =>  mysqli_real_escape_string($link, $_POST['jobReview'       . ($index + 1)] ?: ''),
                    'contentReview' =>  mysqli_real_escape_string($link, $_POST['contentReview'   . ($index + 1)] ?: ''),
                    'ratingReview'  =>  mysqli_real_escape_string($link, $_POST['ratingReview'    . ($index + 1)] ?:  5)
                ];
                if (!empty($idReview)) {
                    $updateReviewQuery  = "UPDATE reviews SET
                        nameReview      = ?,
                        jobReview       = ?,
                        contentReview   = ?,
                        ratingReview    = ?
                    WHERE id            = ?";
                    $updateDataParams = array_merge(array_values($dataReview), [$idReview]);
                    if (!executeQuery($link, $updateReviewQuery, $updateDataParams)) {
                        handleError('Cập nhật thông tin đánh giá không thành công!');
                    }
                } else {
                    handleError('ID đánh giá không hợp lệ!');
                }
                uploadImageAndUpdate($review, $targetReviewsDir,  $link, 'reviews', $idReview, 'imageReview', $imageReview, ['defaultReview.jpg']);
            }

            $blogs = [];
            for ($i = 1; $i <= count($idBlogs); $i++) {
                $blogs[] = 'imageBlogs' . $i;
            }
            foreach ($blogs as $index => $blog) {
                $idBlog             = $idBlogs[$index]       ?: '';
                $imageBlog          = $imageBlogs[$index]    ?: null;
                $titleBlog          = mysqli_real_escape_string($link, $_POST['titleBlogs'       . ($index + 1)] ?? '');
                $descriptionBlog    = mysqli_real_escape_string($link, $_POST['descriptionBlogs' . ($index + 1)] ?? '');

                if (!empty($idBlog)) {
                    $updateBlogQuery    = "UPDATE blogs SET 
                        titleBlog       = ?, 
                        descriptionBlog = ? 
                    WHERE id            = ?";
                    $updateDataParams = array_merge([$titleBlog, $descriptionBlog], [$idBlog]);
                    if (!executeQuery($link, $updateBlogQuery, $updateDataParams)) {
                        handleError('Cập nhật thông tin bài viết không thành công!');
                    }
                } else {
                    handleError('ID bài viết không hợp lệ!');
                }
                uploadImageAndUpdate($blog, $targetBlogsDir, $link, 'blogs', $idBlog, 'imageBlog', $imageBlog, ['defaultBlog.jpg']);
            }
            $actionHistories =  'Chỉnh sửa thương hiệu';
            if ($pageName !== $dataInfo['pageName']) {
                $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành \"<b>{$dataInfo['pageName']}</b>\" thành công.";
            } else {
                $detailHistories = "Người dùng <b>$nameUser</b> đã <b>$actionHistories</b> của thương hiệu \"<b>$pageName</b>\" thành công.";
            }
            logHistory($link, $idUser, $actionHistories, $detailHistories);
            showSuccessUpdateAlert('Đã cập nhật thương hiệu thành công!', "Admin/pages/editpage/$idPage/");
        }
    ?>
        <div class="main-content w-100">
            <div class="main-content-inner">
                <div class="widget-box-2">
                    <div class="box d-flex jcsb">
                        <h3 class="title">Tuỳ Chỉnh Thương Hiệu</h3>
                        <div class="d-flex jcsb">
                            <div class="box">
                                <a href="<?= $pageUrl ?>/" class="whitecolor tf-btn bg-color-primary pd-10 mr2rem"><i class="fa-regular fa-object-group"></i> </span> Xem Trang</a>
                            </div>
                            <div class="box">
                                <a href="Admin/pages/listpage/" class="whitecolor tf-btn bg-color-primary pd-10"><i class="fa-duotone fa-regular fa-list"></i> Danh Sách Thương Hiệu</a>
                            </div>
                        </div>
                    </div>
                    <form class="gap-30" method="post" enctype="multipart/form-data">
                        <div class="widget-box-2 mt4r" id="infopage">
                            <h5 class="title">Thông Tin Trang</h5>
                            <fieldset class="box grid-layout-<?= (($roleUser == 2) || ($roleUser == 3)) ? 4 : 3 ?> mb-0">
                                <div class="box">
                                    <label for="box-avatar">Logo Trang</label>
                                    <div class="box-agent-avt" id="box-avatar">
                                        <div class="avatar borderR0">
                                            <img id="pageImageLogoPreview" class="avatar-img pageImageLogo" src="src/docs/images/imagePages/<?= (!empty($pageImageLogo)) ? $pageImageLogo : 'defaultLogo.jpg' ?>" alt="avatar" loading="lazy" width="128" height="128">
                                        </div>
                                        <div class="content uploadfile">
                                            <div class="box-ip">
                                                <input type="file" class="ip-file" id="pageImageLogo" name="pageImageLogo" accept=".png, .jpg, .jpeg">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box box-fieldset">
                                    <label for="pageName">Tên Doanh Ngiệp<span>*</span></label>
                                    <input type="text" id="pageName" name="pageName" value="<?= $pageName ?>" class="form-control ">
                                </div>
                                <?php if (!empty($_SESSION['userData']['role']) && in_array($_SESSION['userData']['role'], [1, 2, 3])) { ?>
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
                                                        <option value="<?= $persionId ?>" <?= ($persionId == $idRepresentativePersion) ? 'selected' : '' ?>><?= $persionName ?></option>
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
                                                    <option value="<?= $categoryID ?>" <?= ($categoryID == $idCategoryPage) ? 'selected' : '' ?>><?= $categoryPage ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="box box-fieldset">
                                        <label for="userName">Tên Chủ Sở Hữu<span>*</span></label>
                                        <input type="text" id="userName" value="<?= $userName ?>" class="form-control " disabled>
                                    </div>
                                <?php } ?>
                            </fieldset>
                            <fieldset class="box grid-layout-4 gap-30">
                                <div class="box-fieldset">
                                    <label for="linkZalo">Zalo<span>*</span></label>
                                    <input type="text" id="linkZalo" name="linkZalo" value="<?= $linkZalo ?>" placeholder="zalo.me/" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="linkFacebook">Facebook<span>*</span></label>
                                    <input type="text" id="linkFacebook" name="linkFacebook" value="<?= $linkFacebook ?>" placeholder="facebook.com/" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="linkYoutube">Youtube</label>
                                    <input type="text" id="linkYoutube" name="linkYoutube" value="<?= $linkYoutube ?>" placeholder="youtube.com/" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="linkTiktok">TikTok</label>
                                    <input type="text" id="linkTiktok" name="linkTiktok" value="<?= $linkTiktok ?>" placeholder="tiktok.com/" class="form-control ">
                                </div>
                            </fieldset>
                            <fieldset class="box grid-layout-3 gap-30">
                                <div class="box-fieldset">
                                    <label for="pageProvince">Tỉnh/ Thành phố<span></span></label>
                                    <select id="pageProvince" name="pageProvince" title="Chọn Tỉnh Thành">
                                        <option value="0">Tỉnh Thành</option>
                                    </select>
                                    <input type="hidden" id="hiddenProvice" value="<?= $pageProvince ?>" />
                                </div>
                                <div class="box-fieldset">
                                    <label for="pageWard">Phường/ Xã<span></span></label>
                                    <select id="pageWard" name="pageWard" title="Chọn Phường Xã">
                                        <option value="0">Phường Xã</option>
                                    </select>
                                    <input type="hidden" id="hiddenWard" value="<?= $pageWard ?>" />
                                </div>
                                <div class="box-fieldset">
                                    <label for="pageAddress">Địa chỉ<span>*</span></label>
                                    <input type="text" id="pageAddress" name="pageAddress" value="<?= $pageAddress ?>" class="form-control ">
                                </div>
                            </fieldset>
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenInfomation" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateInfomation" class="tf-btn bg-color-primary pd-10">Lưu Thông Tin</button>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="timepage">
                            <h5 class="title">Thời Hạn Kích Hoạt</h5>
                            <fieldset class="box grid-layout-<?= (($roleUser == 2) || ($roleUser == 3)) ? 3 : 2 ?> gap-30 mb-0">
                                <div class="box-fieldset">
                                    <label for="pageStartDate">Ngày bắt đầu hoạt động (Tháng/ Ngày/ Năm)<span></span></label>
                                    <input type="datetime-local" name="pageStartDate" id="pageStartDate" class="form-control" value="<?= $pageStartDate ?>" <?= (($roleUser == 2) || ($roleUser == 3)) ? '' : 'disabled' ?>>
                                </div>
                                <div class="box-fieldset">
                                    <label for="pageEndDate">Ngày kết thúc hoạt động (Tháng/ Ngày/ Năm)<span></span></label>
                                    <input type="datetime-local" name="pageEndDate" id="pageEndDate" class="form-control" value="<?= $pageEndDate ?>" <?= (($roleUser == 2) || ($roleUser == 3)) ? '' : 'disabled' ?>>
                                </div>
                                <?php if (in_array($_SESSION['userData']['role'], [2, 3])) { ?>
                                    <div class="box-fieldset">
                                        <fieldset class="box grid-layout-2 gap-30 mb-0">
                                            <div class="box-fieldset">
                                                <label for="pageStatus">Trạng Thái</label>
                                                <?php $status = ['Đang Trống', 'Đã Cho Thuê', 'Đang Có Lỗi']; ?>
                                                <select id="pageStatus" name="pageStatus" title="--Trạng Thái--">
                                                    <?php for ($s = 0; $s < count($status); $s++): ?>
                                                        <option value="<?= $s ?>" <?= ($pageStatus == $s) ? 'selected' : ''  ?>>
                                                            <?= $status[$s] ?>
                                                        </option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <fieldset class="checkbox-item  style-1  ">
                                                <label for="pagePorpular"> Nổi Bật
                                                    <input type="checkbox" id="pagePorpular" name="pagePorpular" <?= ($pagePorpular) ? 'checked' : '' ?>>
                                                    <span class="btn-checkbox"></span>
                                                </label>
                                            </fieldset>
                                        </fieldset>
                                    </div>
                                <?php } ?>
                            </fieldset>
                            <?php if (in_array($_SESSION['userData']['role'], [2, 3])) { ?>
                                <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                    <input type="hidden" name="csrf_tokenDateTime" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                    <div class="box">
                                        <button type="submit" name="updateDateTime" class="tf-btn bg-color-primary pd-10">Lưu Thông Tin</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <div class="widget-box-2 mt4r" id="keywordpage">
                            <h5 class="title">Tiêu Đề Trang </h5>
                            <fieldset class="box grid-layout-2 gap-30 mb-0">
                                <div class="box-fieldset">
                                    <label for="pageHeaderTitle1">Tiêu đề trái<span>*</span></label>
                                    <input type="text" id="pageHeaderTitle1" name="pageHeaderTitle1" value="<?= $pageHeaderTitle1 ?>" class="form-control ">
                                </div>
                                <div class="box-fieldset">
                                    <label for="pageHeaderTitle2">Tiêu đề phải<span>*</span></label>
                                    <input type="text" id="pageHeaderTitle2" name="pageHeaderTitle2" value="<?= $pageHeaderTitle2 ?>" class="form-control ">
                                </div>
                            </fieldset>
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenHeaderTitle" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateHeaderTitle" class="tf-btn bg-color-primary pd-10">Lưu Tiêu Đề</button>
                                </div>
                            </div>
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
                                                        <img class="lazyload imageSlide" id="imageSlide<?= $i + 1 ?>-preview"
                                                            data-src="src/docs/images/imageSlides/<?= (!empty($imageSlides[$i])) ?   $imageSlides[$i] : 'defaultSlide.jpg' ?>"
                                                            src="src/docs/images/imageSlides/<?= (!empty($imageSlides[$i])) ? $imageSlides[$i] : 'defaultSlide.jpg' ?>" alt="imageSlide">
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
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenSlide" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateSlide" class="tf-btn bg-color-primary pd-10">Lưu Slide</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="widget-box-2 mt4r" id="introducepage">
                            <h5 class="title">Giới Thiệu Trang </h5>
                            <fieldset class="box grid-layout-2-5 mb-0">
                                <div class="box box-fieldset">
                                    <label for="pageContentIntroduce">Mô Tả Ngắn (160 - 300 kí tự)<span>*</span></label>
                                    <textarea class="form-control" name="pageContentIntroduce" rows="4" id="pageContentIntroduce"><?= $pageContentIntroduce ?></textarea>
                                </div>
                                <div class="box">
                                    <label for="box-introduce">Ảnh Giới Thiệu Trang</label>
                                    <div class="box-agent-introduce" id="box-introduce">
                                        <div class="introduce">
                                            <img id="pageImageIntroducePreview" class="avatar-img pageImageIntroduce" src="src/docs/images/imageIntroduces/<?= (!empty($pageImageIntroduce)) ? $pageImageIntroduce : 'defaultIntroduce.jpg' ?>" alt="avatar" loading="lazy" width="128" height="128">
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
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenIntroduce" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateIntroduce" class="tf-btn bg-color-primary pd-10">Lưu Giới Thiệu</button>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="fieldpage">
                            <h5 class="title">Lĩnh Vực Kinh Doanh của Trang</h5>
                            <fieldset class="box ">
                                <div class="box box-fieldset">
                                    <label for="pageBusinessField">Mô tả ngắn lĩnh vực kinh doanh<span>*</span></label>
                                    <input type="text" id="pageBusinessField" name="pageBusinessField" value="<?= $pageBusinessField ?>" class="form-control ">
                                </div>
                            </fieldset>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="2" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <div class="swiper-slide">
                                            <fieldset class="box mb-0">
                                                <div class="box box-fieldset">
                                                    <label for="field">Lĩnh Vực Kinh Doanh <?= $i + 1 ?></label>
                                                    <input type="text" id="field<?= $i + 1 ?>" name="field<?= $i + 1 ?>" value="<?= $fieldTitles[$i] ?>" class="form-control ">
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
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenField" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateField" class="tf-btn bg-color-primary pd-10">Lưu Lĩnh Vực</button>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="bannerpage">
                            
                            <!--<h5 class="title">SEO của Trang</h5>-->
                            <fieldset class="box ">
                                <div class="box box-fieldset">
                                    <label for="pageDescriptionBanner">Mô Tả Chi Tiết Để Seo (160 - 300 kí tự)<span>*</span></label>
                                    <textarea class="form-control" name="pageDescriptionBanner" rows="4" id="pageDescriptionBanner"><?= $pageDescriptionBanner ?></textarea>
                                </div>
                            </fieldset>
                            
                            
                            
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenBanner" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateBanner" class="tf-btn bg-color-primary pd-10">Lưu Mô Tả SEO</button>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="seopage">
                            <h5 class="title">Bài SEO của Trang</h5>
                            <fieldset class="box ">
                                <div class="box box-fieldset">
                                    <label for="pageDescriptionSEO">Mô Tả Chi Tiết bài SEO<span>*</span></label>
                                    <textarea class="form-control" name="pageDescriptionSEO" rows="4" id="pageDescriptionSEO"><?= $pageDescriptionSEO ?></textarea>
                                </div>
                            </fieldset>
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenSEO" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateSEO" class="tf-btn bg-color-primary pd-10">Lưu Bài Viết</button>
                                </div>
                            </div>
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
                                                    <input type="text" id="titleProducts<?= $i + 1 ?>" name="titleProducts<?= $i + 1 ?>" value="<?= $titleProducts[$i] ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload defaultProductSmall" id="imageProducts<?= $i + 1 ?>-preview" data-src="src/docs/images/imageProducts/<?= (!empty($imageProducts[$i])) ?   $imageProducts[$i] : 'defaultProductSmall.jpg' ?>" src="src/docs/images/imageProducts/<?= (!empty($imageProducts[$i])) ? $imageProducts[$i] : 'defaultProductSmall.jpg' ?>" alt="imageProducts">
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
                                                    <input type="text" id="titleProducts<?= $i + 1 ?>" name="titleProducts<?= $i + 1 ?>" value="<?= $titleProducts[$i] ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload defaultProductBig" id="imageProducts<?= $i + 1 ?>-preview" data-src="src/docs/images/imageProducts/<?= (!empty($imageProducts[$i])) ?   $imageProducts[$i] : 'defaultProductBig.jpg' ?>" src="src/docs/images/imageProducts/<?= (!empty($imageProducts[$i])) ? $imageProducts[$i] : 'defaultProductBig.jpg' ?>" alt="imageProducts">
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
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenProduct" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateProduct" class="tf-btn bg-color-primary pd-10">Lưu Sản Phẩm</button>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box-2 mt4r" id="reviewpage">
                            <h5 class="title">Đánh giá của Trang </h5>
                            <span>Hình ảnh đánh giá 300 x 500 <i>(Nên ở định dạng .jpg hoặc .png để có hiệu quả tốt nhất)</i></span>
                            <div class="swiper sw-layout-3 style-pagination " data-preview="3" data-tablet="2" data-mobile-sm="2" data-mobile="1" data-space="15" data-space-md="30" data-space-lg="40" data-speed="1000">
                                <div class="swiper-wrapper mb-48">
                                    <?php for ($i = 0; $i <= 2; $i++): ?>
                                        <div class="swiper-slide mt4r">
                                            <div class="box-house hover-img">
                                                <div class="image-wrap">
                                                    <a href="javascript: void(0)">
                                                        <img class="lazyload imageReview" id="imageReviews<?= $i + 1 ?>-preview" data-src="src/docs/images/imageReviews/<?= (!empty($imageReviews[$i])) ?   $imageReviews[$i] : 'defaultReview.jpg' ?>" src="src/docs/images/imageReviews/<?= (!empty($imageReviews[$i])) ? $imageReviews[$i] : 'defaultReview.jpg' ?>" alt="imageReviews">
                                                    </a>
                                                    <div class="list-btn flex gap-8">
                                                        <label for="imageReviews<?= $i + 1 ?>" class="btn-icon find hover-tooltip">
                                                            <i class="fa-light fa-pen-to-square"></i>
                                                            <span class="tooltip">Chỉnh Sửa Hình Ảnh</span>
                                                        </label>
                                                        <input type="file" name="imageReview<?= $i + 1 ?>" id="imageReviews<?= $i + 1 ?>" class="ec-image-upload imageReviews hidden" accept=".png, .jpg, .jpeg" />
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset class="box mt4r">
                                                <div class="box box-fieldset">
                                                    <label for="nameReviews<?= $i + 1 ?>">Họ Tên Người <?= $i + 1 ?></label>
                                                    <input type="text" id="nameReview<?= $i + 1 ?>" name="nameReview<?= $i + 1 ?>" value="<?= $nameReviews[$i] ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box ">
                                                <div class="box box-fieldset">
                                                    <label for="jobReviews<?= $i + 1 ?>">Chức Vụ</label>
                                                    <input type="text" id="jobReview<?= $i + 1 ?>" name="jobReview<?= $i + 1 ?>" value="<?= $jobReviews[$i] ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box ">
                                                <div class="box box-fieldset">
                                                    <label for="contentReview<?= $i + 1 ?>">Nội Dung</label>
                                                    <textarea class="form-control textareaFormControl" id="contentReview<?= $i + 1 ?>" name="contentReview<?= $i + 1 ?>" rows="4"><?= $contentReviews[$i] ?></textarea>
                                                </div>
                                            </fieldset>
                                            <fieldset class="box">
                                                <div class="box box-fieldset">
                                                    <label for="ratingReview<?= $i + 1 ?>">Đánh giá</label>
                                                    <div class="ec-t-review-rating d-flex jcc" id="review-rating-<?= $i + 1 ?>">
                                                        <?php
                                                        for ($j = 1; $j <= 5; $j++) {
                                                            if ($j <= $ratingReview[$i]) { ?>
                                                                <i class="fa-solid fa-star clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                                            <?php } elseif ($j - 0.5 == $ratingReview[$i]) { ?>
                                                                <i class="fa-duotone fa-star-sharp-half clstart rvstartEdit" data-value="<?= $j ?>"></i>
                                                            <?php } else { ?>
                                                                <i class="fa-duotone fa-solid clstart fa-star rvstartEdit" data-value="<?= $j ?>"></i>
                                                        <?php }
                                                        }
                                                        ?>
                                                    </div>
                                                    <input type="hidden" name="ratingReview<?= $i + 1 ?>" id="selected-rating<?= $i + 1 ?>" value="<?= $ratingReview[$i] ?>">
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
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenReview" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateReview" class="tf-btn bg-color-primary pd-10">Lưu Đánh Giá</button>
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
                                                        <img class="lazyload imageBlog" id="imageBlogs<?= $i + 1 ?>-preview" data-src="src/docs/images/imageBlogs/<?= (!empty($imageBlogs[$i])) ?   $imageBlogs[$i] : 'defaultBlog.jpg' ?>" src="src/docs/images/imageBlogs/<?= (!empty($imageBlogs[$i])) ? $imageBlogs[$i] : 'defaultBlog.jpg' ?>" alt="imageBlogs">
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
                                                    <input type="text" id="titleBlogs<?= $i + 1 ?>" name="titleBlogs<?= $i + 1 ?>" value="<?= $titleBlogs[$i] ?>" class="form-control ">
                                                </div>
                                            </fieldset>
                                            <fieldset class="box mt4r">
                                                <div class="box box-fieldset">
                                                    <label for="descriptionBlogs<?= $i + 1 ?>">Mô Tả Bài Viết <?= $i + 1 ?></label>
                                                    <textarea class="form-control textareaFormControl" id="descriptionBlogs<?= $i + 1 ?>" name="descriptionBlogs<?= $i + 1 ?>" rows="4"><?= $descriptionBlogs[$i] ?></textarea>
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
                            <div class="box grid-layout-2 gap-30 box-info-2 mt4r">
                                <input type="hidden" name="csrf_tokenBlog" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                                <div class="box">
                                    <button type="submit" name="updateBlog" class="tf-btn bg-color-primary pd-10">Lưu Tin Tức</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-btn mt4r">
                            <input type="hidden" name="csrf_tokenProperty" value="<?= $_SESSION['csrf_token'] ?? "" ?>">
                            <button type="submit" name="updateProperty" class="tf-btn style-border pd-10">Lưu & Cập Nhật</button>
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
        var idProvice = document.getElementById('hiddenProvice').value;
        var idWard = document.getElementById('hiddenWard').value;
        $(document).ready(function() {
            $.getJSON('https://esgoo.net/api-tinhthanh-new/1/0.htm', function(data_tinh) {
                if (data_tinh.error == 0) {
                    $.each(data_tinh.data, function(key_tinh, val_tinh) {
                        $("#pageProvince").append('<option value="' + val_tinh.id + '">' + val_tinh.full_name + '</option>');
                    });
                    $("#pageProvince").val(idProvice).change();
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
                        $("#pageWard").val(idWard);
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

<?php } 
    }else { ?>
    <script>
        Swal.fire({
            title: 'Bạn chưa đăng nhập?',
            text: 'Bạn cần đăng nhập để tiếp tục thao tác',
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