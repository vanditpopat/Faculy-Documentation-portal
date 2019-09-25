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
//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

$flag = $_GET['flag'];

    $id = $_SESSION['id'];

	if($flag == 1)
	{

	$sql = "delete from online_course_organised WHERE OC_O_ID = $id";
	$file_name = $id. "_organised.jpg";
	$file_c = "/xampp/htdocs/dd/certificates/$file_name";
	$file_r = "/xampp/htdocs/dd/reports/$file_name";
	$file_a = "/xampp/htdocs/dd/Attendance/$file_name";
	if (!unlink($file_c)){
		echo ("Error deleting $file_c");
	  }else{
		echo ("DELETED");
	}
	if (!unlink($file_r)){
		echo ("Error deleting $file_r");
	  }else{
		echo ("DELETED");
	}
		
	if (!unlink($file_a)){
		echo ("Error deleting $file_a");
	  }else{
		echo ("DELETED");
	}

			if ($conn->query($sql) === TRUE) {
				if ($_SESSION['username'] == 'hodextc@somaiya.edu')
				{
					header("location:2_dashboard_hod_online_organised.php?alert=delete");
				}
				else
					header("location:2_dashboard_online_organised.php?alert=delete");

			} 
			else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	
	}
	else if($flag == 2)
	{
		if ($_SESSION['username'] == 'hodextc@somaiya.edu')
				{
					header("location:2_dashboard_hod_online_organised.php");
				}
				else
					header("location:2_dashboard_online_organised.php");
	}



?>