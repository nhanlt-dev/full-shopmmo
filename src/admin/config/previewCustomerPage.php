<div class="page-layout">
    <?php include('src/handlers/layouts/layoutContent/layoutSidebar.php') ?>
    <div class="main-content w-100">
        <?php
        $pageId = isset($_GET['id']) ? $_GET['id'] : '';
        $resultPageQuery = mysqli_query($link, "SELECT * FROM pages WHERE id = $pageId");
        if ($resultPageQuery) {
            $pageRow = mysqli_fetch_assoc($resultPageQuery);
            if ($pageRow) {
                $pageUrl   = $pageRow['pageUrl'];
            }
        } ?>
        <iframe class="iframeDashboard" src="<?= $pageUrl ?>/" width="100%" height="100%" frameborder="0"></iframe>
        <div class="overlay-dashboard"></div>
    </div>
</div>