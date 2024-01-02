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
if($system_user_type=="Branch Manager" || $system_user_type=="Manager" )
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
				<li>purchaseitem</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_purchaseitem"]))
{
    $sql_insert_purchaseitem="INSERT INTO purchaseitem(purid,modelno,noofitems,unitprice)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_noofitems"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_unit_price"])."')";
    $result_insert_purchaseitem=mysqli_query($con,$sql_insert_purchaseitem) or die("Error in inserting in purchaseitem".mysqli_error($con));
    if($result_insert_purchaseitem)
    {
        echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=purchaseitem.php&option=view";
            </script>';
    }
}
if(isset($_POST["btn_edit_purchaseitem"]))
{
    $sql_update_purchaseitem="UPDATE purchaseitem SET  
                            noofitems='".mysqli_real_escape_string($con,$_POST["txt_noofitems"])."',
                            unitprice='".mysqli_real_escape_string($con,$_POST["txt_unit_price"])."'
                            WHERE purid='".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."' AND modelno='".mysqli_real_escape_string($con,$_POST["txt_model_number"])."'";
                            
     $result_update_purchaseitem=mysqli_query($con,$sql_update_purchaseitem) or die("Error in updating in purchaseitem".mysqli_error($con));
    if($result_update_purchaseitem)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=purchase.php&option=add";
            </script>';
    }
}
	if(isset($_GET["option"]))
	{
		if ($_GET["option"]=="edit") 
        {
            $get_purchaseitem_id=$_GET["purchase_item_id"];
            $get_purchaseitem_modelno=$_GET["purchase_item_modelno"];
            $sql_get_purchaseitem_detail="SELECT * FROM purchaseitem WHERE purid='$get_purchaseitem_id' AND modelno='$get_purchaseitem_modelno'";
            $result_get_purchaseitem_detail=mysqli_query($con,$sql_get_purchaseitem_detail)or die("Error in geting purchaseitem details".mysqli_error($con));
            $row_get_purchaseitem_detail=mysqli_fetch_assoc($result_get_purchaseitem_detail);
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Purchase Item Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">
                                           <div class="form-group col-md-6">
                                                <label>Purchase Id</label>
                                                <input type="text" name="txt_purchase_id" id="txt_purchase_id" class="form-control" value="<?php echo $row_get_purchaseitem_detail["purid"] ?>" readonly>
                                           </div>
                                           <div class="form-group col-md-6">
                                                <label>Model Number</label>
                                                <input type="text" name="txt_model_number" id="txt_model_number" class="form-control" value="<?php echo $row_get_purchaseitem_detail["modelno"] ?>" readonly>
                                           </div>
                                           <div class="form-group col-md-6">
                                                <label>Number of Items</label>
                                                <input type="text" name="txt_noofitems" id="txt_noofitems"class="form-control" value="<?php echo $row_get_purchaseitem_detail["noofitems"] ?>" required>
                                           </div>
                                           <div class="form-group col-md-6">
                                                <label>Unit Price</label>
                                                <input type="text" name="txt_unit_price" id="txt_unit_price"class="form-control" value="<?php echo $row_get_purchaseitem_detail["unitprice"] ?>" required>
                                           </div>

                                        </div>
                                       <div>
                                        <button type="submit" name="btn_edit_purchaseitem" id="btn_edit_purchaseitem" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=purchase.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
            $get_purchaseitem_id=$_GET["purchase_item_id"];
            $get_purchaseitem_modelno=$_GET["purchase_item_modelno"];
            $sql_purchaseitem_delete="DELETE FROM purchaseitem WHERE purid='$get_purchaseitem_id' AND modelno='$get_purchaseitem_modelno'";
            $result_purchaseitem_delete=mysqli_query($con,$sql_purchaseitem_delete)or die("Error in purchaseitem delete".mysqli_error($con));
            if($result_purchaseitem_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=purchase.php&option=add";
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