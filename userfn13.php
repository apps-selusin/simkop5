<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
	$GLOBALS["Periode"] = ew_ExecuteScalar("select Tahun_Bulan from t93_Periode");

	//echo "0".$GLOBALS["Periode"]; //exit;
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function f_cari_detail_angsuran($pinjaman_id) { // -----------------------------------------
	$q = "
		select
			*
		from
			t04_pinjamanangsurantemp
		where
			pinjaman_id = ".$pinjaman_id."
			and Periode is not null
		order by
			id desc
		";
	$r = Conn()->Execute($q);
	$Angsuran_Ke = 0;
	if ($r->EOF) { // belum ada data ber-Periode
		$Angsuran_Ke = 1;

		//return $Angsuran_Ke;
	}
	else {

		// sudah ada data ber-Periode
		$Periode = $r->fields["Periode"];

		// bandingkan data Periode yang ada di database dengan data Periode berjalan
		if ($r->fields["Periode"] == $GLOBALS["Periode"]) {

			// data Periode di database masih pada Periode berjalan
			$Angsuran_Ke = $r->fields["Angsuran_Ke"];

			//return $Angsuran_Ke; //$r->fields["Angsuran_Ke"];
		}
		else {

			// data Periode di database sudah tidak sama dengan Periode berjalan
			$Angsuran_Ke = ++$r->fields["Angsuran_Ke"];

			//return $Angsuran_Ke;
		}
	}
	$t04_pinjamanangsurantemp_id = 0;
	$q = "select id from t04_pinjamanangsurantemp where pinjaman_id = ".
		$pinjaman_id." and Angsuran_Ke = ".$Angsuran_Ke."";
	$t04_pinjamanangsurantemp_id = ew_ExecuteScalar($q);
	return $t04_pinjamanangsurantemp_id;
} // end of function f_cari_detail_angsuran ------------------------------------------------

function f_create_rincian_angsuran($rsnew) { // --------------------------------------------

	// create data rincian angsuran
	$pinjaman_id      = $rsnew["id"];
	$Angsuran_Tanggal = $rsnew["Kontrak_Tgl"];
	$Angsuran_Tgl     = substr($Angsuran_Tanggal, -2);
	$Angsuran_Pokok   = $rsnew["Angsuran_Pokok"];
	$Angsuran_Bunga   = $rsnew["Angsuran_Bunga"];
	$Angsuran_Total   = $Angsuran_Pokok + $Angsuran_Bunga;
	$Sisa_Hutang      = $rsnew["Pinjaman"];
	$Angsuran_Pokok_Total = 0;
	$Angsuran_Bunga_Total = 0;
	$Angsuran_Total_Grand = 0;

	//for ($i; $i <= 12; $i++) {
	for ($Angsuran_Ke = 1; $Angsuran_Ke <= $rsnew["Angsuran_Lama"]; $Angsuran_Ke++) {
		$Angsuran_Tanggal      = f_create_tanggal_angsuran($Angsuran_Tanggal, $Angsuran_Tgl);
		$Angsuran_Pokok_Total += $Angsuran_Pokok;
		if ($Angsuran_Pokok_Total >= $rsnew["Pinjaman"]) {
			$Angsuran_Pokok       = $Angsuran_Pokok - ($Angsuran_Pokok_Total - $rsnew["Pinjaman"]);
			$Angsuran_Pokok_Total = $Angsuran_Pokok_Total - ($Angsuran_Pokok_Total - $rsnew["Pinjaman"]);
		}
		$Sisa_Hutang          -= $Angsuran_Pokok;
		$Angsuran_Bunga        = $Angsuran_Total - $Angsuran_Pokok;
		$Angsuran_Bunga_Total += $Angsuran_Bunga;
		$Angsuran_Total_Grand += $Angsuran_Total;
		$q = "insert into t04_pinjamanangsurantemp (
			pinjaman_id,
			Angsuran_Ke,
			Angsuran_Tanggal,
			Angsuran_Pokok,
			Angsuran_Bunga,
			Angsuran_Total,
			Sisa_Hutang
			) values (
			'".$pinjaman_id."',
			'".$Angsuran_Ke."',
			'".$Angsuran_Tanggal."',
			".$Angsuran_Pokok.",
			".$Angsuran_Bunga.",
			".$Angsuran_Total.",
			".$Sisa_Hutang."
			)";
		ew_Execute($q);
	}
} // end of function f_create_rincian_angsuran ---------------------------------------------

function f_create_tanggal_angsuran($sTanggal, $sTgl) { // ----------------------------------
	$akhir_bulan = 0;
	$dBln2829 = array(2);
	$dBln30 = array(4, 6, 9, 11);
	$dBln31 = array(1, 3, 5, 7, 8, 10, 12);

	//$dTgl = date("j", strtotime($sTanggal));
	$dTgl = $sTgl; //date("j", strtotime($sTanggal));
	$dBln = date("n", strtotime($sTanggal));
	$dThn = date("Y", strtotime($sTanggal));

	// check apakah bulan kontrak adalah desember
	if  ($dBln == 12) {
		$dBln = "01";
		$dThn++;
	}
	else {
		$dBln++;
	}
	if ($dTgl >= 1 and $dTgl <= 27) {
	}
	else {
		if (($dTgl == 28 or $dTgl == 29) and in_array($dBln, $dBln2829)) {
			$akhir_bulan = 1;
		}
		elseif ($dTgl == 30 and in_array($dBln, $dBln30)) {
			$akhir_bulan = 1;
		}
		elseif ($dTgl == 31 and in_array($dBln, $dBln31)) {
			$akhir_bulan = 1;
		}
	}
	if ($akhir_bulan == 0) { //$akhir_bulan = 0 (bukan tanggal akhir bulan)
		do {
			$hasil_check = checkdate(
				substr("00".$dBln, -2),
				substr("00".$dTgl, -2),
				$dThn
				);
			$sTanggalAngsuran = $dThn . "-" . substr("00".$dBln, -2) . "-" . substr("00".$dTgl, -2);
			/*echo substr("00".$dBln, -2).substr("00".$dTgl, -2).$dThn;
			echo " - ";
			echo $hasil_check;
			echo "<br/>";*/
			$dTgl--;
		}
		while ($hasil_check == false);
	}
	else { //$akhir_bulan = 1;
		$sTanggalAngsuran = $dThn . "-" . substr("00".$dBln, -2) . "-" . date("t", mktime(0, 0, 0, $dBln, 1, $dThn));
	}

	//echo $akhir_bulan;
	return $sTanggalAngsuran;
} // end of function f_create_tanggal_angsuran ---------------------------------------------
?>
