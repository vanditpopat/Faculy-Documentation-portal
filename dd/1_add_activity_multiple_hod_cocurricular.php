
<?php
ob_start();
session_start();

include_once('head.php'); 
 include_once('header.php'); 
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
/*if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}*/
$_SESSION['currentTab']="co";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
 

if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
	    include_once('sidebar_hod.php');
  }
  else
	  include_once('sidebar.php');

 


$server="localhost";
$username="root";
$password="";
$db="department";
$conn = mysqli_connect($server,$username,$password,$db);
if(!$conn){
    die("Connection failed".mysqli_connect_error());
}
?>
<?php
//setting error variables
$nameError="";
$emailError="";
$activity_name = $startDate = $endDate = $purpose_of_activity  = $organized_by = "";
$flag= 1;
$success = 0;
		//$fid = $_SESSION['Fac_ID'];
	$count1 = $_SESSION['count'];
	
        $faculty_name= $_SESSION['loggedInUser'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    $activityname_array = $_POST['activityname'];
	$organizedby_array=$_POST['organizedby'];
    $purposeofactivity_array=$_POST['purposeofactivity'];
    $startDate_array = $_POST['startdate'];
	$endDate_array = $_POST['enddate'];
	$facid_array = $_POST['fname'];
	
for($i=0; $i<$count1;$i++)
{
$activityname = mysqli_real_escape_string($conn,$activityname_array[$i]);
$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organizedby = mysqli_real_escape_string($conn,$organizedby_array[$i]);
$purposeofactivity = mysqli_real_escape_string($conn,$purposeofactivity_array[$i]);
$facid = mysqli_real_escape_string($conn,$facid_array[$i]);


        $activityname=validateFormData($activityname);
        $activityname = "'".$activityname."'";
		
        $organizedby=validateFormData($organizedby);
        $organizedby = "'".$organizedby."'";
		
        $purposeofactivity=validateFormData($purposeofactivity);
        $purposeofactivity = "'".$purposeofactivity."'";
	
    	

        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
			if ($startDate > $endDate)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
		
		
		$query = "SELECT Fac_ID from facultydetails where F_NAME = '$facid'";
        $result=mysqli_query($conn,$query);
		//echo "<script>alert('$result')</script>";
       if($result){
		   //echo "<script>alert('$author')</script>";
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

		if($flag!=0)
		{
		
        $sql="INSERT INTO co_curricular(Fac_ID,activity_name,Date_from,Date_to,purpose_of_activity,organized_by) VALUES ('$author',$activityname,$startDate,$endDate,$purposeofactivity,$organizedby)";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			//header("location:2_dashboard_cocurricular.php?alert=success");

					


			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			
		}

			
				
				
		
				
		
 
}//end of for
			if($success == 1)	
			{
			
				 if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					 header("location:2_dashboard_hod_cocurricular.php?alert=success");
				 else
  					header("location:2_dashboard_cocurricular.php?alert=success");

			}

			        


}


//close the connection
mysqli_close($conn);
}
?>

<style>
.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	padding:0px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}
.colour
{
	color:#ff0000;
}
.demo {
	width: 120px;
}
</style>


<div class="content-wrapper">
    
    <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
			  <br/><br/><br/>
        
		<div class="box box-primary">
                <div class="box-header with-border">
				   <div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Co-Curricular Activity Activities Form</b></h3>
					<br>
					<b><a href="menu.php?menu=6 " style="font-size:15px;">Co-Curricular Activity Activities Menu</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_cocurricular.php" style="font-size:15px;">&nbsp;No. of Co-Curricular Activities</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Co-Curricular Activity</a></b>	
				</div>
                </div><!-- /.box-header -->
                <!-- form start -->
	
				
	<?php
			
					for($k=0; $k<$_SESSION['count'] ; $k++)
					{

				?>
           <p>   ******************************************************************************************
		<h4 style="padding-left: 30px; padding-bottom: 10px;color: #2961bc"><strong><em>FORM <?php echo $k+1; ?> :</em></strong></h4>

			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
	<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>							
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                       <?php
					include("includes/connection.php");

					$query="SELECT * from facultydetails";
					$result=mysqli_query($conn,$query);
					echo "<select name='fname[]' id='s1' class='form-control input-lg'>";
					while ($row =mysqli_fetch_assoc($result)) {
						echo "<option value='" . $row['F_NAME'] ."'>" . $row['F_NAME'] ."</option>";
					}
					echo "</select>";
					?>
						 
						 
                     </div>
                     <div class="form-group col-md-6">
                         <label for="activity-name">Activity Name *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input required <?php if(isset($_POST['activityname'])) echo "value = $activityname"; ?>  type="text" class="form-control input-lg"  name="activityname[]" >
                     </div>
                     
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input <?php if(isset($_POST['startdate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startdate[]"
                         placeholder="03:10:10">
                     </div><br><br>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input <?php if(isset($_POST['enddate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="enddate[]"
                         placeholder="03:10:10">
                     </div><br><br>
                    
                    <div class="form-group col-md-6">
                         <label for="organized-by">Organized By *</label>
                         <input required <?php if(isset($_POST['organizedby'])) echo "value = $organizedby"; ?> type="text" class="form-control input-lg"  name="organizedby[]">
                     </div><br><br>
					 
					 <div class="form-group col-md-6">
                         <label for="purpose-of-activity">Purpose of Activity *</label>
                         <input required <?php if(isset($_POST['purposeofactivity'])) echo "value = $purposeofactivity"; ?> type="text" class="form-control input-lg"  name="purposeofactivity[]">
                     </div><br>
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         <a href="2_dashboard_hod_cocurricular.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="add"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   