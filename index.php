<?php
ini_set( 'display_errors', 'Off' ); 
session_start();
$_SESSION["zalogowany"];
if(empty($_SESSION["zalogowany"]))$_SESSION["zalogowany"]=0;

//Łączenie z serwerem
$connection = @mysql_connect('serwer1699338.home.pl', '21777739_z7', 'qwerty123456')
or die('Nie połączono z serwerem !!!<br />Błąd: '.mysql_error()); 

//Łączenie z bazą danych
$db = @mysql_select_db('21777739_z7', $connection) 
or die('Nie połączono się z bazą danych!!!<br />Błąd: '.mysql_error()); 

function ShowLogin($komunikat=""){
	echo "$komunikat<br>";
	echo "<form action='index.php' method=post>";
	echo "Login: <input type=text name=login><br>";
	echo "Haslo: <input type=password name=haslo><br>";
	echo "<input type=submit value='Zaloguj!'>";
	echo "</form>";
	echo "Jesli nie jestes zarejestrowany, <a href='rejestracja.php'>tu znajdziesz formularz</a>";
}

?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<title>Strona glowna</title>
</head>
<body>
<?php

if($_GET["wyloguj"]=="tak"){$_SESSION["zalogowany"]=0;echo "Zostales wylogowany z serwisu";}
if($_SESSION["zalogowany"]!=1){
	if(!empty($_POST["login"]) && !empty($_POST["haslo"])){
		$s = $_POST['haslo'];
		if(mysql_num_rows(mysql_query("select * from users where Login = '".htmlspecialchars($_POST["login"])."' AND Haslo = '".htmlspecialchars($s)."'"))){
			$zap = mysql_query("SELECT * FROM users where Login = '".htmlspecialchars($_POST["login"])."'") or die('Błąd'); 			
			$log = mysql_fetch_array($zap);
			$ilosc = $log["Proby"];
			echo "Zalogowano poprawnie. <a href='index.php'>Przejdz na strone glowna</a>";
			$i=0;
			$_SESSION["zalogowany"]=1;
			$_SESSION['login']=$_POST['login'];
			$ip = $_SERVER["REMOTE_ADDR"];
			$stan = "TAK";
			$log=mysql_query("INSERT INTO logi VALUES (NULL, NULL,'".htmlspecialchars($_POST["login"])."','$ip','$stan')") or die('Blad zapytania');
			$prob=mysql_query("UPDATE users SET Proby='$i' WHERE Login='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
			if ($ilosc != 0){
			$z= "NIE";
			$zapytanie2 = mysql_query("SELECT * FROM logi WHERE Uzytkownik= '".htmlspecialchars($_POST["login"])."' AND Zalogowano='$z' ORDER BY ID DESC LIMIT 1") or die('Błąd'); 			
			$wynik = mysql_fetch_array($zapytanie2);
			$data = $wynik["Data/godzina"];
			echo "<br><br>Uwaga, ostatnie bledne logowanie : $data";
			}
			}
		else{
			$ip = $_SERVER["REMOTE_ADDR"];
			$stan = "NIE";
			$log=mysql_query("INSERT INTO logi VALUES (NULL, NULL,'".htmlspecialchars($_POST["login"])."','$ip','$stan')") or die('Blad zapytania');
			if(mysql_num_rows(mysql_query("select * from users where Login = '".htmlspecialchars($_POST["login"])."'"))){
				$zap = mysql_query("SELECT * FROM users where Login = '".htmlspecialchars($_POST["login"])."'") or die('Błąd');
				$log = mysql_fetch_array($zap);
				$ilosc = $log["Proby"];
				if ($ilosc == 0){
				$q=1;
				echo ShowLogin("Bledne logowanie po raz pierwszy");
				$prob=mysql_query("UPDATE users SET Proby='$q' WHERE Login='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
				}
			if ($ilosc == 1){
				$q=2;
				echo ShowLogin("Bledne logowanie po raz drugi");
				$prob=mysql_query("UPDATE users SET Proby='$q' WHERE Login='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
			}
			if ($ilosc == 2){
				$q=3;
				echo ShowLogin("Bledne logowanie po raz trzeci. Konto zostaje zablokowane. Skontaktuj sie z administratorem");
				$prob=mysql_query("DELETE FROM users WHERE Login='".htmlspecialchars($_POST["login"])."'") or die('Blad zapytania');
			}		


			} else{
				ShowLogin("Podales zly login !!!!!!!!");
			}			
		} 
		}
	else {ShowLogin("Nie podales zadnych danych !!!!!!!!");}
}
else{
?>
<br>Zalogowales sie pomyslnie! Przejdz do <a href="dodaj.php">portalu</a>
<br><a href='index.php?wyloguj=tak'>wyloguj sie</a>
<?php
}
?>

</body>
</html>
<?php mysql_close(); 
?>
