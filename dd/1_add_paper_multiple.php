<?php
ob_start();
session_start();
include_once('head.php'); 
 include_once('header.php'); 
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser']))
{
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "paper";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");



//setting error variables
$nameError="";
$emailError="";
$paperTitle = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coauthors = $volume = ""; 
$presentationStatus =  $publication = $awards = $presentedby = "";
$flag=1;
$success = 0;
		$fid = $_SESSION['Fac_ID'];
		$count1 = $_SESSION['count'];
	
        $faculty_name= $_SESSION['loggedInUser'];

$query="SELECT * from faculty where Fac_ID = $fid ";
    $result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
		
	}
	
$query2 = "SELECT * from facultydetails where Fac_ID = $fid";
$result2 = mysqli_query($conn,$query2);
if($result2)
{
	$row = mysqli_fetch_assoc($result2);
	$F_NAME = $row['F_NAME'];
}	
	
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
if(isset($_POST['add']))
{
    //the form was submitted
	
	$paperTitle_array = $_POST['paperTitle'];
	$paperType_array = $_POST['paperType'];
	$paperLevel_array = $_POST['paperLevel'];
	$conf_array = $_POST['conf'];
	$paperCategory_array = $_POST['paperCategory'];
	$startDate_array = $_POST['startDate'];
	$endDate_array = $_POST['endDate'];
	$location_array = $_POST['location'];
	$coauthors_array = $_POST['coauthors'];
	$volume_array = $_POST['volume'];
	
		if(isset($_POST['index']))
		{
			$index_array = $_POST['index'];
		}
		else
			$index_array = "NULL";
	
	
	$scopus_array = $_POST['scopus'];
	
	$hindex_array = $_POST['hindex'];
	$citation_array = $_POST['citation'];
	
	$applicablefdc_array = $_POST['applicablefdc'];
	
	$fdc_array = $_POST['fdc'];
	$presentationStatus_array = $_POST['presentationStatus'];
	$awards_array = $_POST['awards'];
	$presentedby_array = $_POST['presentedby'];
	$publication_array = $_POST['publication'];
	//echo "<script>alert('$paperTitle_array[0]')</script>";

	/*	$min_no_array=$_POST['min_no'];
		$serial_no_array=$_POST['serial_no'];
				$period_array = $_POST['period'];

		$od_approv_array=$_POST['od_approv'];
		$od_avail_array=$_POST['od_avail'];
		$fee_sac_array=$_POST['fee_sac'];
		$fee_avail_array=$_POST['fee_avail'];*/
	
	
    //check for any blank input which are required
    		
for($i=0; $i<$count1;$i++)
{
$paperTitle = mysqli_real_escape_string($conn,$paperTitle_array[$i]);
$paperType = mysqli_real_escape_string($conn,$paperType_array[$i]);
$paperLevel = mysqli_real_escape_string($conn,$paperLevel_array[$i]);
$conf = mysqli_real_escape_string($conn,$conf_array[$i]);
$paperCategory = mysqli_real_escape_string($conn,$paperCategory_array[$i]);
$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
$location = mysqli_real_escape_string($conn,$location_array[$i]);
$coauthors = mysqli_real_escape_string($conn,$coauthors_array[$i]);
$volume = mysqli_real_escape_string($conn,$volume_array[$i]);
$index = mysqli_real_escape_string($conn,$index_array[$i]);
$scopus = mysqli_real_escape_string($conn,$scopus_array[$i]);

$hindex = mysqli_real_escape_string($conn,$hindex_array[$i]);
$citation = mysqli_real_escape_string($conn,$citation_array[$i]);

$applicablefdc = mysqli_real_escape_string($conn,$applicablefdc_array[$i]);

$fdc = mysqli_real_escape_string($conn,$fdc_array[$i]);
$_SESSION['fdc'] = $fdc;
$presentationStatus = mysqli_real_escape_string($conn,$presentationStatus_array[$i]);
$awards = mysqli_real_escape_string($conn,$awards_array[$i]);
$publication = mysqli_real_escape_string($conn,$publication_array[$i]);
$presentedby = mysqli_real_escape_string($conn,$presentedby_array[$i]);
//echo "<script>alert('$presentationStatus')</script>";
//echo "<script>alert('$presentationStatus')</script>";
 
	
        $paperTitle=validateFormData($paperTitle);
  
	
	
        $paperType=validateFormData($paperType);
        $paperType = "'".$paperType."'";
   
   
        $paperLevel=validateFormData($paperLevel);
        $paperLevel = "'".$paperLevel."'";
    
        $conf=validateFormData($conf);
        $conf = "'".$conf."'";
   
        $paperCategory=validateFormData($paperCategory);
        $paperCategory = "'".$paperCategory."'";
   
		
	
	//if (strtotime($_POST['startDate']) > strtotime($_POST['endDate']))
	if ($startDate > $endDate)		
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
   
	  
	  //following are not required so we can directly take them as it is

		$coAuthors=validateFormData($coauthors);
		$coAuthors = "'".$coAuthors."'";
	
	
	    $volume=validateFormData($volume);
        $volume = "'".$volume."'";
	
		$hindex=validateFormData($hindex);
        $hindex = "'".$hindex."'";
		
		if($index == '')	
		{
			$index = "NULL";		
		}
		else
		{
			$index=validateFormData($index);
			$index = "'".$index."'";	
		}
		
		$scopus=validateFormData($scopus);
        $scopus = "'".$scopus."'";
		
		$citation=validateFormData($citation);
        $citation = "'".$citation."'";
		
		
   
        $presentationStatus=validateFormData($presentationStatus);
        $presentationStatus = "'".$presentationStatus."'";
		
	if($presentationStatus == 'Presented')
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
	
	if($presentationStatus == 'Not Presented')
	{
		//$presentedby="'".$presentedby."'";
		$presentedby= "NULL";
		$publication= "NULL";
		//echo "<script>alert('$presentedby')</script>";
	}		
		
    
	if($applicablefdc == 'Yes')
	{
		$fdc=validateFormData($_POST["fdc"]);
		$fdc = "'".$fdc."'";		}
	else if($applicablefdc == 'No')
	{
		$fdc = 'Not applicable';
	}
		 
		$awards=validateFormData($awards);
		$awards = "'".$awards."'";
		
		
		
				
	  //checking if there was an error or not
        $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result)
	   {
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }

		if($flag!=0)
		{
			
			$sql="INSERT INTO faculty(Fac_ID,Paper_title,Paper_type,Paper_N_I,conf_journal_name,paper_category,Date_from,Date_to, Location,Paper_co_authors, volume,scopusindex,scopus, h_index, citations, FDC_Y_N, presentation_status, Paper_awards, presented_by, Link_publication) VALUES ($author,'$paperTitle',$paperType,$paperLevel,$conf,$paperCategory,$startDate,$endDate,$location,$coAuthors,$volume,$index,$scopus,$hindex,$citation,'$fdc',$presentationStatus,$awards,'$presentedby','$publication')";
		
			if ($conn->query($sql) === TRUE) 
			{
				$success = 1;

					


			} 
			else
			{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
		}
		

			
				
				
		
				
		
 
}//end of for
			if($success == 1)	
			{
				$query = "SELECT * FROM faculty where Fac_ID = $author and FDC_Y_N = 'yes' ;";
				$result = mysqli_query($conn,$query);
				 if(mysqli_num_rows($result)>0 ){
 					header("location:5_fdc_dashboard.php?alert=success");

				 }
				 else
  					header("location:2_dashboard.php?alert=success");

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
            <div class="col-md-8 ">
              <!-- general form elements -->
			  <br/><br/><br/>
       
       <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Paper Publication/Presentation Form</b></h3>
					<br>
					<b><a href="menu.php?menu=1 " style="font-size:15px;">Paper Publication</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="actcount.php" style="font-size:15px;">&nbsp;No. of Papers</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add Paper</a></b>	
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--	<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u>&nbsp &nbsp </a> -->
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
if(isset($_POST['coAuthors']))
	$coAuthors = str_replace($replace_str, "", $coAuthors);
else
	$coAuthors  = '';

$replace_str = array('"', "'",'' ,'');
$volume = str_replace($replace_str, "", $volume);

$replace_str = array('"', "'",'' ,'');
$awards = str_replace($replace_str, "", $awards);

$replace_str = array('"', "'",'' ,'');
$publication = str_replace($replace_str, "", $publication);




?>						 						
                     <div class="form-group col-md-6">
                         <label for="paper-title">Title </label><span class="colour"><b> *</b></span>
                      <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle">-->
					  <input required  type="text" class="form-control input-lg"  name="paperTitle[]" value = '<?php if(isset($_POST['paperTitle'])) echo $paperTitle; ?>'>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-type">Paper Type</label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-type" name="paperType[]">
                             <option <?php if(isset($_POST['paperType'])) if($paperType == 'conference') echo "selected = 'selected'" ?> value = "conference">Conference</option>
                             <option <?php if(isset($_POST['paperType'])) if($paperType == 'journal') echo "selected = 'selected'" ?> value = "journal">Journal</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-level">Paper Level</label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-level" name="paperLevel[]">
                             <option <?php if(isset($_POST['paperLevel'])) if($paperLevel == "national") echo "selected = 'selected'" ?> value = "national">National</option>
                             <option <?php if(isset($_POST['paperLevel'])) if($paperLevel == "international") echo "selected = 'selected'" ?> value = "international">International</option>
                         </select>
                     </div>
				 
					  <div class="form-group col-md-6">
                         <label for="conf">Conference/Journal Name </label><span class="colour"><b> *</b></span>
                         <textarea required class="form-control input-lg" id="conf" name="conf[]" rows="2"><?php if($conf != '') echo $conf; ?></textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-category">Paper Category </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg" id="paper-category" name="paperCategory[]">
                             <option <?php if($paperCategory == "peer reviewed") echo "selected = 'selected'" ?> value = "peer reviewed">Peer Reviewed</option>
                             <option <?php if($paperCategory == "non peer reviewed") echo "selected = 'selected'" ?> value = "non peer reviewed">Non Peer Reviewed</option>
                         </select>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
                         <input <?php if(isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]"
                         placeholder="03:10:10">
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date </label><span class="colour"><b> *</b></span>
                         <input <?php if(isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]"
                         placeholder="03:10:10">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Location </label><span class="colour"><b> *</b></span>
                         <input  <?php if(isset($_POST['location'])) echo "value = $location"; ?> required type="text" class="form-control input-lg" id="location" name="location[]">
                     </div>
		

                     <div class="form-group col-md-6">
                         <label for="coauthors">Co-Author </label>
                         <textarea class="form-control input-lg" id="coauthors" name="coauthors[]" rows="2">
						 <?php if($coAuthors!='') echo $coAuthors; ?>
						 </textarea>
                     </div>
                     <div class="form-group col-md-6">
                         <label for="volume">Volume/Issue/ISSN </label>
                         <textarea class="form-control input-lg" id="volume" name="volume[]" rows="2">
						 <?php if($volume!='') echo $volume; ?>
						 </textarea>
                     </div>		
					 
					  <div class="form-group col-md-6">
                         <label for="Index">Index </label><br/>
						  <input type="radio" name="index[]" <?php if(isset($_POST['index'])) echo($index=="scopus")?'checked':'' ?> value="scopus" > Scopus<br>
							<input type="radio" name="index[]" <?php if(isset($_POST['index'])) echo ($index=='sci')?'checked':'' ?> value="sci"> SCI<br>
						<input type="radio" name="index[]" <?php if(isset($_POST['index'])) echo ($index=='both')?'checked':'' ?> value="both"> Both
						 
					</div>

					<div class="form-group col-md-6">
                         <label for="scopus">Provide Scopus/Sci/both if applicable</label>
                         <input <?php if(isset($_POST['scopus'])) echo "value = $scopus"; ?>
						 type="text" class="form-control input-lg" id="scopus" name="scopus[]" value="">
                     </div>						
					
					 <div class="form-group col-md-6">
                         <label for="H-Index">H-Index</label>
                         <input <?php if(isset($_POST['hindex'])) echo "value = $hindex"; ?>
						 type="text" class="form-control input-lg" id="hindex" name="hindex[]" value="1">
                     </div>	
					 
				 <div class="form-group col-md-6">
                         <label for="citation">Citations</label>
                         <input  <?php if(isset($_POST['citation'])) echo "value = $citation"; ?>
						 type="text" class="form-control input-lg" id="citation" name="citation[]" value="">
                     </div>	
					 
					 <div class="form-group col-md-6">
                         <label for="presentation-status">Presentation status </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg presentation-status" id="presentation-status" name="presentationStatus[]">
                             <option <?php if($presentationStatus == "Not Presented") echo "selected = 'selected'" ?> value ="Not Presented">Not Presented</option>
                             <option <?php if($presentationStatus == "Presented") echo "selected = 'selected'" ?> value ="Presented">Presented</option>
                         </select>
                     </div>
			
					 <div class="form-group col-md-6">
                         <label for="awards">Awards, if any </label>
                         <textarea class="form-control input-lg" id="awards" name="awards[]" rows="1">
						 <?php if(isset($_POST['awards'])) echo $awards; ?>
						 </textarea>
                     </div>
					 <div id="presented-by" style="display:none" class="form-group col-md-6">
                        <label for="presented-by">Presented by </label><span class="colour"><b> *</b></span>
						<input  <?php if(isset($_POST['presentedby'])) echo "value = '$F_NAME'"; else echo "value = '$presentedby'";?>
						type="text" class="form-control input-lg"  name="presentedby[]">
                     </div>
					 <div id="publication" style="display:none" class="form-group col-md-6">
                         <label for="publication">Link of Online Publication</label>
                         <textarea class="form-control input-lg" id="publication" name="publication[]" rows="1">
						 <?php if(isset($_POST['publication'])) echo $publication; ?>
						 </textarea>
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
					 
					 $('.presentation-status').each(function(){
						 $('.presentation-status').on('change',myfunction);
					 });
					 
					  $('.applicable-fdc').each(function(){
						 $('.applicable-fdc').on('change',myfunction1);
					 });
					 
					 
						function myfunction(){
						var x = this.value;
					
						if(x=='Presented')
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
                         <a href="2_dashboard.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>

                         <button name="add"  type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>				
              </div>
           </div> 
		 	
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   