<?php
require_once "header.php";
?>

<?php
session_start();
require_once "functions.php";

Authorisation();

// Получили параметры из формы

unset( $_SESSION['edit_form'] );
$id_key=$_REQUEST['id_key'];

$result=delete_from_base($id_key);
if ($result==true) echo "<H1>Запись удалена!!!</H1>";
   else echo "echo <h2>Неизвестная ошибка!!!</h2>";

echo '<form action="main.php"><input type=submit value="На главную"></form>';

?>

<?php
require_once "footer.php";
?>