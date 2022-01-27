<?php
include "real-creds.php";
// password authentication
if ((isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER'] == $username)) and
      (isset($_SERVER['PHP_AUTH_PW']) && ($_SERVER['PHP_AUTH_PW'] == $password))) {
    $db = new SQLite3('data/recipes.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

    // Create a table.
    $db->exec('CREATE TABLE IF NOT EXISTS "recipes" (
		    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		    "data" TEXT NOT NULL
		)');

    if (!isset($_POST)) {
        die("No edits submitted to process!");
    } else {
        // correctly order inspirations
        $inspirations = [];
        foreach ($_POST['inspirations'] as $k=>$v) {
            $val = intdiv($k, 2);
            $inspirations[$val][key($v)]=$v[key($v)];
        }
        $_POST['inspirations']=$inspirations;

        // remove empty ingredients, tags
        $_POST["ingredients"] = array_filter($_POST["ingredients"]);
        $_POST["tags"] = array_filter($_POST["tags"]);

        if (isset($_POST["recipe-id"])) {
            $recipe_id = $_POST["recipe-id"];
            unset($_POST["recipe-id"]);
            if (isset($_POST["delete-recipe"])) {
                $stmt = $db->prepare('DELETE FROM recipes WHERE id=:id');
                $stmt->bindValue(':id', $recipe_id);
                $result = $stmt->execute();
                header("Location: https://".$_SERVER['HTTP_HOST']);
            } else {
                $stmt = $db->prepare('UPDATE recipes SET data=:data WHERE id=:id');
                $stmt->bindValue(':id', $recipe_id);
                $stmt->bindValue(':data', json_encode($_POST));
                $result = $stmt->execute();
                header("Location: https://".$_SERVER['HTTP_HOST']."/view-recipe.php?id=". strval($recipe_id));
            }
        } else {
            // Insert the POSTed form data as JSON
            $stmt = $db->prepare('INSERT INTO recipes (data) VALUES (:data)');
            $stmt->bindValue(':data', json_encode($_POST));
            $result = $stmt->execute();
            header("Location: https://".$_SERVER['HTTP_HOST']);
        }
    }
    // close the database
    $db->close();

    exit();
} else {
    //Send headers to cause a browser to request
    //username and password from user
    header("WWW-Authenticate: " .
            "Basic realm=\"Login to submit changes\"");
    header("HTTP/1.0 401 Unauthorized");

    //Show failure text, which browsers usually
    //show only after several failed attempts
    print("This page is protected by HTTP " .
            "Authentication. Contact Jacob for the username and password.");
}
