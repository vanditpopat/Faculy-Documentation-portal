<?php
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result
$query = "SELECT * FROM organised where Fac_ID ='".$_SESSION['Fac_ID']."' ;";
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
					<h2 class="box-title"><b>STTP/Workshop/FDP Organised Activity Details</b></h2>
					<br>
					<b><a href="menu.php?menu=3 " style="font-size:15px;">STTP/Workshop/FDP Organised Activities</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Organised Activity</a></b>	
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
                <th>Title</th>
                <th>Type</th>
                <th>Organized by</th>
				<th>Resource Person</th>

                <th>Date from:<br><small>Y-M-D</small></th>
                <th>Date to:<br><small>Y-M-D</small></th>
				<th>Co-ordinated by</th>
                <th>Location</th>

                <th>Role of Faculty</th>
                <th>Full time/Part time</th>
                <th>No of Participants</th>
                <th>Duration</th>
                <th>Status of Activity</th>
                <th>Sponsors</th>
                <th>Sponsorship Details</th>
                <th>Approval Details</th>
                <th>Last Updated</th>

				<th>Permission Letter</th>
                <th>Certificate</th>
                <th>Report</th>
				<th>Brochure</th>


                <th>Upload Permission Letter</th>
				<th>Upload Certificate</th>
                <th>Upload Report</th>
				<th>Upload Brochure</th>

				

                <th>Edit</th>
                <th>Delete</th>

            </tr>
        </thead>
        <?php
				$_SESSION['rows'] = mysqli_num_rows($result);

        if(mysqli_num_rows($result)	> 0){
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
                echo "<tr>";
            //  echo "<td>".$row['Fac_ID']."</td>";
                echo "<td>".$row['Act_title']."</td>";
                echo "<td>".$row['Act_type']."</td>";
               
                echo "<td>".$row['Organized_by']."</td>";
                echo "<td>".$row['Resource']."</td>";
                
				echo "<td>".$row['Date_from']."</td>";
                echo "<td>".$row['Date_to']."</td>";
				echo "<td>".$row['Coordinated_by']."</td>";
                echo "<td>".$row['Location']."</td>";

                echo "<td>".$row['Role_Of_Faculty']."</td>";
                echo "<td>".$row['Time_Activities']."</td>";
                echo "<td>".$row['No_Of_Participants']."</td>";
                echo "<td>".$row['Equivalent_Duration']."</td>";
                echo "<td>".$row['Status_Of_Activity']."</td>";
                echo "<td>".$row['Sponsorship']."</td>";
                echo "<td>".$row['Sponsor_Details']."</td>";
                echo "<td>".$row['Approval_Details']."</td>";
                echo "<td>".$row['LastUpdated']."</td>";
               
				$_SESSION['A_ID'] = $row['A_ID'];

				
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
				
				if(($row['Brochure_path']) != "")
				{
						if(($row['Brochure_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['Brochure_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a target='_blank' href = '".$row['Brochure_path']."' target='_blank'>View Brochure</td>";
				}
				else
						echo "<td>no status </td>";
					
				
				
				
                echo "<td>
                    <form action = 'upload-permission_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
                echo "<td>
                    <form action = 'upload-certificate_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
				echo "<td>
                    <form action = 'upload-report_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				 echo "<td>
                    <form action = 'upload-brochure_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '3_edit_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
                echo"</tr>";
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

	            <div class="text-left"><a href="actcount_organised.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"> Add Organised Activity</span></a>
	            <a href="count_your_organised.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count STTP/Workshop/FDP Organised Activity </span></a> 
	            <a href="export_to_excel_organised_analysis.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 
	<?php }else {?>
		            <div class="text-left"><a href="actcount_organised.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"> Add Organised Activity</span></a>

	<?php } ?>

    </div>
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
<?php include_once('footer.php'); ?>
   
   