<?php
	include("sub_init_database.php");
	include("functions.php");
?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">
	
	<?php
		echo "<title>".abfrageEinstellung("tabellenNameLang")."</title>";
	?>

	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="icons/foundation-icons.css"/>

<style>      
	.size-12 { font-size: 12px; }
	.size-14 { font-size: 14px; }
	.size-16 { font-size: 16px; }
	.size-18 { font-size: 18px; }
	.size-21 { font-size: 21px; }
	.size-24 { font-size: 24px; }
	.size-36 { font-size: 36px; }
	.size-48 { font-size: 48px; }
	.size-60 { font-size: 60px; }
	.size-72 { font-size: 72px; }
	.size-X { font-size: 26px; }
</style>

</head>

<body>
	<?php
		foreach ($_GET as $key => $value) {
			if ($key=="id") {
				$id=$value;
				$abfrage="SELECT buch.id as buchungssatz, verwendungID, konto, beschreibung, datum, betrag FROM metadaten INNER JOIN buchungen as buch ON (buch.idBuchung = metadaten.id) inner join buchung_kategorie as buchung on (buchung.buchung_kategorieID = buch.konto) inner join verwendung as verw on (verw.verwendungID = metadaten.verwendung) WHERE metadaten.id=".$id." AND buch.betrag >= 0";
//				echo $abfrage;
				$ergebnis = mysql_query($abfrage);
				while($row = mysql_fetch_object($ergebnis)) {
					$datum=date("d.m.y",strtotime($row->datum));
					$verwendung=$row->verwendungID;
					$kontoNach=$row->konto;
					$beschreibung=$row->beschreibung;
					$betrag=str_replace(".",",",$row->betrag);
					$idNach=$row->buchungssatz;
				}
				$abfrage="SELECT buch.id as buchungssatz, verwendungID, konto, beschreibung, datum, betrag FROM metadaten INNER JOIN buchungen as buch ON (buch.idBuchung = metadaten.id) inner join buchung_kategorie as buchung on (buchung.buchung_kategorieID = buch.konto) inner join verwendung as verw on (verw.verwendungID = metadaten.verwendung) WHERE metadaten.id=".$id." AND buch.betrag < 0";
				$ergebnis = mysql_query($abfrage);
				while($row = mysql_fetch_object($ergebnis)) {
					$kontoVon=$row->konto;
					$idVon=$row->buchungssatz;
				}
			}
		}
	?>



	<div class="row">
		<fieldset>
			<legend>Ändern</legend>
			<form action="main_suche.php" method="POST" class="custom">
				<div class="small-12 large-3 columns">
					<label for="right-label" class="right">Datum</label>
				</div>
				<div class="small-12 large-9 columns">
					<?php
						echo "<input type=\"text\" name=\"datum\" value=\"".$datum."\" placeholder=\"Datum der Buchung [".date("m.d.Y")."]\">";
					?>
				</div>

				<div class="small-12 large-3 columns">
					<label for="right-label" class="right">Konto Von</label>
				</div>
				<div class="small-12 large-9 columns">
					<select name="kontoVon">
						<option selected value="%">%</option>
						<?php
							generateListOrdnerFormular(0,$kontoVon,"buchung_kategorie",0);
						?>
					</select>
				</div>
				<div class="small-12 large-3 columns">
					<label for="right-label" class="right">Konto Nach</label>
				</div>
				<div class="small-12 large-9 columns">
					<select name="kontoNach">
						<option selected value="%">%</option>
						<?php
							generateListOrdnerFormular(0,$kontoNach,"buchung_kategorie",0);
						?>
					</select>
				</div>

				<div class="small-12 large-3 columns">
					<label for="right-label" class="right">Verwendung</label>
				</div>
				<div class="small-12 large-9 columns">
					<select name="verwendung">
						<option selected value="%">%</option>
						<?php
							generateListOrdnerFormular(0,$verwendung,"verwendung",0);
						?>
					</select>
				</div>

				<div class="small-12 large-3 columns">
					<label for="right-label" class="right">Beschreibung</label>
				</div>
				<div class="small-12 large-9 columns">
					<?php
						echo "<input type=\"text\" name=\"beschreibung\" value=\"".$beschreibung."\" placeholder=\"Beschreibung\">";
					?>
				</div>

				<div class="small-12 large-3 columns">
					<label for="right-label" class="right">Betrag</label>
				</div>
				<div class="small-12 large-9 columns">
					<?php
						echo "<input type=\"text\" name=\"betrag\" value=\"".$betrag."\" placeholder=\"Betrag\">";
					?>
				</div>

				<div class="small-12 large-8 columns">
					<button class="button expand" type="Submit">Daten ändern</button>
				</div>
				<div class="small-12 large-4 columns">
					<?php
						echo "<a href=\"main_suche.php?reset=true&id=".$id."\" class=\"button expand\">Abbrechen</a>";
					?>
				</div>
				<input type="hidden" name="uebergabe" value="updateEintrag">
				<?php
					echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\">";
					echo "<input type=\"hidden\" name=\"reset\" value=\"true\">";
					echo "<input type=\"hidden\" name=\"idNach\" value=\"".$idNach."\">";
					echo "<input type=\"hidden\" name=\"idVon\" value=\"".$idVon."\">";
				?>
			</form>
		</fieldset>
	</div>
<?php
	mysql_close($verbindung);
?>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation/foundation.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script src="js/foundation/foundation.dropdown.js"></script>
  <script src="js/foundation/foundation.reveal.js"></script>
  <script>$(document).foundation();</script> 

</body>
