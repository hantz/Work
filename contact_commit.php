<?php
require_once "controller.php";
use InteractionIN\Contact as Contact;
session_start(); 

if(!$_POST) header("Location: contact.php");
if(!isset($_POST['token']))	$_POST['token']="";

if ( $_POST['token'] == $_SESSION['token'] ) 
{ 
	$token_age = time() - $_SESSION['token_time']; 
	if ( $token_age <= 120 ) 
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
