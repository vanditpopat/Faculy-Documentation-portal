<?php
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab']="co";

//connect to database
include("includes/connection.php");
include_once("includes/functions.php");
$fid = $_SESSION['Fac_ID'];

$ans = 'yes';




$fid = $_SESSION['Fac_ID'];

$query1 = "SELECT * FROM faculty where FDC_Y_N = 'no'";
		$result1 = mysqli_query($conn,$query1);
		if(mysqli_num_rows($result1)>0 ){
					//we have data to display 
					while($row =mysqli_fetch_assoc($result1)){
						$paperTitle = $row['Paper_title'];
						
						$query2 = "delete * FROM fdc where Fac_ID = $fid and Paper_title = '$paperTitle'";
						$result2 = mysqli_query($conn,$query2);
						
					}
		}
//query and result
$query = "SELECT * FROM fdc where Fac_ID ='".$_SESSION['Fac_ID']."';";
$result = mysqli_query($conn,$query);



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
if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu')
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

              <div class="box">
                <div class="box-header">
                  <h2 class="box-title">FDC details</h2>
                </div><!-- /.box-header -->
                <div class="box-body">
				<div class="scroll">
    <table  class="table table-stripped table-bordered " id = 'example1'>
        <thead>
            <tr>
                <th>Paper</th>
				
                <th>Minutes No.</th>
                <th>Serial No.</th>
                <th>Period</th>
                <th>OD approved</th>
                <th>OD availed</th>
                <th>Fee Sanctioned</th>
                <th>Fee availed</th>
               
                <th>Edit</th>
                <th>Delete</th>

            </tr>
        </thead>
        <?php
		
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
                echo "<td>".$row['Paper_title']."</td>";
				
                echo "<td>".$row['min_no']."</td>";
                echo "<td>".$row['serial_no']."</td>";
                echo "<td>".$ans1."</td>";
                echo "<td>".$row['od_approv']."</td>";
                echo "<td>".$row['od_avail']."</td>";
                echo "<td>".$row['fee_sac']."</td>";
                echo "<td>".$row['fee_avail']."</td>";

				$_SESSION['FDC_ID'] = $row['FDC_ID'];

				
               
				
                echo "<td>
                    <form action = '5_fdc_edit.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
                echo "<td>
                    <form action = '5_fdc_delete.php' method = 'POST'>
                        <input type = 'hidden' name = 'id' value = '".$row['FDC_ID']."'>
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
            echo "<div class='alert alert-warning'>You have no fdc</div>";
        }
		
        ?>
        
    </table>
	
       
	</div>
                <a href="2_dashboard.php" type="button" class="btn btn-primary">Skip</a>
 

    </div>
	
              </div>
             </div>
            </div>
          </section>
    
</div>
   
    
    
    
<?php include_once('footer.php'); ?>
   
   