<?php
ob_start();
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index_excurricular.php");
}
/*if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}*/

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
$_SESSION['currentTab'] = 'ex';
//query and result
/*$query = "SELECT P_ID, Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_category,Date_from,Date_to
,Location,paper_path,certificate_path,report_path,Paper_co_authors,volume FROM faculty";*/
$query = "SELECT * from ex_curricular inner join facultydetails on ex_curricular.Fac_ID = facultydetails.Fac_ID ";

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
if($_SESSION['username'] == 'hodextc@somaiya.edu' || 'member@somaiya.edu' || 'hodcomp@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu')
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
					<h2 class="box-title"><b>Extra-Curricular Activity Details</b></h2>
					<br>
					<b><a href="menu.php?menu=7 " style="font-size:15px;">Extra-Curricular Activity</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Activity</a></b>	
					</div>               
			   </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--		<a href="menu.php?menu=7 "> <u>Back to Extra-curricular Menu</u></a> -->
				</div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <!-- <thead>
            <tr>
				<th>Faculty</th>
                <th>Activity Name</th>
                <th>Organized By</th>
                <th>Purpose</th>
                <th>Date from</th>
                <th>Date to</th>
                <th>Last edited</th>
				<th>Permission</th>
                <th>Certificate</th>
                <th>Report</th>
				
		<?php 
				// if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{
			?>		
                <th>Upload Permission</th>
                <th>Upload Certificate</th>
                <th>Upload Report</th>
                <th>Edit</th>
                <th>Delete</th>
		<?php }?>
            </tr>
        </thead> -->
        <?php
		$p1=$_SESSION['Fac_ID'];
		$query = "SELECT * from ex_curricular inner join facultydetails on ex_curricular.Fac_ID = facultydetails.Fac_ID ";
        $result = mysqli_query($conn,$query);
		$num=mysqli_num_rows($result);
		
				$_SESSION['rows'] = mysqli_num_rows($result);
		
        if(mysqli_num_rows($result)>0  ){
            //we have data to display 
            echo "<thead>
            <tr>
                <th>Faculty</th>
                <th>Activity Name</th>
                <th>Organized By</th>
                <th>Purpose</th>
                <th>Date from</th>
                <th>Date to</th>
                <th>Last edited</th>
                <th>Permission</th>
                <th>Certificate</th>
                <th>Report</th>";
                
         
                if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
                {
                  
                echo "<th>Upload Permission</th>
                <th>Upload Certificate</th>
                <th>Upload Report</th>
                <th>Edit</th>
                <th>Delete</th>";
        }
            echo "</tr>
        </thead>";
            while($row =mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['F_NAME']."</td>";

                echo "<td>".$row['activity_name']."</td>";
                echo "<td>".$row['organized_by']."</td>";
                echo "<td>".$row['purpose_of_activity']."</td>";
                echo "<td>".$row['Date_from']."</td>";
                echo "<td>".$row['Date_to']."</td>";
                echo "<td>".$row['currentTimestamp']."</td>";
                $_SESSION['ex_curricular_ID'] = $row['ex_curricular_ID'];
				
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
				
			if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{	
                echo "<td>
                    <form action = 'upload-permission_excurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['ex_curricular_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = 'upload-certificate_excurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['ex_curricular_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
				echo "<td>
                    <form action = 'upload-report_excurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['ex_curricular_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
                echo "<td>
                    <form action = '3_edit_hod_excurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['ex_curricular_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_excurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['ex_curricular_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
                echo"</tr>";
				}          
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
	
	            <div class="text-left"><a href="actcount_excurricular.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Activity</span></a>
	            <a href="count_all_excurricular.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Extra-Curricular Activities</span></a> 
	         <a href="export_to_excel_hod_excurricular.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>
	      
			<?php }else {?>
		            <div class="text-left"><a href="actcount_excurricular.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Activity</span></a>
		
			
	<?php } ?>

    </div>
	
	
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   