<?php
echo "flag  : ".$_GET['flag']."<br>";

?>
<?php
ob_start();

session_start();


//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

$flag = $_GET['flag'];
$id = $_SESSION['id'];

	if($flag == 1)
	{

	$sql = "delete from organised WHERE A_ID = $id";

			if ($conn->query($sql) === TRUE) {
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
				{
					header("location:2_dashboard_organised_hod.php?alert=delete");
				}
				else
					header("location:2_dashboard_organised.php?alert=delete");

			} 
			else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	
	}
	else if($flag == 2)
	{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
				{
					header("location:2_dashboard_organised_hod.php");
				}	
				else
					header("location:2_dashboard_organised.php");
	}



?>