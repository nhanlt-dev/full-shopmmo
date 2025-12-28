<img src="src/iconweb/Bannnerchinhsach.png" alt="ăn vặt đà nẵng" style='padding: 0px;' />

<section class="blog-details" style="padding-top: 30px;">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-7">
                <?php
                $result = mysqli_query($link, "SELECT * FROM introduce where urlIntroduce like '%Dieu-khoan-va-dieu-kien%'");
                if (mysqli_num_rows($result) <> 0) {
                    echo " <table width='100%' border='0' align='left'>";
                    $stt = 0;
                    while ($row = mysqli_fetch_object($result)) {
                        $titleIntroduce = characterConversion($row->titleIntroduce);
                        $contentIntroduce = characterConversion($row->contentIntroduce);
                        $descriptionIntroduce = characterConversion($row->descriptionIntroduce);
                        $keyword1 = $row->keyword1;
                        $keyword2 = $row->keyword1;
                        $linkIntroduce = "about-us/Dieu-khoan-va-dieu-kien";
                        if ($stt % 2 == 0) {
                            echo "<tr>";
                        }
                        echo "<td align='left' width='100%'>";
                        echo "<table align='left' width='100%'>";
                        echo "<div >
                            <h1 style='font-size: 24px;
                            font-weight: bold;
                            margin-bottom: 15px;
                            line-height: 35px;'> $titleIntroduce</h1>
                    		<p style='font-size: 16px;line-height: 29px;font-weight: bold;'> <i>$descriptionIntroduce </i></p>
                                <div style='padding:20px; text-align: center; font-family:'Arial', sans-serif;'>
                               <div style='font-size: 18px; font-family:'Arial', sans-serif;'>
                               $contentIntroduce
                                   </div>
                                    <h2 style='padding: 0px;margin: 0px;font-size: 0px;line-height: 0px;color: #fff;'></i><a href='$linkIntroduce'>$keyword1</a></h2>
                        </div>";
                        echo " </table>";
                        echo "</td>";
                        $stt = $stt + 1;

                        if ($stt % 1 == 0) {
                            echo "</tr>";
                        }
                    }
                    echo " </table>";
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!--services Section End -->

 