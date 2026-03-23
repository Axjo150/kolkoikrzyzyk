let plansza = ["","","","","","","","",""];
// zaimplemetuj metode ktora bedzie wywolywana co 500 milisekund, 
// metoda pobierze stan gry (fetch stan_gry.php) a nastepnie na podstawie zleconych wynikow zauktualizuje widok
// 
function aktualizuj(){

    fetch("stan_gry.php")
    .then(res => res.json())
    .then(gra => {

        for(let i=0;i<9;i++){
            let pole = document.getElementById("p"+i);

            if(gra.plansza[i] == "-"){
                pole.innerHTML = "";
            } else {
                pole.innerHTML = gra.plansza[i];
            }
        }
        document.getElementById("kolej").innerHTML =
            "Kolej gracza: " + gra.gracz;

        document.getElementById("wynik").innerHTML =
            gra.status_gry;

	document.getElementById("wynik").innerHTML =
            gra.status_gry;

	document.getElementById("numer_gry").innerHTML =
            "Numer gry: " +  gra.id;

	
    });
}
        setInterval(aktualizuj, 500);

function klik(i){
    if(plansza[i] != ""){
        return;
    }

    fetch("stan_gry.php")
    .then(res => res.json())
    .then(gra => {

        if(gra.gracz != mojSymbol){
            return;
        }

        fetch("ruch.php?pole=" + i);

    });
// sprawdz czy gracz moze kliknac, czyja jest kolej, jesli jest moja kolej np X, to wywoluje ruch.php z parametrem get pole
    //sprawdz();
 /*   	let wygrane = [
        [0,1,2], [3,4,5], [6,7,8],
        [0,3,6], [1,4,7], [2,5,8],
        [0,4,8], [2,4,6]
    ];

    for(let i=0;i<wygrane.length;i++){

        let a = wygrane[i][0];
        let b = wygrane[i][1];
        let c = wygrane[i][2];

        if(plansza[a] != "" &&
           plansza[a] == plansza[b] &&
           plansza[b] == plansza[c]){

            if(plansza[a] == "x"){
                document.getElementById("wynik").innerHTML =
                    "Wygral Gracz X";
            }
            else{
                document.getElementById("wynik").innerHTML =
                    "Wygral Gracz O";
            }

            return;
        }
    }

    let wolne = false;

    for(let i=0;i<9;i++){
        if(plansza[i] == ""){
            wolne = true;
        }
    }

    if(!wolne){
        document.getElementById("wynik").innerHTML = "Remis";
    }	*/
}	

function reset(){

    for(let i=0;i<9;i++){
        document.getElementById("p"+i).innerHTML = "";
        plansza[i] = "";
    }

    document.getElementById("wynik").innerHTML = "";
}