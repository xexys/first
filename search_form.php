<?php
require_once "header.php";
?>

<?php
session_start();
require_once "functions.php";

Authorisation();

if ( !isset($_SESSION['search']) )
{
$ShortName="";
$FullName="";
$Email="";
$WWW="";
$Object_ID[]="";
$Factory_ID[]="";
$Area="";
$Area_compare="";
$Wokers="";
$Wokers_compare="";
$Production="";
$Error="";
}
else
{

$ShortName=$_SESSION['search']['ShortName'];
$FullName=$_SESSION['search']['FullName'];
$Email=$_SESSION['search']['Email'];
$WWW=$_SESSION['search']['WWW'];

for ($i=0; $i<sizeof($_SESSION['search']['Object_ID']) ; $i++)
   $Object_ID[$i]=$_SESSION['search']['Object_ID'][$i];

for ($j=0; $j<sizeof($_SESSION['search']['Factory_ID']) ; $j++)
   $Factory_ID[$j]=$_SESSION['search']['Factory_ID'][$j];

$Area=$_SESSION['search']['Area'];
$Area_compare=$_SESSION['search']['Area_compare'];
$Wokers=$_SESSION['search']['Wokers'];
$Wokers_compare=$_SESSION['search']['Wokers_compare'];
$Production=$_SESSION['search']['Production'];
$Error=$_SESSION['search']['Error'];
unset($_SESSION['search']);

}
echo '<h2>По умолчанию значения всех полей "%%"</h2>';
echo '<form action="search_script.php">';
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
$i=0;
echo '<tr><td><b>Тип объекта:</b></td><td>&nbsp;</td></tr>';
echo '<tr>';
echo '   <td>Критически важный</td>';
echo '   <td><input type=checkbox name="Object_ID[]" value="critical"'; if ($Object_ID[$i]=="critical") { echo ' checked></td>'; $i++; } else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Важный</td>';
echo '   <td><input type=checkbox name="Object_ID[]" value="important"'; if ($Object_ID[$i]=="important") { echo ' checked></td>'; $i++; } else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Обеспечивающий</td>';
echo '   <td><input type=checkbox name="Object_ID[]" value="provide"'; if ($Object_ID[$i]=="provide") { echo ' checked></td>'; $i++; } else echo '</td>';
echo '</tr>';
$j=0;
echo '<tr><td><b>Тип производства:</b></td><td>&nbsp;</td></tr>';
echo '<tr>';
echo '   <td>Химически опасное</td>';
echo '   <td><input type=checkbox name="Factory_ID[]" value="chemically"'; if ($Factory_ID[$j]=="chemically") { echo ' checked></td>'; $j++; } else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Радиационно опасное</td>';
echo '   <td><input type=checkbox name="Factory_ID[]" value="radiac"'; if ($Factory_ID[$j]=="radiac") { echo ' checked></td>'; $j++; } else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td>Взрывоопасное</td>';
echo '   <td><input type=checkbox name="Factory_ID[]" value="explosion"'; if ($Factory_ID[$j]=="explosion") { echo ' checked></td>'; $j++; } else echo '</td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Занимаемая площадь (м<sup>2</sup>):</b></td>';
echo '   <td><input type=radio name="Area_compare" value="<"'; if ($Area_compare=="<") echo ' checked>&lt;'; else echo '>&lt;';
echo '<input type=radio name="Area_compare" value=">"'; if ($Area_compare==">") echo ' checked>&gt;'; else echo '>&gt;';
echo '<input type=radio name="Area_compare" value="="'; if ($Area_compare=="=") echo ' checked>='; else echo '>=';
echo '<input type=radio name="Area_compare" value="<="'; if ($Area_compare=="<=") echo ' checked>&le;'; else echo '>&le;';
echo '<input type=radio name="Area_compare" value=">="'; if ($Area_compare==">=") echo ' checked>&ge;'; else echo '>&ge;';
echo '<input size="25" type=text name="Area" value="'.$Area.'"></td>';
echo '</tr>';
echo '<tr>';
echo '   <td><b>Колличество сотрудников:</b></td>';
echo '   <td><input type=radio name="Wokers_compare" value="<"'; if ($Wokers_compare=="<") echo ' checked>&lt;'; else echo '>&lt;';
echo '<input type=radio name="Wokers_compare" value=">"'; if ($Wokers_compare==">") echo ' checked>&gt;'; else echo '>&gt;';
echo '<input type=radio name="Wokers_compare" value="="'; if ($Wokers_compare=="=") echo ' checked>='; else echo '>=';
echo '<input type=radio name="Wokers_compare" value="<="'; if ($Wokers_compare=="<=") echo ' checked>&le;'; else echo '>&le;';
echo '<input type=radio name="Wokers_compare" value=">="'; if ($Wokers_compare==">=") echo ' checked>&ge;'; else echo '>&ge;';
echo '<input size="25" type=text name="Wokers" value="'.$Wokers.'"></td>';
echo '</tr>';
echo '<tr>';
echo '   <td valign="top"><b>Производимая продукция:</b></td>';
echo '   <td><textarea name="Production" wrap="virtual" cols="40" rows="6" >'.$Production.'</textarea></td>';
echo '</tr>';
echo '</table>';

echo '<br><font color="red">'.$Error."</font><br>";

echo '<table border="0">';
echo '<tr>';
echo '   <td><input type=submit value="Начать поиск"></form></td>';
echo '   <td><form action="search_form.php"><input type=submit value="Очистить форму"></form></td>';
echo '   <td><form action="main.php"><input type=submit value="На главную"></form></td>';
echo '</tr>';
echo '</table>';

?>

<?php
require_once "footer.php";
?>
