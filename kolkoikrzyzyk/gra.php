<?php
session_start();
require "functions.php";

$sessionID = session_id();
$symbol = "";
date_default_timezone_set('Europe/Warsaw'); 
$czas = date('Y-m-d H:i:s');


$gra = find_or_create_game($sessionID); // nowa funkcja w functions.php
if ($gra["ses_gracz_X"] == $sessionID) {
	$symbol = "X";
} else if ($gra["ses_gracz_O"] == $sessionID) {
	$symbol = "O";
}
//echo "|graId=" . $gra["id"] . " jestes graczem " . $symbol . "|";

$czas = date('Y-m-d H:i:s'); 
?>

<!DOCTYPE html>
<html lang = "pl">
<head>
<meta charset="UTF-8">
<title>Kolko i krzyzyk</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h2 id="numer_gry">Numer gry: <?= $gra["id"] ?></h2>
<h2>Jestes graczem: <?= $symbol?> </h2>
<h2 id="kolej">Kolej gracza: <?= $gra["gracz"]?></h2>


<table id="plansza">
    <tr>
        <td id="p0" onclick="klik(0)"><?= $gra["plansza"][0] == "-" ? "" : $gra["plansza"][0]?></td>
        <td id="p1" onclick="klik(1)"><?= $gra["plansza"][1] == "-" ? "" : $gra["plansza"][1]?></td>
        <td id="p2" onclick="klik(2)"><?= $gra["plansza"][2] == "-" ? "" : $gra["plansza"][2]?></td>
    </tr>
    <tr>
        <td id="p3" onclick="klik(3)"><?= $gra["plansza"][3] == "-" ? "" : $gra["plansza"][3]?></td>
        <td id="p4" onclick="klik(4)"><?= $gra["plansza"][4] == "-" ? "" : $gra["plansza"][4]?></td>
        <td id="p5" onclick="klik(5)"><?= $gra["plansza"][5] == "-" ? "" : $gra["plansza"][5]?></td>
    </tr>
    <tr>
        <td id="p6" onclick="klik(6)"><?= $gra["plansza"][6] == "-" ? "" : $gra["plansza"][6]?></td>
        <td id="p7" onclick="klik(7)"><?= $gra["plansza"][7] == "-" ? "" : $gra["plansza"][7]?></td>
        <td id="p8" onclick="klik(8)"><?= $gra["plansza"][8] == "-" ? "" : $gra["plansza"][8]?></td>
    </tr>
</table>

<h3 id="wynik"><?= $gra["status_gry"]?></h3>    
<?php
if (empty($gra["ses_gracz_O"]) || empty($gra["ses_gracz_X"])) {
    echo "<p>Oczekiwanie na drugiego gracza</p>";
} else {
    echo "<p>Gracz dolaczyl</p>";
}
?>
<script>
let mojSymbol = "<?= $symbol ?>";
</script>

<script src="mainjs.js" type="text/javascript">
</script>
</body>
</html>