<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>
<HEAD>
  <TITLE>База данных критически-важных объектов (КВО) (Версия для печати)</TITLE>
  <meta http-equiv=Content-Type content="text/html; charset=windows-1251">
  <link rel="shortcut icon" href="favicon.ico">
    <STYLE TYPE="text/css" TITLE="Cool table">
        <!--
        TABLE { font-family : Times New Roman; font-size : 12pt;}
        -->
    </STYLE>
    <STYLE type="text/css" media="print">
      .printbutton { visibility: hidden; display: none; }
    </STYLE>
</HEAD>
<BODY>

<?php
require_once "functions.php";

Authorisation();

// Поиск введенного значения в базе
$id_key=$_REQUEST['id_key'];
$result=find_by_key ($id_key);

// Получаем данные из базы в массив $data.
for ($data=array(); $row=mssql_fetch_assoc($result); $data[]=$row);

// Вывод карточки на экран в виде таблицы
print_view ( trim($data[0]['shortname']), trim($data[0]['fullname']), trim($data[0]['email']), trim($data[0]['web']), trim($data[0]['object_value']),
trim($data[0]['factory_value']), $data[0]['area'], $data[0]['wokers'], trim($data[0]['production']) );

//echo '<br><input type="button" onClick="window.print()" value="     Печать     ">';

?>
<br>
<script>
document.write("<input type='button' " +
"onClick='window.print()' " +
"class='printbutton' " +
"value='     Печать     '/>");
</script>


</BODY>
</HTML>