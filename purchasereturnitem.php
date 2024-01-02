<?php
if(!isset($_SESSION))
{
    session_start();
}
if($system_user_type=="Branch Manager" || $system_user_type=="Manager"  )
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
				<li>Return Item</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_edit_return_item"]))
{   
    $sql_update_purchasereturnitem="UPDATE purchasereturnitem SET  
                            noofitems='".mysqli_real_escape_string($con,$_POST["txt_noofitems"])."'
                            WHERE returnid='".mysqli_real_escape_string($con,$_POST["txt_return_item_id"])."' AND purid='".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."' AND modelno='".mysqli_real_escape_string($con,$_POST["txt_model_number"])."'";
                            
    $result_update_purchasereturnitem=mysqli_query($con,$sql_update_purchasereturnitem) or die("Error in updating in purchase return item".mysqli_error($con));
    if($result_update_purchasereturnitem)
    {   
        echo '<script>
                alert("Quantity Updated");
                window.location.href="index.php?page=purchasereturn.php&option=add";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_max_return_quantity()
    {
        let model_id=document.getElementById("txt_model_number").value;
        let purchase_id=document.getElementById("txt_purchase_id").value;
        let return_id=document.getElementById("txt_return_item_id").value;
        if(model_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let stock_quantity=xmlhttp.responseText.trim();
                    if(stock_quantity>=0)
                    {   let quantity_edit=Number(document.getElementById("txt_noofitems").value) + Number(stock_quantity);
                        document.getElementById("txt_noofitems").setAttribute("max",quantity_edit);
                        document.getElementById("div_stock_quantity").style.color="Red";
                        document.getElementById("div_stock_quantity").innerHTML = "Max Return Quantity : " + quantity_edit;
                    }
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_max_return_quantity&ajax_model_id=" + model_id + "&ajax_purchase_id=" + purchase_id + "&ajax_return_id=" + return_id , true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("div_stock_quantity").innerHTML='';
        }
    }
</script>
<?php
	if(isset($_GET["option"]))
	{
		if($_GET["option"]=="edit")
	{      
            $return_id=$_GET["return_item_id"];
            $purchase_id=$_GET["purchase_id"];
            $modelno=$_GET["return_item_modelno"];
            // get purchase return item details
            $sql_get_returnitem="SELECT * FROM purchasereturnitem Where returnid='$return_id' AND purid='$purchase_id' AND modelno='$modelno'";
            $result_get_returnitem=mysqli_query($con,$sql_get_returnitem) or die ("Error in Getting sales id".mysqli_error($con));
            $row_get_returnitem=mysqli_fetch_assoc($result_get_returnitem);
			// get model name
            $sql_get_model="SELECT modelname FROM model WHERE modelno='$row_get_returnitem[modelno]'";
            $result_get_model=mysqli_query($con,$sql_get_model) or die ("Error in get model".mysqli_error($con));
            $row_get_model=mysqli_fetch_assoc($result_get_model);
			?>
            
			<div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Return Item Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Return Id</label>
                                                <input type="text" name="txt_return_item_id" id="txt_return_item_id" class="form-control" value="<?php echo $return_id ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Purchase ID</label>
                                                <input type="text" name="txt_purchase_id" id="txt_purchase_id" class="form-control" value="<?php echo $purchase_id ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Model</label>
                                                <input type="text" name="txt_model_number" id="txt_model_number" class="form-control"  value="<?php echo $modelno ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Return Quantity</label>
                                                <input type="number" min="1" max="" name="txt_noofitems" id="txt_noofitems" class="form-control"  value="<?php echo $row_get_returnitem["noofitems"] ?>" tabindex="2" onclick="check_max_return_quantity()" required>
                                                <label><b id="div_stock_quantity" style=""></b></b></label>
                                            </div>                                           
                                        </div>
                                            <div>
                                            <button type="submit" name="btn_edit_return_item" id="btn_edit_return_item" class="btn btn-success"><i class="fa fa-save"></i> Save</button> 
                                            <a href="index.php?page=purchasereturn.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
            $get_returnitem_id=$_GET["return_item_id"];
            $get_returnitem_modelno=$_GET["return_item_modelno"];
            $get_returnitem_purchase_id=$_GET["purchase_id"];

            $sql_returnitem_delete="DELETE FROM purchasereturnitem WHERE returnid='$get_returnitem_id' AND modelno='$get_returnitem_modelno' AND purid='$get_returnitem_purchase_id'";
            $result_returnitem_delete=mysqli_query($con,$sql_returnitem_delete)or die("Error in salesitem delete".mysqli_error($con));
            if($result_returnitem_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=purchasereturn.php&option=add";
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