
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
$_SESSION['currentTab'] = "sttp";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");



//setting error variables
$nameError="";
$emailError="";
$Act_title = $startDate = $endDate = $Act_type =  $location = $organized_by = $status_activities=$awards="";
$flag= 0;
$success = 0;
$no_of_hours=0;
		$fid = $_SESSION['Fac_ID'];
		$act = $_SESSION['act_name'];
		$count1 = $_SESSION['count'];
		$act_name = $_SESSION['act_name'];
        $faculty_name= $_SESSION['loggedInUser'];

$query="SELECT * from faculty where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    $fname_array = $_POST['fname'];
	$Act_title_array = $_POST['Act_Title'];
	$Act_type_array = $_POST['Act_Type'];
	$organized_by_array = $_POST['organized_by'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$location_array = $_POST['location'];
	$applicablefdc_array = $_POST['applicablefdc'];
	
	$fdc_array = $_POST['fdc'];
	$status_activities_array=$_POST['status_activities'];
    $no_of_hours_array=$_POST['no_of_hours'];
    $awards_array=$_POST['awards'];
	

	/*	$min_no_array=$_POST['min_no'];
		$serial_no_array=$_POST['serial_no'];
				$period_array = $_POST['period'];

		$od_approv_array=$_POST['od_approv'];
		$od_avail_array=$_POST['od_avail'];
		$fee_sac_array=$_POST['fee_sac'];
		$fee_avail_array=$_POST['fee_avail'];*/
	
	
    //check for any blank input which are required
    		
for($i=0; $i<count($Act_title_array);$i++)
{
	$fname = mysqli_real_escape_string($conn,$fname_array[$i]);
	$Act_title = mysqli_real_escape_string($conn,$Act_title_array[$i]);
	$Act_type = mysqli_real_escape_string($conn,$Act_type_array[$i]);
	$organized_by = mysqli_real_escape_string($conn,$organized_by_array[$i]);
	$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
	$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
	$location = mysqli_real_escape_string($conn,$location_array[$i]);
    
$applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

	$fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
	$_SESSION['fdc'] = $fdc;

	$status_activities=mysqli_real_escape_string($conn,$status_activities_array[$i]);
	$no_of_hours=mysqli_real_escape_string($conn,$no_of_hours_array[$i]);
    $awards=mysqli_real_escape_string($conn,$awards_array[$i]);
 
  if(empty($_POST['Act_title[]'])){
        $nameError="Please enter a Title";
		$flag = 0;
    }
    else{
        $Act_title=validateFormData($Act_title);
        $Act_title = "'".$Act_title."'";
		$flag=1;
    }
	if(empty($_POST['organized_by[]'])){
        $nameError="Please enter a Title";
		$flag = 0;
    }
    else{
        $organized_by=validateFormData($organized_by);
        $organized_by = "'".$organized_by."'";
		$flag=1;
    }
	if(empty($_POST['Act_type[]'])){
        $nameError="Please enter a Type";
		$flag = 0;
    }
    else{
        $Act_type=validateFormData($Act_type);
        $Act_type = "'".$Act_type."'";
		$flag=1;
    }
		
		
    if(empty($_POST['startDate[]'])){
        $nameError="Please enter a start date";
		$flag = 0;
    }
    else{
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		$flag=1;
    }
	
	 if(empty($_POST['endDate[]'])){
        $nameError="Please enter end date";
		$flag = 0;
    }
    else{
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		$flag=1;
    }
	 if(empty($_POST['location[]'])){
        $nameError="Please enter location";
    }
    else{
        $location=validateFormData($location);
        $location = "'".$location."'";
    }
    if(empty($_POST['status_activities[]'])){
        $nameError="Please enter status activity";
        $flag=0;
    }
    else{
        $status_activities=validateFormData($status_activities);
        $status_activities = "'".$status_activities."'";
        $flag=1;
    }
    if(empty($_POST['no_of_hours[]'])){
        $nameError="Please enter number of hours";
        $flag=0;
    }
    else{
        $no_of_hours=validateFormData($no_of_hours);
        $no_of_hours = "'".$no_of_hours."'";
        $flag=1;
    }
    if(empty($_POST['awards[]'])){
        $nameError="Please enter award details";
        $flag=0;
    }
    else{
        $awards=validateFormData($awards);
        $awards = "'".$awards."'";
        $flag=1;
    }
	
	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
		$fdc = "'".$fdc."'";		}
	else if($applicablefdc == 'No')
	{
		$fdc = 'Not applicable';
	}
	
    
	  	$fname=validateFormData($fname);
    	$fname = "'".$fname."'";
	
	
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where F_NAME= $fname";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }


        $sql="INSERT INTO attended(Fac_ID,Act_title,Act_type,Organized_by,Date_from,Date_to, Location,FDC_Y_N,Status_Of_Activity,Equivalent_Duration,Awards) VALUES ('$author','$Act_title','$Act_type','$organized_by','$startDate','$endDate','$location','$fdc','$status_activities','$no_of_hours','$awards')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			header("location:2_dashboard_hod.php?alert=success");

					


			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
		
		
 
}//end of for
			if($success == 1)	
			{
				$query = "SELECT * FROM faculty where Fac_ID = $author and FDC_Y_N = 'yes' ;";
				$result = mysqli_query($conn,$query);
				 if(mysqli_num_rows($result)>0 )
				 {
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
				{
					header("location:5_fdc_dashboard_attend_hod.php?alert=delete");
				}
				else
 					header("location:5_fdc_dashboard_attend.php?alert=success");

				 }
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
				{
					header("location:2_dashboard_attend_hod.php?alert=success");
				}
				 else
  					header("location:2_dashboard_attend.php?alert=success");

			}

			        
}

}


//close the connection
mysqli_close($conn);
?>





<?php 
if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
  {
	    include_once('sidebar_hod.php');

  }
  else
	  include_once('sidebar.php');

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
					<h3 class="box-title"><b>STTP/Workshop/FDP Attended Activities Form</b></h3>
					<br>
					<b><a href="menu.php?menu=3 " style="font-size:15px;">STTP/Workshop/FDP Attended/Organised Menu</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_attend.php" style="font-size:15px;">&nbsp;No. of Papers</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Attended Activity</a></b>	
				</div>
				          
                </div><!-- /.box-header -->
                <!-- form start -->
	
				<br>
	<?php
			
					for($k=1; $k<=$_SESSION['count'] ; $k++)
					{

				?>
				 <p>   *******************************************************************
                
            <h4 style="padding-left: 30px; padding-bottom: 10px;color: #2961bc"><strong><em>FORM <?php echo $k ?> :</em></strong></h4>
			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
                    <div class="form-group col-md-6">
                    <label for="fname">Faculty *</label>

					<?php
					include("includes/connection.php");
					
					$query="SELECT * from facultydetails";
					$result=mysqli_query($conn,$query);
					echo "<select name='fname[]' id='fname' class='form-control input-lg'>";
					while ($row =mysqli_fetch_assoc($result)) {
						echo "<option value='" . $row['F_NAME'] ."'>" . $row['F_NAME'] ."</option>";
					}
					echo "</select>";
					?>
			</div>
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input  type="text" class="form-control input-lg"  name="Act_Title[]" id="paper-title" required>
                     </div>
					  <div class="form-group col-md-6">
					 
                         <label for="paper-type">Activity Type *</label>
						 <input  type="text" class="form-control input-lg"  name="Act_Type[]" value="<?php echo $_SESSION['act_name']; ?>" readonly id="paper-type">
                     
					
                     </div>
					
                     
                      <div class="form-group col-md-6">
                         <label for="organized_by">Organized by :(With Brief Address) *</label>
                         <textarea required class="form-control input-lg" id="organized_by" name="organized_by[]" rows="2"></textarea>
                     </div>
					 
                     <div class="form-group col-md-8">
                         <label for="start-date">Start Date and Time *</label>
                         <input required type="datetime-local" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-8">
                         <label for="end-date">End Date and Time*</label>
                         <input required type="datetime-local" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Location *</label>
                         <input required type="text" class="form-control input-lg" id="location" name="location[]">
                     </div>
					<div class="form-group col-md-6">
                         <label for="fdc">Applied for FDC ? *</label>
                         <select required class="form-control input-lg" id="fdc" name="fdc[]">
                             <option value = "yes">Yes</option>
                             <option value = "no">No</option>
                         </select>
                     </div>

                     <br>
                        <div class="form-group col-md-6">
                        <label for="status_activities">Status of Activity ( Local / State / National / International ) *</label>
                        <select required name="status_activities[]" id="status_activities" class="form-control input-lg">
                            <option value="" disabled selected>Select your option:</option>
                            <option name="local" value="local">Local</option>
                            <option name="state" value="state">State</option>
                            <option name="national" value="national">National</option>
                            <option name="international" value="international">InterNational</option>
                        </select>
                        </div>

                        <div class="form-group col-md-6">
                        <label for="no_of_hours">Equivalent duration ( In hours ) *</label>
                        <br><br>
                        <input   class="form-control input-lg" type="text" name="no_of_hours[]" id="no_of_hours" placeholder="Hours" value = "0" required>
                        </div>

                        <div class="form-group col-md-6">
                        <label for="awards">Awards (If Any)</label>
                        <br>
                        <!-- <input class="form-control input-lg" type="text" name="awards[]" id="awards" value="No awards"> -->
                        <textarea name="awards[]" id="awards" class="form-control input-lg">No awards</textarea>
                        </div>
						
					 <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc[]">
                             <option value ="No">No</option>
                             <option value ="Yes">Yes</option>
                         </select>
                     </div>
					  <div class="form-group col-md-6" >
					  </div>
					 <div class="form-group col-md-6" style="display:none">
                         <label for="fdc">Applied for FDC ? </label><span class="colour"><b> *</b></span>
                         <select  class="form-control input-lg" id="fdc" name="fdc[]">
                             <option value = "yes">Yes</option>
                             <option value = "no">No</option>
                         </select>
                     </div>	
						 <script>
					 
						
					 
					  $('.applicable-fdc').each(function(){
						 $('.applicable-fdc').on('change',myfunction1);
					 });
					 
					 
					
						
						function myfunction1(){
						var x = this.value;
					
						if(x=='Yes')
						{
				
							$(this).parent().next().next()[0].style.display = "block";
						
						}
						else
						{
								$(this).parent().next().next()[0].style.display = "none";
						}
						}
						
					 </script>		
	
					 
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         <button name="add"  type="submit" class="btn btn-success btn-lg">Submit</button>
                         <a href="actcount_attend.php" type="button" class="btn btn-warning btn-lg pull-right">Cancel</a>

                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   