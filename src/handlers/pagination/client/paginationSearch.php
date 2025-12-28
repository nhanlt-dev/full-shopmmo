<?php
class pager
{
    function findStart($limit)
    {
        $start = (!isset($_GET['page']) || ($_GET['page'] == "1")) ? 0 : ($_GET['page'] - 1) * $limit;
        $_GET['page'] = $_GET['page'] ?? 1;
        return $start;
    }

    function findPages($count, $limit)
    {
        return (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
    }

    function pageList($curpage, $query, $pages)
    {
        $page_list = "";
        if ($curpage > 1) {
            $page_list .= "<li class='arrow'><a href='search/$query/" . ($curpage - 1) . "/'><i class='icon-arrow-left'></i></a></li>";
        }

        $startPage = max(1, $curpage - 1);
        $endPage = min($pages, $curpage + 1);
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $curpage) {
                $page_list .= "<li class='active'><a href='search/$query/$curpage/'>" . $i . "</a></li>";
            } else {
                $page_list .= "<li><a href='search/$query/" . $i . "/'>" . $i . "</a></li>";
            }
        }

        // Nút tới trang sau
        if ($curpage < $pages) {
            $page_list .= "<li class='arrow'><a href='search/$query/" . ($curpage + 1) . "/'><i class='icon-arrow-right'></i></a></li>";
        }

        return $page_list;
    }
}
