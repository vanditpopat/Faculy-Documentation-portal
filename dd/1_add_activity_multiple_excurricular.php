
<?php
ob_start();
session_start();

$_SESSION['currentTab']="ex";

 include_once('head.php'); 
 include_once('header.php'); 

if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
	    include_once('sidebar_hod.php');
  }
  else
	  include_once('sidebar.php');

//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");



//setting error variables
$nameError="";
$emailError="";
$activity_name = $startDate = $endDate = $purpose_of_activity  = $organized_by = "";
$flag= 0;
$success = 0;
		$fid = $_SESSION['Fac_ID'];
	$count1 = $_SESSION['count'];
	
        $faculty_name= $_SESSION['loggedInUser'];

$query="SELECT * from ex_curricular where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    
	$activityname_array = $_POST['activityname'];
	$organizedby_array=$_POST['organizedby'];
    $purposeofactivity_array=$_POST['purposeofactivity'];
    $startDate_array = $_POST['startdate'];
	$endDate_array = $_POST['enddate'];
	

	


	/*	$min_no_array=$_POST['min_no'];
		$serial_no_array=$_POST['serial_no'];
				$period_array = $_POST['period'];

		$od_approv_array=$_POST['od_approv'];
		$od_avail_array=$_POST['od_avail'];
		$fee_sac_array=$_POST['fee_sac'];
		$fee_avail_array=$_POST['fee_avail'];*/
	
	
    //check for any blank input which are required
    		
for($i=0; $i<count($activityname_array);$i++)
{
$activityname = mysqli_real_escape_string($conn,$activityname_array[$i]);
$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organizedby = mysqli_real_escape_string($conn,$organizedby_array[$i]);
$purposeofactivity = mysqli_real_escape_string($conn,$purposeofactivity_array[$i]);


if(empty($_POST['activityname[]'])){
        $nameError="Please enter a Activity Name";
		$flag = 0;
    }
    else{
        $activityname=validateFormData($activityname);
        $activityname = "'".$activityname."'";
		$flag=1;
    }
if(empty($_POST['$organizedby[]'])){
        $nameError="Please enter the organizer name";
		$flag = 0;
    }
    else{
        $organizedby=validateFormData($organizedby);
        $organizedby = "'".$organizedby."'";
		$flag=1;
    }
if(empty($_POST['$purposeofactivity[]'])){
        $nameError="Please enter the purpose";
		$flag = 0;
    }
    else{
        $purposeofactivity=validateFormData($purposeofactivity);
        $purposeofactivity = "'".$purposeofactivity."'";
		$flag=1;
    }
	
    	
    if(empty($_POST['startdate[]'])){
        $nameError="Please enter a start date";
		$flag = 0;
    }
    else{
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		$flag=1;
    }
	
	 if(empty($_POST['enddate[]'])){
        $nameError="Please enter end date";
		$flag = 0;
    }
    else{
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		$flag=1;
    }
	 
		
	
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }


        $sql="INSERT INTO ex_curricular(Fac_ID,activity_name,Date_from,Date_to,purpose_of_activity,organized_by) VALUES ('$author','$activityname','$startDate','$endDate','$purposeofactivity','$organizedby')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			//header("location:2_dashboard_cocurricular.php?alert=success");

					


			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			
			

			
				
				
		
				
		
 
}//end of for
			if($success == 1)	
			{
				/*$query = "SELECT * FROM faculty where Fac_ID = $author and FDC_Y_N = 'yes' ;";
				$result = mysqli_query($conn,$query);
				 if(mysqli_num_rows($result)>0 ){
 					header("location:5_fdc_dashboard_cocurricular.php?alert=success");

				 }
				 else*/
  					header("location:2_dashboard_excurricular.php?alert=success");

			}

			        


}


//close the connection
mysqli_close($conn);
}
?>






 



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
					<h3 class="box-title"><b>Extra-Curricular Activity Activities Form</b></h3>
					<br>
					<b><a href="menu.php?menu=7 " style="font-size:15px;">Extra-Curricular Activity Activities Menu</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_excurricular.php" style="font-size:15px;">&nbsp;No. of Extra-Curricular Activities</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Extra-Curricular Activity</a></b>	
				</div>
                </div><!-- /.box-header -->
                <!-- form start -->
	
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                     </div><br/> <br/> <br/> <br/> 
	<?php
			
					for($k=0; $k<$_SESSION['count'] ; $k++)
					{

				?>
            <p>   ******************************************************************************************
									            <h4 style="padding-left: 30px; padding-bottom: 10px;color: #2961bc"><strong><em>FORM <?php echo $k+1; ?> :</em></strong></h4>

			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
					
				
                     <div class="form-group col-md-6">
                         <label for="activity-name">Activity Name *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input  type="text" class="form-control input-lg"  name="activityname[]">
                     </div>
                     
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input required type="date" class="form-control input-lg" id="start-date" name="startdate[]"
                         placeholder="03:10:10">
                     </div><br><br>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input required type="date" class="form-control input-lg" id="end-date" name="enddate[]"
                         placeholder="03:10:10">
                     </div><br><br>
                    
                    <div class="form-group col-md-6">
                         <label for="organized-by">Organized By *</label>
                         <input  type="text" class="form-control input-lg"  name="organizedby[]">
                     </div><br><br>
					 
					 <div class="form-group col-md-6">
                         <label for="purpose-of-activity">Purpose of Activity *</label>
                         <input  type="text" class="form-control input-lg"  name="purposeofactivity[]">
                     </div><br>
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         

                         <button name="add"  type="submit" class="btn btn-success btn-lg">Submit</button>
                         <a href="2_dashboard_excurricular.php" type="button" class="btn btn-warning pull-right btn-lg">Cancel</a>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   