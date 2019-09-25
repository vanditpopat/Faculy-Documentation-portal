<?php
session_start();
$_SESSION['active'] = 1;
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab']="sttp";

//connect to database
include("includes/connection.php");
include_once("includes/functions.php");
$fid = $_SESSION['Fac_ID'];

$ans = 'yes';

$sql2 = "SELECT * from fdc_attended";

				$result2 = mysqli_query($conn,$sql2);
				
				
				
				if(mysqli_num_rows($result2)>0){
					$sql = "SELECT * from attended where FDC_Y_N = 'yes' && Fac_ID = '$fid'";

					$result1 = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result1)>0  ){
								//we have data to display 
								while($row =mysqli_fetch_assoc($result1)){
									
									
									$aid[] = $row['A_ID'];
									
										
									
								}
						while($row =mysqli_fetch_assoc($result2)){
						$aid_fdc[] = $row['A_ID'];
						
						
					}
					
					for ($i = 0; $i < count($aid) ;  $i++)
					{
						$equal = 0;
						for ($j = 0 ; $j < count($aid_fdc); $j++)
						{
							if($aid[$i] == $aid_fdc[$j])
							{
								$equal = 1;
								break;
											
							}
														
						}
						if($equal == 0)
							add_fdc($aid[$i]);
					}		
								
					}
					else
					{
						$query = "SELECT * FROM fdc_attended where Fac_ID ='".$_SESSION['Fac_ID']."';";
						$result = mysqli_query($conn,$query);
					}
			
					
				}
				else if(mysqli_num_rows($result2) == 0  )
				{
					$rows = mysqli_num_rows($result2);
					$sql = "SELECT * from attended where FDC_Y_N = 'yes' && Fac_ID = '$fid' ";

					$result1 = mysqli_query($conn,$sql);

					if(mysqli_num_rows($result1)>0  ){
								
								while($row =mysqli_fetch_assoc($result1)){
									
									$Fac_ID[] = $row['Fac_ID'];
									$aid[] = $row['A_ID'];
									$Act_title[] = $row['Act_title'];

										
									
								}
								if(count($aid) != 0)		
								{	
								for ($i = 0; $i < count($aid) ;  $i++)
								{
									
									add($Fac_ID[$i],$aid[$i],$Act_title[$i] );

									
								}
								}
					}
					else
					{
						 $query = "SELECT * FROM fdc_attended where Fac_ID ='".$_SESSION['Fac_ID']."';";
						$result = mysqli_query($conn,$query);

					}
					
					
					
				}
				
function add_fdc($aid)
	{
		include("includes/connection.php");

		$sql_fdc = "select * from attended WHERE A_ID = $aid";
		$result_fdc = mysqli_query($conn,$sql_fdc);
		
		if(mysqli_num_rows($result_fdc)>0  )
		{
								
			while($row =mysqli_fetch_assoc($result_fdc)){
									
									
				$Fac_ID = $row['Fac_ID'];
				$Act_title = $row['Act_title'];
							
									
			}
		}
			
		$sql="INSERT INTO fdc_attended(Fac_ID, A_ID, Act_title) VALUES ('$Fac_ID','$aid','$Act_title')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	}
	function add($Fac_ID, $aid, $Act_title)
	{
		include("includes/connection.php");

		$sql="INSERT INTO fdc_attended(Fac_ID, A_ID, Act_title) VALUES ('$Fac_ID','$aid','$Act_title')";

			if ($conn->query($sql) === TRUE) {
				$success = 1;
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	}

$fid = $_SESSION['Fac_ID'];






$successMessage="";
if(isset($_GET['alert'])){
    if($_GET['alert']=="success"){
        $successMessage='<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record added successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="update"){
        $successMessage='<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Record updated successfully</strong>
        </div>';  

    }
    elseif($_GET['alert']=="delete"){
        $successMessage='<div class="alert alert-success">
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
    ?>
    <section class="content">
          <div class="row">
            <div class="col-xs-12">
						  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
				<div class="icon">
					<i style="font-size:18px" class="fa fa-table"></i>
					<h2 class="box-title"><b>FDC details for STTP/Workshop/FDP Attended</b></h2>
					<br>
					<b><a href="menu.php?menu=3 " style="font-size:15px;"> STTP/Workshop/FDP</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;FDC Details</a></b>	
				</div>
                </div><!-- /.box-header -->
				<div style="text-align:right">
				<a href="menu.php?menu=3 " style="text-align:right"> <u>Back to STTP/Workshop/FDP Menu</u></a>&nbsp;&nbsp; 
                </div>
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
                <th>Activity</th>
				
                <th>Minutes No.</th>
                <th>Serial No.</th>
                <th>Period</th>
                <th>OD approved</th>
                <th>OD availed</th>
                <th>Fee Sanctioned</th>
                <th>Fee availed</th>
   				<th>Last Updated</th>

   				<th>FDC Approval Status</th>

                <th>Edit</th>
            <!--    <th>Delete</th> -->

            </tr>
        </thead>
        <?php
		$query = "SELECT * FROM fdc_attended where Fac_ID ='".$_SESSION['Fac_ID']."';";
		$result = mysqli_query($conn,$query);
        if(mysqli_num_rows($result)>0 ){
            //we have data to display 
            while($row =mysqli_fetch_assoc($result)){
				
				
					
				
				
				if ($row['period'] == 0)
				{
					$ans1 = "vacational";
					
				}
				else if ($row['period'] == 1)
					$ans1 = "non vacational";
				
                echo "<tr>";
                echo "<td>".$row['Act_title']."</td>";
				
                echo "<td>".$row['min_no']."</td>";
                echo "<td>".$row['serial_no']."</td>";
                echo "<td>".$ans1."</td>";
                echo "<td>".$row['od_approv']."</td>";
                echo "<td>".$row['od_avail']."</td>";
                echo "<td>".$row['fee_sac']."</td>";
                echo "<td>".$row['fee_avail']."</td>";
                echo "<td>".$row['LastUpdated']."</td>";
				
                echo "<td>".$row['ApprovalStatus']."</td>";

				$_SESSION['FDC_ID'] = $row['FDC_ID'];
				$FDC_approved_disapproved= $row['ApprovalStatus'];

				
               if($FDC_approved_disapproved == 'disapproved' || $FDC_approved_disapproved== 'not approved' || $FDC_approved_disapproved == 'Disapproved' || $FDC_approved_disapproved== 'Not Approved' )
				{
					echo "<td>
						<form action = '5_fdc_edit_attend.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm'>
								<span class='glyphicon glyphicon-edit'></span>
							</button>
						</form>
					</td>";
				}
				else if($FDC_approved_disapproved== 'Approved' || $FDC_approved_disapproved== 'approved' )
				{
					echo "<td>
						<form action = '5_fdc_edit_attend.php' method = 'POST'>
							<input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
							<button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
								<span class='glyphicon glyphicon-edit'></span>
							</button>
						</form>
					</td>";
				}
				
			echo"</tr>";

            }

        }
        else{
            //if there are no entries
            echo "<div class='alert alert-warning'>You have no fdc</div>";
        }
		
        ?>
        
    </table>
	
       
	</div>
                <a href="menu.php?menu=3 " type="button" class="btn btn-primary">Skip</a>
 

    </div>
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   