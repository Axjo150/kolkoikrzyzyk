<?php
session_start();

require "functions.php";
$sessionID = session_id();
$symbol = "";
$gra = get_active_game($sessionID);
if ($sessionID == $gra["ses_gracz_X"]) {
    $symbol = "X";
}elseif ($sessionID == $gra["ses_gracz_O"]) {
    $symbol = "O";
}
// na wejsciu numer_pola w ktorym gracz chce postawic symbol
// sprawdz czy do gry dolaczyli wszyscy gracze  jesli nie to return false
// 2. sprawdz czy jest moj ruch (jesli nie to return false)
// 3. sprawdz czy pole jest wolne   jesli nie to return false
// 4.sprawdz czy gra jest aktywna   jesli nie to return false
// ustaw symbol gracza w polu 
// sprawdz stan gry czy wygrana, przegrana czy remis czy nadal sie toczy
// zapisz stan gry do bazy

if (!isset($_GET["pole"])) {
     echo "false";
    return false;
}
$numer_pola = $_GET["pole"];
echo wykonaj_ruch($symbol, $numer_pola, $gra);



?>