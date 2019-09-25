<?php
ob_start();
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "paper";

include_once('head.php'); 
 include_once('header.php'); 

include_once("includes/connection.php");
if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
	    include_once('sidebar_hod.php');

  }
  else
	  include_once('sidebar.php');

//include custom functions files 
include_once("includes/functions.php");

    $id = $_POST['id'];

$sql = "DELETE FROM invitedlec WHERE  p_id = $id";;

	if ($conn->query($sql) === TRUE) 
			{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:view_invited_hod_lec.php");

					}
					else
					{
						header("location:view_invited_lec.php");

					}
				
				
				}	
					

$conn->close();
?>




