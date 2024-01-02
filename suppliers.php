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
if($system_user_type=="Manager" || $system_user_type=="Branch Manager")
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
				<li>Suppliers</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_suppliers"]))
{
    $sql_insert_suppliers="INSERT INTO suppliers (supid,supname,address,tpno)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_suppliers_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_suppliers_name"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_suppliers_address"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_suppliers_contact_number"])."')";
    $result_insert_suppliers=mysqli_query($con,$sql_insert_suppliers) or die("Error in inserting in suppliers".mysqli_error($con));
    if($result_insert_suppliers)
    {  if(isset($_GET["url_id"]))
        {   if($_GET["url_id"]=="purchase")
            {
                echo '<script>
                        alert("Successful Added!!");
                        window.location.href="index.php?page=purchase.php&option=add";
                    </script>';
            }
        }
        else
        {
                echo '<script>
                        alert("Successful Added!!");
                        window.location.href="index.php?page=suppliers.php&option=view";
                    </script>';    
        }

    }
}
if(isset($_POST["btn_edit_suppliers"]))
{
    $sql_update_suppliers="UPDATE suppliers SET 
                            supname='".mysqli_real_escape_string($con,$_POST["txt_suppliers_name"])."',
                            address='".mysqli_real_escape_string($con,$_POST["txt_suppliers_address"])."',
                            tpno='".mysqli_real_escape_string($con,$_POST["txt_suppliers_contact_number"])."'
                            WHERE supid='".mysqli_real_escape_string($con,$_POST["txt_suppliers_id"])."'";
     $result_update_suppliers=mysqli_query($con,$sql_update_suppliers) or die("Error in updating in suppliers".mysqli_error($con));
    if($result_update_suppliers)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=suppliers.php&option=view";
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
                                <h4 class="card-title">Suppliers Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <?php 
                                            $sql_create_suppliers_id="SELECT supid FROM suppliers ORDER BY supid DESC LIMIT 1";
                                            $result_create_suppliers_id=mysqli_query($con,$sql_create_suppliers_id) or die ("Error in Creating id".mysqli_error($con));
                                            if(mysqli_num_rows($result_create_suppliers_id)==1)
                                            {
                                                $row_create_suppliers_id=mysqli_fetch_assoc($result_create_suppliers_id);
                                                $suppliers_id=++$row_create_suppliers_id["supid"];
                                            }
                                            else
                                            {
                                                $suppliers_id="SUP001";
                                            }
                                            ?>
                                                <label>Supplier ID</label>
                                                <input type="text" name="txt_suppliers_id" id="txt_suppliers_id" class="form-control" value="<?php echo $suppliers_id ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Suppliers Name</label>
                                                <input type="text" name="txt_suppliers_name" id="txt_suppliers_name"class="form-control" placeholder="Example:A.W.S.Kumara" onkeypress="return isTextKey(event)" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Suppliers Contact Number</label>
                                                <input type="text" name="txt_suppliers_contact_number" id="txt_suppliers_contact_number" class="form-control" onkeypress="return isNumberKey(event)" onblur="phonenumber('txt_suppliers_contact_number');" required>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="txt_suppliers_address" id="txt_suppliers_address" class="form-control" required>
                                            </div>
                                            <div>
                                                <button type="submit" name="btn_save_suppliers" id="btn_save_suppliers" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                <?php
                                                if(isset($_GET["url_id"]))
                                                {   if($_GET["url_id"]=="purchase")
                                                    {
                                                        echo '<a href="index.php?page=purchase.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                                    }
                                                }
                                                else
                                                {
                                                        echo '<a href="index.php?page=suppliers.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';    
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
                                    <h4 class="card-title">Suppliers Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=suppliers.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Suppliers ID</th>
                                                    <th>Sppliers Name</th>
                                                    <th>Address</th>
                                                    <th>Telephone No</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_suppliers_details_view="SELECT supid,supname,address,tpno FROM suppliers";
                                                $result_suppliers_details_view=mysqli_query($con,$sql_suppliers_details_view)or die("Error in suppliers details view".mysqli_error());
                                                while ($row_suppliers_details_view=mysqli_fetch_assoc($result_suppliers_details_view)) 
                                                {   //check supplier in purchase
                                                    $sql_check_supplier="SELECT * FROM purchase WHERE supid='$row_suppliers_details_view[supid]'";
                                                    $result_check_supplier=mysqli_query($con,$sql_check_supplier)or die("Error in checking supplier view".mysqli_error());
                                                    echo '<tr>
                                                            <td>'.$row_suppliers_details_view["supid"].'</td>
                                                            <td>'.$row_suppliers_details_view["supname"].'</td>
                                                            <td>'.$row_suppliers_details_view["address"].'</td>
                                                            <td>'.$row_suppliers_details_view["tpno"].'</td>
                                                            <td>
                                                                <a href="index.php?page=suppliers.php&option=edit&suppliers_id='.$row_suppliers_details_view["supid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>&nbsp';
                                                                if($system_user_type=="Manager")
                                                                {
                                                                    if(mysqli_num_rows($result_check_supplier)==0)
                                                                    {
                                                                        echo '<a href="index.php?page=suppliers.php&option=delete&suppliers_id='.$row_suppliers_details_view["supid"].'"><button type="button"class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button></a>';
                                                                    }else{}
                                                                }
                                                    echo       '</td>
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
            $get_suppliers_id=$_GET["suppliers_id"];
            $sql_get_suppliers_detail="SELECT * FROM suppliers WHERE supid='$get_suppliers_id'";
            $result_get_suppliers_detail=mysqli_query($con,$sql_get_suppliers_detail)or die("Error in geting suppliers details".mysqli_error($con));
            $row_get_suppliers_detail=mysqli_fetch_assoc($result_get_suppliers_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Suppliers Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Suppliers Id</label>
                                            <input type="text" readonly name="txt_suppliers_id" id="txt_suppliers_id" class="form-control" value="<?php echo $row_get_suppliers_detail["supid"] ?>" >
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Suppliers Name</label>
                                            <input maxlength="25" type="text" name="txt_suppliers_name" id="txt_suppliers_name" class="form-control" placeholder="Example:A.W.S.Kumara" value="<?php echo $row_get_suppliers_detail["supname"] ?>" onkeypress="return isTextKey(event)" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Suppliers Contact Number</label>
                                            <input type="text" name="txt_suppliers_contact_number" id="txt_suppliers_contact_number" class="form-control" placeholder="Mobile Number" value="<?php echo $row_get_suppliers_detail["tpno"] ?>" onkeypress="return isNumberKey(event)" onblur="phonenumber('txt_suppliers_contact_number')" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="txt_suppliers_address" id="txt_suppliers_address"class="form-control" placeholder="No.06,Kandy Road,Chavakachcheri" value="<?php echo $row_get_suppliers_detail["address"] ?>" required>
                                    </div>
                                    <div>
                                        <button type="submit" name="btn_edit_suppliers" id="btn_edit_suppliers" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=suppliers.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
         }
         elseif ($_GET["option"]=="delete" & $system_user_type=="Manager") 
            {
                $get_suppliers_id=$_GET["suppliers_id"];
                $sql_suppliers_delete="DELETE FROM suppliers WHERE supid='$get_suppliers_id'";
                $result_suppliers_delete=mysqli_query($con,$sql_suppliers_delete)or die("Error in suppliers delete".mysqli_error($con));
                if($result_suppliers_delete)
                {
                    echo '<script>
                            alert("Successful Deleted!!");
                            window.location.href="index.php?page=suppliers.php&option=view";
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