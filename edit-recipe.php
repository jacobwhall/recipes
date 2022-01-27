<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Editing Recipe</title>
		<link rel="stylesheet" href="pico.classless.min.css">
		<script type='text/javascript'>
		function addIngredient(){
			ingrContainer = document.getElementById("ingr-container");
			fieldHTML = "<td><input type=\"text\" name=\"ingredients[]\"></td><td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td>";
			var newField = document.createElement("tr");
			newField.innerHTML = fieldHTML;
			ingrContainer.appendChild(newField);
		}
		function addInspiration(){
			inspContainer = document.getElementById("insp-container");
			fieldHTML = "<td><input type=\"text\" name=\"inspirations[][name]\"></td><td><input type=\"text\" name=\"inspirations[][link]\"></td><td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td>";
			var newField = document.createElement("tr");
			newField.innerHTML = fieldHTML;
			inspContainer.appendChild(newField);
		}
		function addTag(){
			tagContainer = document.getElementById("tag-container");
			var newField = document.createElement("tr");
			newField.innerHTML = "<td><input type=\"text\" name=\"tags[]\"></td><td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td>";
			tagContainer.appendChild(newField);
		}
		 function selectText(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(containerid);
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(containerid);
            window.getSelection().addRange(range);
        }
    }
	    </script>
		<?php if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $e = true;
    $db = new SQLite3('data/recipes.db', SQLITE3_OPEN_READONLY);
    $stmt = $db->prepare('SELECT id, data FROM recipes WHERE id=:id');
    $stmt->bindValue(':id', $_GET["id"], SQLITE3_INTEGER);

    $results = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    $details = json_decode($results["data"], true);
    $recipe_id = $results["id"];
}
        ?>
	</head>
	<body>
		<main>
			<h1>Editing a Recipe!</h1>
			<p>Please note that you will need a valid username and password to submit these changes!</p>
			<p>Character that look like <kbd>this</kbd> are click-to-select for easy copying.</p>
			<form action="update-recipe.php" method="POST" name="recipe">
				<?php if ($e) {
            echo "<input type=\"hidden\" name=\"recipe-id\" value=\"" . $recipe_id . "\">\n";
        } ?>
				<label for="title">Title:</label>
				<input type="text" name="title" id="title" <?php if ($e) {
            echo "value=\"".$details["title"]."\" ";
        } ?>required>
				<label for="desc">Description:</label>
				<textarea id="desc" name="description"><?php if ($e) {
            echo $details["description"];
        } ?></textarea>
				<label for="prep">Prep time (minutes):</label>
				<input type="number" id="prep" name="prep_time" min="0" max="999" <?php if ($e) {
            echo "value=\"".$details["prep_time"]."\"";
        } ?>>
				<label for="cook">Cook time (minutes):</label>
				<input type="number" id="cook" name="cook_time" min="0" max="999" <?php if ($e) {
            echo "value=\"".$details["cook_time"]."\"";
        } ?>>
				<label for="serves"># of servings:</label>
				<input type="number" id="serves" name="servings" min="0" max="999" <?php if ($e) {
            echo "value=\"".$details["servings"]."\"";
        } ?>>
				<h2>Ingredients</h2>
				<p>
					<kbd onclick="selectText(this);">¼</kbd>&numsp;
					<kbd onclick="selectText(this);">½</kbd>&numsp;
					<kbd onclick="selectText(this);">¾</kbd>&numsp;
				</p>
				<table id="ingr-container">
				<?php if ($e) {
            foreach ($details["ingredients"] as $num => $ingredient) {
                if (isset($ingredient["name"]) || isset($ingredient["quantity"])) {
                    echo "<tr><td><input type=\"text\" name=\"ingredients[" . $num . "]\" value=\"" . $ingredient["quantity"] . " ". $ingredient["name"] ."\"></td><td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td></tr>";
                } else {
                    echo "<tr><td><input type=\"text\" name=\"ingredients[]\" value=\"" . $ingredient ."\"></td><td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td></tr>";
                }
            }
        }
                ?>
				</table>
				<button type="button" onclick="addIngredient()">Add Ingredient</button>
				<h2>Instructions</h2>
				<p>
					<kbd onclick="selectText(this);">°</kbd>&numsp;
				</p>
				<p>You can use markdown here if you like, e.g. *italics* or **bold**</p>
				<textarea id="intr" name="instructions"><?php if ($e) {
                    echo $details["instructions"];
                } ?></textarea>
				<h2>Tips & Thoughts</h2>
				<textarea id="tips" name="remarks"><?php if ($e) {
                    echo $details["remarks"];
                } ?></textarea>
				<h2>Inspirations</h2>
				<table id="insp-container">
				<tr>
					<th>Name</th>
					<th>URL</th>
				<tr>
				<?php if ($e) {
                    foreach ($details["inspirations"] as $insp_num => $inspiration) {
                        echo "<tr><td><input type=\"text\" name=\"inspirations[][name]\" value=\"" . $inspiration["name"] . "\"></td><td><input type=\"text\" name=\"inspirations[][link]\" value=\"" . $inspiration["link"] . "\"></td><td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td></tr>";
                    }
                }
                ?>
				</table>
				<button type="button" onclick="addInspiration()">Add Inspiration</button>
				<h2>Tags</h2>
				<table id="tag-container">
				<?php if ($e) {
                    foreach ($details["tags"] as $tag) {
                        echo "<tr><td><input type=\"text\" name=\"tags[]\" value=\"" . $tag . "\"></td>\n<td><a onclick=\"this.parentElement.parentElement.remove()\">delete</a></td>\n</tr>";
                    }
                }
                ?>
				</table>
				<button type="button" onclick="addTag()">Add Tag</button>
				<button>Submit Edits</button>
			</form>
		</main><?php if ($e) {
                    echo "<footer><form action=\"/update-recipe.php\" method=\"POST\">\n<input type=\"hidden\" name=\"delete-recipe\">\n<input type=\"hidden\" name=\"recipe-id\" value=\"" . $recipe_id . "\">\n<button class=\"warning\">Delete Recipe</button></form></footer>";
                } ?>
	</body>
</html>
