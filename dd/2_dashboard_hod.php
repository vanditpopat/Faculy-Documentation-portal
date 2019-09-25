
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

$_SESSION['currentTab'] = "paper";
 include_once('head.php'); 
include_once('header.php'); 
 
if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu')
  {
	    include_once('sidebar_hod.php');

  }
  else
	  include_once('sidebar.php');


//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result
/*$query = "SELECT P_ID, Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_category,Date_from,Date_to
,Location,paper_path,certificate_path,report_path,Paper_co_authors,volume FROM faculty";*/
$query = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID ";
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






<style>
div.scroll
{
overflow:scroll;

}


</style>
<script type="text/javascript">
	function preventBack() { window.history.forward(); 
	
	}
	setTimeout("preventBack()",0);
	
	window.onunload = function() {null};
</script>


	
<div class="content-wrapper">
   <?php 
        {
        echo $successMessage;
    }
	$display = 0;
	if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
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
					<h2 class="box-title"><b>Paper Publication/Presentation</b></h2>
					<br>
					<b><a href="menu.php?menu=1 " style="font-size:15px;">Paper Publication</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Activity</a></b>	
					</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
			<!--	<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u></a>&nbsp&nbsp -->
                </div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
				<th>Faculty</th>
                <th>Title</th>
                <th>Type</th>
                <th>N/I</th>
                <th>Conference/Journal Name</th>

                <th>Category</th>
                <th>Co-authors</th>
                <th>Year</th>
                <th>Month</th>
                <th>Location</th>
                <th>Volume</th>
                <th>Index (Scopus/Sci/Both) applicable</th>
				
                <th>Index (Scopus/Sci/Both)</th>
				
				<th>H-Index</th>
                <th>Citations</th>
				
				<th>FDC Status</th>
				<th>Presentation Status</th>
                <th>Awards</th>
                <th>Link of Publication</th>
				<th>Presented By</th>
				<th>FDC Approval Status</th>
				<th>Added on</th>
				<th>Updated on</th>				
				
                <th>Paper</th>
                <th>Certificate</th>
                <th>Report</th>
			<?php 
				if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{
			?>
                <th>Upload Paper</th>
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

                echo "<td>".$row['Paper_title']."</td>";
                echo "<td>".$row['Paper_type']."</td>";
                echo "<td>".$row['Paper_N_I']."</td>";
                echo "<td>".$row['conf_journal_name']."</td>";
				
                echo "<td>".$row['paper_category']."</td>";
                echo "<td>".$row['Paper_co_authors']."</td>";
                echo "<td>".$row['Date_from']."</td>";
                echo "<td>".$row['Date_to']."</td>";
                echo "<td>".$row['Location']."</td>";
                echo "<td>".$row['volume']."</td>";
                echo "<td>".$row['scopusindex']."</td>";
                echo "<td>".$row['scopus']."</td>";
				
				echo "<td>".$row['h_index']."</td>";
                echo "<td>".$row['citations']."</td>";
				
                echo "<td>".$row['FDC_Y_N']."</td>";
				echo "<td>".$row['presentation_status']."</td>";
				echo "<td>".$row['Paper_awards']."</td>";
				echo "<td>".$row['Link_publication']."</td>";
				echo "<td>".$row['presented_by']."</td>";
				echo "<td>".$row['FDC_approved_disapproved']."</td>";
				echo "<td>".$row['Adate']."</td>";
				echo "<td>".$row['Udate']."</td>";
				
				$_SESSION['P_ID'] = $row['P_ID'];
				
				if(($row['paper_path']) != "")
				{
						if(($row['paper_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['paper_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a href = '".$row['paper_path']."' target='_blank'>View paper</td>";
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
					
					
						<form action = 'upload-paper.php' method = 'POST'>
						
						 <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-upload'></span>
							</button>
						</form>
					</td>";
					echo "<td>
						<form action = 'upload-certificate.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-upload'></span>
							</button>
						</form>
					</td>";
					
					echo "<td>
						<form action = 'upload-report.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-upload'></span>
							</button>
						</form>
					</td>";
					
					$FDC_approved_disapproved= $row['FDC_approved_disapproved'];

				if($FDC_approved_disapproved== 'disapproved' || $FDC_approved_disapproved== 'not approved')
				{
					echo "<td>
						<form action = '3_edit_hod.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-edit'></span>
							</button>
						</form>
					</td>";
				}
				else if($FDC_approved_disapproved== 'approved')
				{
					echo "<td>
						<form action = '3_edit_hod.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
								<span class='glyphicon glyphicon-edit'></span>
							</button>
						</form>
					</td>";
				}

				if($FDC_approved_disapproved== 'disapproved' || $FDC_approved_disapproved== 'not approved')
				{
					echo "<td>
							<form action = '4_delete.php' method = 'POST'>
								<input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
								<button type = 'submit' class = 'btn btn-primary btn-sm' >
									<span class='glyphicon glyphicon-trash'></span>
								</button>
							</form>
						</td>";
				}
				else if($FDC_approved_disapproved== 'approved')
				{
						echo "<td>
							<form action = '4_delete.php' method = 'POST'>
								<input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
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
            echo "<div class='alert alert-warning'>You have no papers</div>";
        }
		
		
		
        ?>
        
    </table>
	
       
	</div>
		<?php if ($_SESSION['rows'] > 0) {?>

	            <div class="text-left"><a href="actcount.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper</span></a>
	            <a href="count_all.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Publications</span></a> 
	           <!-- <a href="mail_reminder.php" type="button" class="btn btn-success btn-sm" target="_blank"><span class="glyphicon ">Mail Reminder</span></a> </div>-->
			   <a href="export_to_excel_publication_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 
	<?php }else {?>

	            <div class="text-left"><a href="actcount.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper</span></a>

		<?php } ?>

    </div>
	
	
	
              </div>
             </div>
            </div>
          </section>
    
</div>
  
    
<?php include_once('footer.php'); ?>
   
   