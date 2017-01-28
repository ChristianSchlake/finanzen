<?php
	session_start();
	include("sub_init_database.php");
	include("functions.php");

	// reset session
	foreach ($_GET as $key => $value) {
		if ($key=="reset" AND $value=="true") {
			$_SESSION = array();
		}
	}
	foreach ($_POST as $key => $value) {
		if ($key=="reset" AND $value=="true") {
			$_SESSION = array();
		}
	}


	if (!isset($_SESSION['datum'])) {$_SESSION['datum'] ="01.01.1902-".date("d.m.Y");}
	if (!isset($_SESSION['verwendung'])) {$_SESSION['verwendung'] = "%";}
	if (!isset($_SESSION['beschreibung'])) {$_SESSION['beschreibung'] = "%";}
	if (!isset($_SESSION['konto'])) {$_SESSION['konto'] = "%";}
	if (!isset($_SESSION['betrag'])) {$_SESSION['betrag'] = "%";}
	if (!isset($_SESSION['id'])) {$_SESSION['id'] = "%";}

	if (!isset($_SESSION['startPage'])) {$_SESSION['startPage'] ="0";}

?>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8"/>
	<meta name="viewport" content="width=device-width">

	<title>Finanzen</title>
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
		.size-X { font-size: 20px; }
	</style>

</head>

<body>

<?php	
/*-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -*/
/* Variablen eintragen */
	$neuerEintrag=0;
	$suchEintrag=0;
	$updateEintrag=0;
	$sortBy="DESC";
	$sort="datum";
	$whereClause="";
	$kontoVon="%";
	$kontoNach="%";
	$maxEintraegeProSite=abfrageEinstellung("maxEintraegeProSite");
	$showEingabemaske=abfrageEinstellung("showEingabemaske");
	$showSuchmaske=abfrageEinstellung("showSuchmaske");

	foreach ($_POST as $key => $value) {
		if ($key=="uebergabe") {
			switch ($value) {
				case "neuerEintrag":
					$neuerEintrag=1;							
					break;
				case "updateEintrag":
					$updateEintrag=1;
					break;
			}
		}
		if ($key=="datum") {$datum=$value;}
		if ($key=="verwendung") {$verwendung=$value;}
		if ($key=="betrag") {$betrag=str_replace(",",".",$value);}
		if ($key=="kontoVon") {$kontoVon=$value;}
		if ($key=="kontoNach") {$kontoNach=$value;}
		if ($key=="beschreibung") {$beschreibung=$value;}
		if ($key=="sort") {$sort=$value;}
		if ($key=="sortBy") {$sortBy=$value;}
		if ($key=="id") {$id=$value;}
		if ($key=="idVon") {$idVon=$value;}
		if ($key=="idNach") {$idNach=$value;}

	}
	foreach ($_GET as $key => $value) {
		if ($key=="uebergabe" AND $value=="suchEintrag") {$suchEintrag=1;}
		if ($key=="sort") {$sort=$value;}
		if ($key=="sortBy") {$sortBy=$value;}
		if ($key=="datum") {$_SESSION['datum'] = $value;}
		if ($key=="verwendung") {$_SESSION['verwendung'] = $value;}
		if ($key=="konto") {$_SESSION['konto'] = $value;}
		if ($key=="betrag") {$_SESSION['betrag'] = $value;}
		if ($key=="startPage") {$_SESSION['startPage'] = $value;}
		if ($key=="id") {$_SESSION['id'] = $value;}		
	}

	switch ($sortBy) {
		case "ASC":
			$sortBy="DESC";
			break;
		case "DESC":
			$sortBy="ASC";
			break;
	}


// Neuer Eintrag
	if ($neuerEintrag==1) {
		$betragNeg=$betrag*-1;
		$MaxID=mysqli_query("SELECT MAX(id) FROM metadaten");
		$MaxID=mysqli_fetch_array($MaxID, MYSQL_BOTH);
		$MaxID=$MaxID[0];
		$MaxID=$MaxID+1;
		$_SESSION['id']=$MaxID;
		$aufruf="INSERT INTO metadaten (id,datum,verwendung,beschreibung) VALUES (".$MaxID.",STR_TO_DATE('".$datum."', '%d.%m.%Y'),".$verwendung.",\"".$beschreibung."\")";
		$eintragen = mysqli_query($aufruf);
		$aufruf="INSERT INTO buchungen (konto,betrag,idBuchung) VALUES (".$kontoVon.",".$betragNeg.",".$MaxID.")";
		$eintragen = mysqli_query($aufruf);
		$aufruf="INSERT INTO buchungen (konto,betrag,idBuchung) VALUES (".$kontoNach.",".$betrag.",".$MaxID.")";
		$eintragen = mysqli_query($aufruf);
	}
// Update Eintrag
	if ($updateEintrag==1) {
		$betragNeg=$betrag*-1;

		$aufruf="UPDATE metadaten SET datum=STR_TO_DATE(\"".$datum."\", \"%d.%m.%Y\"),verwendung=".$verwendung.",beschreibung=\"".$beschreibung."\" WHERE id=".$id;
		$eintragen = mysqli_query($aufruf);

		$aufruf="UPDATE buchungen SET konto=".$kontoVon.",betrag=".$betragNeg." WHERE id=".$idVon;
		$eintragen = mysqli_query($aufruf);

		$aufruf="UPDATE buchungen SET konto=".$kontoNach.",betrag=".$betrag." WHERE id=".$idNach;
		$eintragen = mysqli_query($aufruf);

		$_SESSION['id']=$id;
	}

// Suchen
	if ($suchEintrag==1) {$_SESSION['startPage'] ="0";}
	// Datum auseinandernehmen
	$datumX=explode("-",$_SESSION['datum']);		
	if (strtotime($datumX[0])>strtotime("01.01.1902")) {			
		$whereClause="WHERE meta.datum >= STR_TO_DATE('".$datumX[0]."','%d.%m.%Y')";
	}
	if (strtotime($datumX[1])>strtotime("01.01.1902")) {
		$whereClause=$whereClause." AND meta.datum <= STR_TO_DATE('".$datumX[1]."','%d.%m.%Y')";
	} else {
		// wenn nur ein Wert, dann nach dem genauen Datum suchen!
		$whereClause="WHERE meta.datum = STR_TO_DATE('".$datumX[0]."','%d.%m.%Y')";
	}
	// Betrag auseinandernehmen
	$betragX=explode("-",$_SESSION['betrag']);
	$betragX[0]=str_replace(",",".",$betragX[0]);
	$betragX[1]=str_replace(",",".",$betragX[1]);
	if ($betragX[0]!="%") {
		$AddClause=" AND betrag >= ".$betragX[0];
	}
	if ($betragX[1]!="") {
		$AddClause=$AddClause." AND betrag <= ".$betragX[1];
	} else {
		// wenn nur ein Wert, dann nach dem genauen Datum suchen!
		$AddClause=" AND betrag = ".$betragX[0];
	}
	if ($betragX[0]=="%") {
		$AddClause="";
	}	
	$whereClause=$whereClause.$AddClause;
	// rest eintragen
	if ($_SESSION['verwendung']!="%") {$whereClause=$whereClause." AND meta.verwendung LIKE ".$_SESSION['verwendung'];}
	if ($_SESSION['konto']!="%") {$whereClause=$whereClause." AND konto LIKE ".$_SESSION['konto'];}
	if ($_SESSION['id']<>"%") {$whereClause=$whereClause." AND meta.id = ".$_SESSION['id'];}

?>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Navigationsleiste anzeigen -->
	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name"><h1><a href="main_suche.php?reset=true">Finanzen</a></h1></li>
			<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
		</ul>
		<section class="top-bar-section">
			<ul class="left">
			    <li class="divider"></li>
			    <li class="has-dropdown"><a href="#"><i class="fi-graph-bar "></i> Auswertungen</a>
			            <ul class="dropdown">
							<li><a href="sub_auswertungStruktur.php?spaltenTypX=auswahlStruktur&spalte=konto&tabelle=buchung_kategorie&tabellenBeschreibung=Konto">Konto</a></li>
			            </ul>
			    </li>
			    <li class="divider"></li>
			    <li class="has-dropdown"><a href="#"><i class="fi-list "></i> Listen verwalten</a>
			            <ul class="dropdown">
							<li><a href="sub_verwalte_auswahlStruktur.php?editStatus=0&tabelle=verwendung&tabellenBeschreibung=Verwendung">Verwendung</a></li>
							<li><a href="sub_verwalte_auswahlStruktur.php?editStatus=0&tabelle=buchung_kategorie&tabellenBeschreibung=Konto">Konto</a></li>
			            </ul>
			    </li>
			    <li class="divider"></li>
				<li><a href="sub_einstellungen.php"><i class="fi-wrench"></i> Einstellungen</a></li>
			    <li class="divider"></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="newFileModal"><i class="fi-page-add"></i> neuer Eintrag</a></li>
				<li class="active"><a href="main_suche.php" data-reveal-id="searchFileModal"><i class="fi-page-search"></i> Eintrag suchen</a></li>
			</ul>
		</section>
	</nav>

	<?php
		echo "<div class=\"row\">";
			if ($showEingabemaske==1) {
				if ($showSuchmaske==1) {
					echo "<div class=\"small-12 large-6 columns\">";
				} else {
					echo "<div class=\"small-12 large-12 columns\">";
				}
					echo "<div class=\"row\">";
						echo "<fieldset>";
						echo "<legend>neuer Eintrag</legend>";
							include("sub_addbuchung.php");
						echo "</fieldset>";
					echo "</div>";
				echo "</div>";
			}
			if ($showSuchmaske==1) {
				if ($showEingabemaske==1) {
					echo "<div class=\"small-12 large-6 columns\">";
				} else {
					echo "<div class=\"small-12 large-12 columns\">";
				}
					echo "<div class=\"row\">";
						echo "<fieldset>";
						echo "<legend>Eintrag suchen</legend>";
							include("sub_suchbuchung.php");
						echo "</fieldset>";
					echo "</div>";
				echo "</div>";
			}
		echo "</div>";
	?>
	<div class="row">
		<fieldset>
			<legend>Tabelle</legend>
			<?php
				$abfrage="SELECT * FROM buchungen INNER JOIN metadaten as meta ON (idBuchung = meta.id) inner join buchung_kategorie as buchung on (buchung.buchung_kategorieID = konto) inner join verwendung as verw on (verw.verwendungID = meta.verwendung) ".$whereClause." ORDER BY ".$sort." ".$sortBy;
				$ergebnis = mysqli_query($abfrage);
				$menge = mysqli_num_rows($ergebnis);

				$abfrage=$abfrage." LIMIT ".$_SESSION['startPage'].",".$maxEintraegeProSite;
				$ergebnis = mysqli_query($abfrage);
//				echo $abfrage;

				$abfrage2="SELECT sum(betrag) as summe FROM buchungen INNER JOIN metadaten as meta ON (idBuchung = meta.id) inner join buchung_kategorie as buchung on (buchung.buchung_kategorieID = konto) inner join verwendung as verw on (verw.verwendungID = meta.verwendung) ".$whereClause." ORDER BY ".$sort." ".$sortBy;
//				echo "<br>",$abfrage2;
				$ergebnis2=mysqli_query($abfrage2);
				$row2 = mysqli_fetch_assoc($ergebnis2);
				$summe = $row2['summe'];

				echo "<div class=\"row\">";
				echo "<h2>Summe: ",str_replace(".",",",$summe)," €<h2>";
				echo "</div>";
				echo "<div class=\"row\">";
					echo "<div class=\"small-12 large-1 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=datum&sortBy=".$sortBy."\">Datum</a>";
					echo "</div>";
					echo "<div class=\"small-12 large-3 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=buchung_kategorie&sortBy=".$sortBy."\">Konto</a>";
					echo "</div>";
					echo "<div class=\"small-12 large-3 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=meta.verwendung&sortBy=".$sortBy."\">Verwendung</a>";
					echo "</div>";
					echo "<div class=\"small-12 large-1 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=betrag&sortBy=".$sortBy."\">Betrag</a>";
					echo "</div>";
					echo "<div class=\"small-12 large-4 columns\">";
						echo "<a class=\"button expand\" href=\"main_suche.php?sort=beschreibung&sortBy=".$sortBy."\">Beschreibung</a>";
					echo "</div>";
				echo "</div>";
				echo "<hr>";
				while($row = mysqli_fetch_object($ergebnis)) {
					echo "<hr>";
					echo "<div class=\"row\">";
						echo "<div class=\"small-12 large-1 columns\">";
							echo date("d.m.y",strtotime($row->datum));
						echo "</div>";
						echo "<div class=\"small-12 large-3 columns\">";
							echo "<a href=\"main_suche.php?reset=true&konto=".$row->buchung_kategorieID."\">".$row->buchung_kategorie."</a>";
						echo "</div>";
						echo "<div class=\"small-12 large-3 columns\">";
							echo "<a href=\"main_suche.php?reset=true&verwendung=".$row->verwendungID."\">".$row->verwendung."</a>";
						echo "</div>";
						echo "<div class=\"small-12 large-1 columns\">";
							echo "<a class=\"has-tip\" title=\"Zeige diese Buchung und die Gegenbuchung an\" href=\"main_suche.php?reset=true&id=".$row->idBuchung."\">".str_replace(".",",",$row->betrag)."</a>";
						echo "</div>";
						echo "<div class=\"small-12 large-4 columns\">";
							echo "<a class=\"has-tip\" title=\"Editiere die Buchung\" href=\"sub_editbuchung.php?reset=true&id=".$row->idBuchung."\">".$row->beschreibung."</a>";
						echo "</div>";
					echo "</div>";
				}
			?>

			<div class="row">
				<div class="pagination-centered">
					<ul class="pagination">
						<?php
							echo "Menge: ",$menge,"<br>";
							echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$_SESSION['startPage']-$maxEintraegeProSite,"\">&laquo;</a></li>";
							for ($i=0; $i < $menge; $i=$i+$maxEintraegeProSite) { 								
								if($i>=$_SESSION['startPage'] and $i <$_SESSION['startPage']+$maxEintraegeProSite){
									echo "<li class=\"current\"><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
								}
								else{
									echo "<li><a href=\"main_suche.php?startPage=",$i,"\">",$i,"</a></li>";
								}
							}
							echo "<li class=\"arrow\"><a href=\"main_suche.php?startPage=",$_SESSION['startPage']+$maxEintraegeProSite,"\">&raquo;</a></li>";							
						?>
					</ul>
				</div>
			</div>
		</fieldset>
	<div>

	<!-- Formular neue Datei -->
	<div id="newFileModal" class="reveal-modal" data-reveal>
		<fieldset>
		<legend>neues Dokument</legend>
			<?php
				include("sub_addbuchung.php");
			?>
		</fieldset>
	</div>

	<!-- suchen -->
	<div id="searchFileModal" class="reveal-modal" data-reveal>
		<fieldset>
		<legend>neues Dokument</legend>
			<?php
				include("sub_suchbuchung.php");
			?>
		</fieldset>
	</div>

	<?php
		mysqli_close($verbindung);
	?>


	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation/foundation.js"></script>
	<script src="js/foundation/foundation.topbar.js"></script>
	<script src="js/foundation/foundation.dropdown.js"></script>
	<script src="js/foundation/foundation.reveal.js"></script>
	<script>$(document).foundation();</script>  

</body>
