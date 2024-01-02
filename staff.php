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
if($system_user_type=="Manager" || $system_user_type=="Branch Manger")
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
				<li>Staff</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_staff"]))
{   $sql_insert_login="INSERT INTO login(userid,password,usertype,attempt,status)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_staff_nic_number"])."',
                                '".mysqli_real_escape_string($con,md5($_POST["txt_staff_mobile_number"]))."',
                                '".mysqli_real_escape_string($con,$_POST["txt_designation"])."',
                                '".mysqli_real_escape_string($con,0)."',
                                '".mysqli_real_escape_string($con,"Active")."')";
    $result_insert_login=mysqli_query($con,$sql_insert_login) or die("Error in inserting in login".mysqli_error($con));
   
    $sql_insert_staff="INSERT INTO staff(staffid,staffname,address,tpno,nicno,designation,branchid,dob,gender,email)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_staff_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_name"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_address"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_mobile_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_nic_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_designation"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_dob"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_gender"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_staff_email"])."')";
    $result_insert_staff=mysqli_query($con,$sql_insert_staff) or die("Error in inserting in staff".mysqli_error($con));
    if($result_insert_login && $result_insert_staff)
    {
        echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=staff.php&option=view";
            </script>';
    }

}
if(isset($_POST["btn_edit_staff"]))
{
    $sql_update_staff="UPDATE staff SET 
                            staffname='".mysqli_real_escape_string($con,$_POST["txt_staff_name"])."',
                            address='".mysqli_real_escape_string($con,$_POST["txt_staff_address"])."',
                            tpno='".mysqli_real_escape_string($con,$_POST["txt_staff_mobile_number"])."',
                            nicno='".mysqli_real_escape_string($con,$_POST["txt_staff_nic_number"])."',
                            designation='".mysqli_real_escape_string($con,$_POST["txt_designation"])."',
                            branchid='".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                            dob='".mysqli_real_escape_string($con,$_POST["txt_staff_dob"])."',
                            gender='".mysqli_real_escape_string($con,$_POST["txt_staff_gender"])."',
                            email='".mysqli_real_escape_string($con,$_POST["txt_staff_email"])."'
                            WHERE staffid='".mysqli_real_escape_string($con,$_POST["txt_staff_id"])."'";
     $result_update_staff=mysqli_query($con,$sql_update_staff) or die("Error in updating in staff".mysqli_error($con));
    if($result_update_staff)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=staff.php&option=view";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_staff_nic_no()
    {
        let nicno=document.getElementById("txt_staff_nic_number").value;
        if(nicno!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result_nic=xmlhttp.responseText.trim();
                    if(result_nic=="true")
                    {   
                        alert("Same NIC Number Already Exists");
                        document.getElementById("txt_staff_nic_number").value="";
                        document.getElementById("txt_staff_nic_number").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_staff_nic_no&ajax_nicno=" + nicno, true);
            xmlhttp.send();
        }
        else
        {
            
        }
    }
</script>
<script type="text/javascript">
    function check_staff_mobile_no()
    {
        let mobileno=document.getElementById("txt_staff_mobile_number").value;
        if(mobileno!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result_mobileno=xmlhttp.responseText.trim();
                    if(result_mobileno=="true")
                    {   
                        alert("Same Mobile Number Already Exists");
                        document.getElementById("txt_staff_mobile_number").value="";
                        document.getElementById("txt_staff_mobile_number").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_staff_mobile_no&ajax_mobile_no=" + mobileno, true);
            xmlhttp.send();
        }
        else
        {
            
        }
    }
</script>
<?php
	if(isset($_GET["option"]))
	{
		if($_GET["option"]=="add" & $system_user_type=="Manager")
		{
			
			?>
			<div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Staff Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <?php 
                                            $sql_create_staff_id="SELECT staffid FROM staff ORDER BY staffid DESC LIMIT 1";
                                            $result_create_staff_id=mysqli_query($con,$sql_create_staff_id) or die ("Error in Creating id".mysqli_error($con));
                                            if(mysqli_num_rows($result_create_staff_id)==1)
                                            {
                                                $row_create_staff_id=mysqli_fetch_assoc($result_create_staff_id);
                                                $staff_id=++$row_create_staff_id["staffid"];
                                            }
                                            else
                                            {
                                                $staff_id="STA001";
                                            }
                                            ?>
                                                <label>Staff ID</label>
                                                <input type="text" readonly name="txt_staff_id" id="txt_staff_id" class="form-control" value="<?php echo $staff_id ?>">
                                            </div>
                                             <div class="form-group col-md-6">
                                                <label>Staff Name</label>
                                                <input type="text" name="txt_staff_name" id="txt_staff_name" class="form-control" placeholder="Example:A.W.S.Kumara" onkeypress="return isTextKey(event)" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Email</label>
                                                <input type="email" name="txt_staff_email" id="txt_staff_email" class="form-control" placeholder="abcd@gmail.com" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Mobile Number</label>
                                                <input type="text" name="txt_staff_mobile_number" id="txt_staff_mobile_number" class="form-control" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" onblur="phonenumber('txt_staff_mobile_number');check_staff_mobile_no()" required>
                                            </div>
                                             <div class="form-group col-md-6">
                                                <label>Date of Birth</label>
                                                <input type="Date" name="txt_staff_dob" id="txt_staff_dob" class="form-control" placeholder="Mobile Number" required>
                                            </div>
                                             <div class="form-group col-md-6">
                                                <label>Gender</label>
                                                <select name="txt_staff_gender" id="txt_staff_gender" class="form-control" required>
                                                <?php
                                                $gender = array("Male", "Female");
                                                for ($i = 0; $i < count($gender); $i++) {
                                                echo '<option value="' . $gender[$i] . '">' . $gender[$i] . '</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>NIC Number</label>
                                                <input type="text" name="txt_staff_nic_number" id="txt_staff_nic_number" class="form-control" placeholder="NIC Number" onblur="nicnumber('txt_staff_nic_number');check_staff_nic_no()" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Branch Name</label>
                                                <select name="txt_branch_id" id="txt_branch_id" class="form-control chzn-select" required>
                                                <option>Select Branch</option>
                                                <?php
                                                    $sql_get_branch="SELECT * FROM branch";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                    while ($row_get_branch=mysqli_fetch_assoc($result_get_branch)) 
                                                    {
                                                        echo '<option value="'.$row_get_branch["branchid"].'">'.$row_get_branch["branchname"].'</option>';
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Designation</label>
                                                <select type="text" name="txt_designation" id="txt_designation" class="form-control"  required>
                                                    <option>Branch Manager</option>
                                                    <option>Cashier</option>
                                                    <option>Sales Person</option>
                                                    <option>Technician</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="txt_staff_address" id="txt_staff_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" required>
                                        </div>
                                        <div>
                                        <button type="submit" name="btn_save_staff" id="btn_save_staff" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=staff.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
			<?php
        }
        elseif($_GET["option"]=="add" & $system_user_type!="Manager")
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
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
                                    <h4 class="card-title">Staff Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                    <?php
                                    if($system_user_type=="Manager")
                                    {
                                    ?>
                                        <a href="index.php?page=staff.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    <?php
                                    }
                                    ?>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Staff ID</th>
                                                    <th>Staff Name</th>
                                                    <th>Branch</th>
                                                    <th>Designation</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_staff_details_view="SELECT staffid,staffname,nicno,branchid,designation FROM staff ORDER BY staffid ASC";
                                                $result_staff_details_view=mysqli_query($con,$sql_staff_details_view)or die("Error in staff details view".mysqli_error());
                                                while ($row_staff_details_view=mysqli_fetch_assoc($result_staff_details_view)) 
                                                {   // get staff status
                                                    $sql_get_login="SELECT status FROM login WHERE userid='$row_staff_details_view[nicno]'";
                                                    $result_get_login=mysqli_query($con,$sql_get_login)or die("Error in login details view".mysqli_error());
                                                    $row_get_login=mysqli_fetch_assoc($result_get_login);
                                                    // get branch name
                                                    $sql_get_branch="SELECT branchname FROM branch WHERE branchid='$row_staff_details_view[branchid]'";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch)or die("Error in branch".mysqli_error());
                                                    $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                                                        {
                                                        echo '<tr>
                                                                <td>'.$row_staff_details_view["staffid"].'</td>
                                                                <td>'.$row_staff_details_view["staffname"].'</td>
                                                                <td>'.$row_get_branch["branchname"].'</td>
                                                                <td>'.$row_staff_details_view["designation"].'</td>
                                                                <td>'.$row_get_login["status"].'</td>
                                                                <td>
                                                                    <a href="index.php?page=staff.php&option=fullview&staff_id='.$row_staff_details_view["staffid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>&nbsp';
                                                                    if($row_get_login["status"]=="Active" & $system_user_type=="Manager")
                                                                    {   echo'<a href="index.php?page=staff.php&option=edit&staff_id='.$row_staff_details_view["staffid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>&nbsp';
                                                                        echo'<a href="index.php?page=staff.php&option=suspend&staff_nic='.$row_staff_details_view["nicno"].'"><button type="button"class="btn btn-danger"><i class="fas fa-ban"></i> Suspend</button></a>';
                                                                    }
                                                        echo   '</td>
                                                            </tr>';
                                                        }
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
        elseif ($_GET["option"]=="edit" & $system_user_type=="Manager") 
        {
            $get_staff_id=$_GET["staff_id"];
            $sql_get_staff_detail="SELECT * FROM staff WHERE staffid='$get_staff_id'";
            $result_get_staff_detail=mysqli_query($con,$sql_get_staff_detail)or die("Error in geting staff details".mysqli_error($con));
            $row_get_staff_detail=mysqli_fetch_assoc($result_get_staff_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Staff Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Staff Id</label>
                                            <input type="text" readonly name="txt_staff_id" id="txt_staff_id" class="form-control" value="<?php echo $row_get_staff_detail["staffid"] ?>">
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Staff Name</label>
                                            <input type="text" name="txt_staff_name" id="txt_staff_name" class="form-control" placeholder="Example:A.W.S.Kumara" value="<?php echo $row_get_staff_detail["staffname"] ?>" onkeypress="return isTextKey(event)" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="email" name="txt_staff_email" id="txt_staff_email" class="form-control" placeholder="abcd@gmail.com" value="<?php echo $row_get_staff_detail["email"] ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mobile Number</label>
                                            <input type="text" name="txt_staff_mobile_number" id="txt_staff_mobile_number" class="form-control" placeholder="Mobile Number" value="<?php echo $row_get_staff_detail["tpno"] ?>" onkeypress="return isNumberKey(event)" onblur="phonenumber('txt_staff_mobile_number')" required>
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Date of Birth</label>
                                            <input type="Date" name="txt_staff_dob" id="txt_staff_dob" class="form-control" value="<?php echo $row_get_staff_detail["dob"] ?>" required>
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Gender</label>
                                            <select name="txt_staff_gender" id="txt_staff_gender" class="form-control" required>
                                            <?php
                                            $gender = array("Male", "Female");
                                            for ($i = 0; $i < count($gender); $i++) {
                                            if ($row_get_staff_detail["gender"] == $gender[$i]) {
                                            echo '<option selected value="' . $gender[$i] . '">' . $gender[$i] . '</option>';
                                            } else {
                                            echo '<option value="' . $gender[$i] . '">' . $gender[$i] . '</option>';
                                            }
                                            }
                                            ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>NIC Number</label>
                                            <input type="text" name="txt_staff_nic_number" id="txt_staff_nic_number" class="form-control" placeholder="NIC Number" value="<?php echo $row_get_staff_detail["nicno"] ?>" onblur="nicnumber('txt_staff_nic_number')" required>
                                        </div>
                                            <div class="form-group col-md-6">
                                               <label>Branch Name</label>
                                                    <select name="txt_branch_id" id="txt_branch_id" class="form-control chzn-select" required>
                                                <?php
                                                    $sql_get_branch="SELECT * FROM branch";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                    while ($row_get_branch=mysqli_fetch_assoc($result_get_branch)) 
                                                    {
                                                           if($row_get_branch["branchid"]==$row_get_staff_detail["branchid"])
                                                        {
                                                            echo '<option value="'.$row_get_branch["branchid"].'" selected>'.$row_get_branch["branchname"].'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$row_get_branch["branchid"].'">'.$row_get_branch["branchname"].'</option>';
                                                        }
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Designation</label>
                                                <input type="text" name="txt_designation" id="txt_designation" class="form-control"  value="<?php echo $row_get_staff_detail["designation"] ?>" required>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="txt_staff_address" id="txt_staff_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" value="<?php echo $row_get_staff_detail["address"] ?>" required>
                                    </div>
                                    <div>
                                        <button type="submit" name="btn_edit_staff" id="btn_edit_staff" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=staff.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }
        elseif($_GET["option"]=="edit" & $system_user_type!="Manager")
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
        }
        elseif ($_GET["option"]=="fullview") 
        {   // get staff details
            $get_staff_id=$_GET["staff_id"];
            $sql_staff_fullview="SELECT * FROM staff WHERE staffid='$get_staff_id'";
            $result_staff_fullviewl=mysqli_query($con,$sql_staff_fullview)or die("Error in geting staff fullview details".mysqli_error($con));
            $row_staff_fullview=mysqli_fetch_assoc($result_staff_fullviewl);
            // get login 
            $sql_get_login="SELECT status FROM login WHERE userid='$row_staff_fullview[nicno]'";
            $result_get_login=mysqli_query($con,$sql_get_login)or die("Error in login details view".mysqli_error());
            $row_get_login=mysqli_fetch_assoc($result_get_login);
            // get branch name
            $sql_get_branch="SELECT branchname FROM branch WHERE branchid='$row_staff_fullview[branchid]'";
            $result_get_branch=mysqli_query($con,$sql_get_branch)or die("Error in branch".mysqli_error());
            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Staff Full Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <tr><th style="width: 50%">Staff Id</th><td><?php echo $row_staff_fullview["staffid"] ?></td></tr>
                                            <tr><th>Staff Name</th><td><?php echo $row_staff_fullview["staffname"] ?></td></tr>
                                            <tr><th>Email</th><td><?php echo $row_staff_fullview["email"] ?></td></tr>
                                            <tr><th>Mobile Number</th><td><?php echo $row_staff_fullview["tpno"] ?></td></tr>
                                            <tr><th>Date of Birth</th><td><?php echo $row_staff_fullview["dob"] ?></td></tr>
                                            <tr><th>Gender</th><td><?php echo $row_staff_fullview["gender"] ?></td></tr>
                                            <tr><th>NIC Number</th><td><?php echo $row_staff_fullview["nicno"] ?></td></tr>
                                            <tr><th>Designation</th><td><?php echo $row_staff_fullview["designation"] ?></td></tr>
                                            <tr><th>Branch ID</th><td><?php echo $row_get_branch["branchname"] ?></td></tr>
                                            <tr><th>Address</th><td><?php echo $row_staff_fullview["address"] ?></td></tr>
                                            <tr><th>Status</th><td><?php echo $row_get_login["status"] ?></td></tr>
                                            <tr>
                                                <td colspan="2">
                                                    <center>
                                                        <a href="index.php?page=staff.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
                                                        <?php
                                                        if($row_get_login["status"]=="Active" & $system_user_type=="Manager")
                                                        {
                                                            echo'<a href="index.php?page=staff.php&option=edit&staff_id='.$row_staff_fullview["staffid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>';
                                                        }
                                                        elseif($row_get_login["status"]!="Active" & $system_user_type=="Manager")
                                                        {
                                                            echo'<a href="index.php?page=staff.php&option=activate&staff_nic='.$row_staff_fullview["nicno"].'"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Activate</button></a>';
                                                        }
                                                        ?>
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
        elseif ($_GET["option"]=="suspend" & $system_user_type=="Manager") 
        {
            $get_staff_nic=$_GET["staff_nic"];
            $sql_staff_suspend="UPDATE login SET 
                                status='".mysqli_real_escape_string($con,"Supended")."'
                                WHERE userid='$get_staff_nic'";
            $result_staff_suspend=mysqli_query($con,$sql_staff_suspend)or die("Error in staff delete".mysqli_error($con));
            if($result_staff_suspend)
            {
                echo '<script>
                        alert("Successfully Suspended!!");
                        window.location.href="index.php?page=staff.php&option=view";
                    </script>';
            }

		}
        elseif($_GET["option"]=="suspend" & $system_user_type!="Manager") 
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
        }
        elseif ($_GET["option"]=="activate") 
        {
            $get_staff_nic=$_GET["staff_nic"];
            $sql_staff_delete="UPDATE login SET 
                                status='".mysqli_real_escape_string($con,"Active")."'
                                WHERE userid='$get_staff_nic'";
            $result_staff_delete=mysqli_query($con,$sql_staff_delete)or die("Error in staff delete".mysqli_error($con));
            if($result_staff_delete)
            {
                echo '<script>
                        alert("Successfully Activated!!");
                        window.location.href="index.php?page=staff.php&option=view";
                    </script>';
            }

        }elseif($_GET["option"]=="activate" & $system_user_type!="Manager")
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