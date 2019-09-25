<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "organised_guest";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");




//setting error variables
$nameError="";
$emailError="";
$flag = 1;

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $query = "SELECT * from guestlec where p_id = $id";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['fac_id'];

    $topic = $row['topic'];
    $startDate = $row['durationf'];
    $endDate = $row['durationt'];
    $name = $row['name'];
	$designation= $row['designation'];
	$organisation= $row['organisation'];
	$targetaudience = $row['targetaudience'];
    


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

    
    
    //check for any blank input which are required
    
 
        $topic1=validateFormData($_POST['topic']);
        $topic1 = "'".$topic1."'";
    
		if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
		{
			$nameError=$nameError."Start Date cannot be greater than end date<br>";
			$flag = 0;
		}	
	
        $startDate1=validateFormData($_POST['startDate']);
        $startDate1 = "'".$startDate1."'";
   
        $endDate1=validateFormData($_POST['endDate']);
        $endDate1 = "'".$endDate1."'";
   
        $name1=validateFormData($_POST['name']);
        $name1 = "'".$name1."'";
    
        $designation1=validateFormData($_POST['designation']);
        $designation1 = "'".$designation1."'";
   
   
 
  
  
        $organisation1=validateFormData($_POST['organisation']);
        $organisation1 = "'".$organisation1."'";
    
        $targetaudience1=validateFormData($_POST['targetaudience']);
        $targetaudience1 = "'".$targetaudience1."'";
    
   

    //checking if there was an error or not
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
				$succ = 0;
				$success1 = 0;
				
	if($flag!=0)
	{		

	$sql = "update guestlec set topic = $topic1 ,targetaudience=$targetaudience1,organisation=$organisation1,designation=$designation1,name=$name1,durationf=$startDate1,durationt=$endDate1 WHERE p_id = $id";
							

			if ($conn->query($sql) === TRUE) 
			{
				if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
					{
					   header("location:view_organised_hod_lec.php?alert=update");

					}
					else
					{
						header("location:view_organised_lec.php?alert=update");

					}
				
				
				}	
					
				
					
			 else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
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
              <div class="box box-primary">
                <div class="box-header with-border">
                <div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Guest Lecture Organised Edit Form</b></h3>
					<br>
					<b><a href="menu.php?menu=4 " style="font-size:15px;">Guest Lecture Organised</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="view_organised_lec.php" style="font-size:15px;">&nbsp;View/Edit Activity</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Edit Guest Lecture Organised</a></b>	
				</div>
				
				</div><!-- /.box-header -->
				<div class="form-group col-md-6">

                         <label for="faculty-name">Faculty Name</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                     </div>
					                      <br><br><br><br>

                <!-- form start -->
                <form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
				
				<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
					//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
				}	
			?>		
                    <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
					
                     <div class="form-group col-md-6">
                         <label for="paper-title">TOPIC *</label>
                         <input required type="text" class="form-control input-lg" id="topic" name="topic" value='<?php echo $topic ?>' >
                     </div>
                     
                     <div class="form-group col-md-6">
                         <label for="start-date">Start Date *</label> <?php $value = date("Y-m-d\TH:i:s", strtotime($startDate));  ?>
                         <input 
                            
                            type="datetime-local" required class="form-control input-lg" id="start-date" name="startDate"
                         placeholder="" value="<?php echo $value; ?>" >
                     </div>

                    <div class="form-group col-md-6">
                         <label for="end-date">End Date *</label><?php  $value = date("Y-m-d\TH:i:s", strtotime($endDate)); ?>
                         <input
                             
                           type="datetime-local" required class="form-control input-lg" id="end-date" name="endDate"
                         placeholder="" value="<?php echo $value; ?>">
                     </div>
                    
                    <div class="form-group col-md-6">
                         <label for="location">Resource Person Name *</label>
                         <input
                            
                           required type="text" class="form-control input-lg" id="name" name="name" value='<?php echo $name ?>'>
                     </div>

                  <div class="form-group col-md-6">
                         <label for="paper-title">Designation *</label>
                         <input required type="text" class="form-control input-lg" id="designation" name="designation" value='<?php echo $designation ?>' >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="paper-title">Organisation *</label>
                         <input   required type="text" class="form-control input-lg" id="organisation" name="organisation"  value='<?php echo $organisation ?>'>
                     </div>
                  <div class="form-group col-md-6">
                         <label for="paper-title">Target Audience *</label>
                         <input  required type="text" class="form-control input-lg" id="targetaudience" name="targetaudience" value='<?php echo $targetaudience ?>' >
                     </div>
                       

                    <div class="form-group col-md-12">
                         <a href="view_organised_hod_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>

                    </div>
                </form>
                
                </div>
              </div>
           </div>      
        </section>

    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   