<?php
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_GET['who']) || strlen($_GET['who']) < 1  ) {
    die('Name parameter missing');
} else {
  $name = $_GET['who'];
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['addnew']) && isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
  $make = $_POST['make'];
  if (strlen($make) > 1) {
    if (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
      $sql = "INSERT INTO autos (make, year, mileage)
                VALUES (:make, :year, :mileage)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':make' => htmlentities($_POST['make']),
          ':year' => $_POST['year'],
          ':mileage' => $_POST['mileage']));
      } else {
        echo("Mileage and year must be numeric.");
      }
    } else {
      echo ("Make is required.");
    }
}

if ( isset($_POST['delete']) && isset($_POST['auto_id']) ) {
    $sql = "DELETE FROM autos WHERE auto_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['auto_id']));
}

$stmt = $pdo->query("SELECT make, year, mileage, auto_id FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
<title>Jared Best | Automobile Tracker</title>
</head><body>
  <?php echo("<h1>Tracking Autos for $name</h1>\n"); ?>
<p>Add A New Auto</p>
<form method="post">
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p><input type="submit" value="Add New" name="addnew" /></p>
</form>
<table border="1">
<?php
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo($row['make']);
    echo("</td><td>");
    echo($row['year']);
    echo("</td><td>");
    echo($row['mileage']);
    echo("</td><td>");
    echo('<form method="post"><input type="hidden" ');
    echo('name="auto_id" value="'.$row['auto_id'].'">'."\n");
    echo('<input type="submit" value="Del" name="delete">');
    echo("\n</form>\n");
    echo("</td></tr>\n");
}
?>
</table>
<form method="post">
<input type="submit" name="logout" value="Logout">
</form>
</body>
