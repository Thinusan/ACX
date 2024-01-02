<!--Header -->
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
//priviledge
if($system_user_type=="Manager" || $system_user_type=="Branch Manager" || $system_user_type=="Cashier" || $system_user_type=="Sales Person" || $system_user_type=="Technician" )
{
?>
<div class="services-breadcrumb">
	<div class="agile_inner_breadcrumb">
		<div class="container">
			<ul class="w3_short">
				<li>
					<a href="index.php">Home</a>
					<i>|</i>
				</li>
				<li>Customer</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_customer"]))
{
    $sql_insert_customer="INSERT INTO customer(custid,cusname,address,tpno,nicno,dob,gender,email)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_customer_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_name"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_address"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_mobile_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_nic_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_dob"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_gender"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_email"])."')";
    $result_insert_customer=mysqli_query($con,$sql_insert_customer) or die("Error in inserting in customer".mysqli_error($con));
    if($result_insert_customer)
    {   if(isset($_GET["url_id"]))
        {   if($_GET["url_id"]=="sales"){
                echo '<script>
                        alert("Successful Added!!");
                        window.location.href="index.php?page=sales.php&option=add";
                    </script>';
            }
            elseif($_GET["url_id"]=="repair")
            {
                echo '<script>
                    alert("Successful Added!!");
                    window.location.href="index.php?page=repair.php&option=add";
                </script>';
            }
        }
        else
        {
            echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=customer.php&option=view";
            </script>';
        }

    }
}
if(isset($_POST["btn_edit_customer"]))
{
    $sql_update_customer="UPDATE customer SET 
                            cusname='".mysqli_real_escape_string($con,$_POST["txt_customer_name"])."',
                            address='".mysqli_real_escape_string($con,$_POST["txt_customer_address"])."',
                            tpno='".mysqli_real_escape_string($con,$_POST["txt_customer_mobile_number"])."',
                            nicno='".mysqli_real_escape_string($con,$_POST["txt_customer_nic_number"])."',
                            dob='".mysqli_real_escape_string($con,$_POST["txt_customer_dob"])."',
                            gender='".mysqli_real_escape_string($con,$_POST["txt_customer_gender"])."',
                            email='".mysqli_real_escape_string($con,$_POST["txt_customer_email"])."'
                            WHERE custid='".mysqli_real_escape_string($con,$_POST["txt_customer_id"])."'";
     $result_update_customer=mysqli_query($con,$sql_update_customer) or die("Error in updating in customer".mysqli_error($con));
    if($result_update_customer)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=customer.php&option=view";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_cus_nic_no()
    {
        let nicno=document.getElementById("txt_customer_nic_number").value;
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
                        document.getElementById("txt_customer_nic_number").value="";
                        document.getElementById("txt_customer_nic_number").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_cus_nic_no&ajax_nicno=" + nicno, true);
            xmlhttp.send();
        }
        else
        {
            
        }
    }
</script>
<script type="text/javascript">
    function check_cus_mobile_no()
    {
        let mobileno=document.getElementById("txt_customer_mobile_number").value;
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
                        document.getElementById("txt_customer_mobile_number").value="";
                        document.getElementById("txt_customer_mobile_number").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_cus_mobile_no&ajax_mobile_no=" + mobileno, true);
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
		if($_GET["option"]=="add")
		{ 
			
			?>
			<div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Details</h4>
                        <div class="basic-form">
                            <form method="POST" action="" autocomplete="off">
                                <div class="form-row">
                                	<div class="form-group col-md-6">
                                	<?php 
                                	$sql_create_cus_id="SELECT custid FROM customer ORDER BY custid DESC LIMIT 1";
                                	$result_create_cus_id=mysqli_query($con,$sql_create_cus_id) or die ("Error in Creating id".mysqli_error($con));
                                	if(mysqli_num_rows($result_create_cus_id)==1)
                                	{
                                		$row_create_cus_id=mysqli_fetch_assoc($result_create_cus_id);
                                		$customer_id=++$row_create_cus_id["custid"];
                                	}
                                	else
                                	{
                                		$customer_id="CUS001";
                                	}
                                	?>
                                        <label>Customer ID</label>
                                        <input type="text" readonly name="txt_customer_id" id="txt_customer_id" class="form-control" value="<?php echo $customer_id ?>" readonly>
                                    </div>
                                	 <div class="form-group col-md-6">
                                        <label>Customer Name</label>
                                        <input type="text" name="txt_customer_name" id="txt_customer_name" class="form-control" placeholder="Example:A.W.S.Kumara" onkeypress="return isTextKey(event)" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="txt_customer_email" id="txt_customer_email" class="form-control" placeholder="abcd@gmail.com" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mobile Number</label>
                                        <input type="text" name="txt_customer_mobile_number" id="txt_customer_mobile_number" class="form-control" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" onblur="phonenumber('txt_customer_mobile_number');check_cus_mobile_no()" required>
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label>Date of Birth</label>    
                                        <input type="Date" name="txt_customer_dob" id="txt_customer_dob" class="form-control" placeholder="Date of Birth" required>
                                    </div>
                                     <div class="form-group col-md-6">
                                        <label>Gender</label>   
                                        <select name="txt_customer_gender" id="txt_customer_gender" class="form-control" required>
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
                                        <input type="text" name="txt_customer_nic_number" id="txt_customer_nic_number" class="form-control" placeholder="NIC Number" onblur="nicnumber('txt_customer_nic_number');check_cus_nic_no()" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="txt_customer_address" id="txt_customer_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" required>
                                </div>
                                <div>
                                    <button type="submit" name="btn_save_customer" id="btn_save_customer" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                               		<button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                    <?php
                                    if(isset($_GET["url_id"]))
                                    {   if($_GET["url_id"]=="sales"){
                                            echo '<a href="index.php?page=sales.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                        }
                                        elseif($_GET["url_id"]=="repair")
                                        {
                                            echo '<a href="index.php?page=repair.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                        }
                                    }
                                    else
                                    {
                                        echo '<a href="index.php?page=customer.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
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
        elseif ($_GET["option"]=="view") 
        {
        ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=customer.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Customer ID</th>
                                                    <th>Customer Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_customer_details_view="SELECT custid,cusname,tpno,email FROM customer";
                                                $result_customer_details_view=mysqli_query($con,$sql_customer_details_view)or die("Error in customer details view".mysqli_error());
                                                while ($row_customer_details_view=mysqli_fetch_assoc($result_customer_details_view)) 
                                                {   // get sales details
                                                    $sql_get_sales_details="SELECT custid FROM sales WHERE custid='$row_customer_details_view[custid]'";
                                                    $result_get_sales_details=mysqli_query($con,$sql_get_sales_details)or die("Error in getting sales details view".mysqli_error($con));
                                                    // get repair details
                                                    $sql_get_repair_details="SELECT custid FROM repair WHERE custid='$row_customer_details_view[custid]'";
                                                    $result_get_repair_details=mysqli_query($con,$sql_get_repair_details)or die("Error in getting repair details view".mysqli_error($con));
                                                    echo '<tr>
                                                            <td>'.$row_customer_details_view["custid"].'</td>
                                                            <td>'.$row_customer_details_view["cusname"].'</td>
                                                            <td>'.$row_customer_details_view["tpno"].'</td>
                                                            <td>'.$row_customer_details_view["email"].'</td>
                                                            <td>
                                                                <a href="index.php?page=customer.php&option=fullview&customer_id='.$row_customer_details_view["custid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
                                                                <a href="index.php?page=customer.php&option=edit&customer_id='.$row_customer_details_view["custid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>';
                                                                if($system_user_type=="Manager")
                                                                {
                                                                    if(mysqli_num_rows($result_get_sales_details)==0 & mysqli_num_rows($result_get_repair_details)==0)
                                                                    {
                                                                    ?>
                                                                        <a href="index.php?page=customer.php&option=delete&customer_id=<?php echo $row_customer_details_view["custid"];?>" onclick="return confirm('Are you sure, You want to delete this record?')"><button type="button"class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button></a>
                                                                    <?php
                                                                    }
                                                                }
                                                    echo    '</td>
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
            $get_customer_id=$_GET["customer_id"];
            $sql_get_customer_detail="SELECT * FROM customer WHERE custid='$get_customer_id'";
            $result_get_customer_detail=mysqli_query($con,$sql_get_customer_detail)or die("Error in geting customer details".mysqli_error($con));
            $row_get_customer_detail=mysqli_fetch_assoc($result_get_customer_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Customer Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Customer ID</label>
                                            <input type="text" readonly name="txt_customer_id" id="txt_customer_id" class="form-control" value="<?php echo $row_get_customer_detail["custid"] ?>" readonly>
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Customer Name</label>
                                            <input type="text" name="txt_customer_name" id="txt_customer_name" class="form-control" placeholder="Example:A.W.S.Kumara" value="<?php echo $row_get_customer_detail["cusname"] ?>" onkeypress="return isTextKey(event)" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="email" name="txt_customer_email" id="txt_customer_email" class="form-control" placeholder="abcd@gmail.com" value="<?php echo $row_get_customer_detail["email"] ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mobile Number</label>
                                            <input type="text" name="txt_customer_mobile_number" id="txt_customer_mobile_number" class="form-control" placeholder="Mobile Number" value="<?php echo $row_get_customer_detail["tpno"] ?>" onkeypress="return isNumberKey(event)" onblur="phonenumber('txt_customer_mobile_number')" required>
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Date of Birth</label>
                                            <input type="Date" name="txt_customer_dob" id="txt_customer_dob" class="form-control" value="<?php echo $row_get_customer_detail["dob"] ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Gender</label>
                                            <select name="txt_customer_gender" id="txt_customer_gender" class="form-control" required>
                                            <?php
                                            $gender = array("Male", "Female");
                                            for ($i = 0; $i < count($gender); $i++) {
                                            if ($row_get_customer_detail["gender"] == $gender[$i]) {
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
                                            <input type="text" name="txt_customer_nic_number" id="txt_customer_nic_number" class="form-control" placeholder="NIC Number" value="<?php echo $row_get_customer_detail["nicno"] ?>" onblur="nicnumber('txt_customer_nic_number')" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="txt_customer_address" id="txt_customer_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" value="<?php echo $row_get_customer_detail["address"] ?>" required>
                                    </div>
                                    <div>
                                        <button type="submit" name="btn_edit_customer" id="btn_edit_customer" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=customer.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }
        elseif ($_GET["option"]=="fullview") 
        {
            $get_customer_id=$_GET["customer_id"];
            $sql_customer_fullview="SELECT * FROM customer WHERE custid='$get_customer_id'";
            $result_customer_fullviewl=mysqli_query($con,$sql_customer_fullview)or die("Error in geting customer fullview details".mysqli_error($con));
            $row_customer_fullview=mysqli_fetch_assoc($result_customer_fullviewl);
            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer Full Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <tr><th style="width: 50%">Customer ID</th><td><?php echo $row_customer_fullview["custid"] ?></td></tr>
                                            <tr><th>Customer Name</th><td><?php echo $row_customer_fullview["cusname"] ?></td></tr>
                                            <tr><th>Email</th><td><?php echo $row_customer_fullview["email"] ?></td></tr>
                                            <tr><th>Mobile Number</th><td><?php echo $row_customer_fullview["tpno"] ?></td></tr>
                                            <tr><th>Date of Birth</th><td><?php echo $row_customer_fullview["dob"] ?></td></tr>
                                            <tr><th>Gender</th><td><?php echo $row_customer_fullview["gender"] ?></td></tr>
                                            <tr><th>NIC Number</th><td><?php echo $row_customer_fullview["nicno"] ?></td></tr>
                                            <tr><th>Address</th><td><?php echo $row_customer_fullview["address"] ?></td></tr>
                                            <tr>
                                                <td colspan="2">
                                                    <center>
                                                        <a href="index.php?page=customer.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
                                                        <a href="index.php?page=customer.php&option=edit&customer_id=<?php echo $row_customer_fullview["custid"]?>"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>
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
        elseif ($_GET["option"]=="delete" & $system_user_type=="Manager") 
        {
            $get_customer_id=$_GET["customer_id"];
            $sql_customer_delete="DELETE FROM customer WHERE custid='$get_customer_id'";
            $result_customer_delete=mysqli_query($con,$sql_customer_delete)or die("Error in customer delete".mysqli_error($con));
            if($result_customer_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=customer.php&option=view";
                    </script>';
            }

        }
        elseif($_GET["option"]=="delete" & $system_user_type!="Manager")
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