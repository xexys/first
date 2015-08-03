<?
function send_email($to, $text)
{

$login="xexys";
$pass="21x1e2x1y9s83";
$from="xexys@mail.ru";
$subject="Subject: =?windows-1251?Q?".str_replace("+","_",str_replace("%","=",urlencode('Информация о записе в базе данных (XML формат)')))."?=\r\n";

$header="From: ".$from."\r\n";
$header.="To: ".$to."\r\n";
$header.=$subject;
$header.="Content-Type: text/plain; charset=windows-1251\r\n";

function get_data($smtp_conn)
{
$data="";
while($str = fgets($smtp_conn))
{
$data .= $str;
if(substr($str,3,1) == " ") break;
}
return $data;
}

$smtp_conn = fsockopen("smtp.mail.ru", 25,$errno, $errstr, 10);
if(!$smtp_conn) {print "Соединение с серверов не прошло"; fclose($smtp_conn); exit;}
$data = get_data($smtp_conn);

fputs($smtp_conn,"EHLO mail.ru\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250) {print "Ошибка приветсвия EHLO"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"AUTH LOGIN\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 334) {print "Сервер не разрешил начать авторизацию"; fclose($smtp_conn); exit;}

fputs($smtp_conn,base64_encode($login)."\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 334) {print "Ошибка доступа к такому юзеру"; fclose($smtp_conn); exit;}

fputs($smtp_conn,base64_encode($pass)."\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 235) {print "Неправильный пароль"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"MAIL FROM:".$from."\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250) {print "Сервер отказал в команде MAIL FROM"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"RCPT TO:".$to."\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250 AND $code != 251) {print "Сервер не принял команду RCPT TO"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"DATA\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 354) {print "Сервер не принял DATA"; fclose($smtp_conn); exit;}

fputs($smtp_conn,$header."\r\n".$text."\r\n.\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250) {print "Ошибка отправки письма"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"QUIT\r\n");
fclose($smtp_conn);

}
?>

<?php
// Проверка существования и вход в базу
function Authorisation()
{
@session_start();
$user = $_SESSION['authorisation']['login'];
$pass = $_SESSION['authorisation']['password'];
$db = "first";
// Подключаемся к СУБД MySQL.
if ( mssql_connect("localhost", $user, $pass)==true )
   { if ( mssql_select_db($db)==false )
        { header ("Location: authorisation.php");
          die;
        }
   }
else
   { header ("Location: authorisation.php");
     die;
   }
}
?>


<?php
function find_by_key ($id_key)
{
// Проверка существования и уникальности записи по первичному ключу
$query="SELECT card_data.id_key, shortname, fullname, email, web, area, wokers, production, factory_value, object_value, factory_type.factory_id, object_type.object_id";
$query=$query." FROM card_data, card_characteristics, factory_type, object_type";
$query=$query." WHERE card_data.id_key=card_characteristics.id_key and card_characteristics.factory_id=factory_type.factory_id and card_characteristics.object_id=object_type.object_id";
$query=$query." and card_data.id_key='".$id_key."'";
return mssql_query($query);
}
?>

<?php
function convert2xml($id_key)
{$result=find_by_key ($id_key);

// Получаем данные из базы в массив $data.
for ($data=array(); $row=mssql_fetch_assoc($result); $data[]=$row);

$xml= '<?xml version="1.0" encoding="windows-1251" ?>'."\r\n";
$xml.= "<root>\r\n";
for ($i=0; $i<count($data); $i++)
{
  $xml.= "  <row>\r\n";
  foreach($data[0] as $key => $value)
  {
     $xml.= "    <$key>".trim($value)."</$key>\r\n";
  }
  $xml.= "  </row>\r\n";
}
$xml.= "</root>\r\n";
return $xml;}
?>

<?php
function search_in_base ($ShortName="", $FullName="", $Email="", $WWW="", $Object_ID_Query, $Factory_ID_Query, $Area="", $Wokers="", $Production="",
$Area_compare="", $Wokers_compare="")
{$query="SELECT card_data.id_key, shortname, fullname, email, web, area, wokers, production, factory_value, object_value, factory_type.factory_id, object_type.object_id";
$query=$query." FROM card_data, card_characteristics, factory_type, object_type";
$query=$query." WHERE card_data.id_key=card_characteristics.id_key and card_characteristics.factory_id=factory_type.factory_id and card_characteristics.object_id=object_type.object_id";
$query=$query." and card_data.shortname like '%".$ShortName."%'";
$query=$query." and card_data.fullname like '%".$FullName."%'";
$query=$query." and card_data.email like '%".$Email."%'";
$query=$query." and card_data.web like '%".$WWW."%'";
$query=$query.$Object_ID_Query;
$query=$query.$Factory_ID_Query;
if ($Area!="") $query=$query." and card_characteristics.area".$Area_compare.$Area;
if ($Wokers!="") $query=$query." and card_characteristics.wokers".$Wokers_compare.$Wokers;
$query=$query." and card_characteristics.production like '%".$Production."%'";
return mssql_query($query);
}
?>

<?php
// Изменение в базе
function update_base($id_key, $new_id_key, $ShortName, $FullName, $Email, $WWW, $Object_ID, $Factory_ID, $Area, $Wokers, $Production)
{
$query="UPDATE card_data ";
$query=$query."SET id_key='".$new_id_key."', ShortName='".$ShortName."', FullName='".$FullName."', Email='".$Email."', Web='".$WWW."'";
$query=$query." WHERE id_key='".$id_key."'";
if (mssql_query($query)==true)
{$query="UPDATE card_characteristics ";
$query=$query."SET Object_ID='".$Object_ID."', Factory_ID='".$Factory_ID."', Area='".$Area."', Wokers='".$Wokers."', Production='".$Production."'";
$query=$query." WHERE id_key='".$new_id_key."'";
mssql_query($query);
return true;
}
else return false;
}
?>

<?php
// Удаление из базы
function delete_from_base($id_key)
{
$query="DELETE FROM card_data WHERE id_key='".$id_key."'";
mssql_query ($query);
if (mssql_query ($query)==true) return true;
   else return false;
}
?>

<?php
//Добавление в базу
function insert_in_base ($id_key, $ShortName, $FullName, $Email, $WWW, $Object_ID, $Factory_ID, $Area, $Wokers, $Production)
{
$query="INSERT INTO card_data VALUES ('".$id_key."',";
$query=$query."'".$ShortName."',";
$query=$query."'".$FullName."',";
$query=$query."'".$Email."',";
$query=$query."'".$WWW."')";
if (mssql_query($query)==true)
{
$query="INSERT INTO card_characteristics VALUES ('".$id_key."',";
$query=$query."'".$Object_ID."',";
$query=$query."'".$Factory_ID."',";
$query=$query.$Area.",";
$query=$query.$Wokers.",";
$query=$query."'".$Production."')";
mssql_query($query);
return true;
}
else return false;
}
?>

<?php
// Функция выводит таблицу с заголовком и данными
function view ($ShortName, $FullName, $Email, $WWW, $Object_Value, $Factory_Value, $Area, $Wokers, $Production)
{
echo '<table width="550" border="1">';
echo '<tr>';
echo '   <td width="250"><b>Краткое наименование</b></td>';
echo '   <td width="300">'.$ShortName.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td valign="top"><b>Полное название</b></td>';
echo '   <td>'.$FullName.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Електронная почта</b></td>';
echo '   <td>'.$Email.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Оффициальный сайт</b></td>';
echo '   <td><a href="'.$WWW.'" target="_blank">'.$WWW.'</a></td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Тип объекта</b></td>';
echo '   <td>'.$Object_Value.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Тип производства</b></td>';
echo '   <td>'.$Factory_Value.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Занимаемая площадь</b></td>';
echo '   <td>'.$Area.'м<sup>2</sup></td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Колличество сотрудников</b></td>';
echo '   <td>'.$Wokers.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td valign="top"><b>Производимая продукция</b></td>';
echo '   <td>'.$Production.'</td>';
echo '</tr>';
echo '</table>';
}
?>

<?php
// Функция выводит таблицу с заголовком и данными для печати
function print_view ($ShortName, $FullName, $Email, $WWW, $Object_Value, $Factory_Value, $Area, $Wokers, $Production)
{
echo '<table width="550" border="1" cellpadding="5" cellspacing="0" bordercolorlight="black" bordercolordark="white">';
echo '<tr>';
echo '   <td width="250"><b>Краткое наименование</b></td>';
echo '   <td width="300">'.$ShortName.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td valign="top"><b>Полное название</b></td>';
echo '   <td>'.$FullName.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Електронная почта</b></td>';
echo '   <td>'.$Email.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Оффициальный сайт</b></td>';
echo '   <td>'.$WWW.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Тип объекта</b></td>';
echo '   <td>'.$Object_Value.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Тип производства</b></td>';
echo '   <td>'.$Factory_Value.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Занимаемая площадь</b></td>';
echo '   <td>'.$Area.'м<sup>2</sup></td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Колличество сотрудников</b></td>';
echo '   <td>'.$Wokers.'</td>';
echo '</tr>';
echo '<tr>';
echo '   <td valign="top"><b>Производимая продукция</b></td>';
echo '   <td>'.$Production.'</td>';
echo '</tr>';
echo '</table>';
}
?>