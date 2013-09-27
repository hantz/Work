<?php
require_once "controller.php";
use InteractionIN\Contact as Contact;
session_start(); 

if(!$_POST) header("Location: contact.php");
if(!isset($_POST['csrf_token']))	$_POST['csrf_token']="";

if ( $_POST['csrf_token'] == $_SESSION['csrf_token'] ) 
{ 
	$csrf_token_age = time() - $_SESSION['csrf_token_time']; 
	if ( $csrf_token_age <= 180 ) 
	{
		$prepare = new Contact($_POST);
		$result = $prepare->validateInput(); 

		if(!$result) echo "Uuups ! Something wrong was happened. ";
		else echo $result;
	}
	else exit;
}
else exit;

?>
