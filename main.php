<?php
require_once "header.php";
?>

<?php

require_once "functions.php";

if (isset ($_SESSION['authorisation']) === false)
{
$_SESSION['authorisation']=array();
$_SESSION['authorisation']['login']=$_REQUEST['login'];
$_SESSION['authorisation']['password']=$_REQUEST['password'];
}

Authorisation();

echo '<h1><a href="add_form.php">Добавить данные</a></h1>';
echo '<h1><a href="search_form.php">Поиск</a></h1><br>';
echo '<form action="authorisation.php"><input type=submit value="Выйти из базы"></form>';
?>

<?php
require_once "footer.php";
?>