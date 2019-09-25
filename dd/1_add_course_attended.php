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



//setting error variables
$nameError="";
$emailError="";
$type_of_course = $status_of_activity = $duration = $credit_audit = $course = $startDate = $endDate = $organised = $purpose = "";
$flag= 1;
$success = 0;
		$fid = $_SESSION['Fac_ID'];
	$count1 = $_SESSION['count'];
	
        $faculty_name= $_SESSION['loggedInUser'];
		$_SESSION['currentTab']="Online";
$query="SELECT * from online_course_attended where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if(isset($_POST['add'])){

    //the form was submitted
    
	$course_array = $_POST['course'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$organised_array = $_POST['organised'];
    $purpose_array = $_POST['purpose'];
	$applicablefdc_array = $_POST['applicablefdc'];
	
	$fdc_array = $_POST['fdc'];

	/*  MY CODE */
	$type_of_course_array = $_POST['type'];
	$status_of_activity_array = $_POST['status'];
	$duration_array = $_POST['duration'];
	$credit_audit_array = $_POST['creau'];

	
    //check for any blank input which are required
    		
for($i=0; $i<count($course_array);$i++)
{
$course = mysqli_real_escape_string($conn,$course_array[$i]);

$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organised = mysqli_real_escape_string($conn,$organised_array[$i]);
$purpose = mysqli_real_escape_string($conn,$purpose_array[$i]);

/*  MY CODE */
$type_of_course = mysqli_real_escape_string($conn,$type_of_course_array[$i]);
$status_of_activity = mysqli_real_escape_string($conn,$status_of_activity_array[$i]);
$duration = mysqli_real_escape_string($conn,$duration_array[$i]);
$credit_audit = mysqli_real_escape_string($conn,$credit_audit_array[$i]);

$applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

$fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
$_SESSION['fdc'] = $fdc;

 
        $course=validateFormData($course);
        $course = "'".$course."'";
		
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
		
   
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		
	
		if ($startDate > $endDate)		
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
		
	
       $purpose=validateFormData($purpose);
        $purpose = "'".$purpose."'";
		
		$type_of_course=validateFormData($type_of_course);
        $type_of_course = "'".$type_of_course."'";		
   
        $status_of_activity=validateFormData($status_of_activity);
        $status_of_activity = "'".$status_of_activity."'";
    
        $organised=validateFormData($organised);
        $organised = "'".$organised."'";
        
		$duration=validateFormData($duration);
        $duration = "'".$duration."'";
		
		$credit_audit=validateFormData($credit_audit);
        $credit_audit = "'".$credit_audit."'";
		
		

	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
		$fdc = "'".$fdc."'";		}
	else if($applicablefdc == 'No')
	{
		$fdc = 'Not applicable';
		$fdc = "'".$fdc."'";		
		
	}
		
	
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
	   
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['purpose']))
$purpose = str_replace($replace_str, "", $purpose);
else
	$purpose  = '';


	   if($flag!=0)
	   {
	   	// MY QUERY
        $sql="INSERT INTO online_course_attended(Fac_ID,Course_Name, Date_from, Date_to,Organised_by, Purpose, FDC_Y_N,type_of_course,status_of_activity,duration,credit_audit) VALUES ('$author',$course,$startDate,$endDate,$organised,'$purpose',$fdc,$type_of_course,$status_of_activity,$duration,$credit_audit)";
			if ($conn->query($sql) === TRUE) {
				$success = 1;
			
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		/*	if($success == 1 && $fdc == 'yes')
			{
				$sql="INSERT INTO fdc_online_course(Fac_ID,Course_Name) VALUES ('$author','$course')";
				$result = mysqli_query($conn,$sql);
				
			}*/
	   }
}//end of for
			if($success == 1)	
			{
				$query = "SELECT * FROM online_course_attended where Fac_ID = $author and FDC_Y_N = 'yes' ;";
				$result = mysqli_query($conn,$query);
				 if(mysqli_num_rows($result)>0 && $fdc == 'yes'){
				  					header("location:2_dashboard_online_attended.php?alert=success");
	

				 }
				 else
  					header("location:2_dashboard_online_attended.php?alert=success");
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
					<h3 class="box-title"><b>Online/Offline Course Attended Form</b></h3>
					<br>
					<b><a href="menu.php?menu=5 " style="font-size:15px;">Online/Offline Course</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_course_attended.php" style="font-size:15px;">&nbsp;No. of Online/Offline Courses</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Online/Offline Course</a></b>	
					</div>
                </div>
                <div style="text-align:right">
              <!--  <a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a> -->
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

?>			

                      <div class="form-group col-md-6">
                          <label for="type">Type Of Course*</label>
                          <select required class="form-control input-lg" id="type" name="type[]">
                              <option <?php if(isset($_POST['type'])) if($type_of_course == "online") echo "selected = 'selected'" ?> value = "online">Online</option>
                              <option <?php if(isset($_POST['type'])) if($type_of_course == "offline") echo "selected = 'selected'" ?> value = "offline">Offline</option>
                          </select>
                      </div>
                     <div class="form-group col-md-6">
                         <label for="course-name">Name of course *</label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
					  <input <?php if(isset($_POST['course'])) echo "value = $course"; ?> required  type="text" class="form-control input-lg"  name="course[]">
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
						
					     <label for="purpose">Purpose of Course *</label>
                         <textarea class="form-control input-lg" id="purpose" name="purpose[]" rows="2">
						 <?php if(isset($_POST['purpose'])) echo $purpose; ?>
						 </textarea> </div>
                  
                      <div class="form-group col-md-6">
                          <label for="status">Status Of Activity *</label>
                          <select required class="form-control input-lg" id="status" name="status[]">
                            <option <?php if(isset($_POST['status_of_activity'])) if($status_of_activity == "local") echo "selected = 'selected'" ?> name="local" value="local">Local</option>
                            <option <?php if(isset($_POST['status_of_activity'])) if($status_of_activity == "state") echo "selected = 'selected'" ?> name="state" value="state">State</option>
                            <option <?php if(isset($_POST['status_of_activity'])) if($status_of_activity == "national") echo "selected = 'selected'" ?> name="national" value="national">National</option>
                            <option <?php if(isset($_POST['status_of_activity'])) if($status_of_activity == "international") echo "selected = 'selected'" ?> name="international" value="international">InterNational</option>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                          <label for="duration">Enter the duration of the course in hrs/week</label>
                          <input <?php if(isset($_POST['duration'])) echo "value = $duration"; ?> required type="text" class="form-control input-lg" id="duration" name="duration[]">
                      </div>
                      <div class="form-group col-md-6">
                          <label for="creau">Credit/Audit *</label>
                          <select required class="form-control input-lg" id="creau" name="creau[]">
                              <option  <?php if(isset($_POST['creau'])) if($credit_audit == "credit") echo "selected = 'selected'" ?> value = "credit">Credit</option>
                              <option  <?php if(isset($_POST['creau'])) if($credit_audit == "audit") echo "selected = 'selected'" ?> value = "audit">Audit</option>
                          </select>
                      </div>
					  
					  
					  
					 	 <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc[]">
                              <option <?php if(isset($_POST['fdc'])) if($fdc == "Not applicable") echo "selected = 'selected'" ?> value ="No">No</option>
                             <option <?php if(isset($_POST['fdc'])) if($fdc == 'no' || $fdc =='yes' || $fdc == 'No' || $fdc == 'Yes') echo "selected = 'selected'" ?> value ="Yes">Yes</option>
                         </select>
                     </div>
					  <div class="form-group col-md-6" >
					  </div>
					 <div class="form-group col-md-6" style="display:none">
                         <label for="fdc">Applied for FDC ? </label><span class="colour"><b> *</b></span>
                         <select  class="form-control input-lg" id="fdc" name="fdc[]">
                            <option <?php if(isset($_POST['fdc'])) if($fdc == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                            <option <?php if(isset($_POST['fdc'])) if($fdc == "no") echo "selected = 'selected'" ?> value = "no">No</option>
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
                         <a href="menu.php?menu=5" type="button" class="btn pull-right btn-warning btn-lg">Cancel</a>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>
</div>
<?php include_once('footer.php'); ?>