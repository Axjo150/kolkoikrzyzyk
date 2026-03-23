<?php
session_start();

require "functions.php";

$sessionID = session_id();
$gra = find_or_create_game($sessionID);

if (empty($gra["ses_gracz_X"]) || empty($gra["ses_gracz_O"])) {
    $gra["status_gry"] = "oczekiwanie";
} else {
    // 2?? Gra w toku, ustaw status na rozgrywka jeœli nie jest wygrana/remis
    if ($gra["status_gry"] != "wygrana" && $gra["status_gry"] != "remis") {
        $gra["status_gry"] = "rozgrywka";
    }
}
$jsonOutput = json_encode($gra);
echo json_encode($gra);