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


$_SESSION['currentTab']="technical_review";
//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
//query and result
/*$query = "SELECT P_ID, Fac_ID,Paper_title,Paper_type,Paper_N_I,Paper_category,Date_from,Date_to
,Location,paper_path,certificate_path,report_path,Paper_co_authors,volume FROM faculty";*/
$query = "SELECT * from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID ";

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
if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
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
					<h2 class="box-title"><b>Technical Paper Reviewed Details</b></h2>
					<br>
					<b><a href="menu.php?menu=2 " style="font-size:15px;">Technical Paper Reviewed</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;View/Edit Activity</a></b>	
					</div>                </div><!-- /.box-header -->
				<div style="text-align:right">
				<!--	<a href="menu.php?menu=2 "> <u>Back to Technical Papers Reviewed Menu</u></a> -->
				</div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
				<th>Faculty</th>
                <th>Paper Title</th>
                <th>Paper Type</th>
                <th>Paper Level National/International</th>
				<th>Conference/Journal Name</th>
                <th>Paper Category</th>
                <th>Date from</th>
                <th>Date to</th>
                <th>Oranized by</th>
                <th>Paper details</th>
                <th>Volume</th>
                <th>Last updated</th>

			
                <th>Mail/Letter</th>
                <th>Certificate</th>
                <th>Report</th>
			<?php 
				if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu' )
				{
			?>
                <th>Upload Mail/Letter Copy</th>
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

                echo "<td>".$row['Paper_title']."</td>";
                echo "<td>".$row['Paper_type']."</td>";
                echo "<td>".$row['Paper_N_I']."</td>";
                echo "<td>".$row['conf_journal_name']."</td>";

                echo "<td>".$row['paper_category']."</td>";
                echo "<td>".$row['Date_from']."</td>";
                echo "<td>".$row['Date_to']."</td>";
               echo "<td>".$row['organised_by']."</td>";
                echo "<td>".$row['details']."</td>";
                echo "<td>".$row['volume']."</td>";
                echo "<td>".$row['last_added']."</td>";
				

				$_SESSION['paper_review_ID'] = $row['paper_review_ID'];
				
				 if(($row['mail_letter_path']) != "")
				{
						if(($row['mail_letter_path']) == "NULL")
							echo "<td>not yet available</td>";
						else if(($row['mail_letter_path']) == "not_applicable") 
							echo "<td>not applicable</td>";
						else
							echo "<td> <a href = '".$row['mail_letter_path']."' target='_blank'>View mail/letter</td>";
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
                    <form action = 'upload-mail-letter.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = 'upload-certificate-review.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
				echo "<td>
                    <form action = 'upload-report-review.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-upload'></span>
                        </button>
                    </form>
                </td>";
				
                echo "<td>
                    <form action = '3_edit_review_hod.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '4_delete_review.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
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
            echo "<div class='alert alert-warning'>You have no papers</div>";
        }
		
		
		
        ?>
        
    </table>
	
       
	</div>
		<?php if ($_SESSION['rows'] > 0) {?>

	            <div class="text-left"><a href="actcount_review.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Technical Papers Reviewed</span></a>
	            <a href="count_all_review.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Technical Papers Reviewed</span></a> 
			<a href="export_to_excel_review_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

	         <!--   <a href="mail_reminder.php" type="button" class="btn btn-success btn-sm" target="_blank"><span class="glyphicon ">Mail Reminder</span></a> </div> -->
<?php }else {?>
	            <div class="text-left"><a href="actcount_review.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Technical Papers Reviewed</span></a>
<?php } ?>

    </div>
	
	
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   