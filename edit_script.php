<?php
require_once "header.php";
?>

<?php
session_start();
require_once "functions.php";

Authorisation();

// �������� ��������� �� �����
$id_key=$_REQUEST['id_key'];
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

// ��������� ����� �� ������ ���� ������
if ( empty($ShortName) ) $Error=$Error.'�� ��������� ���� "������� ������������"<br>';
if ( empty($FullName) ) $Error=$Error.'�� ��������� ���� "������ ��������"<br>';
   else if ( strlen($FullName)>200 ) $Error=$Error.'�������� ���� "������ ��������" ��������� 200 ��������<br>';
if ( empty($Email) ) $Error=$Error.'�� ��������� ���� "����������� �����"<br>';
   else if ( !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $Email ) ) $Error=$Error.'����� e-mail ������ ��������������� ������� somebody@somewhere.ru<br>';
if ( empty($WWW) ) $Error=$Error.'�� ��������� ���� "������������ ����"<br>';
   else if ( !preg_match( "#^(http:\/\/)?(www.)?[0-9a-z\-\.]+\.[a-z]{2,6}\/?$#i", $WWW ) ) $Error=$Error.'���� "������������ ����" ������ ��������������� ������� http://www.offsite.ru</li><br>';
           else if (strpos($WWW,"http://")===false) $WWW='http://'.$WWW;
if ( empty($Object_ID) ) $Error=$Error.'�� ������ "��� �������"<br>';
if ( empty($Factory_ID) ) $Error=$Error.'�� ������ "��� ������������"<br>';
if ( empty($Area) ) $Error=$Error.'�� ��������� ���� "���������� �������"<br>';
// ��������� ��������-�� ��������� ����� �����
   else
      { $str=$Area;
      	settype($str, "integer");
      	settype($str, "string");
        if ($str!=$Area) $Error=$Error.'�������� ���� "���������� �������" �� �������� ����� ������<br>';
      }

if ( empty($Wokers) ) $Error=$Error.'�� ��������� ���� "����������� �����������"<br>';
   else
      { $str=$Wokers;
      	settype($str, "integer");
      	settype($str, "string");
        if ($str!=$Wokers) $Error=$Error.'�������� ���� "����������� �����������" �� �������� ����� ������<br>';
      }

if ( empty($Production) ) $Error=$Error.'�� ��������� ���� "������������ ���������"<br>';
   else if ( strlen($Production)>500 ) $Error=$Error.'�������� ���� "������������ ���������" ��������� 500 ��������<br>';

// ���� ����� �� ������, �� ��������, ��� �� � ���� ����������� ������
if ( empty($Error) )
   { $new_id_key = md5 ( trim($ShortName).trim($FullName).trim($Email).trim($WWW).trim($Object_ID).trim($Factory_ID).trim($Area).trim($Wokers).trim($Production) );
     $result=update_base($id_key, $new_id_key, $ShortName, $FullName, $Email, $WWW, $Object_ID, $Factory_ID, $Area, $Wokers, $Production);
     if ($result!=true) $Error="����� ������ � ���� ��� ����������!!!<br>";
   }

// ���� ���� �������� ������ ��� ���������� ����� ��� ��������� ������ � ����, �� ���������� �� ����� ��������� ����
if ( !empty($Error) )
{
$_SESSION['edit_form']=array();
$_SESSION['edit_form']['id_key']=$id_key;
$_SESSION['edit_form']['ShortName']=$ShortName;
$_SESSION['edit_form']['FullName']=$FullName;
$_SESSION['edit_form']['Email']=$Email;
$_SESSION['edit_form']['WWW']=$WWW;
$_SESSION['edit_form']['Object_ID']=$Object_ID;
$_SESSION['edit_form']['Factory_ID']=$Factory_ID;
$_SESSION['edit_form']['Area']=$Area;
$_SESSION['edit_form']['Wokers']=$Wokers;
$_SESSION['edit_form']['Production']=$Production;
$_SESSION['edit_form']['Error']=$Error;
header ("Location: edit_form.php");
die();
}

$id_key=$new_id_key;
$_SESSION['delete_form']['id_key']=$id_key;

// ����� ���������� �������� � ����
$result=find_by_key ($id_key);
if (mssql_num_rows($result)!=1) { echo "<h2>����������� ������!!!</h2>"; die; }

echo "<h1>��������� ���������!!!</h1>";

// �������� ������ �� ���� � ������ $data.
for ($data=array(); $row=mssql_fetch_assoc($result); $data[]=$row);

// ����� �������� �� ����� � ���� �������
view ( trim($data[0]['shortname']), trim($data[0]['fullname']), trim($data[0]['email']), trim($data[0]['web']), trim($data[0]['object_value']),
trim($data[0]['factory_value']), $data[0]['area'], $data[0]['wokers'], trim($data[0]['production']) );

echo '<br>';
echo '<table width="550" border="0">';
echo '<tr>';
echo '   <td><form action="main.php"><input type=submit value="�� �������"></form></td>';
echo '   <td><form action="edit_form.php"><input type=submit value="��������"></form></td>';
echo '   <td><form action="delete_script.php"><input type=hidden name="id_key" value="'.$data[0]['id_key'].'"><input type=submit value="�������"></form></td>';
echo '   <td><form action="print_form.php" target="_blank"><input type=hidden name="id_key" value="'.$data[0]['id_key'].'"><input type=submit value="������ ��� ������"></form></td>';
echo '   <td><form action="email_form.php"><input type=hidden name="id_key" value="'.$data[0]['id_key'].'"><input type=submit value="��������� �� e-mail"></form></td>';
echo '</tr>';
echo '</table>';

// ������� ������ ��� �������� �� ����� edit_form.php
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