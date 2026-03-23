<?php
date_default_timezone_set('Europe/Warsaw'); 
function db(){
    return mysqli_connect("localhost","kzajac","Zamkowa23","kolko_krzyzyk");
}	

function get_active_game($sessionID){

    $conn = db();

    $query = "
    SELECT * FROM gry
    WHERE ses_gracz_X = '$sessionID'
    OR ses_gracz_O = '$sessionID'
    LIMIT 1
    ";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) > 0){
        return mysqli_fetch_assoc($result);
    }

    return null;
}

function find_or_create_game($sessionID){

    $conn = db();

    $aktywnaGra = get_active_game($sessionID);

    if (!$aktywnaGra) {

        $query = "
            SELECT * FROM gry
            WHERE ses_gracz_X IS NULL
            OR ses_gracz_O IS NULL
            LIMIT 1
        ";

        $result = mysqli_query($conn,$query);

        if(mysqli_num_rows($result) > 0){
            $aktywnaGra = mysqli_fetch_assoc($result);
        }

        if (!$aktywnaGra) {

            $plansza = "---------";

            $query = "
                INSERT INTO gry (ses_gracz_X, plansza, gracz, status_gry, zwyciezca)
                VALUES ('$sessionID', '$plansza', 'X', 'oczekiwanie', NULL)
            ";

            mysqli_query($conn,$query);

            $graID = mysqli_insert_id($conn);

            //echo "|graId=" . $graID . " jestes graczem X|";

            return get_active_game($sessionID);

        } else {

            $graID = $aktywnaGra["id"];

            if (empty($aktywnaGra["ses_gracz_X"])) {

                $query = "
                    UPDATE gry
                    SET ses_gracz_X='$sessionID'
                    WHERE id=$graID
                ";

                mysqli_query($conn,$query);

                //echo "|graId=" . $graID . " jestes graczem X|";

                return get_active_game($sessionID);

            } elseif (empty($aktywnaGra["ses_gracz_O"])) {

                $query = "
                    UPDATE gry
                    SET ses_gracz_O='$sessionID'
                    WHERE id=$graID
                ";

                mysqli_query($conn,$query);

                //echo "|graId=" . $graID . " jestes graczem O|";

                return get_active_game($sessionID);
            }
        }

    } else {

        $graID = $aktywnaGra["id"];

        if ($aktywnaGra["ses_gracz_X"] == $sessionID) {
            $symbol = "X";
        } elseif ($aktywnaGra["ses_gracz_O"] == $sessionID) {
            $symbol = "O";
        }

        //echo "|graId=" . $graID . " jestes graczem " . $symbol . "|";

        return $aktywnaGra;
    }
	if (empty($aktywnaGra["ses_gracz_O"])) {
    $query = "
        UPDATE gry
        SET ses_gracz_O='$sessionID',
            data_rozpoczecia=NOW()
        WHERE id=$graID
    ";
    mysqli_query($conn, $query);
    return get_active_game($sessionID);
}
	
}

function wykonaj_ruch($symbol, $numer_pola, $gra){

    if (!$gra) {
        return false;
    }

    if (empty($gra["ses_gracz_X"]) || empty($gra["ses_gracz_O"])) {
        return false;
    }

    if (!is_numeric($numer_pola)) {
        return false;
    }

    if ($numer_pola < 0 || $numer_pola > 8) {
        return false;
    }

    if ($gra ["gracz"] != $symbol ) {
        return false;
    }

    if ($gra ["plansza"] [$numer_pola] != "-") {
        return false; 
    }

    if ($gra ["status_gry"] == "wygrana" || $gra ["status_gry"] == "remis" ) {
        return false;
    }

    $gra ["plansza"] [$numer_pola] = $symbol; // ustawienie symbolu gracza
    $stan_gry = sprawdz_stan_gry($gra ["plansza"]);
    // jezeli gra jest wygrana to ustawiamy na gra status gry "wygrana"
    // a w polu gra zwyciesca symbol gracza wygranego
    // jesli gra jest zremisowana to status bedzie remis
    // jesli gra jest nie zakonczona to zmieniamy symbol gracza ktorego jest kolej na przeciwny do aktualnego 
    // zapisz zmiane do bazy

    if ($stan_gry == "X" || $stan_gry == "O") {

        $gra["status_gry"] = "wygrana";
        $gra["zwyciezca"] = $stan_gry;

    } elseif ($stan_gry == "-") {

        $gra["status_gry"] = "remis";
        $gra["zwyciezca"] = null;

    } else {

        if ($symbol == "X") {
            $gra["gracz"] = "O";
        } else {
            $gra["gracz"] = "X";
        }
    }
    $conn = db(); 
    $query = "
	UPDATE gry
	SET 
    		plansza = '".$gra["plansza"]."',
    		gracz = '".$gra["gracz"]."',
    		status_gry = '".$gra["status_gry"]."',
    		zwyciezca = ".($gra["zwyciezca"] ? "'".$gra["zwyciezca"]."'" : "NULL")."
 	WHERE id=".$gra["id"];
	echo $query;
    mysqli_query($conn, $query);   

    return true;
}

function sprawdz_stan_gry($plansza) {

    $linie = [
        [0,1,2],[3,4,5],[6,7,8],
        [0,3,6],[1,4,7],[2,5,8],
        [0,4,8],[2,4,6]
    ];

    foreach($linie as $linia){

        if(
            $plansza[$linia[0]] != "-" &&
            $plansza[$linia[0]] == $plansza[$linia[1]] &&
            $plansza[$linia[1]] == $plansza[$linia[2]]
        ){
            return $plansza[$linia[0]]; 
        }
    }

    if(strpos($plansza, "-") === false){
        return "-";
    }
    return null;
}

