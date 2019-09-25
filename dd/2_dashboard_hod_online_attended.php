<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}

/*if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}*/
//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result
/*$query = "SELECT P_ID, Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_category,Date_from,Date_to
,Location,paper_path,certificate_path,report_path,Paper_co_authors,volume FROM faculty";*/
$query = "SELECT * from online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID ";

$result = mysqli_query($conn,$query);
$_SESSION['currentTab']="Online";

$successMessage="";
if(isset($_GET['alert'])){
    if($_GET['alert']=="success"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record added successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="update"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record updated successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="delete"){
        $successMessage='<br/><br/><br/><div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record deleted successfully</strong>
        </div>';  

    }
}
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php 
if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu')
  {
	    include_once('sidebar_hod.php');

  }
  else
	  include_once('sidebar.php');

 ?>

<style>
div.scroll
{
overflow:scroll;

}


</style>



<div class="content-wrapper">
   <?php 
        {
        echo $successMessage;
    }
	$display = 0;
	if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
	{
		$display = 1;
	}
	else if($_SESSION['username'] == 'member@somaiya.edu')
	{
		$display = 2;
	}
    ?>
    <section class="content">
          <div class="row">
            <div class="col-xs-12">
<?php if(!isset($_GET['alert'])){ ?>
           <br/><br/><br/>
			<?php } ?>            
			<div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:18px" class="fa fa-table"></i>
					<h2 class="box-title"><b>Online/Offline Course Attended Details</b></h2>
					<br>
					<b><a href="menu.php?menu=5 " style="font-size:15px;">Online/Offline Course Attended</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Activity</a></b>	
					</div>                </div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a> -->
				</div>
                <div class="box-body">
                <div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
                <th>Faculty</th>
                <th>Name</th>
                <th>Duration From (y-m-d)</th>
                <th>Duration To (y-m-d)</th>
                <th>Organised By</th>
                <th>Purpose</th>
                <th>FDC Status</th>
                
                <!-- My CODE -->
                
                <th>Type of Course</th>
                <th>Status</th>
                <th>Duration</th>
                <th>Credit/Audit</th>
                <th>FDC Approval Status</th>
				
				<th>Updated on</th>


                <!-- My Code End  -->

                <th>Certificate</th>
                <th>Report</th>
				
		<?php 
				if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{
			?>		
                <th>Upload Certificate</th>
                <th>Upload Report</th>
                <th>Edit</th>
                <th>Delete</th>
		<?php }?>
            </tr>
        </thead>
        <?php
		$_SESSION['rows'] = mysqli_num_rows($result);
		
        if(mysqli_num_rows($result)>0){
            
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['F_NAME']."</td>";
                echo "<td>".$row['Course_Name']."</td>";
                echo "<td>".$row['Date_From']."</td>";
                echo "<td>".$row['Date_To']."</td>";
                echo "<td>".$row['Organised_by']."</td>";
                echo "<td>".$row['Purpose']."</td>";
                echo "<td>".$row['FDC_Y_N']."</td>";

                /* My Code */

                echo "<td>".$row['type_of_course']."</td>";
                echo "<td>".$row['status_of_activity']."</td>";
                echo "<td>".$row['duration']."</td>";
                echo "<td>".$row['credit_audit']."</td>";       
                echo "<td>".$row['FDC_approved_disapproved']."</td>";       
				
                echo "<td>".$row['updated_at']."</td>";                


                /*My COde End*/

                $_SESSION['OC_A_ID'] = $row['OC_A_ID'];
                if(($row['certificate_path']) != "")
                {
                        if(($row['certificate_path']) == "NULL")
                            echo "<td>not yet available</td>";
                        else if(($row['certificate_path']) == "not_applicable") 
                            echo "<td>not applicable</td>";
                        else
                            echo "<td> <a href = '".$row['certificate_path']."' target='_blank'>View certificate</td>";
                }
                else
                        echo "<td>no status </td>";
                
                if(($row['report_path']) != "")
                {
                        if(($row['report_path']) == "NULL")
                            echo "<td>not yet available</td>";
                        else if(($row['report_path']) == "not_applicable") 
                            echo "<td>not applicable</td>";
                        else
                            echo "<td> <a href = '".$row['report_path']."' target='_blank'>View report</td>";
                }
                else
                        echo "<td>no status </td>";
					
		if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{			
                echo "<td>
                    <form action = 'upload-certificate-attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['OC_A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' id='hide_value' value = '".$row['OC_A_ID']."'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
					</form>
                </td>";
                echo "<td>
                    <form action = 'upload-report-attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['OC_A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' id='hide_value' value = '".$row['OC_A_ID']."'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
                
				$FDC_approved_disapproved= $row['FDC_approved_disapproved'];

				if($FDC_approved_disapproved== 'Disapproved' || $FDC_approved_disapproved== 'Not Approved' ||$FDC_approved_disapproved== 'not approved' || $FDC_approved_disapproved== '')
				{
				
                echo "<td>
                    <form action = '3_edit_online_attended_hod.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['OC_A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_online_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['OC_A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
				}
				else if($FDC_approved_disapproved== 'Approved')
				{
					echo "<td>
                    <form action = '3_edit_online_attended_hod.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['OC_A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_online_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['OC_A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
				}
                echo"</tr>";
				}
			}
        }
        else{
            //if ther are no entries
            echo "<div class='alert alert-warning'>You have no courses attended</div>";
        }	
        ?>
        
    </table>
	
       
	</div>
	
		<?php if ($_SESSION['rows'] > 0) {?>

	            <div class="text-left"><a href="actcount_course_attended.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Course</span></a>
	            <a href="count_all_online.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Online Course Attended/Organised </span> </a> 
		<a href="export_to_excel_online_attended_all.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>
      	
		
		<?php }else {?>
	            <div class="text-left"><a href="actcount_course_attended.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Course</span></a>


	<?php } ?>

	  <?php
            $_SESSION["type"] = "Attended";
        ?>
    <!--    <a href="select.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Show Charts</span></a>  -->

	            <!--<a href="mail_reminder.php" type="button" class="btn btn-success btn-sm" target="_blank"><span class="glyphicon ">Mail Reminder</span></a>--> </div>

    </div>
	
	
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
   
    
<?php include_once('footer.php'); ?>
   
   