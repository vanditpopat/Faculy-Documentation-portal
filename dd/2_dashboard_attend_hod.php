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

$_SESSION['currentTab'] = "sttp";

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result

$query = "SELECT * from attended inner join facultydetails on attended.Fac_ID = facultydetails.Fac_ID ";

$result = mysqli_query($conn,$query);



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
if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
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
					<h2 class="box-title"><b>STTP/Workshop/FDP Attended Activity Details</b></h2>
					<br>
					<b><a href="menu.php?menu=3 " style="font-size:15px;">STTP/Workshop/FDP Attended Activities</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Attended Activity</a></b>	
					</div>                 

				 </div><!-- /.box-header -->
				<!-- <div style="text-align:right">
					<a href="menu.php?menu=3 "> <u>Back to STTP/Workshop/FDP Attended/Organised Menu</u></a>
				</div> -->
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
				<th>Faculty</th>
                <th>Title</th>
                <!-- <th>ID</th> -->
                <th>Type</th>
                <th>Organized by</th>
                <th>Date from:<br><small>Y-M-D</small></th>
                <th>Date to:<br><small>Y-M-D</small></th>
                <th>Status Of Activity</th>
                <th>Equivalent Duration</th>
                <th>Awards</th>
                <th>Location</th>
				<th>FDC Status</th>
                <th>Approval Status</th>
                <th>Last Updated</th>
                <th>Permission Letter</th>
                <th>Certificate</th>
                <th>Report</th>
		<?php 
				if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{
			?>	
                <th>Upload Permission Letter</th>
                <th>Upload Certificate</th>
                <th>Upload Report</th>
			
                <th>Edit</th>
                <th>Delete</th>
		<?php }?>
            </tr>
        </thead>
        <?php
		$_SESSION['rows'] = mysqli_num_rows($result);
		
        if(mysqli_num_rows($result)>0  ){
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['F_NAME']."</td>";
                echo "<td>".$row['Act_title']."</td>";
                // echo "<td>".$row['A_ID']."</td>";
                echo "<td>".$row['Act_type']."</td>";
                echo "<td>".$row['Organized_by']."</td>";
                echo "<td>".$row['Date_from']."</td>";
                echo "<td>".$row['Date_to']."</td>";
                echo "<td>".$row['Status_Of_Activity']."</td>";
                echo "<td>".$row['Equivalent_Duration']."</td>";
                echo "<td>".$row['Awards']."</td>";
                echo "<td>".$row['Location']."</td>";
                
				
                echo "<td>".$row['FDC_Y_N']."</td>";
                echo "<td>".$row['ApprovalStatus']."</td>";
                echo "<td>".$row['LastUpdated']."</td>";
				$_SESSION['A_ID'] = $row['A_ID'];
				
				$ApprovalStatus = $row['ApprovalStatus'];
				
				if(($row['Permission_path']) != "")
				{
						if(($row['Permission_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['Permission_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a target='_blank' href = '".$row['Permission_path']."' target='_blank'>View permission</td>";
				}
				else
						echo "<td>no status </td>";

				
				if(($row['Certificate_path']) != "")
				{
						if(($row['Certificate_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['Certificate_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a target='_blank' href = '".$row['Certificate_path']."' target='_blank'>View certificate</td>";
				}
				else
						echo "<td>no status </td>";
				
				if(($row['Report_path']) != "")
				{
						if(($row['Report_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['Report_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a target='_blank' href = '".$row['Report_path']."' target='_blank'>View report</td>";
				}
				else
						echo "<td>no status </td>";
               
			   
			    if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{ 
			   
                echo "<td>
				
				
                    <form action = 'upload-permission_attend.php' method = 'POST'>
					
                     <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = 'upload-certificate_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
				echo "<td>
                    <form action = 'upload-report_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
		if($ApprovalStatus== 'Disapproved' || $ApprovalStatus== 'Not Approved' || $ApprovalStatus== 'disapproved' || $ApprovalStatus== 'not approved' ) {
		
				
                echo "<td>
                    <form action = '3_edit_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
		}
		else if($ApprovalStatus== 'Approved' ) {
			 echo "<td>
                    <form action = '3_edit_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
			
		}

	}
	 echo "</tr>";

			}
        }
        else{
            //if ther are no entries
            echo "<div class='alert alert-warning'>You have no activities</div>";
        }
		
		
		
        ?>
        
    </table>
	
       
	</div>
		<?php if ($_SESSION['rows'] > 0) {?>
	
	            <div class="text-left"><a href="actcount_attend.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"> Add Activity</span></a>
	            <a href="count_all_attend.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count STTP/Workshop/FDP Attended Activities</span></a> 
	           <!-- <a href="mail_reminder.php" type="button" class="btn btn-success btn-sm" target="_blank"><span class="glyphicon ">Mail Reminder</span></a> </div>-->
			  <a href="export_to_excel_attend_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 
	    
			<?php }else {?>
				            <div class="text-left"><a href="actcount_attend.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"> Add Activity</span></a>

	<?php } ?>


    </div>
	
	
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
  
<?php include_once('footer.php'); ?>
   
   