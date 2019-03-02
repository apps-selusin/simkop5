<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "userfn13.php" ?>

<?php
// --------------------------------------------------
// tabel periode
// tabel periodeold
// --------------------------------------------------
// proses pindah data dari tabel periode ke tabel periode_old
$q = "insert into t92_periodeold (Bulan, Tahun, Tahun_Bulan) select Bulan, Tahun, Tahun_Bulan from t93_periode";
Conn()->Execute($q);

// update data di tabel periode dengan data periode baru
$q = "select * from t93_periode";
$r = Conn()->Execute($q);
$periode_skrg = $r->fields["Tahun_Bulan"];
$periode_lalu_bulan = $r->fields["Bulan"];
$periode_lalu_tahun = $r->fields["Tahun"];
$periode_skrg_bulan = $periode_lalu_bulan + 1;
$periode_skrg_tahun = $periode_lalu_tahun;
if ($periode_skrg_bulan == 13) {
    $periode_skrg_bulan = 1;
    $periode_skrg_tahun = $periode_lalu_tahun + 1;
}
$periode_skrg_tahun_bulan = $periode_skrg_tahun . substr("00".$periode_skrg_bulan, -2);
$q = "truncate t93_periode";
Conn()->Execute($q);

$q = "insert into t93_periode values (null, ".$periode_skrg_bulan.", ".$periode_skrg_tahun.", ".$periode_skrg_tahun_bulan.")";
Conn()->Execute($q); //echo $q; exit();
// --------------------------------------------------


// --------------------------------------------------
// view v22_labarugi
// tabel labarugiold
// --------------------------------------------------
// append data dari view labarugi ke labarugiold
$q = "insert into t77_labarugiold SELECT * FROM `v22_labarugi`";
Conn()->Execute($q);
// --------------------------------------------------


// --------------------------------------------------
// view v23_neraca
// tabel neracaold
// --------------------------------------------------
// append data dari view neraca ke neracaold
$q = "insert into t76_neracaold SELECT * FROM `v23_neraca`";
Conn()->Execute($q);
// --------------------------------------------------


// --------------------------------------------------
// tabel rekening
// tabel rekeningold
// --------------------------------------------------
// append data dari tabel t91_rekening ke t80_rekeningold
$q = "insert into t80_rekeningold select * from `t91_rekening`";
Conn()->Execute($q);

// update data saldo di tabel t91_rekening, update dari v12_saldoakhir
$q = "update t91_rekening left join v12_saldoakhir on t91_rekening.id = v12_saldoakhir.id
	set t91_rekening.saldo = v12_saldoakhir.saldo";
Conn()->Execute($q);

// update data Periode di tabel t91_rekening dengan data periode baru
$q = "update t91_rekening set Periode = '".$periode_skrg_tahun_bulan."'";
Conn()->Execute($q);
// --------------------------------------------------


// --------------------------------------------------
// tabel jurnal
// tabel jurnalold
// --------------------------------------------------
// append data dari tabel jurnal ke jurnalold
$q = "insert into t82_jurnalold (Tanggal, Periode, NomorTransaksi, Rekening, Debet, Kredit, Keterangan) 
	SELECT Tanggal, Periode, NomorTransaksi, Rekening, Debet, Kredit, Keterangan FROM `t10_jurnal`";
Conn()->Execute($q);

// truncate tabel jurnal
$q = "truncate t10_jurnal";
Conn()->Execute($q);
// --------------------------------------------------


// kembali ke cf02_tutupbuku
header("location: cf02_tutupbuku.php?ok=1");
?>