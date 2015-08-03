<?php
require_once "header.php";

require_once "functions.php";

Authorisation();

// Получили параметры из формы
$ShortName=$_REQUEST['ShortName'];
$FullName=$_REQUEST['FullName'];
$Email=$_REQUEST['Email'];
$WWW=$_REQUEST['WWW'];
$Object_ID=$_REQUEST['Object_ID'];
$Factory_ID=$_REQUEST['Factory_ID'];
$Area=$_REQUEST['Area'];
$Wokers=$_REQUEST['Wokers'];
$Production=$_REQUEST['Production'];
$Error="";

// Проверяем форма не должны быть пустой
if ( empty($ShortName) ) $Error=$Error.'Не заполнено поле "Краткое наименование"<br>';
if ( empty($FullName) ) $Error=$Error.'Не заполнено поле "Полное название"<br>';
   else if ( strlen($FullName)>200 ) $Error=$Error.'Значение поля "Полное название" превышает 200 символов<br>';
if ( empty($Email) ) $Error=$Error.'Не заполнено поле "Електронная почта"<br>';
   else if ( !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $Email ) ) $Error=$Error.'Адрес e-mail должен соответствовать формату somebody@somewhere.ru<br>';
if ( empty($WWW) ) $Error=$Error.'Не заполнено поле "Оффициальный сайт"<br>';
   else if ( !preg_match( "#^(http:\/\/)?(www.)?[0-9a-z\-\.]+\.[a-z]{2,6}\/?$#i", $WWW ) ) $Error=$Error.'Поле "Оффициальный сайт" должно соответствовать формату http://www.offsite.ru</li><br>';
           else if (strpos($WWW,"http://")===false) $WWW='http://'.$WWW;
if ( empty($Object_ID) ) $Error=$Error.'Не выбран "Тип объекта"<br>';
if ( empty($Factory_ID) ) $Error=$Error.'Не выбран "Тип производства"<br>';
if ( empty($Area) ) $Error=$Error.'Не заполнено поле "Занимаемая площадь"<br>';
// Проверяем является-ли введенное число целым
   else
      { $str=$Area;
      	settype($str, "integer");
      	settype($str, "string");
        if ($str!=$Area) $Error=$Error.'Значение поля "Занимаемая площадь" не является целым числом<br>';
      }

if ( empty($Wokers) ) $Error=$Error.'Не заполнено поле "Колличество сотрудников"<br>';
   else
      { $str=$Wokers;
      	settype($str, "integer");
      	settype($str, "string");
        if ($str!=$Wokers) $Error=$Error.'Значение поля "Колличество сотрудников" не является целым числом<br>';
      }

if ( empty($Production) ) $Error=$Error.'Не заполнено поле "Производимая продукция"<br>';
   else if ( strlen($Production)>500 ) $Error=$Error.'Значение поля "Производимая продукция" превышает 500 символов<br>';

// Если форма не пустая, то проверка, нет ли в базе добавляемой записи
if ( empty($Error) )
   { // Определяем уникальный ключ для записи в базе
     $id_key = md5 ( trim($ShortName).trim($FullName).trim($Email).trim($WWW).trim($Object_ID).trim($Factory_ID).trim($Area).trim($Wokers).trim($Production) );
     $result=insert_in_base ($id_key, $ShortName, $FullName, $Email, $WWW, $Object_ID, $Factory_ID, $Area, $Wokers, $Production);
     if ($result!=true) $Error="Такая запись в базе уже существует!!!<br>";
   }

// Если были допущены ошибки при заполнении формы или добавлении в базу, то отправляем на форму введенные поля
if ( !empty($Error) )
{
$_SESSION['add_form']=array();
$_SESSION['add_form']['ShortName']=$ShortName;
$_SESSION['add_form']['FullName']=$FullName;
$_SESSION['add_form']['Email']=$Email;
$_SESSION['add_form']['WWW']=$WWW;
$_SESSION['add_form']['Object_ID']=$Object_ID;
$_SESSION['add_form']['Factory_ID']=$Factory_ID;
$_SESSION['add_form']['Area']=$Area;
$_SESSION['add_form']['Wokers']=$Wokers;
$_SESSION['add_form']['Production']=$Production;
$_SESSION['add_form']['Error']=$Error;
header ("Location: add_form.php");
die();
}

unset($_SESSION['add_form']);

// Поиск введенного значения в базе
$result=find_by_key ($id_key);
if (mssql_num_rows($result)!=1) { echo "<h2>Неизвестная ошибка!!!</h2>"; die; }

echo "<h1>Запись добавлена!!!</h1>";

// Получаем данные из базы в массив $data.
for ($data=array(); $row=mssql_fetch_assoc($result); $data[]=$row);

// Вывод карточки на экран в виде таблицы
view ( trim($data[0]['shortname']), trim($data[0]['fullname']), trim($data[0]['email']), trim($data[0]['web']), trim($data[0]['object_value']),
trim($data[0]['factory_value']), $data[0]['area'], $data[0]['wokers'], trim($data[0]['production']) );

echo '<br>';
echo '<table width="550" border="0">';
echo '<tr>';
echo '   <td><form action="main.php"><input type=submit value="На главную"></form></td>';
echo '   <td><form action="edit_form.php"><input type=submit value="Изменить"></form></td>';
echo '   <td><form action="delete_script.php"><input type=hidden name="id_key" value="'.$data[0]['id_key'].'"><input type=submit value="Удалить"></form></td>';
echo '   <td><form action="print_form.php" target="_blank"><input type=hidden name="id_key" value="'.$data[0]['id_key'].'"><input type=submit value="Версия для печати"></form></td>';
echo '   <td><form action="email_form.php"><input type=hidden name="id_key" value="'.$data[0]['id_key'].'"><input type=submit value="Отправить по e-mail"></form></td>';
echo '</tr>';
echo '</table>';

// Создаем сессию для отправки на форму edit_form.php
$_SESSION['edit_form']=array();
$_SESSION['edit_form']['id_key']=$data[0]['id_key'];
$_SESSION['edit_form']['ShortName']=trim($data[0]['shortname']);
$_SESSION['edit_form']['FullName']=trim($data[0]['fullname']);
$_SESSION['edit_form']['Email']=trim($data[0]['email']);
$_SESSION['edit_form']['WWW']=trim($data[0]['web']);
$_SESSION['edit_form']['Object_ID']=trim($data[0]['object_id']);
$_SESSION['edit_form']['Factory_ID']=trim($data[0]['factory_id']);
$_SESSION['edit_form']['Area']=$data[0]['area'];
$_SESSION['edit_form']['Wokers']=$data[0]['wokers'];
$_SESSION['edit_form']['Production']=trim($data[0]['production']);
$_SESSION['edit_form']['Error']="";

?>

<?php
require_once "footer.php";
?>