<?php


require_once ("includes/database.php");


//Save the reservering to the database
$stmt = $db->prepare( "INSERT INTO `reserveringssysteem` (`naam`,`telefoonnummer`, `mail`, `datum`, `tijd`, `personen`, `opmerkingen`)
                  VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sisssis", $name, $telnr, $mail, $datum, $time, $personen, $opmerkingen);

//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {
    //Postback with the data showed to the user, first retrieve data from 'Super global'
    $name = mysqli_escape_string($db, $_POST['naam']);
    $telnr = mysqli_escape_string($db, $_POST['telefoonnummer']);
    $mail = mysqli_escape_string($db, $_POST['mail']);
    $datum = mysqli_escape_string($db, $_POST['datum']);
    $time = mysqli_escape_string($db, $_POST['tijd']);
    $personen = mysqli_escape_string($db, $_POST['personen']);
    $opmerkingen = mysqli_escape_string($db, $_POST['opmerkingen']);

    function getErrorsForFields($name, $telnr, $mail, $datum, $time, $personen, $opmerkingen) {
//Check if data is valid & generate error if not so
        $errors = [];
        if ($name == "") {
            $errors[] = 'Uw Naam cannot be empty';
        }
        if ($telnr == "") {
            $errors[] = 'Uw Telefoonnummer cannot be empty';
        }
        if ($mail == "") {
            $errors[] = ' Uw E-mail cannot be empty';
        }
        if ($datum == "") {
            $errors[] = 'dd-mm-jjjj cannot be empty';
        }
        if ($time == "") {
            $errors[] = 'Tijd cannot be empty';
        }
        if (!is_numeric($personen) || strlen($personen) != 1 || strlen($personen) != 2) {
            $errors[] = ' Aantal Personen needs to be a number with the length of 2';
        }
        if ($opmerkingen == "") {
            $errors[] = 'Opmerkingen cannot be empty';
        }
        return $errors;
    }
    $errors = getErrorsForFields($name, $telnr, $mail, $datum, $time, $personen, $opmerkingen);

    $hasErrors = !empty($errors);

    if (!$hasErrors) {
        insertIntoDatabase($name, $telnr, $mail, $datum, $time, $personen, $opmerkingen);
    }

    $stmt->execute();


    mysqli_close($db);
}
?>

<!doctype html>
<html>
<head>
    <title> Snackbar 't Centrum </title>
    <link rel="stylesheet" type="text/css" href="css/reservering.css"/>
</head>
<header>
    <nav>
        <a href="index.php"><img src="css/logo.png" width="300" height="75" class="logo"></a>
        <ul>
            <li><a href="index.php">Home Page</a></li>
            <li><a href="menu.php">Menukaart</a></li>
            <li><a class="active" href="reservering.php">Reserveren</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>
<body>
<div class="center">
    <h1> Online Tafel Reserveren</h1>
    <div id="error"></div>
    <form id="form" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post"
          enctype="multipart/form-data">
        <div class="data-field">
            <label for="Uw Naam"></label>
            <input id="naam" type="text" placeholder="Uw Naam" name="naam" value="<?= (isset($name) ? $name : ''); ?>"required/>
            <span><?= (isset($errors['Uw Naam']) ? $errors['Uw Naam'] : '') ?></span>
            <label for="Uw Telefoonnummer"></label>
            <input id="telefoonnummer" type="text" placeholder="Uw Telefoonnummer" name="telefoonnummer"
                   value="<?= (isset($telnr) ? $telnr : ''); ?>" required/>
            <label for="Uw Email"></label>
            <input id="email" type="email" placeholder="Uw E-mail" name="mail"
                   value="<?= (isset($mail) ? $mail : ''); ?>" required/>
        </div>
        <div class="data-field">
            <label for="dd-mm-jjjj"></label>
            <input id="dd-mm-jjjj" type="date" placeholder="Datum" name="datum"
                   value="<?= (isset($datum) ? $datum : ''); ?>" required/>
            <label for="Tijd"></label>
            <input id="tijd" type="time" placeholder="Tijd" name="tijd" value="<?= (isset($time) ? $time : ''); ?>"/>
            <label for="Aantal Personen"></label>
            <input id="personen" type="number" placeholder="Aantal Personen" name="personen"
                   value="<?= (isset($personen) ? $personen : ''); ?>" required/>
        </div>
        <div class="data-field">
            <label for="Opmerkingen"></label>
            <input id="opmerkingen" type="text" placeholder="Opmerkingen" name="opmerkingen"
                   value="<?= (isset($opmerkingen) ? $opmerkingen : ''); ?>"/>
        </div>
        <div class="data-submit">
            <a
                    href="bedankt.php">
            <input type="submit" class="btn" name="submit" value="Reseveren"/>
        </div>
    </form>
</div>

</body>
</html>
