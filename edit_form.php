<?php
require_once "header.php";
?>

<?php
session_start();
require_once "functions.php";

Authorisation();

$id_key=$_SESSION['edit_form']['id_key'];
$ShortName=$_SESSION['edit_form']['ShortName'];
$FullName=$_SESSION['edit_form']['FullName'];
$Email=$_SESSION['edit_form']['Email'];
$WWW=$_SESSION['edit_form']['WWW'];
$Object_ID=$_SESSION['edit_form']['Object_ID'];
$Factory_ID=$_SESSION['edit_form']['Factory_ID'];
$Area=$_SESSION['edit_form']['Area'];
$Wokers=$_SESSION['edit_form']['Wokers'];
$Production=$_SESSION['edit_form']['Production'];
$Error=$_SESSION['edit_form']['Error'];
unset( $_SESSION['edit_form'] );

echo '<form action="edit_script.php">';
echo '<table border="0">';
echo '<tr>';
echo '   <td><b>Краткое наименование:</b></td>';
echo '   <td><input type=text size="53" maxlength="20" name="ShortName" value="'.$ShortName.'"></td>';
echo '</tr>';
echo '<tr>';
echo '   <td valign="top"><b>Полное название:</b></td>';
echo '   <td><textarea name="FullName" wrap="virtual" cols="40" rows="4" >'.$FullName.'</textarea></td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Електронная почта:</b></td>';
echo '   <td><input size="53" type=text maxlength="50" name="Email" value="'.$Email.'"></td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Оффициальный сайт:</b></td>';
echo '   <td><input size="53" type=text maxlength="100" name="WWW" value="'.$WWW.'"></td>';
echo '</tr>';
echo '<tr><td><b>Тип объекта:</b></td><td>&nbsp;</td></tr>';
echo '<tr>';
echo '   <td>Критически важный</td>';
echo '   <td><input type=radio name="Object_ID" value="critical"'; if ($Object_ID=="critical") echo ' checked></td>'; else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Важный</td>';
echo '   <td><input type=radio name="Object_ID" value="important"'; if ($Object_ID=="important") echo ' checked></td>'; else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Обеспечивающий</td>';
echo '   <td><input type=radio name="Object_ID" value="provide"'; if ($Object_ID=="provide") echo ' checked></td>'; else echo '</td>';
echo '</tr>';
echo '<tr><td><b>Тип производства:</b></td><td>&nbsp;</td></tr>';
echo '<tr>';
echo '   <td>Химически опасное</td>';
echo '   <td><input type=radio name="Factory_ID" value="chemically"'; if ($Factory_ID=="chemically") echo ' checked></td>'; else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Радиационно опасное</td>';
echo '   <td><input type=radio name="Factory_ID" value="radiac"'; if ($Factory_ID=="radiac") echo ' checked></td>'; else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Взрывоопасное</td>';
echo '   <td><input type=radio name="Factory_ID" value="explosion"'; if ($Factory_ID=="explosion") echo ' checked></td>'; else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Занимаемая площадь (м<sup>2</sup>):</b></td>';
echo '   <td><input size="53" type=text name="Area" value="'.$Area.'"></td>';
echo '</tr>';

echo '<tr>';
echo '   <td><b>Колличество сотрудников:</b></td>';
echo '   <td><input size="53" type=text name="Wokers" value="'.$Wokers.'"></td>';
echo '</tr>';

echo '<tr>';
echo '   <td valign="top"><b>Производимая продукция:</b></td>';
echo '   <td><textarea name="Production" wrap="virtual" cols="40" rows="6" >'.$Production.'</textarea></td>';
echo '</tr>';

echo '</table>';

echo '<input type=hidden name="id_key" value="'.$id_key.'">';

echo '<br><font color="red">'.$Error."</font><br>";

echo '<table border="0">';
echo '<tr>';
echo '   <td><input type=submit value="Сохранить"></form></td>';
echo '   <td><form action="main.php"><input type=submit value="На главную"></form></td>';
echo '</tr>';
echo '</table>';

?>

<?php
require_once "footer.php";
?>