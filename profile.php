<!--Header -->
<?php
if(!isset($_SESSION))
{
    session_start();
}
include("config.php");
if(isset($_SESSION["LOGIN_USER_TYPE"]))
{
    $system_user_type=$_SESSION["LOGIN_USER_TYPE"];
}
else
{
    $system_user_type="guest";
}
//priviledge
if($system_user_type=="Manager" || $system_user_type=="Branch Manager" || $system_user_type=="Cashier" || $system_user_type=="Sales Person" || $system_user_type=="Technician" )
{
if(isset($_SESSION["LOGIN_USER_NAME"]))
{	
    //get staff details
    $sql_get_staff_details="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
    $result_get_staff_details=mysqli_query($con,$sql_get_staff_details) or die ("Error in getting staff_details".mysqli_error($con));
    $row_get_staff_details=mysqli_fetch_assoc($result_get_staff_details);

    // get branch name
    $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_staff_details[branchid]'";
    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
    $row_get_branch=mysqli_fetch_assoc($result_get_branch);
?>
<div class="services-breadcrumb">
	<div class="agile_inner_breadcrumb">
		<div class="container">
			<ul class="w3_short">
				<li>
					<a href="index.php">Home</a>
					<i>|</i>
				</li>
				<li>Profile</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
			<div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    	<title>ACX Phone Shop</title>
                    		<div class="form-row" style="background-color: #dbd5d5;height: 490px;">
                    			<div class="form-group col-md-6" >
                    				<div style="display: flex;justify-content: center;vertical-align:center;">&nbsp
                    					<div>
                    						<img src="images/profile.png" width="480px" height="490px">

                    					</div>
                    				</div>
								</div>
								<div class="form-group col-md-6">
										<table class="table zero-configuration">&nbsp
											<tr>
												<td><h4>Name </td>
												<td><h4> : <?php echo $row_get_staff_details["staffname"] ?></h4></td>
											</tr>
											<tr>
												<td><h4>Designation </td>
												<td><h4> : <?php echo $row_get_staff_details["designation"] ?></h4></td>
											</tr>
											<tr>
												<td><h4>Branch </td>
												<td><h4> : <?php echo $row_get_branch["branchname"] ?></h4></td>
											</tr>
											<tr>
												<td><h4>DOB </td>
												<td><h4> : <?php echo $row_get_staff_details["dob"] ?></h4></td>
											</tr>
											<tr>
												<td><h4>Email </td>
												<td><h4> : <?php echo $row_get_staff_details["email"] ?></h4></td>
											</tr>
											<tr>
												<td><h4>Address </td>
												<td><h4> : <?php echo $row_get_staff_details["address"] ?></h4></td>
											</tr>
											<tr>
												<td><h4>Contact No </td>
												<td><h4> : <?php echo $row_get_staff_details["tpno"] ?></h4></td>
											</tr>
											<tr colspan="2" align="center">
												<td><a id="user_change_password" href="http://localhost/ACX/user_changepassword.php"><button class="btn btn-primary"><i class="fas fa-key"></i> Change Password</button></a></td>
											<?php
											if($system_user_type=="Manager")
												echo'<td><a id="user_change_branch" href="index.php?page=branch.php&option=change_branch"><button class="btn btn-primary"><i class="	fas fa-briefcase"></i> Change Branch</button></a></td>';
											?>
											</tr>
										</table>
								</div>	
							</div>
						</div>
					</div>
				</div>
						
		<?php
	}
}
else
{
	echo'<div class="card-body">
            <div class="alert alert-danger" role="alert">
                <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
            </div>
        </div>';
}
?>