<?php
ini_set( 'display_errors', 'Off' ); 
session_start();
$_SESSION["zalogowany"];
if(empty($_SESSION["zalogowany"]))$_SESSION["zalogowany"]=0;

//£¹czenie z serwerem
$connection = @mysql_connect('serwer1699338.home.pl', '21777739_z7', 'qwerty123456')
or die('Nie po³¹czono z serwerem !!!<br />B³¹d: '.mysql_error()); 

//£¹czenie z baz¹ danych
$db = @mysql_select_db('21777739_z7', $connection) 
or die('Nie po³¹czono siê z baz¹ danych!!!<br />B³¹d: '.mysql_error()); 

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
			echo "Zalogowano poprawnie. <a href='index.php'>Przejdz na strone glowna</a>";
			$_SESSION["zalogowany"]=1;
			$_SESSION['login']=$_POST['login'];
			$ip = $_SERVER["REMOTE_ADDR"];
			$stan = "TAK";
			$log=mysql_query("INSERT INTO logi VALUES (NULL, NULL,'".htmlspecialchars($_POST["login"])."','$ip','$stan')") or die('Blad zapytania');
			}
		else echo ShowLogin("Podano zle dane!!!");
		}
	else ShowLogin();
}
else{
?>
Zalogowales sie pomyslnie! Przejdz do <a href="dodaj.php">portalu</a>
<br><a href='index.php?wyloguj=tak'>wyloguj sie</a>
<?php
}
?>

</body>
</html>
<?php mysql_close(); 
?>