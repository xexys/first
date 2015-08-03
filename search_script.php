<?php
require_once "header.php";
?>

<?php
session_start();
require_once "functions.php";

Authorisation();

// �������� ��������� �� �����
$ShortName=$_REQUEST['ShortName'];
$FullName=$_REQUEST['FullName'];
$Email=$_REQUEST['Email'];
$WWW=$_REQUEST['WWW'];
if ( isset($_REQUEST['Object_ID[]']) ) $Object_ID[]=$_REQUEST['Object_ID[]']; else $Object_ID[]="";
if ( isset($_REQUEST['Factory_ID[]']) ) $Factory_ID[]=$_REQUEST['Factory_ID[]']; else $Factory_ID[]="";
$Area=$_REQUEST['Area'];
if ( isset($_REQUEST['Area_compare']) ) $Area_compare=$_REQUEST['Area_compare']; else $Area_compare="";
$Wokers=$_REQUEST['Wokers'];
if ( isset($_REQUEST['Wokers_compare']) ) $Wokers_compare=$_REQUEST['Wokers_compare']; else $Wokers_compare="";
$Production=$_REQUEST['Production'];
$Error="";
unset ($_SESSION['search']);

if ( !empty($Area_compare) || !empty($Area) )
      { $str=$Area;
      	settype($str, "integer");
      	settype($str, "string");
        if ($str!=$Area) $Error=$Error.'�������� ���� "���������� �������" �� �������� ����� ������ ��� �� ���������<br>';
        if ( empty($Area_compare) ) $Error=$Error.'�� ������ �������� ��������� ��� ���� "���������� �������"<br>';
      }

if ( !empty($Wokers_compare) || !empty($Wokers) )
      { $str=$Wokers;
      	settype($str, "integer");
      	settype($str, "string");
        if ($str!=$Wokers) $Error=$Error.'�������� ���� "����������� �����������" �� �������� ����� ������ ��� �� ���������<br>';
        if ( empty($Wokers_compare) ) $Error=$Error.'�� ������ �������� ��������� ��� ���� "����������� �����������"<br>';
      }

// ���� ���� �������� ������ ��� ���������� ����� ��� ��������� ������ � ����, �� ���������� �� ����� ��������� ����
if ( !empty($Error) )
{
$_SESSION['search']=array();
$_SESSION['search']['ShortName']=$ShortName;
$_SESSION['search']['FullName']=$FullName;
$_SESSION['search']['Email']=$Email;
$_SESSION['search']['WWW']=$WWW;
for ($i=0; $i<sizeof($Object_ID); $i++)
   $_SESSION['search']['Object_ID'][$i]=$Object_ID[$i];
for ($j=0; $j<sizeof($Factory_ID); $j++)
   $_SESSION['search']['Factory_ID'][$j]=$Factory_ID[$j];
$_SESSION['search']['Area']=$Area;
$_SESSION['search']['Area_compare']=$Area_compare;
$_SESSION['search']['Wokers']=$Wokers;
$_SESSION['search']['Wokers_compare']=$Wokers_compare;
$_SESSION['search']['Production']=$Production;
$_SESSION['search']['Error']=$Error;
header ("Location: search_form.php");
die();
}

// �������� ������� �� $Object_ID
$Object_ID_Query=" and (object_type.object_id like '%".$Object_ID[0]."%'";
for ($i=1; $i<sizeof($Object_ID)-1; $i++)
   $Object_ID_Query=$Object_ID_Query." or object_type.object_id like '%".$Object_ID[$i]."%'";
$Object_ID_Query=$Object_ID_Query.")";

// �������� ������� �� $Factory_ID
$Factory_ID_Query=" and (factory_type.factory_id like '%".$Factory_ID[0]."%'";
for ($j=1; $j<sizeof($Factory_ID)-1; $j++)
   $Factory_ID_Query=$Factory_ID_Query." or factory_type.factory_id like '%".$Factory_ID[$j]."%'";
$Factory_ID_Query=$Factory_ID_Query.")";

// ����� ���������� �������� � ����
$result=search_in_base ($ShortName, $FullName, $Email, $WWW, $Object_ID_Query, $Factory_ID_Query, $Area, $Wokers, $Production, $Area_compare, $Wokers_compare);

// �������� ������ �� ���� � ������ $data.
for ($data=array(); $row=mssql_fetch_assoc($result); $data[]=$row);

// ����� �������� �� ����� � ���� �������
echo "<h1>�� ������ ������� ������� ".sizeof($data)." ������� � ����</h1>";
if (sizeof($data)==0)
   { echo '<table border="0">';
     echo '<tr>';
     echo '   <td><form action="main.php"><input type=submit value="�� �������"></form></td>';
     echo '   <td><form action="search_form.php"><input type=submit value="����� �����"></form></td>';
     echo '</tr>';
     echo '</table>';
   }

for ($k=0; $k<sizeof($data); $k++)
{   view ( trim($data[$k]['shortname']), trim($data[$k]['fullname']), trim($data[$k]['email']), trim($data[$k]['web']), trim($data[$k]['object_value']),
   trim($data[$k]['factory_value']), $data[$k]['area'], $data[$k]['wokers'], trim($data[$k]['production']) );
   echo '<br>';
   echo '<table width="550" border="0">';
   echo '<tr>';
   echo '   <td><form action="main.php"><input type=submit value="�� �������"></form></td>';
   echo '   <td><form action="search_script_selection.php"><input type=submit value="��������"><input type=hidden name="id_key" value="'.$data[$k]['id_key'].'"></form></td>';
   echo '   <td><form action="delete_script.php"><input type=hidden name="id_key" value="'.$data[$k]['id_key'].'"><input type=submit value="�������"></form></td>';
   echo '   <td><form action="print_form.php" target="_blank"><input type=hidden name="id_key" value="'.$data[$k]['id_key'].'"><input type=submit value="������ ��� ������"></form></td>';
   echo '   <td><form action="email_form.php"><input type=hidden name="id_key" value="'.$data[$k]['id_key'].'"><input type=submit value="��������� �� e-mail"></form></td>';
   echo '</tr>';
   echo '</table><br>';
}

?>

<?php
require_once "footer.php";
?>