<?php
session_start();
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




//setting error variables
$nameError="";
$emailError="";
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";
$flag = 1;

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from paper_review where paper_review_ID = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];

    $paperTitle = $row['Paper_title'];
    $startDate = $row['Date_from'];
    $endDate = $row['Date_to'];
    $paperType_db = $row['Paper_type'];
    $paperLevel_db = $row['Paper_N_I'];
		$conf = $row['conf_journal_name'];

    $paperCategory_db = $row['paper_category'];
    $organized = $row['organised_by'];
    $details = $row['details'];
    $volume = $row['volume'];



}

			
			$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
			$result2 = mysqli_query($conn,$query2);
			if($result2)
			{
	            $row = mysqli_fetch_assoc($result2);
				$F_NAME = $row['F_NAME'];

			}
	   

//check if the form was submitted
if(isset($_POST['update'])){
    //the form was submitted
    $clientName=$clientEmail=$clientPhone=$clientAddress=$clientCompany=$clientNotes="";
    
    //check for any blank input which are required
    
  
        $paperTitle=validateFormData($_POST['paperTitle']);
        $paperTitle = "'".$paperTitle."'";
   
		if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}	
   
        $startDate=validateFormData($_POST['startDate']);
        $startDate = "'".$startDate."'";
   
        $endDate=validateFormData($_POST['endDate']);
        $endDate = "'".$endDate."'";
  
        $paperType=validateFormData($_POST['paperType']);
        $paperType = "'".$paperType."'";
   
        $paperLevel=validateFormData($_POST['paperLevel']);
        $paperLevel = "'".$paperLevel."'";
   
        $paperCategory=validateFormData($_POST['paperCategory']);
        $paperCategory = "'".$paperCategory."'";
  
        $organized=validateFormData($_POST['organized']);
        $organized = "'".$organized."'";
 
        $conf=validateFormData($_POST['conf']);
        $conf = "'".$conf."'";
   
    //following are not required so we can directly take them as it is
    
    $details=validateFormData($_POST["details"]);
    $details = "'".$details."'";
	
	
	        $volume=validateFormData($_POST["volume"]);
        $volume = "'".$volume."'";
		
		
		

    
    //checking if there was an error or not
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
				$succ = 0;
				$success1 = 0;
if($flag == 1)
{
	$sql = "update paper_review set Paper_title = $paperTitle,
                               Paper_type = $paperType,
							   Paper_N_I = $paperLevel,
   							   conf_journal_name = $conf,

							   paper_category = $paperCategory,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   organised_by = $organized,
							   details =$details,
							   volume = $volume
							  
							   WHERE paper_review_ID = $id";

			if ($conn->query($sql) === TRUE) 
			{
				$success = 1;
				
				
					
				
					
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
			
			
			if($success ==1 )
			{
					if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:2_dashboard_hod_review.php?alert=update");

					}
					else
					{
						header("location:2_dashboard_review.php?alert=update");

					}
			}
			else 
			   header("location:2_dashboard_review.php");
}
}

//close the connection
mysqli_close($conn);
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
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
					<h3 class="box-title"><b>Technical Paper Reviewed Edit Form</b></h3>
					<br>
					<b><a href="menu.php?menu=2 " style="font-size:15px;">Technical Paper Reviewed </a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="2_dashboard_review.php" style="font-size:15px;">&nbsp;View/Edit Activity</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Edit Technical Paper Reviewed</a></b>	
				</div>
                </div><!-- /.box-header -->
				<div style="text-align:left">
			<!--	<p style="color:#428bca;"><b>&nbsp;&nbsp;&nbsp;<u>Last Edit was made on <?php //echo $Udate?></u></b></u></p>  -->
				</div>
				<div style="text-align:right">
			<!--		<a href="menu.php?menu=2 "> <u>Back to Technical Papers Reviewed Menu</u></a> -->
				</div>
                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
 <?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
			?>		
<?php 
$replace_str = array('"', "'",'' ,'');
if(isset($_POST['conf']))
$conf = str_replace($replace_str, "", $conf);


$replace_str = array('"', "'",'' ,'');
if(isset($_POST['details']))
	$details = str_replace($replace_str, "", $details);


$replace_str = array('"', "'",'' ,'');
$volume = str_replace($replace_str, "", $volume);
?>							
				
                    <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title</label>
                          <textarea type="text" class="form-control input-lg" id="paper-title" name="paperTitle">
						 <?php echo "$paperTitle"; ?> </textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type *</label>
                         <select required class="form-control input-lg" id="paper-type" name="paperType">
                             <option <?php if($paperType_db == "conference") echo "selected = 'selected'" ?>  value = "conference">Conference</option>
                             <option <?php if($paperType_db == "journal") echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level *</label>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel">
                             <option <?php if($paperLevel_db == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option  <?php if($paperLevel_db == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
					  <div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name </label>
                         <textarea  class="form-control input-lg" id="conf" name="conf" rows="2"><?php echo $conf; ?></textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category *</label>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory">
                             <option  <?php if($paperCategory_db == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if($paperCategory_db == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label>
                         <input 
                             <?php echo "value = $startDate"; ?>
                           required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label>
                         <input
                             <?php echo "value = $endDate"; ?>
                           required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="organized">Organized by*</label>
                         <input
              
                           required type="text" class="form-control input-lg" id="organized" name="organized" value='<?php echo $organized ?>'>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="details">Details of Program/Your Role *</label>
                         <textarea required class="form-control input-lg" id="details" name="details" rows="2">
                             <?php echo $details; ?>
                         </textarea>
                     </div>
					 
					  <div class="form-group col-md-6">
                         <label for="volume">Volume/Issue/ISSN </label>
                         <textarea class="form-control input-lg" id="volume" name="volume" rows="2">
                             <?php echo $volume; ?>
                         </textarea>
                     </div>
					
					 

                    <div class="form-group col-md-12">
                         <a href="2_dashboard_review.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>

                    </div>
                </form>
                
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   