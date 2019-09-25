
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
$Act_title = $startDate = $endDate = $Act_type =  $location = $organized_by = $resource= $coordinated= $role_of_faculty=$time_activities=$status_act=$sponsors=$sponsor_details=$approval_details="";
$flag= 1;
$success = 0;
$no_of_participants=0;
$no_hours=0;
		$fid = $_SESSION['Fac_ID'];
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
    
	$Act_title_array = $_POST['Act_title'];
	$Act_type_array = $_POST['Act_type'];
	$organized_by_array = $_POST['organized_by'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$location_array = $_POST['location'];
	$resouce_array = $_POST['resource'];
	$coordinated_array = $_POST['coordinated'];
    $role_of_faculty_array=$_POST['role_of_faculty'];
    $time_activities_array=$_POST['time_activities'];
    $no_of_participants_array=$_POST['no_of_participants'];
    $no_hours_array=$_POST['no_hours'];
    $status_act_array=$_POST['status_act'];
    $sponsors_array=$_POST['sponsors'];
    $sponsor_details_array=$_POST['sponsor_details'];
    $approval_details_array=$_POST['approval_details'];

	

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
$resource = mysqli_real_escape_string($conn,$resouce_array[$i]);
$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$location = mysqli_real_escape_string($conn,$location_array[$i]);
$coordinated = mysqli_real_escape_string($conn,$coordinated_array[$i]);
$role_of_faculty = mysqli_real_escape_string($conn,$role_of_faculty_array[$i]);
$time_activities = mysqli_real_escape_string($conn,$time_activities_array[$i]);
$no_of_participants = mysqli_real_escape_string($conn,$no_of_participants_array[$i]);
$no_hours = mysqli_real_escape_string($conn,$no_hours_array[$i]);
$status_act = mysqli_real_escape_string($conn,$status_act_array[$i]);
$sponsors = mysqli_real_escape_string($conn,$sponsors_array[$i]);
$sponsor_details = mysqli_real_escape_string($conn,$sponsor_details_array[$i]);
$approval_details = mysqli_real_escape_string($conn,$approval_details_array[$i]);

 
 
        $Act_title=validateFormData($Act_title);
        $Act_title = "'".$Act_title."'";
		
        $organized_by=validateFormData($organized_by);
        $organized_by = "'".$organized_by."'";
		
        $resource=validateFormData($resource);
        $resource = "'".$resource."'";
		
        $Act_type=validateFormData($Act_type);
        $Act_type = "'".$Act_type."'";
		
		if ($_POST['startDate'] > $_POST['endDate'])		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
		
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		
        $location=validateFormData($location);
        $location = "'".$location."'";
   
        $role_of_faculty=validateFormData($role_of_faculty);
        $role_of_faculty = "'".$role_of_faculty."'";
    
        $time_activities=validateFormData($time_activities);
        $time_activities = "'".$time_activities."'";
        
        $no_of_participants=validateFormData($no_of_participants);
        $no_of_participants = "'".$no_of_participants."'";
        
        $no_hours=validateFormData($no_hours);
        $no_hours = "'".$no_hours."'";
        
        $status_act=validateFormData($status_act);
        $status_act = "'".$status_act."'";
      
        $sponsors=validateFormData($sponsors);
        $sponsors = "'".$sponsors."'";
     
        $sponsor_details=validateFormData($sponsor_details);
        $sponsor_details = "'".$sponsor_details."'";
        $flag=1;
    
        $approval_details=validateFormData($approval_details);
        $approval_details = "'".$approval_details."'";
    
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['resource']))
$resource = str_replace($replace_str, "", $resource);
else
	$resource  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['role_of_faculty']))
$role_of_faculty = str_replace($replace_str, "", $role_of_faculty);
else
	$role_of_faculty  = '';


$replace_str = array('"', "'",'' ,'');
if(isset($_POST['organized_by']))
{
$organized_by = str_replace($replace_str, "", $organized_by);
$organized_by = str_replace("rn",'', $organized_by);

}
else
	$organized_by  = '';
	 
	 
	
	  //checking if there was an error or not
         $query = "SELECT Fac_ID from facultydetails where F_NAME= $fname";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

if($flag!=0)
		{
        $sql="INSERT INTO organised(Fac_ID,Act_title,Act_type,Organized_by,Resource,Date_from,Date_to, Location,Coordinated_by,Role_Of_Faculty,Time_Activities,No_Of_Participants,Equivalent_Duration,Status_Of_Activity,Sponsorship,Sponsor_Details,Approval_Details) VALUES ('$author',$Act_title,$Act_type,'$organized_by','$resource',$startDate,$endDate,$location,'$coordinated','$role_of_faculty',$time_activities,$no_of_participants,$no_hours,$status_act,$sponsors,$sponsor_details,$approval_details)";
        //$sql="INSERT INTO organised(Fac_ID,Act_title,Act_type) VALUES ('$author',$Act_title,$Act_type)";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			header("location:2_dashboard_organised_hod.php?alert=success");

					


			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			/*if($success == 1 && $fdc == 'yes')
			{
				$sql="INSERT INTO fdc_organised(Fac_ID,Act_title) VALUES ('$author','$Act_title')";
				$result = mysqli_query($conn,$sql);
				
			}*/
			
			

		}
				
				
		
				
		
 
}//end of for
			/*if($success == 1)	
			{
				$query = "SELECT * FROM faculty where Fac_ID = $author and FDC_Y_N = 'yes' ;";
				$result = mysqli_query($conn,$query);
				 if(mysqli_num_rows($result)>0 )
				 {
 					header("location:2_dashboard_organised_hod.php?alert=success");

				 }
				 else
  					header("location:2_dashboard_organised_hod.php?alert=success");

			}*/
	

			        
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
          
		  <div class="box box-primary" >
                <div class="box-header with-border" >
				<div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>STTP/Workshop/FDP Organised Activities Form</b></h3>
					<br>
					<b><a href="menu.php?menu=3 " style="font-size:15px;">STTP/Workshop/FDP Attended/Organised Menu</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_organised.php" style="font-size:15px;">&nbsp;No. of Papers</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Organised Activity</a></b>	
				</div>				 
                </div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=3 "> <u>Back to STTP/Workshop/FDP Attended/Organised Menu</u></a> -->
				</div>
                <br>
                <!-- form start -->
	
			
	<?php
			
					for($k=1; $k<=$_SESSION['count'] ; $k++)
					{

				?>
            <p>   ***********************************************************************
                <h4 style="padding-left: 30px; padding-bottom: 10px; color: #2961bc"><strong><em>FORM <?php echo $k ?> :</em></strong></h4>
			<form role="form" method="POST" class="row" action ="" style= "margin:10px;align:center" >
					
	<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
				
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['resource']))
$resource = str_replace($replace_str, "", $resource);
else
	$resource  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['role_of_faculty']))
$role_of_faculty = str_replace($replace_str, "", $role_of_faculty);
else
	$role_of_faculty  = '';


$replace_str = array('"', "'",'' ,'');
if(isset($_POST['organized_by']))
{
$organized_by = str_replace($replace_str, "", $organized_by);
$organized_by = str_replace("rn",'', $organized_by);

}
else
	$organized_by  = '';				
				
			?>			
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
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="Act_title[]">-->
					  <input  type="text" class="form-control input-lg"  name="Act_title[]" id="paper-title" <?php if($Act_title != '') echo "value = $Act_title"; ?>  required>
                     </div>
                     <div class="form-group col-md-6">
					 
                         <label for="paper-type">Activity Type *</label>
						 <input  type="text" class="form-control input-lg"  name="Act_type[]" value="<?php echo $act_name; ?>" readonly id='paper-type'>
                     
					 
                     </div>
                     
					 <div class="form-group col-md-6">
                         <label for="organized_by">Organized by :(With Brief Address) *</label>
                         <br><br>
                         <textarea required class="form-control input-lg" id="organized_by" name="organized_by[]" rows="4" id="organized_by">
						 <?php if($organized_by!='') echo $organized_by; ?>
						 </textarea>
                     </div>
					 
					 <div class="form-group col-md-6">
                         <label for="resource">Resource person (Designation & Organization Study) *</label>
                         <textarea required class="form-control input-lg" id="resource" name="resource[]" rows="4">
						  <?php if($resource!='') echo $resource; ?>
						 </textarea>
                     </div>
					 
					 <div class="form-group col-md-6">
                         <label for="coordinated">Co-ordinated by *</label>
                         <input <?php if(isset($_POST['coordinated'])) echo "value = $coordinated"; ?> required type="text" class="form-control input-lg" id="coordinated" name="coordinated[]">
                     </div>
					 
                     <div class="form-group col-md-8">
                         <label for="start-date">Start Date and Time *</label>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="datetime-local" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-8">
                         <label for="end-date">End Date and Time *</label>
                         <input <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="datetime-local" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Location *</label>
                         <input <?php if(isset($_POST['location'])) echo "value = $location"; ?> required type="text" class="form-control input-lg" id="location" name="location[]">
                     </div>

                        <div class="form-group col-md-6">
                        <label for="role_of_faculty">Role of Faculty: *</label>
                        <br>
                        <textarea id="role_of_faculty" class="form-control input-lg" name="role_of_faculty[]" required>
						 <?php if($role_of_faculty!='') echo $role_of_faculty; ?>
						</textarea></div>

                        <div class="form-group col-md-6">
                        <label id="time_activities">Full-Time/Part-Time: *</label>
                        <select required name="time_activities[]" id="time_activites" class="form-control input-lg">
                            <option  value="" disabled selected>Select your option:</option>
                            <option <?php if(isset($_POST['time_activities'])) if($time_activities == "full-time") echo "selected = 'selected'" ?> name="full-time" value="full-time">Full-Time</option>
                            <option <?php if(isset($_POST['time_activities'])) if($time_activities == "part-time") echo "selected = 'selected'" ?> name="part-time" value="part-time">Part-Time</option>
                        </select></div>

                        <div class="form-group col-md-6">
                        <label for="no_of_participants">Number of participants: *</label>
                        <input <?php if(isset($_POST['no_of_participants'])) echo "value = $no_of_participants"; ?> type="number" name="no_of_participants[]" id="no_of_participants" class="form-control input-lg" min="1" required></div>

                        <div class="form-group col-md-6">
                        <label for="no_hours">Equivalent duration(In hours) *</label>
                        <input <?php if(isset($_POST['no_hours'])) echo "value = $no_hours"; ?> type="text" name="no_hours[]" id="no_hours" placeholder="Enter the total hours" class="form-control input-lg" required></div>

                        <div class="form-group col-md-6">
                        <label for="status_act">Status of Activity: *</label>
                        <select required name="status_act[]" id="status_act" class="form-control input-lg">
                            <option <?php if(isset($_POST['status_act'])) if($status_act == "local") echo "selected = 'selected'" ?> name="local" value="local">Local</option>
                            <option <?php if(isset($_POST['status_act'])) if($status_act == "state") echo "selected = 'selected'" ?> name="state" value="state">State</option>
                            <option <?php if(isset($_POST['status_act'])) if($status_act == "national") echo "selected = 'selected'" ?> name="national" value="national">National</option>
                            <option <?php if(isset($_POST['status_act'])) if($status_act == "international") echo "selected = 'selected'" ?> name="international" value="international">InterNational</option>
                        </select>
                        </div>

						 <div class="form-group col-md-6">
                         <label for="sponsors">Sponsored/Not-sponsored: *</label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg sponsors" id="sponsors" name="sponsors[]">
                             <option <?php if(isset($_POST['sponsors'])) if($sponsors == "not-sponsored") echo "selected = 'selected'" ?> value ="not-sponsored">Not sponsored</option>
                             <option <?php if(isset($_POST['sponsors'])) if($sponsors == "sponsored") echo "selected = 'selected'" ?> value ="sponsored">Sponsored</option>
                         </select>
                     </div>
						
                      <div class="form-group col-md-6">
                     
                        </div> 

                    
                            <div class="form-group col-md-6" style="display:none">
                                <label for="sponsor_details">Sponsor Details: </label>
                                <br>
                                <textarea name="sponsor_details[]" id="sponsor_details" class="form-control input-lg">
												 <?php if($sponsor_details!='') echo $sponsor_details; ?>
		
								</textarea>
                            </div>
                        

                        <div class="form-group col-md-6" style="display:none">
                        <label for="approval_details">Approval Details: *</label>
                        <br>
                        <textarea name="approval_details[]" id="approval_details" class="form-control input-lg">
							<?php if($approval_details!='') echo $approval_details; ?>

						</textarea>
                        </div>
                        
					   <script>
					 
					 $('.sponsors').each(function(){
						 $('.sponsors').on('change',myfunction);
					 });
					 
					 
					 
						function myfunction(){
						var x = this.value;
					
						if(x=='sponsored')
						{
				
							//document.getElementById("demo").innerHTML = "You selected:" +x;
							$(this).parent().next().next()[0].style.display = "block";
							$(this).parent().next().next().next()[0].style.display = "block";
						}
						else
						{
								$(this).parent().next().next()[0].style.display = "none";
							$(this).parent().next().next().next()[0].style.display = "none";
						}
						}
					 </script>		
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                        <button name="add"  type="submit" class="btn btn-success btn-lg">Submit</button>
                         <a href="actcount_organised.php" type="button" class="btn btn-warning btn-lg pull-right">Cancel</a>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   