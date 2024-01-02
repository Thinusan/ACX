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
<!--Header -->
<div class="services-breadcrumb">
	<div class="agile_inner_breadcrumb">
		<div class="container">
			<ul class="w3_short">
				<li>
					<a href="index.php">Home</a>
					<i>|</i>
				</li>
				<li>Repair Item</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_edit_repairitem"]))
{
    $sql_update_repairitem="UPDATE repairitem SET 
                            device_name='".mysqli_real_escape_string($con,$_POST["txt_device_name"])."',
                            description='".mysqli_real_escape_string($con,$_POST["txt_description"])."'
                            WHERE repairid='".mysqli_real_escape_string($con,$_POST["txt_repair_item_id"])."' AND imei_number='".mysqli_real_escape_string($con,$_POST["txt_imei_number"])."'";
                            
     $result_update_repairitem=mysqli_query($con,$sql_update_repairitem) or die("Error in updating in repairitem".mysqli_error($con));
    if($result_update_repairitem)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=repair.php&option=add";
            </script>';
    }
}
	if(isset($_GET["option"]))
	{
		if($_GET["option"]=="edit")
		{   $get_repairitem_id=$_GET["repair_item_id"];
            $get_repairitem_imei_no=$_GET["repair_item_imei_no"];
            $sql_get_repairitem_detail="SELECT * FROM repairitem WHERE repairid='$get_repairitem_id' AND imei_number='$get_repairitem_imei_no'";
            $result_get_repairitem_detail=mysqli_query($con,$sql_get_repairitem_detail)or die("Error in geting repairitem details".mysqli_error($con));
            $row_get_repairitem_detail=mysqli_fetch_assoc($result_get_repairitem_detail);
            ?>
		
			<div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Repair Item Details</h4>
                                    <div class="basic-form">
                                        <form method="POST" action="">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                <label>Repair Id</label>
                                                <input type="text" name="txt_repair_item_id" id="txt_repair_item_id" class="form-control" value="<?php echo $row_get_repairitem_detail["repairid"] ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>IMEI Number</label>
                                                    <input type="text" name="txt_imei_number" id="txt_imei_number" class="form-control"  value="<?php echo $row_get_repairitem_detail["imei_number"] ?>" readonly>
                                                </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Device Name</label>
                                                    <input type="text" class="form-control" name="txt_device_name" id="txt_device_name"  value="<?php echo $row_get_repairitem_detail["device_name"] ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <input type="text" name="txt_description" id="txt_description" class="form-control"  value="<?php echo $row_get_repairitem_detail["description"] ?>" required>
                                                </div>
                                                <div>
                                                    <button type="submit" name="btn_edit_repairitem" id="btn_edit_repairitem" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                                    <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                    <a href="index.php?page=repair.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
			<?php
		}
        elseif ($_GET["option"]=="delete") 
        {
            $get_repairitem_id=$_GET["repair_item_id"];
            $get_repair_item_imei_no=$_GET["repair_item_imei_no"];
            $sql_repairitem_delete="DELETE FROM repairitem WHERE repairid='$get_repairitem_id' AND imei_number='$get_repair_item_imei_no'";
            $result_repairitem_delete=mysqli_query($con,$sql_repairitem_delete)or die("Error in repair item delete".mysqli_error($con));
            if($result_repairitem_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=repair.php&option=add";
                    </script>';
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