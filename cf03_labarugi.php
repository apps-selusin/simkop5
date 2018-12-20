<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t96_employeesinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cf03_labarugi_php = NULL; // Initialize page object first

class ccf03_labarugi_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{C5FF1E3B-3DAB-4591-8A48-EB66171DE031}";

	// Table name
	var $TableName = 'cf03_labarugi.php';

	// Page object name
	var $PageObjName = 'cf03_labarugi_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cf03_labarugi.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// User table object (t96_employees)
		if (!isset($UserTable)) {
			$UserTable = new ct96_employees();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		 // Close connection

		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "cf03_labarugi_php", $url, "", "cf03_labarugi_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($cf03_labarugi_php)) $cf03_labarugi_php = new ccf03_labarugi_php();

// Page init
$cf03_labarugi_php->Page_Init();

// Page main
$cf03_labarugi_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php

// hapus t88_labarugi
$q = "delete from t88_labarugi";
Conn()->Execute($q);

$q = "select * from t91_rekening where id = '3'";
$r = Conn()->Execute($q);

$q = "select * from t91_rekening where parent = '3' and tipe = 'DETAIL' order by id";
$rdet = Conn()->Execute($q);

$q = "select * from t91_rekening where id = '5'";
$r2 = Conn()->Execute($q);

$q = "select * from t91_rekening where parent = '5' and tipe = 'DETAIL' order by id";
$rdet2 = Conn()->Execute($q);

$q = "select * from t91_rekening where id = '4'";
$r3 = Conn()->Execute($q);

$q = "select * from t91_rekening where parent = '4' and tipe = 'DETAIL' order by id";
$rdet3 = Conn()->Execute($q);

$q = "select * from t91_rekening where id = '6'";
$r4 = Conn()->Execute($q);

$q = "select * from t91_rekening where parent = '6' and tipe = 'DETAIL' order by id";
$rdet4 = Conn()->Execute($q);
?>

<div class="panel panel-default">
	<div class="panel-heading"><strong><a class='collapsed' data-toggle="collapse" href="#labarugi">Laba Rugi Periode <?php echo $GLOBALS["Periode"]; ?></a></strong></div>
	<div id="labarugi" class="panel-collapse collapse in">
		<div class="panel-body">
			<div>
				<table class='table table-striped table-hover table-condensed'>
					<tbody>

					<!-- id 3 -->
					<?php while (!$r->EOF) { ?>
					<tr>
						<td><strong><?php echo $r->fields["rekening"]; ?></strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('<strong>".$r->fields["rekening"]."</strong>', '', '')"; Conn()->Execute($q);?>
					<?php   $r->MoveNext(); ?>
					<?php } ?>

					<?php $mtotal = 0;?>
					<?php while (!$rdet->EOF) { ?>
					<?php
							$q = "select sum(Kredit) - sum(Debet) as Nilai from t10_jurnal where
								Rekening = '".$rdet->fields["id"]."'
								and Periode = '".$GLOBALS["Periode"]."'";
							$rhasil = Conn()->Execute($q);
							$nilai = $rhasil->fields["Nilai"] == null ? 0 : $rhasil->fields["Nilai"];
							$mtotal += $nilai;
					?>
					<tr>
						<td><?php echo $rdet->fields["id"]; ?></td>
						<td><?php echo $rdet->fields["rekening"]; ?></td>
						<td align="right"><?php echo number_format($nilai, 2); ?></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('".$rdet->fields["id"]."', '".$rdet->fields["rekening"]."', '".number_format($nilai, 2)."')"; Conn()->Execute($q);?>
					<?php   $rdet->MoveNext(); ?>
					<?php } ?>


					<!-- id 5 -->
					<?php while (!$r2->EOF) { ?>
					<tr>
						<td><strong><?php echo $r2->fields["rekening"]; ?></strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('<strong>".$r2->fields["rekening"]."</strong>', '', '')"; Conn()->Execute($q);?>
					<?php   $r2->MoveNext(); ?>
					<?php } ?>

					<?php while (!$rdet2->EOF) { ?>
					<?php
							$q = "select sum(Kredit) - sum(Debet) as Nilai from t10_jurnal where
								Rekening = '".$rdet2->fields["id"]."'
								and Periode = '".$GLOBALS["Periode"]."'";
							$rhasil = Conn()->Execute($q);
							$nilai = $rhasil->fields["Nilai"] == null ? 0 : $rhasil->fields["Nilai"];
							$mtotal += $nilai;
					?>
					<tr>
						<td><?php echo $rdet2->fields["id"]; ?></td>
						<td><?php echo $rdet2->fields["rekening"]; ?></td>
						<td align="right"><?php echo number_format($nilai, 2); ?></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('".$rdet2->fields["id"]."', '".$rdet2->fields["rekening"]."', '".number_format($nilai, 2)."')"; Conn()->Execute($q);?>
					<?php   $rdet2->MoveNext(); ?>
					<?php } ?>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right"><strong><?php echo number_format($mtotal, 2); ?></strong></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('', '', '<strong>".number_format($mtotal, 2)."</strong>')"; Conn()->Execute($q);?>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('', '', '')"; Conn()->Execute($q);?>

					<!-- id 4 -->
					<?php while (!$r3->EOF) { ?>
					<tr>
						<td><strong><?php echo $r3->fields["rekening"]; ?></strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('<strong>".$r3->fields["rekening"]."</strong>', '', '')"; Conn()->Execute($q);?>
					<?php   $r3->MoveNext(); ?>
					<?php } ?>

					<?php $mtotal2 = 0;?>
					<?php while (!$rdet3->EOF) { ?>
					<?php
							$q = "select sum(Debet) - sum(Kredit) as Nilai from t10_jurnal where
								Rekening = '".$rdet3->fields["id"]."'
								and Periode = '".$GLOBALS["Periode"]."'";
							$rhasil = Conn()->Execute($q);
							$nilai = $rhasil->fields["Nilai"] == null ? 0 : $rhasil->fields["Nilai"];
							$mtotal2 += $nilai;
					?>
					<tr>
						<td><?php echo $rdet3->fields["id"]; ?></td>
						<td><?php echo $rdet3->fields["rekening"]; ?></td>
						<td align="right"><?php echo number_format($nilai, 2); ?></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('".$rdet3->fields["id"]."', '".$rdet3->fields["rekening"]."', '".number_format($nilai, 2)."')"; Conn()->Execute($q);?>
					<?php   $rdet3->MoveNext(); ?>
					<?php } ?>


					<!-- id 6 -->
					<?php while (!$r4->EOF) { ?>
					<tr>
						<td><strong><?php echo $r4->fields["rekening"]; ?></strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('<strong>".$r4->fields["rekening"]."</strong>', '', '')"; Conn()->Execute($q);?>
					<?php   $r4->MoveNext(); ?>
					<?php } ?>

					<?php while (!$rdet4->EOF) { ?>
					<?php
							$q = "select sum(Debet) - sum(Kredit) as Nilai from t10_jurnal where
								Rekening = '".$rdet4->fields["id"]."'
								and Periode = '".$GLOBALS["Periode"]."'";
							$rhasil = Conn()->Execute($q);
							$nilai = $rhasil->fields["Nilai"] == null ? 0 : $rhasil->fields["Nilai"];
							$mtotal2 += $nilai;
					?>
					<tr>
						<td><?php echo $rdet4->fields["id"]; ?></td>
						<td><?php echo $rdet4->fields["rekening"]; ?></td>
						<td align="right"><?php echo number_format($nilai, 2); ?></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('".$rdet4->fields["id"]."', '".$rdet4->fields["rekening"]."', '".number_format($nilai, 2)."')"; Conn()->Execute($q);?>
					<?php   $rdet4->MoveNext(); ?>
					<?php } ?>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right"><strong><?php echo number_format($mtotal2, 2); ?></strong></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('', '', '<strong>".number_format($mtotal2, 2)."</strong>')"; Conn()->Execute($q);?>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('', '', '')"; Conn()->Execute($q);?>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right"><strong><?php echo number_format($mtotal - $mtotal2, 2); ?></strong></td>
					</tr>
					<?php   $q = "insert into t88_labarugi (field01, field02, field03) values ('', '', '<strong>".number_format($mtotal - $mtotal2, 2)."</strong>')"; Conn()->Execute($q);?>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php //header("Location: r05_labarugismry.php"); ?>
<?php header("Location: t88_labarugilist.php"); ?>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$cf03_labarugi_php->Page_Terminate();
?>
