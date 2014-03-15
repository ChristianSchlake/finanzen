<?php
//	session_start();
	include("sub_init_database.php");
	include("functions.php");
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
	$updateStatus=0;
	$editStatus=0;
	$deleteStatus=0;
	$suchWert="%";
	$editWert="";
	$editNeuerWert="";
	$tabellenBeschreibung="";
//	print_r($_GET);
	foreach ($_GET as $key => $value) {
		if ($key=="tabelle") {
			$tabelle=$value;
			getSpaltenDMS();
			foreach ($spaltenName as $i => $value) {
				if ($spaltenName[$i]==$tabelle) {
					$tabellenBeschreibung=$spaltenBeschreibung[$i];
				}
			}
		}
		if ($key=="tabellenBeschreibung") {$tabellenBeschreibung=$value;}
		if ($key=="editWert") {
			$editWert=$value;
		}
		if ($key=="editWertID") {
			$editWertID=$value;
		}
		if ($key=="suchWert") {
			$suchWert=$value;
		}		
		if ($key=="editStatus" AND $value=="1"){
			$editStatus=1;
		}
		if ($key=="updateStatus" AND $value=="1"){
			$updateStatus=1;
		}
		if ($key=="deleteStatus" AND $value=="1"){
			$deleteStatus=1;
		}		
		if ($key=="id"){
			$updateID=$value;
		}
		if ($key=="editNeuerWert"){
			$editNeuerWert=$value;
			$suchWert=$value;
		}
		if ($key=="idFatherOrig"){ //Original ID des Vaters
			$idFatherOrig=$value;
		}

		
		
	}
	if ($updateStatus==1) {
		$abfrage="UPDATE ".$tabelle." SET ".$tabelle." =\"".$editWert."\" WHERE ".$tabelle."ID=\"".$updateID."\"";
		mysql_query($abfrage);
//		echo $abfrage,"<br>";
		$abfrage="UPDATE ".$tabelle."Structure SET father=\"".$editWertID."\" WHERE son=\"".$updateID."\"";
		mysql_query($abfrage);
//		echo $abfrage;
	}

	if ($deleteStatus==1) {
		$abfrage="DELETE FROM ".$tabelle." WHERE ".$tabelle."ID=\"".$updateID."\"";
//		echo $abfrage,"<br>";
		mysql_query($abfrage);
		$abfrage="DELETE FROM ".$tabelle."Structure WHERE son=\"".$updateID."\" and father=\"".$idFatherOrig."\"";
//		echo $abfrage;
		mysql_query($abfrage);
	}
	if ($editNeuerWert!="") {
		$sql="select MAX(".$tabelle."ID) from ".$tabelle;
		$result=mysql_query($sql);
		$maxID=mysql_result($result,0,0);
		$maxID=$maxID+1;

		$sql="insert into my_table(id, field1) values('$max','$this')";


		$abfrage="INSERT INTO ".$tabelle." (".$tabelle.",".$tabelle."ID) VALUES (\"".$editNeuerWert."\",\"".$maxID."\")";
//		echo $abfrage,"<br>";
		mysql_query($abfrage);
		$abfrage="INSERT INTO ".$tabelle."Structure (father, son) VALUES (\"".$editNeuerWert."\", \"".$maxID."\")";
//		echo $abfrage;
		mysql_query($abfrage);
	}
	
	
?>
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
		    <!--li class="divider"></li>
			<li class="active"><a href="sub_verwalte_auswahlStruktur.php" data-reveal-id="eingabeModal2"><i class="fi-page-add"></i> neuer Eintrag</a></li-->
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
		<form action="sub_verwalte_auswahlStruktur.php" method="get">
			<?php
				echo "<input type=\"hidden\" name=\"editStatus\" value=\"".$editStatus."\">";
				echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";
			?>
			<div class="small-8 columns">
				<input type="text" placeholder="Suche" name="suchWert">
			</div>
			<div class="small-4 columns">
				<input class="button prefix secondary" value="suchen" type="Submit">
			</div>
		</form>		
	</fieldset>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Suchformular -->
<div class="row collapse">
	<fieldset>
		<?php
			echo "<legend>Eingabeformular - ".$tabellenBeschreibung."</legend>"
		?>
		<form action="sub_verwalte_auswahlStruktur.php" method="GET" class="custom">
			<div class="row collapse">
				<div class="small-12 large-12 columns">
					<?php
						echo "<input type=\"hidden\" name=\"editStatus\" value=\"0\">";
						echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";					
						echo "<input type=\"text\" placeholder=\"Beschreibung\" name=\"editNeuerWert\">";
					?>
				</div>
			</div>
			<div class="row collapse">
				<div class="small-12 large-12 columns">
					<button class="button expand" type="Submit">eintragen</button>
				</div>
			</div>
		</form>
	</fieldset>
</div>

<!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<!-- Tabelle anzeigen -->

<div class="row">
	<fieldset>
		<?php
			echo "<legend>".$tabellenBeschreibung."</legend>";
		?>
		<div class="small-12 large-12 columns"\>
			<dl class="sub-nav">
				<?php
					if($editStatus==0){
		  				echo "<dd class=\"active\"><a href=\"sub_verwalte_auswahlStruktur.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=0&tabelle=".$tabelle."\">Show</a></dd>";
	  					echo "<dd><a href=\"sub_verwalte_auswahlStruktur.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=1&tabelle=".$tabelle."\">Edit</a></dd>";
					}
					else {
		  				echo "<dd><a href=\"sub_verwalte_auswahlStruktur.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=0&tabelle=".$tabelle."\">Show</a></dd>";
	  					echo "<dd class=\"active\"><a href=\"sub_verwalte_auswahlStruktur.php?tabellenBeschreibung=".$tabellenBeschreibung."&suchWert=".$suchWert."&editStatus=1&tabelle=".$tabelle."\">Edit</a></dd>";
					}
				?>
			</dl>		
		</div>
	</fieldset>
</div>


<div class="row collapse">
	<div class="small-2 large-2 columns">	
		<h2>ID</h2>
	</div>
	<div class="small-2 large-2 columns">	
		<h2>Vater</h2>
	</div>
	<div class="small-8 large-8 columns">	
		<h2>Wert</h2>
	</div>
	<hr>
</div>

<?php
	$auswahlX=array();
//	print_r($auswahlX);
	if($editStatus==0){
		generateListOrdnerAenderung(0,"",$tabelle,0,true);
		foreach ($auswahlX as $i => $value)
		{
			$ordner=explode("|", $value);
			echo "<div class=\"row collapse\">";
				echo "<div class=\"small-2 large-2 columns\">";
					echo "<p>",$ordner[0],"</p>";
				echo "</div>";
				echo "<div class=\"small-2 large-2 columns\">";
					echo "<p>",$ordner[1],"</p>";
				echo "</div>";
				echo "<div class=\"small- large-8 columns\">";
					echo "<p>",$ordner[2],"</p>";
				echo "</div>";
			echo "</div>";
		}
	} else {
		generateListOrdnerAenderung(0,"",$tabelle,0,false);
		foreach ($auswahlX as $i => $value) {
			$ordner=explode("|", $value);
			echo "<div class=\"row collapse\">";
				echo "<form class=\"custom\" action=\"sub_verwalte_auswahlStruktur.php\" method=\"get\">";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$ordner[0]."\"\>";
					echo "<input type=\"hidden\" name=\"idFatherOrig\" value=\"".$ordner[1]."\"\>";
					echo "<input type=\"hidden\" name=\"editStatus\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";
					echo "<div class=\"small-2 large-2 columns\">";
						echo "<p>",$ordner[0],"</p>";
					echo "</div>";
					echo "<div class=\"small-2 large-2 columns\">";
						echo "<input type=\"text\" value=\"",$ordner[1],"\" name=\"editWertID\">";
					echo "</div>";
					echo "<div class=\"small-2 large-2 columns\">";
						echo "<input type=\"text\" value=\"",$ordner[2],"\" name=\"editWert\">";
					echo "</div>";
					echo "<div class=\"small-6 large-6 columns\">";
						echo "<button class=\"fi-page-edit secondary size-X\" name=\"updateStatus\" value=\"1\" type=\"submit\"></button>";
						echo "<button class=\"fi-page-delete secondary size-X\" name=\"deleteStatus\" value=\"1\" type=\"submit\"></button>";
					echo "</div>";
				echo "</form>";
			echo "</div>";
		}
	}
?>

<!--div id="eingabeModal2" class="reveal-modal" data-reveal>
	<fieldset>
		<?php
			echo "<legend>Eingabeformular - ".$tabellenBeschreibung."</legend>"
		?>
		<form action="sub_verwalte_auswahlStruktur.php" method="GET" class="custom">
			<div class="row collapse">
				<div class="small-12 large-12 columns">
					<?php
						echo "<input type=\"hidden\" name=\"editStatus\" value=\"0\">";
						echo "<input type=\"hidden\" name=\"tabelle\" value=\"".$tabelle."\"\>";					
						echo "<input type=\"text\" placeholder=\"Beschreibung\" name=\"editNeuerWert\">";
					?>
				</div>
			</div>
			<div class="row collapse">
				<div class="small-12 large-12 columns">
					<button class="button expand" type="Submit">eintragen</button>
				</div>
			</div>
		</form>
	</fieldset>
</div-->




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
