    
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
//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");


$_SESSION['currentTab']="Online";
//setting error variables
$nameError="";
$emailError="";
$faculty_role=$full_part_time=$no_of_part=$duration=$status=$sponsored=$name_of_sponsor=$is_approved=$course = $startDate = $endDate = $organised = $purpose = $target = "";
$flag= 1;
$success = 0;
		$fid = $_SESSION['Fac_ID'];
	$count1 = $_SESSION['count'];
	
        $faculty_name= $_SESSION['loggedInUser'];

$query="SELECT * from online_course_attended where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
	$type_of_course_array = $_POST['typeofcourse'];
    
	$course_array = $_POST['course'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$organised_array = $_POST['organised'];
  $purpose_array = $_POST['purpose'];
  $target_array = $_POST['target'];
  $faculty_role_array = $_POST['role'];
  $full_part_time_array = $_POST['type'];
  $no_of_part_array = $_POST['participants'];
  $duration_array = $_POST['duration'];
  $status_array = $_POST['status'];
  $sponsored_array = $_POST['sponsored'];
  
  if(isset($_POST['sponsor']))
	$name_of_sponsor_array = $_POST['sponsor'];
else
	$name_of_sponsor_array = 'NULL';
  
  if(isset($_POST['isApproved']))
	$is_approved_array = $_POST['isApproved'];
else
	$is_approved_array = 'NULL';
  
  


	
    //check for any blank input which are required
    		
for($i=0; $i<count($course_array);$i++)
{
$type_of_course = mysqli_real_escape_string($conn,$type_of_course_array[$i]);

$course = mysqli_real_escape_string($conn,$course_array[$i]);

$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organised = mysqli_real_escape_string($conn,$organised_array[$i]);
$purpose = mysqli_real_escape_string($conn,$purpose_array[$i]);
$target = mysqli_real_escape_string($conn,$target_array[$i]);
$faculty_role = mysqli_real_escape_string($conn,$faculty_role_array[$i]);
$full_part_time = mysqli_real_escape_string($conn,$full_part_time_array[$i]);
$no_of_part = mysqli_real_escape_string($conn,$no_of_part_array[$i]);
$duration = mysqli_real_escape_string($conn,$duration_array[$i]);
$status = mysqli_real_escape_string($conn,$status_array[$i]);
$sponsored = mysqli_real_escape_string($conn,$sponsored_array[$i]);
$name_of_sponsor = mysqli_real_escape_string($conn,$name_of_sponsor_array[$i]);
$is_approved = mysqli_real_escape_string($conn,$is_approved_array[$i]);

		$type_of_course=validateFormData($type_of_course);
        $type_of_course = "'".$type_of_course."'";
 
        $course=validateFormData($course);
        $course = "'".$course."'";
		
		$organised=validateFormData($organised);
        $organised = "'".$organised."'";
		
		$purpose=validateFormData($purpose);
        $purpose = "'".$purpose."'";
		
		$target=validateFormData($target);
        $target = "'".$target."'";
		
		$faculty_role=validateFormData($faculty_role);
        $faculty_role = "'".$faculty_role."'";
		
		$no_of_part=validateFormData($no_of_part);
        $no_of_part = "'".$no_of_part."'";
		
		$duration=validateFormData($duration);
        $duration = "'".$duration."'";
		
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		
        
    
		if ($startDate > $endDate)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
	
	  
	  //following are not required so we can directly take them as it is

		
	
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

 if($flag!=0)
	   {
        $sql="INSERT INTO online_course_organised(Fac_ID,type_of_course, Course_Name, Date_from, Date_to,Organised_by, Purpose, Target_Audience,faculty_role, full_part_time, no_of_part, duration, status, sponsored, name_of_sponsor, is_approved) VALUES ('$author',$type_of_course,$course,$startDate,$endDate,$organised,$purpose,$target,$faculty_role, '$full_part_time', $no_of_part, $duration, '$status', '$sponsored', '$name_of_sponsor', '$is_approved')";
			if ($conn->query($sql) === TRUE) {
				$success = 1;
                header("location:2_dashboard_online_organised.php?alert=success");
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	   }
			
}//end of for
           
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
             
			 <div class="box box-primary">
                <div class="box-header with-border">
				   <div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Online/Offline Course Organised Form</b></h3>
					<br>
					<b><a href="menu.php?menu=5 " style="font-size:15px;">Online/Offline Course</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_course_organised.php" style="font-size:15px;">&nbsp;No. of Online/Offline Courses</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Online/Offline Course</a></b>	
					</div>
                </div>
                <div style="text-align:right">
       <!--   <a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a> -->
        </div>
                <!-- /.box-header -->
                <!-- form start -->
	
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                     </div><br/> <br/> <br/> <br/> 

	<?php
			
					for($k=0; $k<$_SESSION['count'] ; $k++)
					{

				?>
              <p>   &nbsp&nbsp&nbsp&nbsp&nbsp************************************************************************************
              <h4 style="padding-left: 30px; padding-bottom: 10px;color: #2961bc"><strong><em>FORM <?php echo $k+1 ?> :</em></strong></h4>

			<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>   
			
<?php 
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['purpose']))
$purpose = str_replace($replace_str, "", $purpose);
else
	$purpose  = ''; 


$replace_str = array('"', "'",'' ,'');
if(isset($_POST['target']))
$target = str_replace($replace_str, "", $target);
else
	$target  = ''; 


$replace_str = array('"', "'",'' ,'');
if(isset($_POST['role']))
$faculty_role = str_replace($replace_str, "", $faculty_role);
else
	$faculty_role  = ''; 

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['isApproved']))
$is_approved = str_replace($replace_str, "", $is_approved);
else
	$is_approved  = ''; 

?>			

				
					<div class="form-group col-md-6">
                          <label for="typeofcourse">Type Of Course*</label>
                          <select required class="form-control input-lg" id="typeofcourse" name="typeofcourse[]">
                              <option value = "online">Online</option>
                              <option value = "offline">Offline</option>
                          </select>
                      </div>
				
                     <div class="form-group col-md-6">
                         <label for="course-name">Name of course *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
					  <input <?php if(isset($_POST['course'])) echo "value = $course"; ?> required type="text" class="form-control input-lg"  name="course[]">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="start-date">Duration From *</label>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">Duration To *</label>
                         <input <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="organised">Course organised by *</label>
                         <input <?php if(isset($_POST['organised'])) echo "value = $organised"; ?> required type="text" class="form-control input-lg" id="organised" name="organised[]">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="purpose">Purpose of Course * </label>
                         <textarea  required class="form-control input-lg" id="purpose" name="purpose[]" rows="2">
						  <?php if(isset($_POST['purpose'])) echo $purpose; ?>
						 </textarea>
                     </div>
                     
					 <div class="form-group col-md-6">
                         <label for="target">Target Audience * </label>
                         <textarea  required class="form-control input-lg" id="target" name="target[]" rows="2">
						  <?php if(isset($_POST['target'])) echo $target; ?>
						 </textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="role">Faculty Role</label>
                         <textarea  required class="form-control input-lg" id="role" name="role[]" rows="2">
						  <?php if(isset($_POST['role'])) echo $faculty_role; ?>
						 </textarea>
                     </div>
                      <div class="form-group col-md-6">
                          <label for="type">Fulltime/Part-time</label>
                          <select required class="form-control input-lg" id="type" name="type[]">
                              <option value = "fulltime">Full time</option>
                              <option value="parttime">Part time</option>
                          </select>
                      </div>
                      <div class="form-group col-md-6">
                          <label for="noofparticipants">Number Of Participants *</label>
                          <input <?php if(isset($_POST['participants'])) echo "value = $no_of_part"; ?>  required type="text" class="form-control input-lg"  id="participants" name="participants[]">
                      </div>
                      <div class="form-group col-md-6">
                          <label for="duration">Enter the durationof the course in hrs/week *</label>
                          <input <?php if(isset($_POST['duration'])) echo "value = $duration"; ?>  required type="text" class="form-control input-lg" id="duration" name="duration[]">
                      </div>
                      <div class="form-group col-md-6">
                          <label for="status">Status Of Activity *</label>
                          <select required class="form-control input-lg" id="status" name="status[]">
                              <option value = "local">Local</option>
                              <option value = "state">State</option>
                              <option value="national">National</option>
                              <option value="international">International</option>
                          </select>
                      </div>
                      <div class="form-group col-md-6">
                          <label for="sponsored">Sponsored?</label>
                          <input required type='radio' name='sponsored' class='not-sponsored' value='not-sponsored' >Not Sponsored <br>
                          <input type='radio' name='sponsored' class='sponsored' value='sponsored' > Sponsored
                      </div>
					<div class='second-reveal'><hr >

                     <!-- <div class="sponsored-div"> -->
					  <div class="form-group col-md-6">
						   </div>
					 
                          <div class="form-group col-md-6">
                              <label for="sponsorer">Name Of Sponsorer</label>
                              <input <?php if(isset($_POST['sponsor'])) echo $name_of_sponsor; ?> type="text" class="form-control input-lg" id="sponsor" name="sponsor[]">
                          </div>
						   <div class="form-group col-md-6">
						   </div>
                          <div class="form-group col-md-6">
                              <label for="isApproved">Approval Details</label>
							<textarea class="form-control input-lg" id="isApproved" name="isApproved[]" rows="3">
							<?php if(isset($_POST['isApproved'])) echo $is_approved; ?>
							</textarea>
                         
						 </div>
                     <!-- </div> -->
					 </div> 
					
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         
                         <button name="add"  type="submit" class="btn btn-success btn-lg">Submit</button>
                         <a href="menu.php?menu=5" type="button" class="btn pull-right btn-warning btn-lg">Cancel</a>

                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>
</div>
<?php include_once('footer.php'); ?>

