
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

$_SESSION['currentTab']="technical_review";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");



//setting error variables
$nameError="";
$emailError="";
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume = "";
$flag= 1;
$success = 0;
		$fid = $_SESSION['Fac_ID'];
	$count1 = $_SESSION['count'];
	
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
    
	$paperTitle_array = $_POST['paperTitle'];
	$paperType_array = $_POST['paperType'];
	$paperLevel_array = $_POST['paperLevel'];
			$conf_array = $_POST['conf'];

	$paperCategory_array = $_POST['paperCategory'];

	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$organized_array = $_POST['organized'];
	$details_array = $_POST['details'];
	$volume_array = $_POST['volume'];

	

	
	
    //check for any blank input which are required
    		
for($i=0; $i<count($paperTitle_array);$i++)
{
$paperTitle = mysqli_real_escape_string($conn,$paperTitle_array[$i]);
$paperType = mysqli_real_escape_string($conn,$paperType_array[$i]);
$paperLevel = mysqli_real_escape_string($conn,$paperLevel_array[$i]);
$conf = mysqli_real_escape_string($conn,$conf_array[$i]);

$paperCategory = mysqli_real_escape_string($conn,$paperCategory_array[$i]);

$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$organized = mysqli_real_escape_string($conn,$organized_array[$i]);
$details = mysqli_real_escape_string($conn,$details_array[$i]);
$volume = mysqli_real_escape_string($conn,$volume_array[$i]);




 
  
        $paperTitle=validateFormData($paperTitle);
        $paperTitle = "'".$paperTitle."'";
	
        $paperType=validateFormData($paperType);
        $paperType = "'".$paperType."'";
	
	
        $paperLevel=validateFormData($paperLevel);
        $paperLevel = "'".$paperLevel."'";
	
        $paperCategory=validateFormData($paperCategory);
        $paperCategory = "'".$paperCategory."'";
	
        $startDate=validateFormData($startDate);
        $startDate = "'".$startDate."'";
	
        $endDate=validateFormData($endDate);
        $endDate = "'".$endDate."'";
		
	
	
	
	if ($startDate > $endDate)
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}
	
	
	
        $organized=validateFormData($organized);
        $organized = "'".$organized."'";
   
        $conf=validateFormData($conf);
        $conf = "'".$conf."'";

   
	  //following are not required so we can directly take them as it is

	 $details=validateFormData($details);
    $details = "'".$details."'";
	
	
	        $volume=validateFormData($volume);
        $volume = "'".$volume."'";
	
			
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

	if($flag==1)
	    {
        $sql="INSERT INTO paper_review(Fac_ID,Paper_title,Paper_type,Paper_N_I,conf_journal_name,paper_category,Date_from,Date_to, organised_by,details,volume) VALUES ($author,$paperTitle,$paperType,$paperLevel,$conf,$paperCategory,$startDate,$endDate,$organized,$details,$volume)";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			header("location:2_dashboard_review.php?alert=success");
		


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
					<h3 class="box-title"><b>Technical Paper Reviewed Form</b></h3>
					<br>
					<b><a href="menu.php?menu=2 " style="font-size:15px;">Technical Paper Reviewed</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount_review.php" style="font-size:15px;">&nbsp;No. of Papers</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Paper</a></b>	
					</div>
				 
                </div><!-- /.box-header -->
				<div style="text-align:right">
		<!--			<a href="menu.php?menu=2 "> <u>Back to Technical Papers Reviewed Menu</u></a> -->
				</div>
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
if(isset($_POST['conf']))
$conf = str_replace($replace_str, "", $conf);
else
	$conf  = '';

$replace_str = array('"', "'",'' ,'');
if(isset($_POST['details']))
	$details = str_replace($replace_str, "", $details);
else
	$details  = '';

$replace_str = array('"', "'",'' ,'');
$volume = str_replace($replace_str, "", $volume);
?>						 									
				
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title </label>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
					  <input <?php if(isset($_POST['paperTitle'])) echo "value = $paperTitle"; ?> type="text" class="form-control input-lg"  name="paperTitle[]" >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type *</label>
                         <select required class="form-control input-lg" id="paper-type" name="paperType[]">
                             <option <?php if(isset($_POST['paperType'])) if($paperType == 'conference') echo "selected = 'selected'" ?> value = "conference">Conference</option>
                             <option <?php if(isset($_POST['paperType'])) if($paperType == 'journal') echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level *</label>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel[]">
                             <option <?php if(isset($_POST['paperLevel'])) if($paperLevel == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option <?php if(isset($_POST['paperLevel'])) if($paperLevel == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
     				  <div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name </label>
                         <textarea class="form-control input-lg" id="conf" name="conf[]" rows="2">
						 <?php if($conf != '') echo $conf; ?>
						 </textarea>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category *</label>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory[]">
                             <option <?php if($paperCategory == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if($paperCategory == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Organized by *</label>
                         <input <?php if(isset($_POST['organized'])) echo "value = $organized"; ?> required type="text" class="form-control input-lg" id="location" name="organized[]">
                     </div>

                     <div class="form-group col-md-6">
                         <label for="details">Details of Program/Your Role * </label>
                         <textarea  required class="form-control input-lg" id="details" name="details[]" rows="2">
						 <?php if($details!='') echo $details; ?>
						 </textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="volume">Volume/Issue/ISSN </label>
                         <textarea class="form-control input-lg" id="volume" name="volume[]" rows="2">
						 <?php if($volume!='') echo $volume; ?>
						 </textarea>
                     </div>		
					 
                   <?php
					}
					?>
					<br/>
                    <div class="form-group col-md-12">
                         <a href="2_dashboard_review.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="add"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>
			
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   