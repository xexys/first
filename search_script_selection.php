<?php
session_start();
require_once "functions.php";

Authorisation();

$id_key=$_REQUEST['id_key'];
$result=find_by_key ($id_key);
for ($data=array(); $row=mssql_fetch_assoc($result); $data[]=$row);

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
header ("Location: edit_form.php");

?>