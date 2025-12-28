<?php
function dkl($mang)
{
	//in_mang($mang);
	//xuat4(dem_mang($mang));
	$mang1 = array_filter($mang);
	$tln = 0;
	foreach ($mang1 as $tieulongnu) {
		//xuat4($tieulongnu);
		$mang2[$tln] = $tieulongnu;
		$tln++;
	}
	//in_mang($mang2);
	return $mang2;
}
function catchuoi($chuoi, $so)
{
	//	thongbao($chuoi);
	//	xuat4($chuoi);
	$chuoi = str_replace("'", "", $chuoi);
	$chuoi = str_replace('"', '', $chuoi);
	$chuoi = trim(strip_tags($chuoi));
	$chuoi = str_replace("\n", "", $chuoi);
	//$chuoi=str_replace("\t","",$chuoi);
	//echo $so."<br>";
	//echo $chuoi."<br>";
	$chuoi_mang_a = "e,é,è,ẻ,ẽ,ẹ,ê,ế,ề,ể,ễ,ệ";
	$chuoi_mang_a_1 = "e,e1,e2,e3,e4,e5,e6,e61,e62,e63,e64,e65";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	$chuoi_mang_b = "ý,ỳ,ỷ,ỹ,ỵ,í,ì,ỉ,ĩ,ị";
	$chuoi_mang_b_1 = "y1,y2,y3,y4,y5,i1,i2,i3,i4,i5";

	$mang_b = explode(",", $chuoi_mang_b);
	$mang_b_1 = explode(",", $chuoi_mang_b_1);
	//in_mang1($mang_b);
	//in_mang1($mang_b_1);
	//echo $chuoi."<hr>";
	for ($i = 0; $i < count($mang_b); $i++) {
		//echo $mang_b[$i]." va ".$chuoi_mang_b_1[$i]."<br>";
		$chuoi = str_replace($mang_b[$i], $mang_b_1[$i], $chuoi);
	}
	//echo $chuoi."<hr>";
	$chuoi_mang_a = "u,ú,ù,ủ,ũ,ụ,ư,ứ,ừ,ử,ữ,ự";
	$chuoi_mang_a_1 = "u,u1,u2,u3,u4,u5,u7,u71,u72,u73,u74,u75";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	//echo $chuoi."<br>";
	//in_mang1($mang_a);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	$chuoi_mang_a = "o,ó,ò,ỏ,õ,ọ,ô,ố,ồ,ổ,ỗ,ộ,ơ,ớ,ờ,ở,ỡ,ợ";
	$chuoi_mang_a_1 = "o,o1,o2,o3,o4,o5,o6,o61,o62,o63,o64,o65,o7,o71,o72,o73,o74,o75";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	$chuoi_mang_a = "a,á,à,ả,ã,ạ,â,ấ,ầ,ẩ,ẫ,ậ,ă,ắ,ằ,ẳ,ẵ,ặ";
	$chuoi_mang_a_1 = "a,a1,a2,a3,a4,a5,a6,a61,a62,a63,a64,a65,a8,a81,a82,a83,a84,a85";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i <= count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	////echo $chuoi."<br>";
	//	xuat4($chuoi);
	////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	$chuoi_mang_a = "E,É,È,Ẻ,Ẽ,Ẹ,Ê,Ế,Ề,Ể,Ễ,Ệ";
	$chuoi_mang_a_1 = "E,E1,E2,E3,E4,E5,E6,E61,E62,E63,E64,E65";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $chuoi_mang_a_1[$i], $chuoi);
	}
	$chuoi_mang_a = "Ý,Ỳ,Ỷ,Ỹ,Ỵ,Í,Ì,Ỉ,Ĩ,Ị";
	$chuoi_mang_a_1 = "Y1,Y2,Y3,Y4,Y5,I1,I2,I3,I4,I5";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	//echo $chuoi."<br>";
	$chuoi_mang_a = "U,Ú,Ù,Ủ,Ũ,Ụ,Ư,Ứ,Ừ,Ử,Ữ,Ự";
	$chuoi_mang_a_1 = "U,U1,U2,U3,U4,U5,U7,U71,U72,U73,U74,U75";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	//in_mang1($mang_a);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	//////echo $chuoi."<br>";
	$chuoi_mang_a = "O,Ó,Ò,Ỏ,Õ,Ọ,Ô,Ố,Ồ,Ổ,Ỗ,Ộ,Ơ,Ớ,Ờ,Ở,Ỡ,Ợ";
	$chuoi_mang_a_1 = "O,O1,O2,O3,O4,O5,O6,O61,O62,O63,O64,O65,O7,O71,O72,O73,O74,O75";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	$chuoi_mang_a = "A,Á,À,Ả,Ã,Ạ,Â,Ấ,Ầ,Ẩ,Ẫ,Ậ,Ă,Ắ,Ằ,Ẳ,Ẵ,Ặ";
	$chuoi_mang_a_1 = "A,A1,A2,A3,A4,A5,A6,A61,A62,A63,A64,A65,A8,A81,A82,A83,A84,A85";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a[$i], $mang_a_1[$i], $chuoi);
	}
	//	xuat4($chuoi);
	//echo $chuoi."666666666666666666666<br>";
	$chuoi = str_replace("đ", "d9", $chuoi);
	$chuoi = str_replace("Đ", "D9", $chuoi);
	//	thongbao("$chuoi");
	//echo $chuoi."tttttttttttttttttteeeeeeeeeeeeeeeeeeeee<br>";
	//kiembien1($chuoi);
	$chuoi = substr($chuoi, 0, $so);
	//break;

	//echo $chuoi."eeeeeeeeeeeeeeeeeeeee<br>";
	//exit;
	//	xuat4($chuoi);
	$mang_chuoi = explode(" ", $chuoi);
	if (count($mang_chuoi) == 1) {
	} else {
		unset($mang_chuoi[count($mang_chuoi) - 1]);
	}
	$chuoi = implode(" ", $mang_chuoi);
	//echo $chuoi."<hr>";
	$chuoi = chuyennguoc_753($chuoi);

	return $chuoi;
}
function chuyennguoc_753($chuoi)
{
	//	thongbao("$chuoi");
	$chuoi_mang_a = "ệ,ễ,ể,ề,ế,ê,ẹ,ẽ,ẻ,è,é";
	$chuoi_mang_a_1 = "e65,e64,e63,e62,e61,e6,e5,e4,e3,e2,e1";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi_mang_b = "ý,ỳ,ỷ,ỹ,ỵ,í,ì,ỉ,ĩ,ị";
	$chuoi_mang_b_1 = "y1,y2,y3,y4,y5,i1,i2,i3,i4,i5";

	$mang_b = explode(",", $chuoi_mang_b);
	$mang_b_1 = explode(",", $chuoi_mang_b_1);
	//in_mang1($mang_b);
	//in_mang1($mang_b_1);
	//echo $chuoi."<hr>";
	for ($i = 0; $i < count($mang_b); $i++) {
		//echo $mang_b[$i]." va ".$chuoi_mang_b_1[$i]."<br>";
		$chuoi = str_replace($mang_b_1[$i], $mang_b[$i], $chuoi);
	}
	//echo $chuoi."<hr>";
	$chuoi_mang_a = "ự,ữ,ử,ừ,ứ,ú,ù,ủ,ũ,ụ,ư,ứ,ừ,ử,ữ,ự";
	$chuoi_mang_a_1 = "u75,u74,u73,u72,u71,u1,u2,u3,u4,u5,u7,u71,u72,u73,u64,u75";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	//in_mang1($mang_a);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi_mang_a = "ộ,ỗ,ổ,ồ,ố,ợ,ỡ,ở,ờ,ớ,ó,ò,ỏ,õ,ọ,ô,ố,ồ,ổ,ỗ,ộ,ơ,ớ,ờ,ở,ỡ,ợ";
	$chuoi_mang_a_1 = "o65,o64,o63,o62,o61,o75,o74,o73,o72,o71,o1,o2,o3,o4,o5,o6,o61,o62,o63,o64,o65,o7,o71,o72,o73,o74,o75";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi_mang_a = "ậ,ẫ,ẩ,ầ,ấ,á,à,ả,ã,ạ,â,ấ,ầ,ẩ,ẫ,ậ,ắ,ằ,ẳ,ẵ,ặ,ă";
	$chuoi_mang_a_1 = "a65,a64,a63,a62,a61,a1,a2,a3,a4,a5,a6,a61,a62,a63,a64,a65,a81,a82,a83,a84,a85,a8";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i <= count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
	$chuoi_mang_a = "E,É,È,Ẻ,Ẽ,Ẹ,Ế,Ề,Ể,Ễ,Ệ,Ê";
	$chuoi_mang_a_1 = "E,E1,E2,E3,E4,E5,E61,E62,E63,E64,E65,E6";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $chuoi_mang_a[$i], $chuoi);
	}
	$chuoi_mang_a = "Ý,Ỳ,Ỷ,Ỹ,Ỵ,Í,Ì,Ỉ,Ĩ,Ị";
	$chuoi_mang_a_1 = "Y1,Y2,Y3,Y4,Y5,I1,I2,I3,I4,I5";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi_mang_a = "U,Ú,Ù,Ủ,Ũ,Ụ,Ứ,Ừ,Ử,Ữ,Ự,Ư";
	$chuoi_mang_a_1 = "U,U1,U2,U3,U4,U5,U71,U72,U73,U74,U75,U7";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	//in_mang1($mang_a);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi_mang_a = "O,Ó,Ò,Ỏ,Õ,Ọ,Ố,Ồ,Ổ,Ỗ,Ộ,Ô,Ớ,Ờ,Ở,Ỡ,Ợ,Ơ";
	$chuoi_mang_a_1 = "O,O1,O2,O3,O4,O5,O61,O62,O63,O64,O65,O6,O71,O72,O73,O74,O75,O7";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi_mang_a = "A,Á,À,Ả,Ã,Ạ,Ấ,Ầ,Ẩ,Ẫ,Ậ,Â,Ắ,Ằ,Ẳ,Ẵ,Ặ,Ă";
	$chuoi_mang_a_1 = "A,A1,A2,A3,A4,A5,A61,A62,A63,A64,A65,A6,A81,A82,A83,A84,A85,A8";
	$mang_a = explode(",", $chuoi_mang_a);
	$mang_a_1 = explode(",", $chuoi_mang_a_1);
	for ($i = 0; $i < count($mang_a); $i++) {
		$chuoi = str_replace($mang_a_1[$i], $mang_a[$i], $chuoi);
	}
	$chuoi = str_replace("d9", "đ", $chuoi);
	$chuoi = str_replace("D9", "Đ", $chuoi);
	$chuoi_mang = explode(" ", $chuoi);

	$chuoi_mang1 = dkl($chuoi_mang);
	//thongbao("$tv2[6]");
	if (count($chuoi_mang1) != 0) {
		$chuoi = implode(" ", $chuoi_mang1);
	} else {
		$chuoi = "";
	}
	//$chuoi=str_replace("\n","",$chuoi);
	$chuoi = trim($chuoi);
	$chuoi = str_replace("\r", "", $chuoi);
	return $chuoi;
}
