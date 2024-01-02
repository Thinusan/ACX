<?php
if(!isset($_SESSION))
{
    session_start();
}
if(isset($_SESSION["LOGIN_USER_TYPE"]))
{
    $system_user_type=$_SESSION["LOGIN_USER_TYPE"];
}
else
{
    $system_user_type="guest";
}
// page priviledege
if($system_user_type=="Manager")
{
?>
<!--Header -->
<div class="services-breadcrumb">
	<div class="agile_inner_breadcrumb">
		<div class="container">
			<ul class="w3_short">
				<li>
					<a href="index.php">Home</a>
					<i>|</i>
				</li>
				<li>CHANGE BRANCH</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_branch"]))
{   // insert staff
    $sql_insert_branch="INSERT INTO branch (branchid,branchname,address,tpno,managerid)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_name"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_address"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_contact_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_manager_id"])."')";
    $result_insert_branch=mysqli_query($con,$sql_insert_branch) or die("Error in inserting in branch".mysqli_error($con));
    // update branch of branch manger in staff
    $sql_update_staff="UPDATE staff SET 
                            branchid='".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."'
                            WHERE staffid='".mysqli_real_escape_string($con,$_POST["txt_manager_id"])."'";
     $result_update_staff=mysqli_query($con,$sql_update_staff) or die("Error in updating in manger branch in staff".mysqli_error($con));
    if($result_insert_branch & $result_update_staff)
    {
        echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=branch.php&option=view";
            </script>';
    }
}
if(isset($_POST["btn_edit_branch"]))
{   //update branch
    $sql_update_branch="UPDATE branch SET 
                            branchname='".mysqli_real_escape_string($con,$_POST["txt_branch_name"])."',
                            address='".mysqli_real_escape_string($con,$_POST["txt_branch_address"])."',
                            tpno='".mysqli_real_escape_string($con,$_POST["txt_branch_contact_number"])."',
                            managerid='".mysqli_real_escape_string($con,$_POST["txt_manager_id"])."'
                            WHERE branchid='".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."'";
    $result_update_branch=mysqli_query($con,$sql_update_branch) or die("Error in updating in branch".mysqli_error($con));
    //update branch of branch manager
    $sql_update_staff="UPDATE staff SET 
                            branchid='".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."'
                            WHERE staffid='".mysqli_real_escape_string($con,$_POST["txt_manager_id"])."'";
    $result_update_staff=mysqli_query($con,$sql_update_staff) or die("Error in updating in manger branch in staff".mysqli_error($con));
    if($result_update_branch & $result_update_staff)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=branch.php&option=view";
            </script>';
    }
}if(isset($_POST["btn_save_change_branch"]))
{   
    $sql_update_staff="UPDATE staff SET 
                            branchid='".mysqli_real_escape_string($con,$_POST["txt_assign_branch_id"])."'
                            WHERE nicno='".mysqli_real_escape_string($con,$_SESSION['LOGIN_USER_NAME'])."'"; 
    $result_update_staff=mysqli_query($con,$sql_update_staff) or die("Error in updating in manger branch in staff".mysqli_error($con));
    if($result_update_staff)
    {   // get branch name
        $sql_get_assigned_branch="SELECT * FROM branch WHERE branchid='$_POST[txt_assign_branch_id]'";
        $result_get_assigned_branch=mysqli_query($con,$sql_get_assigned_branch) or die ("Error in get Category".mysqli_error($con));
        $row_get_assigned_branch=mysqli_fetch_assoc($result_get_assigned_branch);

        echo'<div class="card-body">
            <div class="alert alert-success" role="alert">
                <center><h4>You Have Assigned To '.$row_get_assigned_branch["branchname"].' Branch</h4></center>
            </div>
        </div>';
        unset($_SESSION["BRANCHNAME"]);
        echo '<script>
                window.setInterval(function(){window.location.href="index.php?page=logout.php"},1200);
            </script>';
    }

}

	if(isset($_GET["option"]))
	{   
    		if($_GET["option"]=="add")
    		{ 
    			?>
    			<div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Branch Details</h4>
                                    <div class="basic-form">
                                        <form method="POST" action="" autocomplete="off">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                <?php 
                                                $sql_create_branch_id="SELECT branchid FROM branch ORDER BY branchid DESC LIMIT 1";
                                                $result_create_branch_id=mysqli_query($con,$sql_create_branch_id) or die ("Error in Creating id".mysqli_error($con));
                                                if(mysqli_num_rows($result_create_branch_id)==1)
                                                {
                                                    $row_create_branch_id=mysqli_fetch_assoc($result_create_branch_id);
                                                    $branch_id=++$row_create_branch_id["branchid"];
                                                }
                                                else
                                                {
                                                    $branch_id="BRA001";
                                                }
                                                ?>
                                                    <label>Branch ID</label>
                                                    <input type="text" readonly name="txt_branch_id" id="txt_branch_id" class="form-control" value="<?php echo $branch_id ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Branch Name</label>
                                                    <input type="text" name="txt_branch_name" id="txt_branch_name"class="form-control" placeholder="Jaffna" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Manager</label>
                                                    <select name="txt_manager_id" id="txt_manager_id" class="form-control chzn-select" required>
                                                    <option>Select Branch Manager</option>
                                                    <?php
                                                        $sql_get_staff="SELECT staffid,staffname FROM staff WHERE designation='Branch Manager'";
                                                        $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in getting staff".mysqli_error($con));
                                                        while ($row_get_staff=mysqli_fetch_assoc($result_get_staff)) 
                                                            {
                                                                echo '<option value="'.$row_get_staff["staffid"].'" >'.$row_get_staff["staffid"]." - ".$row_get_staff["staffname"].'</option>';
                                                            }
                                                    ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Branch Contact Number</label>
                                                    <input type="text" name="txt_branch_contact_number" id="txt_branch_contact_number" class="form-control" onblur="landphonenumber('txt_branch_contact_number')" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="txt_branch_address" id="txt_branch_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" required>
                                            </div>
                                                
                                    </div> 
                                    <div>
                                            <button type="submit" name="btn_save_branch" id="btn_save_branch" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                           <a href="index.php?page=branch.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div> 
                                       </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
            }
            elseif ($_GET["option"]=="view") 
            {
            ?>
                <div class="content-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Branch Details</h4>
                                        <div class="col-6" style="padding-bottom: 10px;">
                                            <a href="index.php?page=branch.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered zero-configuration">
                                                <thead>
                                                    <tr>
                                                        <th>Branch ID</th>
                                                        <th>Branch Name</th>
                                                        <th>Address</th>
                                                        <th>Telephone No</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql_branch_details_view="SELECT branchid,branchname,address,tpno FROM branch";
                                                    $result_branch_details_view=mysqli_query($con,$sql_branch_details_view)or die("Error in branch details view".mysqli_error());
                                                    while ($row_branch_details_view=mysqli_fetch_assoc($result_branch_details_view)) 
                                                    {
                                                        echo '<tr>
                                                                <td>'.$row_branch_details_view["branchid"].'</td>
                                                                <td>'.$row_branch_details_view["branchname"].'</td>
                                                                <td>'.$row_branch_details_view["address"].'</td>
                                                                <td>'.$row_branch_details_view["tpno"].'</td>
                                                                <td>
                                                                    <a href="index.php?page=branch.php&option=fullview&branch_id='.$row_branch_details_view["branchid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
                                                                    <a href="index.php?page=branch.php&option=edit&branch_id='.$row_branch_details_view["branchid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>
                                                                </td>
                                                            </tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #/ container -->
                </div>
    		 <?php
    		}
               elseif ($_GET["option"]=="edit") 
            {
                $get_branch_id=$_GET["branch_id"];
                $sql_get_branch_detail="SELECT * FROM branch WHERE branchid='$get_branch_id'";
                $result_get_branch_detail=mysqli_query($con,$sql_get_branch_detail)or die("Error in geting branch details".mysqli_error($con));
                $row_get_branch_detail=mysqli_fetch_assoc($result_get_branch_detail);
                ?>
                <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Branch Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Branch ID</label>
                                                <input type="text" readonly name="txt_branch_id" id="txt_branch_id" class="form-control" value="<?php echo $row_get_branch_detail["branchid"] ?>" readonly>
                                            </div>
                                             <div class="form-group col-md-6">
                                                <label>Branch Name</label>
                                                <input type="text" name="txt_branch_name" id="txt_branch_name" class="form-control" placeholder="Example:A.W.S.Kumara" value="<?php echo $row_get_branch_detail["branchname"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Manager ID</label>
                                                <select name="txt_manager_id" id="txt_manager_id" class="form-control chzn-select" required>
                                                    <?php
                                                         // check login for ststus
                                                        $sql_get_login="SELECT userid FROM login WHERE status='Active' AND usertype='Branch Manager'";
                                                        $result_get_login=mysqli_query($con,$sql_get_login)or die("Error in login details view".mysqli_error());
                                                        while($row_get_login=mysqli_fetch_assoc($result_get_login))
                                                        {
                                                        //get staff
                                                        $sql_get_staff="SELECT staffid,staffname FROM staff WHERE designation='Branch Manager' AND nicno='$row_get_login[userid]'";
                                                        $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in getting staff".mysqli_error($con));
                                                        while ($row_get_staff=mysqli_fetch_assoc($result_get_staff)) 
                                                            {   
                                                                if($row_get_branch_detail["managerid"]==$row_get_staff["staffid"]){
                                                                echo '<option value="'.$row_get_staff["staffid"].'" selected>'.$row_get_staff["staffid"]." - ".$row_get_staff["staffname"].'</option>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<option value="'.$row_get_staff["staffid"].'" >'.$row_get_staff["staffid"]." - ".$row_get_staff["staffname"].'</option>';
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Branch Contact Number</label>
                                                <input type="text" name="txt_branch_contact_number" id="txt_branch_contact_number" class="form-control" placeholder="Mobile Number" value="<?php echo $row_get_branch_detail["tpno"] ?>" onblur="landphonenumber('txt_branch_contact_number')" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="txt_branch_address" id="txt_branch_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" value="<?php echo $row_get_branch_detail["address"] ?>" required>
                                        </div>
                                        <div>
                                            <button type="submit" name="btn_edit_branch" id="btn_edit_branch" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                            <a href="index.php?page=branch.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
            }
            elseif ($_GET["option"]=="fullview") 
            {   // get branch details
                $get_branch_id=$_GET["branch_id"];
                $sql_branch_fullview="SELECT * FROM branch WHERE branchid='$get_branch_id'";
                $result_branch_fullviewl=mysqli_query($con,$sql_branch_fullview)or die("Error in geting branch fullview details".mysqli_error($con));
                $row_branch_fullview=mysqli_fetch_assoc($result_branch_fullviewl);
                // get manager details
                $sql_get_manager_name="SELECT staffname FROM staff WHERE staffid='$row_branch_fullview[managerid]'";
                $result_get_manager_name=mysqli_query($con,$sql_get_manager_name) or die ("Error in getting manager_name".mysqli_error($con));
                $row_get_manager_name=mysqli_fetch_assoc($result_get_manager_name);

                ?>
                <div class="content-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Branch Full Details</h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered zero-configuration">
                                                <tr><th style="width: 50%">Branch Id</th><td><?php echo $row_branch_fullview["branchid"] ?></td></tr>
                                                <tr><th>Branch Name</th><td><?php echo $row_branch_fullview["branchname"] ?></td></tr>
                                                <tr><th>Manager</th><td><?php echo $row_get_manager_name["staffname"] ?></td></tr>
                                                <tr><th>Branch Contact Number</th><td><?php echo $row_branch_fullview["tpno"] ?></td></tr>
                                                <tr><th>Address</th><td><?php echo $row_branch_fullview["address"] ?></td></tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <center>
                                                            <a href="index.php?page=branch.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
                                                            <a href="index.php?page=branch.php&option=edit&branch_id=<?php echo $row_branch_fullview["branchid"]?>"><button type="button"class="btn btn-warning"><i class="fas fa-plus"></i> Edit</button></a>
                                                        </center>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #/ container -->
                </div>
            <?php
        }
        elseif ($_GET["option"]=="change_branch" & $system_user_type=="Manager") 
        {   // get current branch id
            $sql_get_branchid="SELECT branchid FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
            $result_get_branchid=mysqli_query($con,$sql_get_branchid) or die ("Error in getting enterby".mysqli_error($con));
            $row_get_branchid=mysqli_fetch_assoc($result_get_branchid);
            
            // get branch name
            $sql_get_current_branch="SELECT * FROM branch WHERE branchid='$row_get_branchid[branchid]'";
            $result_get_current_branch=mysqli_query($con,$sql_get_current_branch) or die ("Error in get Category".mysqli_error($con));
            $row_get_current_branch=mysqli_fetch_assoc($result_get_current_branch);

            ?> 
            <div class="col-lg-12" id="change">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Change Branch</h4>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                         <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Logged In Branch Name</label>
                                                <input type="text" name="txt_logged_in_branch_name" id="txt_logged_in_branch_name" class="form-control" value="<?php echo $row_get_current_branch["branchid"];echo " - ";echo $row_get_current_branch["branchname"] ;  ?>" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Assign To - Branch</label>
                                                <select name="txt_assign_branch_id" id="txt_assign_branch_id" class="form-control" required>
                                                <option selected disabled hidden value="">Select Branch</option>
                                                <?php
                                                    $sql_get_branch="SELECT * FROM branch";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                                                    while ($row_get_branch=mysqli_fetch_assoc($result_get_branch)) 
                                                    {
                                                        echo '<option value="'.$row_get_branch["branchid"].'">'.$row_get_branch["branchname"].'</option>';  
                                                    }

                                                ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div>
                                        <button type="submit" name="btn_save_change_branch" id="btn_save_change_branch" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <?php
                                         if(isset($_GET["url_id"]))
                                        {   if($_GET["url_id"]=="model")
                                            {
                                                echo '<a href="index.php?page=model.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                            }else{}
                                        }
                                        else
                                        {
                                                echo '<a href="index.php?page=profile.php"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                        }
                                        ?>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
        } 
        elseif ($_GET["option"]=="change_branch" & $system_user_type!="Manager") 
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
        }
    	else
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>OOPS!</b></h1></center>
                        <center><h3>- 404 Not Found -</h3></center>
                        <center><h4>Sorry, Requested page not found!</h4></center>
                    </div>
                </div>'; 
        }
    }
    else
    {
        echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>OOPS!</b></h1></center>
                        <center><h3>- 404 Not Found -</h3></center>
                        <center><h4>Sorry, Requested page not found!</h4></center>
                    </div>
                </div>'; 
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