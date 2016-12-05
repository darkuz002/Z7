<?php
ini_set( 'display_errors', 'Off' ); 
//Łączenie z serwerem
$connection = @mysql_connect('serwer1699338.home.pl', '21777739_z7', 'qwerty123456')
or die('Nie połączono z serwerem !!!<br />Błąd: '.mysql_error()); 

//Łączenie z bazą danych
$db = @mysql_select_db('21777739_z7', $connection) 
or die('Nie połączono się z bazą danych!!!<br />Błąd: '.mysql_error()); 

function ShowForm($komunikat=""){	//funkcja wyświetlająca formularz rejestracyjny
	echo "$komunikat<br>";
	echo "<form action='rejestracja.php' method=post>";
	echo "Login: <input type=text name=login><br>";
	echo "Haslo: <input type=password name=haslo><br>";
	echo "<input type=hidden value='1' name=send>";
	echo "<input type=submit value='Zarejestruj mnie'>";
	echo "<br><br><a href='index.php'>Powrot do strony glownej</a>";
	echo "</form>";
}
?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<title>Formularz rejestracyjny</title>
</head>
<body>
<?php
if($_POST["send"]==1){	//sprawdzanie czy formularz został wysłany
	if(!empty($_POST["login"]) && !empty($_POST["haslo"])){	//oraz czy uzupełniono wszystkie dane
		if(mysql_num_rows(mysql_query("select * from users where Login='".htmlspecialchars($_POST["login"]."'"))))ShowForm("Uzytkownik o podanym loginie juz istnieje!!!"); // sprawdzanie czy użytkownik o podanej nazwie już istnieje
		else{
		$a=0;
			mysql_query("insert into users values (NULL, '".htmlspecialchars($_POST["login"])."', '".htmlspecialchars($_POST['haslo'])."','$a')"); // zapisywanie rekordu do bazy
			echo "Rejestracja przebiegla pomyslnie. Mozesz teraz przejsc do <a href='index.php'>strony glownej</a> i sie zalogowac.";
			$folder = $_POST['login'];
			mkdir ("./$folder", 0777);
			}
	}
	else ShowForm("Nie uzupelniono wszystkich pol!!!");
}
else ShowForm();
mysql_close(); //zamykanie połączenia z bazą
?>
</body>
</html>
