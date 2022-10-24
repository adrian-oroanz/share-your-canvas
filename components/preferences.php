<?php
$page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
?>

<div class="position-fixed bottom-0 end-0 m-2 d-flex gap-2">
	<?php
		if ($page == "home.php" || $page == "explore.php") {
			echo "
			<button class='btn btn-secondary' onclick='align(\"left\")'>
				<i class='bi bi-text-left'></i>
			</button>
			<button class='btn btn-secondary' onclick='align(\"center\")'>
				<i class='bi bi-text-center'></i>
			</button>
			<button class='btn btn-secondary' onclick='align(\"right\")'>
				<i class='bi bi-text-right'></i>
			</button>
			";
		}
	?>
	<label for="color-palette">
		<select name="palette" id="color-palette" class="form-select">
			<option value="none">Predeterminado</option>
			<option value="dark">Noche</option>
			<option value="warm">Cálido</option>
			<option value="cold">Frío</option>
			<option value="nature">Natural</option>
		</select>
	</label>
</div>

<script>
	$(document).ready(function () {
		$("#color-palette").on("change", function () {
			$("body").removeClass("bg-dark text-white");

			switch (this.value) {
			case "dark": $("body").addClass("bg-dark text-white"); break;
			case "warm": $("body").css("background-color", "#fffaef"); break;
			case "cold": $("body").css("background-color", "#efefff"); break;
			case "nature": $("body").css("background-color", "#efffef"); break;
			default: $("body").css("background-color", "#fff");
			}

			localStorage.setItem("palette", this.value);
		});

		if (localStorage.getItem("palette") !== null)
			$("#color-palette").val(localStorage.getItem("palette")).trigger("change");
		else if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
			$("#color-palette").val("dark").trigger("change");
			localStorage.setItem("palette", "dark");
		}
	});

	function align (to) {
		switch (to) {
		case "left":
			$("#l").removeClass("col-sm-3 col-sm-1 col-sm-6");
			break;
		case "center": 
			$("#l").removeClass("col-sm-3 col-sm-1 col-sm-6").addClass("col-sm-3");
			break;
		case "right":
			$("#l").removeClass("col-sm-3 col-sm-1 col-sm-6").addClass("col-sm-6");
		}
	}
</script>
