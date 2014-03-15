<form action="main_suche.php" method="GET" class="custom">
		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Datum</label>
		</div>
		<div class="small-12 large-9 columns">
			<?php
				echo "<input type=\"text\" name=\"datum\" value=\"".$_SESSION['datum']."\" placeholder=\"Datum der Buchung [".date("m.d.Y")."]\">";
			?>
		</div>

		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Konto</label>
		</div>
		<div class="small-12 large-9 columns">
			<select name="konto">
				<option selected value="%">%</option>
				<?php
					generateListOrdnerFormular(0,$_SESSION['konto'],"buchung_kategorie",0);
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
					generateListOrdnerFormular(0,$_SESSION['verwendung'],"verwendung",0);
				?>
			</select>
		</div>

		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Beschreibung</label>
		</div>
		<div class="small-12 large-9 columns">
			<?php
				echo "<input type=\"text\" name=\"beschreibung\" value=\"".$_SESSION['beschreibung']."\" placeholder=\"Beschreibung\">";
			?>
		</div>

		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Betrag</label>
		</div>
		<div class="small-12 large-9 columns">
			<?php
				echo "<input type=\"text\" name=\"betrag\" value=\"".$_SESSION['betrag']."\" placeholder=\"Betrag\">";
			?>
		</div>

		<div class="small-12 large-12 columns">
			<button class="button expand" type="Submit">Dokument suchen</button>
		</div>
		<input type="hidden" name="uebergabe" value="suchEintrag">
</form>
