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
				<li>Customer</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_edit_salesitem"]))
{
    $sql_update_salesitem="UPDATE salesitem SET  
                            quantity='".mysqli_real_escape_string($con,$_POST["txt_quantity"])."'
                            WHERE salesid='".mysqli_real_escape_string($con,$_POST["txt_sales_item_id"])."' AND modelno='".mysqli_real_escape_string($con,$_POST["txt_model_number"])."'";
                            
    $result_update_salesitem=mysqli_query($con,$sql_update_salesitem) or die("Error in updating in salesitem".mysqli_error($con));
    if($result_update_salesitem)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=sales.php&option=add";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_max_sales_quantity()
    {
        let model_id=document.getElementById("txt_model_number").value;
        let sales_id=document.getElementById("txt_sales_item_id").value;
        if(model_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                        let stock_quantity=xmlhttp.responseText.trim();
                        document.getElementById("txt_quantity").setAttribute("max",Number(stock_quantity));
                        document.getElementById("div_stock_quantity").style.color="Red";
                        document.getElementById("div_stock_quantity").innerHTML = "Max Sales Quantity : " + stock_quantity;
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_max_sales_quantity&ajax_model_id=" + model_id + "&ajax_sales_id=" + sales_id, true);
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
		if ($_GET["option"]=="edit") 
        {
            $get_salesitem_id=$_GET["sales_item_id"];
            $get_salesitem_modelno=$_GET["sales_item_modelno"];
            $sql_get_salesitem_detail="SELECT * FROM salesitem WHERE salesid='$get_salesitem_id' AND modelno='$get_salesitem_modelno'";
            $result_get_salesitem_detail=mysqli_query($con,$sql_get_salesitem_detail)or die("Error in geting salesitem details".mysqli_error($con));
            $row_get_salesitem_detail=mysqli_fetch_assoc($result_get_salesitem_detail);
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Sales Item Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Sales Id</label>
                                                <input type="text" name="txt_sales_item_id" id="txt_sales_item_id" class="form-control" value="<?php echo $row_get_salesitem_detail["salesid"] ?>"  readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Model Number</label>
                                                <input type="text" name="txt_model_number" id="txt_model_number" class="form-control" value="<?php echo $row_get_salesitem_detail["modelno"] ?>" readonly >
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Quantity</label>
                                                <input type="number" min="1" max="" name="txt_quantity" id="txt_quantity" class="form-control" value="<?php echo $row_get_salesitem_detail["quantity"] ?>" tabindex="2" onclick="check_max_sales_quantity()" required>
                                                <label><b id="div_stock_quantity" style=""></b></label>
                                            </div>                                            
                                            </div>
                                            <div>
                                            <button type="submit" name="btn_edit_salesitem" id="btn_edit_salesitem" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                            <a href="index.php?page=sales.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
            $get_salesitem_id=$_GET["sales_item_id"];
            $get_salesitem_modelno=$_GET["sales_item_modelno"];
            $sql_salesitem_delete="DELETE FROM salesitem WHERE salesid='$get_salesitem_id' AND modelno='$get_salesitem_modelno'";
            $result_salesitem_delete=mysqli_query($con,$sql_salesitem_delete)or die("Error in salesitem delete".mysqli_error($con));
            if($result_salesitem_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=sales.php&option=add";
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