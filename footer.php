<?php if (@!$gbSkipHeaderFooter) { ?>
				<!-- right column (end) -->
				<?php if (isset($gTimer)) $gTimer->Stop() ?>
			</div>
		</div>
	</div>
	<!-- content (end) -->
	<!-- footer (begin) --><!-- ** Note: Only licensed users are allowed to remove or change the following copyright statement. ** -->
	<div id="ewFooterRow" class="ewFooterRow">	
		<div class="ewFooterText"><?php echo $Language->ProjectPhrase("FooterText") ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
</div>
<?php } ?>
<!-- modal dialog -->
<div id="ewModalDialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>
<!-- message box -->
<div id="ewMsgBox" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- prompt -->
<div id="ewPrompt" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("MessageOK") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!-- session timer -->
<div id="ewTimer" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- tooltip -->
<div id="ewTooltip"></div>
<script type="text/javascript">
jQuery.get("<?php echo $EW_RELATIVE_PATH ?>phpjs/userevt13.js");
</script>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");
	// Table 't03_pinjaman' Field 'Pinjaman'

	$('[data-table=t03_pinjaman][data-field=x_Pinjaman]').on(
		{ // keys = event types, values = handler functions
			"change keyup": function(e) {
				var $row = $(this).fields();
				var pinjaman_asli = $row["Pinjaman"].val();
				var pinjaman_clean = pinjaman_asli.replace(/,/g, '');
				var pinjaman = parseFloat(pinjaman_clean);
				var lama_angsuran = parseFloat($row["Angsuran_Lama"].val());
				var bunga = parseFloat($row["Angsuran_Bunga_Prosen"].val());
				var angsuran_pokok = pinjaman / lama_angsuran;
				var angsuran_bunga = pinjaman * (bunga / 100);
				var angsuran_total = angsuran_pokok + angsuran_bunga;
				$row["Angsuran_Pokok"].val(angsuran_pokok);
				$row["Angsuran_Bunga"].val(angsuran_bunga);
				$row["Angsuran_Total"].val(angsuran_total);
			}
		}
	);

	// Table 't03_pinjaman' Field 'Angsuran_Lama'
	$('[data-table=t03_pinjaman][data-field=x_Angsuran_Lama]').on(
		{ // keys = event types, values = handler functions
			"change keyup": function(e) {
				var $row = $(this).fields();
				var pinjaman_asli = $row["Pinjaman"].val();
				var pinjaman_clean = pinjaman_asli.replace(/,/g, '');
				var pinjaman = parseFloat(pinjaman_clean);
				var lama_angsuran = parseFloat($row["Angsuran_Lama"].val());
				var bunga = parseFloat($row["Angsuran_Bunga_Prosen"].val());
				var angsuran_pokok = pinjaman / lama_angsuran;
				var angsuran_bunga = pinjaman * (bunga / 100);
				var angsuran_total = angsuran_pokok + angsuran_bunga;
				$row["Angsuran_Pokok"].val(angsuran_pokok);
				$row["Angsuran_Bunga"].val(angsuran_bunga);
				$row["Angsuran_Total"].val(angsuran_total);
			}
		}
	);

	// Table 't03_pinjaman' Field 'Angsuran_Bunga_Prosen'
	$('[data-table=t03_pinjaman][data-field=x_Angsuran_Bunga_Prosen]').on(
		{ // keys = event types, values = handler functions
			"change keyup": function(e) {
				var $row = $(this).fields();
				var pinjaman_asli = $row["Pinjaman"].val();
				var pinjaman_clean = pinjaman_asli.replace(/,/g, '');
				var pinjaman = parseFloat(pinjaman_clean);
				var lama_angsuran = parseFloat($row["Angsuran_Lama"].val());
				var bunga = parseFloat($row["Angsuran_Bunga_Prosen"].val());
				var angsuran_pokok = pinjaman / lama_angsuran;
				var angsuran_bunga = pinjaman * (bunga / 100);
				var angsuran_total = angsuran_pokok + angsuran_bunga;
				$row["Angsuran_Pokok"].val(angsuran_pokok);
				$row["Angsuran_Bunga"].val(angsuran_bunga);
				$row["Angsuran_Total"].val(angsuran_total);
			}
		}
	);

	// Table 't03_pinjaman' Field 'Angsuran_Pokok'
	$('[data-table=t03_pinjaman][data-field=x_Angsuran_Pokok]').on(
		{ // keys = event types, values = handler functions
			"change keyup": function(e) {
				var $row = $(this).fields();
				var angsuran_pokok_asli = $row["Angsuran_Pokok"].val();
				var angsuran_pokok_clean = angsuran_pokok_asli.replace(/,/g, '');
				var angsuran_pokok = parseFloat(angsuran_pokok_clean);
				var angsuran_bunga_asli = $row["Angsuran_Bunga"].val();
				var angsuran_bunga_clean = angsuran_bunga_asli.replace(/,/g, '');
				var angsuran_bunga = parseFloat(angsuran_bunga_clean);
				var angsuran_total = angsuran_pokok + angsuran_bunga;
				$row["Angsuran_Total"].val(angsuran_total);
			}
		}
	);

	// Table 't03_pinjaman' Field 'Angsuran_Bunga'
	$('[data-table=t03_pinjaman][data-field=x_Angsuran_Bunga]').on(
		{ // keys = event types, values = handler functions
			"change keyup": function(e) {
				var $row = $(this).fields();
				var angsuran_pokok_asli = $row["Angsuran_Pokok"].val();
				var angsuran_pokok_clean = angsuran_pokok_asli.replace(/,/g, '');
				var angsuran_pokok = parseFloat(angsuran_pokok_clean);
				var angsuran_bunga_asli = $row["Angsuran_Bunga"].val();
				var angsuran_bunga_clean = angsuran_bunga_asli.replace(/,/g, '');
				var angsuran_bunga = parseFloat(angsuran_bunga_clean);
				var angsuran_total = angsuran_pokok + angsuran_bunga;
				$row["Angsuran_Total"].val(angsuran_total);
				var pinjaman_asli = $row["Pinjaman"].val();
				var pinjaman_clean = pinjaman_asli.replace(/,/g, '');
				var pinjaman = parseFloat(pinjaman_clean);
				var bunga_asli = (angsuran_bunga / pinjaman) * 100;
				var bunga = bunga_asli.toFixed(2);
				$row["Angsuran_Bunga_Prosen"].val(bunga);
			}
		}
	);
</script>
</body>
</html>
