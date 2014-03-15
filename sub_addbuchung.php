<form action="main_suche.php" method="POST" class="custom">
		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Datum</label>
		</div>
		<div class="small-12 large-9 columns">
			<?php
				echo "<input type=\"text\" name=\"datum\" value=\"".$datum."\" placeholder=\"Datum der Buchung\">";
			?>
		</div>

		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Konto Von</label>
		</div>
		<div class="small-12 large-9 columns">
			<select name="kontoVon">
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
				<?php
					generateListOrdnerFormular(0,$verwendung,"verwendung",0);
				?>
			</select>
		</div>

		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Beschreibung</label>
		</div>
		<div class="small-12 large-9 columns">
			<input type="text" name="beschreibung" placeholder="Beschreibung" value="Einkaufen">
		</div>

		<div class="small-12 large-3 columns">
			<label for="right-label" class="right">Betrag</label>
		</div>
		<div class="small-12 large-9 columns">
			<input type="text" name="betrag" placeholder="Betrag">
		</div>

		<div class="small-12 large-12 columns">
			<button class="button expand" type="Submit">Daten eintragen</button>
		</div>
		<input type="hidden" name="uebergabe" value="neuerEintrag">
</form>
