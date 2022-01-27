<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Viewing Recipe</title>
		<link rel="stylesheet" href="pico.classless.min.css">
	</head>
	<body>
		<main>
			<?php
        include "Parsedown.php";
            if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
                $db = new SQLite3('data/recipes.db', SQLITE3_OPEN_READONLY);
                $stmt = $db->prepare('SELECT id, data FROM recipes WHERE id=:id');
                $stmt->bindValue(':id', $_GET["id"], SQLITE3_INTEGER);

                $results = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
                $details = json_decode($results["data"], true);
                $recipe_id = $results["id"];
            } else {
                exit("No recipe ID passed!");
            }
            ?>
			<h1><?php echo $details["title"]; ?></h1>
			<p><?php echo $details["description"]; ?></p>
			<?php
            if (isset($details["prep_time"]) && is_numeric($details["prep_time"])) {
                echo "<p>Prep time: " . $details["prep_time"] . " min</p>";
            }
            if (isset($details["cook_time"]) && is_numeric($details["cook_time"])) {
                echo "<p>Cook time: " . $details["cook_time"] . " min</p>";
            }
            if (isset($details["servings"]) && is_numeric($details["servings"])) {
                echo "<p>Serves " . $details["servings"] . "</p>";
            }
            ?>	
			<h2>Ingredients</h2>
			<ul>
				<?php
                foreach ($details["ingredients"] as $ingredient) {
                    if (isset($ingredient["quantity"]) || isset($ingredient["name"])) {
                        echo "<li>". $ingredient["quantity"] ." ". $ingredient["name"]."</li>\n";
                        echo "<li> this recipe should be edited! </li>";
                    } else {
                        echo "<li>". $ingredient ."</li>\n";
                    }
                }
                ?>
			</ul>
			<h2>Instructions</h2>
			<?php $Parsedown = new Parsedown();

        echo $Parsedown->text($details["instructions"]);
         ?>
			<?php
            if (isset($details["remarks"]) && $details["remarks"] != "") {
                echo "<h2>Tips & Thoughts</h2>";
                echo $Parsedown->text($details["remarks"]);
            } ?>
			<h2>Inspirations</h2>
			<ul>
			<?php
            foreach ($details["inspirations"] as $inspiration) {
                if (isset($inspiration["link"]) && $inspiration["link"] != "") {
                    if (isset($inspiration["name"]) && $inspiration["name"] != "") {
                        echo "<li><a href=\"". $inspiration["link"] ."\">". $inspiration["name"]."</a></li>\n";
                    } else {
                        echo "<li><a href=\"". $inspiration["link"] ."\">". $inspiration["link"]."</a></li>\n";
                    }
                } else {
                    echo "<li>" . $inspiration["name"] . "</li>";
                }
            }
?>
			</ul>
			<?php
            if (isset($details["tags"])) {
                echo "<h2>Tags</h2>\n<ul>\n";
                foreach ($details["tags"] as $tag) {
                    echo "<li>".$tag."</li>\n";
                }
                echo "</ul>";
            }
            ?>
		</main>
		<footer>
		<form action="/">
			<button>Back home</button>
		</form>
		<form action="/edit-recipe.php" method="GET">
		<input type="hidden" name="id" value="<?php echo $recipe_id; ?>">
			<button>Edit this recipe</button>
		</form>
	</body>
</html>
