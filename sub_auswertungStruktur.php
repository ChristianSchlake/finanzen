<?php
//	session_start();
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
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
	$tabellenBeschreibung="";
	$tabelle="";
	$datumVon="01.01.1902";
	$datumBis = date("d.m.Y");
	$spaltenTypX="auswahlStruktur";
	$spalte="konto";
//	print_r($_GET);
	foreach ($_GET as $key => $value) {
		if ($key=="tabelle") {$tabelle=$value;}
		if ($key=="tabellenBeschreibung") {$tabellenBeschreibung=$value;}
		if ($key=="spalte") {$spalte=$value;}
		if ($key=="spaltenTypX") {$spaltenTypX=$value;}
		if ($key=="datum") {
			if ($value!="") {
				$datumX=explode("-",$value);
				if (strtotime($datumX[0])>strtotime("01.01.0000")) {
					$datumVon=$datumX[0];
				} else {
					$datumVon="01.01.1000";
				}
				if (strtotime($datumX[1])>strtotime("01.01.0000")) {
					$datumBis=$datumX[1];
				} else {
					$datumBis="31.12.9999";
				}
			}
		}
	}
?>


<nav class="top-bar" data-topbar data-options="is_hover:true">
	<ul class="title-area">
		<li class="name">
			<?php
				echo "<h1><a href=\"main_suche.php\"><i class=\"fi-refresh \"></i> ".$tabellenBeschreibung."</a></h1>";
			?>			
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="left">
	        <li class="divider hide-for-small"></li>
	        <li class="has-dropdown"><a href="#"><i class="fi-graph-bar "></i> Auswertungen</a>
	                <ul class="dropdown">
							<li><a href="sub_auswertungStruktur.php?spaltenTypX=auswahlStruktur&spalte=konto&tabelle=buchung_kategorie&tabellenBeschreibung=Konto">Konto</a></li>
	                </ul>
	        </li>
		</ul>
		<ul class="right">
			<a class="button secondary round" href="sub_auswertungStruktur.php" data-reveal-id="eingabeModal"><i class="fi-page-add"></i> neuer Eintrag</a>
		</ul>
		
	</section>
</nav>
<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Suchformular -->
<div class="row collapse">
	<fieldset>
		<?php
			echo "<legend>".$tabellenBeschreibung."</legend>";
		?>
		<form action="sub_auswertungStruktur.php" method="get">
			<?php
				echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";
				echo "<input type=\"hidden\" name=\"spaltenTypX\" value=\"".$spaltenTypX."\"\>";
				echo "<input type=\"hidden\" name=\"tabellenBeschreibung\" value=\"".$tabellenBeschreibung."\"\>";
				echo "<input type=\"hidden\" name=\"spalte\" value=\"".$spalte."\"\>";
			?>
			<div class="small-8 columns">
				<?php
					echo "<input type=\"text\" placeholder=\"Datum\" name=\"datum\" value=\"".$datumVon."-".$datumBis."\">";
				?>
			</div>
			<div class="small-4 columns">
				<input class="button prefix secondary" value="suchen" type="Submit">			
			</div>
		</form>		
	</fieldset>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->

<div class="row collapse">
	<div class="small-6 large-6 columns">
		<h2>Wert</h2>
	</div>
		<div class="small-6 large-6 columns"><h2>Summe</h2></div>
	<hr>
</div>

<?php
	$auswahlX=array();
	switch ($spaltenTypX) {
		case 'auswahlStruktur':
				generateListOrdnerAenderung(0,"",$tabelle,0,true);
			break;
		case 'auswahl':
				generateListOrdnerAenderung_auswahl($tabelle);
			break;
	}
	foreach ($auswahlX as $i => $value)
	{
		$ordner=explode("|", $value);
//		print_r($ordner);
		echo "<div class=\"row collapse\">";
			echo "<div class=\"small-4 large-4 columns\">";
				echo "<p>",$ordner[2],"</p>";
			echo "</div>";
				echo "<div class=\"small-4 large-4 columns\">";
					$GesSumme=0;
					auswertungGesSumme($ordner[0],$tabelle,$datumVon,$datumBis,$spalte);
//					$abfrage="SELECT sum(betrag) AS summe FROM buchungen INNER JOIN metadaten as meta ON (idBuchung = meta.id) inner join buchung_kategorie as buchung on (buchung.buchung_kategorieID = konto) inner join verwendung as verw on (verw.verwendungID = meta.verwendung) WHERE ".$spalte."=".$ordner[0]." AND meta.datum >= STR_TO_DATE('".$datumVon."','%d.%m.%Y') AND meta.datum <= STR_TO_DATE('".$datumBis."','%d.%m.%Y')";
//					$ergebnis=mysql_query($abfrage);
//					$row = mysql_fetch_assoc($ergebnis);
//					$summe = $row['summe'];
					if ($GesSumme<>0) {
						echo "<p>",str_replace(".",",",$GesSumme)," €</p>";
					}
				echo "</div>";
			echo "<hr>";
		echo "</div>";
	}
?>





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
