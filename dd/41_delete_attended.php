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
			$del = 0;
			$sql = "delete from attended WHERE A_ID = $id";
			
			if ($conn->query($sql) === TRUE) {
				$sql1 = "delete from fdc_attended WHERE A_ID = $id";
				if ($conn->query($sql1) === TRUE)
				{					
					$del = 1;
				}
			} 
			
	
			if($del == 1)
			{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
				{
					header("location:2_dashboard_attend_hod.php?alert=delete");
				}
				else
					header("location:2_dashboard_attend.php?alert=delete");
			}
			else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	
	}
	else if($flag == 2)
	{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
				{
					header("location:2_dashboard_attend_hod.php");
				}
				else
					header("location:2_dashboard_attend.php");
	}



?>