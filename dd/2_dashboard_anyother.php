<?php
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}

$_SESSION['currentTab']="anyOther";

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result
$query = "SELECT * FROM any_other_activity where Fac_ID ='".$_SESSION['Fac_ID']."' ;";
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
					<h2 class="box-title"><b>Any Other Activity</b></h2>
					<br>
					<b><a href="menu.php?menu=8 " style="font-size:15px;">Any Other Activity</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Activity</a></b>	
					</div>
				
				</div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=8 "> <u>Back to Any Other Menu</u></a> -->
				</div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
                <th>Activity Name</th>
                <th>Organized By</th>
                <th>Purpose</th>
                <th>Date from</th>
                <th>Date to</th>
                <th>Last Edited</th>
				<th>Permission</th>
                <th>Certificate</th>
                <th>Report</th>
                <th>Upload Permission</th>
                <th>Upload Certificate</th>
                <th>Upload Report</th>
                <th>Edit</th>
                <th>Delete</th>

            </tr>
        </thead>
        <?php
		$p1=$_SESSION['Fac_ID'];
		$query = "SELECT * FROM any_other_activity where Fac_ID ='$p1'";
        $result = mysqli_query($conn,$query);
		$_SESSION['rows'] = mysqli_num_rows($result);
		
        if(mysqli_num_rows($result)>0){
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
                echo "<tr>";
            //  echo "<td>".$row['Fac_ID']."</td>";
                echo "<td>".$row['activity_name']."</td>";
                echo "<td>".$row['organized_by']."</td>";
                echo "<td>".$row['purpose_of_activity']."</td>";
                echo "<td>".$row['Date_from']."</td>";
                echo "<td>".$row['Date_to']."</td>";
                echo "<td>".$row['currentTimestamp']."</td>";
                

				$_SESSION['any_other_ID'] = $row['any_other_ID'];

				if(($row['permission_path']) != "")
				{
						if(($row['permission_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['permission_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a href = '".$row['permission_path']."' target='_blank'>View Permission Letter</td>";
				}
				else
						echo "<td>no status </td>";
				
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
				
				
                echo "<td>
                    <form action = 'upload-permission_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = 'upload-certificate_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
				echo "<td>
                    <form action = 'upload-report_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
                echo "<td>
                    <form action = '3_edit_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
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
            echo "<div class='alert alert-warning'>You have no activity</div>";
        }
        ?>
        
    </table>
	
       
	</div>
		<?php if ($_SESSION['rows'] > 0) {?>

	            <div class="text-left"><a href="actcount_anyother.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Activity</span></a>
	           <a href="count_your_anyother.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Any Other Activity</span></a> 

	            <a href="export_to_excel_anyother.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 
	<?php }else {?>
		            <div class="text-left"><a href="actcount_anyother.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Activity</span></a>

	<?php } ?>

    </div>
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
<?php include_once('footer.php'); ?>
   
   