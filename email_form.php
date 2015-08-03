<?php
require_once "header.php";
?>

<?php
require_once "functions.php";

Authorisation();

$Error="";
$id_key=$_REQUEST['id_key'];

if ( isset($_POST['to']) )
{
$Email=$_POST['to'];
if ( empty($Email) ) $Error=$Error.'Адрес получателя не указан<br>';
   else if ( !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $Email ) ) $Error=$Error.'Адрес e-mail должен соответствовать формату somebody@somewhere.ru<br>';

// Если не были допущены ошибки при заполнении поля адрес отправителя
if ( empty($Error) )
   { $xml=convert2xml($id_key);
     send_email($Email,$xml);
     echo "<H1>Письмо успешно отправлено!!!</H1>";
     echo '<form action="main.php"><input type=submit value="На главную"></form>';
     die;
   }

}

echo '<form name="" action="" method="post">';
echo 'Укажите адрес получателя: <input type=text name="to" value="';
if ( isset($Email) ) echo $Email.'">'; else echo '">';
echo '<input type=submit value="Отправить">';
echo '</form>';
echo '<font color="red">'.$Error.'</font><br>';
echo '<form action="main.php"><input type=submit value="На главную"></form>';

?>

<?php
require_once "footer.php";
?>