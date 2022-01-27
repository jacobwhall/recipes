<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>List Recipes</title>
		<link rel="stylesheet" href="pico.classless.min.css">
		<script>
		<?php
        $db = new SQLite3('data/recipes.db', SQLITE3_OPEN_READONLY);
        $results = $db->query("SELECT json_group_array(json_array(id, json_extract(data, '$.title'), json_extract(data, '$.tags'))) FROM recipes;");
        echo "var recipes = " . $results->fetchArray()[0] . ";\n";
        ?>
		var currentTags = [];
		function tabulate() {
			var elem = document.getElementById('recipes');
			var list_html = "";
			for (let recipe of recipes) {
				var printRecipe = 0;
				if (recipe.length > 2 && recipe[2] != null) {
					for (let tag of currentTags) {
						if (recipe[2].indexOf(tag) > -1) {
							printRecipe = 1;
						} else {
							printRecipe = 0;
							break;
						}
					}
				}
				if (printRecipe || currentTags.length == 0) {
					list_html += "<li><a href=\"/view-recipe.php?id="+recipe[0]+"\">"+recipe[1]+"</a></li>\n";
				}
			}
			elem.innerHTML = list_html;
		}
		function toggle_tag(tag_elem) {
			if (tag_elem.children.length == 0) {
				currentTags.push(tag_elem.innerHTML);
				tag_elem.innerHTML = "<mark>"+tag_elem.innerHTML+"</mark>";
			} else {
				tag_elem.innerHTML = tag_elem.firstChild.innerHTML;
				const index = currentTags.indexOf(tag_elem.innerHTML);
				if (index > -1) {
					currentTags.splice(index, 1);
				}
			}
			tabulate();
		}
		function initialize() {
			html_list = [];
			for (let tag of recipes.map(r => r[2]).flat()) {
				var tag_html = "<a onclick=\"toggle_tag(this);\">"+tag+"</a>";
				if (html_list.indexOf(tag_html) == -1 && tag != null) {
					html_list.push(tag_html);
				}
			}
			document.getElementById('tags').innerHTML = html_list.join(", ");
			tabulate();
		}
		</script>
	</head>
	<body onload="initialize();">
		<main>
			<p id="tags"></p>
			<ul id="recipes">
				<li>This page requires javascript. If you are seeing this message, please contact me and we can work something out.</li>
			</ul>
		</main>
		<footer>
		<form action="/list-recipes.php">
			<button>Back home</button>
		</form>
	</body>
</html>
