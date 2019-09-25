<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

$_SESSION['currentTab'] = "paper";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");


//setting error variables
$nameError="";
$emailError="";
$flag=1;
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";
$presentationStatus =  $publication = $awards = $presentedby = $Udate = "";

date_default_timezone_set("Asia/Kolkata");
    $_SESSION['id'] = $_POST['id'];
	$_POST['id'] = $_SESSION['id'] ;

if(isset( $_POST['id']))
{
    $id = $_POST['id'];
    $query = "SELECT * from faculty where P_ID = $id";
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
    $location = $row['Location'];
    $coAuthors = $row['Paper_co_authors'];
    $volume = $row['volume'];
    $index_db = $row['scopusindex'];
    $scopus = $row['scopus'];
	
	$hindex = $row['h_index'];
    $citation = $row['citations'];
	
    $fdc_db = $row['FDC_Y_N'];
	$presentationStatus_db = $row['presentation_status'];
	$publication = $row['Link_publication'];
	$awards = $row['Paper_awards'];
	$presentedby_db = $row['presented_by'];
	$Udate = $row['Udate'];
	
}

$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
$result2 = mysqli_query($conn,$query2);
if($result2)
{
	$row = mysqli_fetch_assoc($result2);
	$F_NAME = $row['F_NAME'];
}
	   
//check if the form was submitted
if(isset($_POST['update']))
{
    //the form was submitted
    $clientName=$clientEmail=$clientPhone=$clientAddress=$clientCompany=$clientNotes="";
    
    //check for any blank input which are required
    $Udate = $_POST['Udate'];
	$Udate = "'".$Udate."'";
	
   
        $paperTitle=validateFormData($_POST['paperTitle']);
        $paperTitle = "'".$paperTitle."'";
   
		
		
        $startDate=validateFormData($_POST['startDate']);
        $startDate = "'".$startDate."'";
		
	
	
        $endDate=validateFormData($_POST['endDate']);
        $endDate = "'".$endDate."'";
		
	
	if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
	{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
	}
		
	
   
        $paperType=validateFormData($_POST['paperType']);
        $paperType = "'".$paperType."'";
   
        $paperLevel=validateFormData($_POST['paperLevel']);
        $paperLevel = "'".$paperLevel."'";
   
        $paperCategory=validateFormData($_POST['paperCategory']);
        $paperCategory = "'".$paperCategory."'";
   
        $location=validateFormData($_POST['location']);
        $location = "'".$location."'";
    
   
        $conf=validateFormData($_POST['conf']);
        $conf = "'".$conf."'";
  
        $presentationStatus=validateFormData($_POST['presentationStatus']);
        $presentationStatus = "'".$presentationStatus."'";
	
	if($_POST['presentationStatus'] == 'Presented')
	{
		if(!$_POST['presentedby'])
		{
			$nameError=$nameError."Please enter by whom it is presented<br>";
			$flag = 0;
		}
		else
		{
			 $presentedby=validateFormData($_POST['presentedby']);
			 $presentedby="'".$presentedby."'";
			 $publication=validateFormData($_POST["publication"]);
			 $publication = "'".$publication."'";
		}
	}
	
	if($_POST['presentationStatus'] == 'Not Presented')
	{
		
		$presentedby= '';
		$presentedby = "'".$presentedby."'";

		$publication= '';
		$publication = "'".$publication."'";
		
		
	}	
   
    //following are not required so we can directly take them as it is
    
    $coAuthors=validateFormData($_POST["coauthors"]);
    $coAuthors = "'".$coAuthors."'";
	
	
	$volume=validateFormData($_POST["volume"]);
    $volume = "'".$volume."'";
		
	if(isset($_POST["index"]))	
	{
		$index=validateFormData($_POST["index"]);
		$index = "'".$index."'";		
	}
	else
	{
		$index= '';
		$index = "'".$index."'";
	}
	
	$hindex=validateFormData($_POST["hindex"]);
    $hindex = "'".$hindex."'";
	
	$scopus=validateFormData($_POST["scopus"]);
    $scopus = "'".$scopus."'";
	
	$citation=validateFormData($_POST["citation"]);
    $citation = "'".$citation."'";
	
	
		
	$awards=validateFormData($_POST["awards"]);
    $awards = "'".$awards."'";
			
	
	$applicablefdc = $_POST["applicablefdc"];
	$fdc = $applicablefdc;
	
	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
	
	}
	else if($applicablefdc == 'No')
	{
		
		$fdc = "Not applicable";
		//$fdc = "'".$fdc."'";
		
	}
	
	
    //checking if there was an error or not
	$query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
		if($result)
		{
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
		}
		$succ = 0;
		$success1 = 0;
		
	if($flag==1)
	{
				$sql = "update faculty set Paper_title = $paperTitle,		
                               Paper_type = $paperType,
							   Paper_N_I = $paperLevel,							   
							   conf_journal_name = $conf,
							   paper_category = $paperCategory,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Location = $location,
							   Paper_co_authors =$coAuthors,
							   volume = $volume,
							   scopusindex = $index,
								scopus = $scopus,		   
							   h_index = $hindex,
							   citations = $citation,							   
							   FDC_Y_N = '$fdc',
							   presentation_status = $presentationStatus,
							   Paper_awards = $awards,
							   presented_by = $presentedby,
							   Link_publication = $publication,
							   Udate = $Udate								   
							   WHERE P_ID = $id";

			$sql1= "update fdc set Paper_title = $paperTitle where P_ID = $id ";
			$result1=mysqli_query($conn, $sql1);				   
							   
			if ($conn->query($sql) === TRUE) {
				
				$success = 1;				
			
				if($success == 1 && preg_match('/no/', $fdc))
				{
					$sql="delete from fdc where Paper_title = $paperTitle";
					$result = mysqli_query($conn,$sql);
					$succ =1;
					
				}	
			} 
			else 
			{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			if($succ ==1 || $success ==1 )
			{
					if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:2_dashboard_hod.php?alert=update");

					}
					else
					{
						header("location:2_dashboard.php?alert=update");

					}
			}
			else 
			   header("location:2_dashboard_hod.php");
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
	<!--	  <img src="images\509536872-612x612.jpg" alt="Soamiya" align="right" width="350" height="910" style="margin-right:15px;"> -->
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
				<div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Paper Presentation/Publication</b></h3>
					<br>	
					<b><a href="menu.php?menu=1 " style="font-size:15px;">Paper Publication</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="2_dashboard_hod.php" style="font-size:15px;">&nbsp;View/Edit Activity</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Edit Paper</a></b>					
				</div>
                </div><!-- /.box-header -->
				<div style="text-align:left">
				<p style="color:#428bca;"><b>&nbsp;&nbsp;&nbsp;<u>Last Edit was made on <?php echo $Udate?></u></b></u></p>
				</div>
                <!-- form start -->
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label> 
                         <input type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div><br/> <br/> <br/> <br/>
                <form role="form" method="POST" class="row" action ="3_edit_hod.php" style= "margin:10px;" >
					 <?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
			?>		
					 
                    <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
					<input type="hidden" name="Udate" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                 <br/>     <div class="form-group col-md-6">
                         <label for="paper-title">Title </label><span class="colour"><b> *</b></span>
                         <textarea class="form-control input-lg" id="paper-title" name="paperTitle" >
						 		<?php echo "$paperTitle"; ?> </textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-type" name="paperType">

							<option <?php if($paperType_db == 'conference') echo "selected = 'selected'" ?>  value = "conference">Conference</option>
                             <option <?php if($paperType_db == 'journal') echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel">
                             <option <?php if($paperLevel_db == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option  <?php if($paperLevel_db == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
					 
<?php 


$replace_str = array('"', "'",'' ,'');
$conf = str_replace($replace_str, "", $conf);	

?>		
					<div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name </label><span class="colour"><b> *</b></span>
                         <textarea required class="form-control input-lg" id="conf" name="conf" rows="2"><?php echo $conf; ?></textarea>
                     </div>
					 
					 
                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory">
                             <option  <?php if($paperCategory_db == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if($paperCategory_db == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
                         <input 
                             <?php echo "value = $startDate"; ?>
                           required type="date" class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date </label><span class="colour"><b> *</b></span>
                         <input
                             <?php echo "value = $endDate"; ?>
                           required type="date" class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Location </label><span class="colour"><b> *</b></span>
                         <input
                           
                           required type="text" class="form-control input-lg" id="location" name="location" value='<?php echo $location ?>'>
                     </div>
					<div class="form-group col-md-6">
                         <label for="Index">Index </label><br/>
						  <input type="radio" name="index" value="scopus" <?php  echo($index_db=='scopus')?'checked':'' ?>> Scopus<br>
							<input type="radio" name="index" value="sci" <?php echo ($index_db=='sci')?'checked':'' ?>> SCI<br>
						<input type="radio" name="index" value="both" <?php echo ($index_db=='both')?'checked':'' ?>> Both
						 
					</div>	

					<div class="form-group col-md-6">
                         <label for="scopus">Provide Scopus/SCI/both if applicable</label>
                         <input 
						  <?php if($scopus == "") echo ""; else echo "value = $scopus"; ?>
						 type="text" class="form-control input-lg" id="scopus" name="scopus">
                     </div>	

					
					 <div class="form-group col-md-6">
                         <label for="location">H-Index</label>
                         <input 
						  <?php if($hindex == "") echo ""; else echo "value = $hindex"; ?>
						 type="text" class="form-control input-lg" id="hindex" name="hindex">
                     </div>	
					 
					 	<div class="form-group col-md-6">
                         <label for="citation">Citations</label>
                         <input 
						  <?php if($citation == "") echo ""; else echo "value = $citation"; ?>
						 type="text" class="form-control input-lg" id="citation" name="citation">
                     </div>	

                   
<?php 
$replace_str = array('"', "'",'' ,'');
$coAuthors = str_replace($replace_str, "", $coAuthors);



$replace_str = array('"', "'",'' ,'');
$volume = str_replace($replace_str, "", $volume);

$replace_str = array('"', "'",'' ,'');
$awards = str_replace($replace_str, "", $awards);

$replace_str = array('"', "'",'' ,'');
$publication = str_replace($replace_str, "", $publication);

$replace_str = array('"', "'",'' ,'');
$presentedby_db = str_replace($replace_str, "", $presentedby_db);


?>						 
						<div class="form-group col-md-6">
                         <label for="coauthors">Co-Author </label>
                         <textarea class="form-control input-lg" id="coauthors" name="coauthors" rows="2"><?php echo $coAuthors; ?>
                         </textarea>
                     </div>
					
					  <div class="form-group col-md-6">
                         <label for="volume">Volume/Issue/ISSN </label>
                         <textarea class="form-control input-lg" id="volume" name="volume" rows="1"><?php echo $volume; ?>
                         </textarea>
                     </div>
                    
					
					 <div class="form-group col-md-6">
                         <label for="presentation-status">Presentation Status </label><span class="colour"><b> *</b></span>
                         <select required onchange="myfunction()" class="form-control input-lg" id="presentation-status" name="presentationStatus">
                             <option <?php if($presentationStatus_db == "Not Presented") echo "selected = 'selected'" ?> value = "Not Presented">Not Presented</option>
							 <option  <?php if($presentationStatus_db == "Presented") echo "selected = 'selected'" ?> value = "Presented">Presented</option>
                         </select>
                     </div>
					 <div class="form-group col-md-6">
                         <label for="awards">Awards, if any</label>
                         <textarea class="form-control input-lg" id="awards" name="awards" rows="1"><?php echo $awards; ?></textarea>
                     </div>
					 <div id="presented-by" class="form-group col-md-6" style="display:">
                         <label for="presented-by">Presented By </label><span class="colour"><b> *</b></span>
                         <input
                             <?php if ($presentedby_db == "") echo "value = '$F_NAME'"; else echo "value = '$presentedby_db'";?>
                           required type="text" class="form-control input-lg" id="presented-by"
						   name="presentedby">
                     </div>
					 <div id="publication" class="form-group col-md-6" style="display:">
                         <label for="publication">Link of Online Publication</label>
                         <textarea class="form-control input-lg" id="publication" name="publication" rows="1"><?php echo $publication; ?></textarea>
                     </div>
					   <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required onchange="myfunction1()" class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc">
                             <option <?php if($fdc_db == "Not applicable") echo "selected = 'selected'" ?> value ="No">No</option>
                             <option <?php if($fdc_db == 'no' || $fdc_db =='yes' || $fdc_db == 'No' || $fdc_db == 'Yes') echo "selected = 'selected'" ?> value ="Yes">Yes</option>
                         </select>
                     </div>
					 <div class="form-group col-md-6" >
					  </div>
					  
					  <div id="fdc" class="form-group col-md-6" style="display:none">
                         <label for="fdc">Applied for FDC ? </label><span class="colour"><b> *</b></span>
                         <select  class="form-control input-lg" id="fdc" name="fdc">
                             <option <?php if($fdc_db == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                             <option <?php if($fdc_db == "no") echo "selected = 'selected'" ?> value = "no">No</option>
                         </select>
                     </div>   
					 
                    <script>
					 
					 window.onload = function() {
						 myfunction();
										 myfunction1();
		 
					 }
					 
					 function myfunction()
					 {
						 var x = document.getElementById("presentation-status").value;
						 
						 if(x=='Presented')
						 {
							//document.getElementById("demo").innerHTML = "You selected: " + x;
							//console.log(document.getElementById("presented-by"));
							document.getElementById("presented-by").style.display = 'block';
							document.getElementById("publication").style.display = 'block';
						 }
						 else
						 {
							//document.getElementById("demo").innerHTML = "You selected: " + x;
							document.getElementById("presented-by").style.display = 'none';
							document.getElementById("publication").style.display = 'none'; 
						 }
					 }
					 function myfunction1(){
								 var y = document.getElementById("applicable-fdc").value;

						if(y=='Yes')
						{
				
							document.getElementById("fdc").style.display = 'block';
						
						}
						else
						{
							document.getElementById("fdc").style.display = 'none';
						}
						}
					 </script>

                    <div class="form-group col-md-12">
                         <a href="2_dashboard_hod.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>

                         <button name="update"  type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>

                    </div>
					

                </form> 
                
                </div>
              </div>
           </div> 
	   
        </section>
  
    
</div>
   
 
    
    
<?php include_once('footer.php'); ?>
   
   