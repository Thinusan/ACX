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
				<li>Sales</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_SESSION["SALES_ID"]))
{
        if(isset($_POST["btn_save_sales"]))
        {   
            // get branch id
            $sql_get_enterby="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
            $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
            $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
            $branchid=$row_get_enterby["branchid"];
            // reduce stock start
            $sales_id=$_SESSION["SALES_ID"];
            $sql_get_sales_item_details="SELECT modelno,quantity FROM salesitem WHERE salesid='$sales_id'";
            $result_get_sales_items_details=mysqli_query($con,$sql_get_sales_item_details) or die("Error in sql_sales_item_details".mysqli_error($con));
            while($row_sales_item_details=mysqli_fetch_assoc($result_get_sales_items_details))
            {
                $sales_item_modelno=$row_sales_item_details["modelno"];
                $sales_item_quantity=$row_sales_item_details["quantity"];
                
                    $product_sales_purchase=array();
                    $sql_get_stock_details="SELECT purid,modelno,quantity FROM stock WHERE modelno='$sales_item_modelno' AND quantity!='0' AND branchid='$branchid'";
                    $result_get_stock_details=mysqli_query($con,$sql_get_stock_details) or die("Error in sql_stock".mysqli_error($con));
                    $x=0;
                    while($row_get_stock_details=mysqli_fetch_assoc($result_get_stock_details))
                    {
                        if($sales_item_quantity>0)
                        {
                            if ($row_get_stock_details["quantity"]<=$sales_item_quantity) 
                            {
                                $product_sales_purchase[$x][0]=$row_get_stock_details["purid"];
                                $product_sales_purchase[$x][1]=$row_get_stock_details["quantity"];
                                $sales_item_quantity=$sales_item_quantity-$row_get_stock_details["quantity"];
                            } 
                            else 
                            {
                                $product_sales_purchase[$x][0]=$row_get_stock_details["purid"];
                                $product_sales_purchase[$x][1]=$sales_item_quantity;
                                $sales_item_quantity=$sales_item_quantity-$sales_item_quantity;               
                            }
                            $x++;
                        }
                        else
                        {
                            break;
                        }
                        
                    }

                    for ($i=0; $i < count($product_sales_purchase); $i++) 
                    { 
                        $redused_quantity=$product_sales_purchase[$i][1]; 
                        $sql_update_stock="UPDATE stock SET 
                                        quantity=quantity-$redused_quantity
                                        WHERE purid='".mysqli_real_escape_string($con,$product_sales_purchase[$i][0])."' AND 
                                        modelno='".mysqli_real_escape_string($con,$sales_item_modelno)."'";
                        $result_update_stock=mysqli_query($con,$sql_update_stock) or die("Error in sql_update".mysqli_error($con));                       
                    }
            }
            // reduce stock end
            echo'<input type="hidden" id="txt_salesid" value="'.$_POST["txt_sales_id"].'">';
            $sql_insert_sales="INSERT INTO sales(salesid,salesdate,custid,enterby,branchid,total_amount,discount_type,discount,sales_amount)
                                VALUES('".mysqli_real_escape_string($con,$_POST["txt_sales_id"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_sales_date"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_customer_id"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_enterby"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_total_amount"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_discount_type"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_discount"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_sales_amount"])."')";
            $result_insert_sales=mysqli_query($con,$sql_insert_sales) or die("Error in inserting in sales".mysqli_error($con));
            if($result_insert_sales)
            {   
                echo '<script>
                        alert("Successful Added!!");
                        let salesid=document.getElementById("txt_salesid").value;
                        window.location.href="index.php?page=invoice.php&option=sales_invoice&salesid=" + salesid;
                      </script>';
                unset($_SESSION["SALES_ID"]);
            }
        }
}

?>
<script type="text/javascript">
    function calc_discount()
    {   
        var subtotal_amount = document.getElementById('txt_total_amount').value;
        if(document.getElementById('txt_discount_type').value=="Percentage")
        {
            document.getElementById('txt_discount').max="20";
            var discount_percentage = document.getElementById('txt_discount').value/100;
            var discount_amount= subtotal_amount * discount_percentage;
            document.getElementById('txt_sales_amount').value = subtotal_amount - discount_amount;

        }
        else if(document.getElementById('txt_discount_type').value=="Amount")
        {
            var discount_amount = document.getElementById('txt_discount').value;
            document.getElementById('txt_sales_amount').value = subtotal_amount - discount_amount;
        }
        
    }   
</script>
<script type="text/javascript">
    function calc_balance()
    {
        var received_amount=document.getElementById("txt_received_amount").value;
        document.getElementById('txt_refund_amount').value="";
        if(received_amount!="")
        {
            let sales_amount=document.getElementById('txt_sales_amount').value;
            document.getElementById('txt_refund_amount').value = received_amount - sales_amount;
        }
    }
</script>
<script type="text/javascript">
    function add_sales_item_on_sale()
    {
        let barcode=document.getElementById("txt_model_barcode").value;
        let sales_id=document.getElementById("txt_sales_item_id").value;
        if(barcode!="" & sales_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result=xmlhttp.responseText.trim();
                    if(result=="true")
                    {
                    location.reload("http://localhost/ACX/index.php?page=sales.php&option=add");
                    }
                    else if(result=="false")
                    {
                        document.getElementById("salesitem_error_msg").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Check Barcode -</h3></center><center><h4>Model Not Found</h4></center></div>';
                        document.getElementById("txt_model_barcode").value="";
                        document.getElementById("txt_model_barcode").focus();
                    }
                    else if(result=="out_of_stock")
                    {
                        document.getElementById("salesitem_error_msg").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Out of Stock -</h3></center></div>';
                        document.getElementById("txt_model_barcode").value="";
                        document.getElementById("txt_model_barcode").focus();
                    }
                    else if(result=="model_blocked")
                    {
                        document.getElementById("salesitem_error_msg").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Model Blocked-</h3></center><center><h4>Sales Price Unavailable</h4></center></div>';
                        document.getElementById("txt_model_barcode").value="";
                        document.getElementById("txt_model_barcode").focus();
                    }
                    
 
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=add_sales_item_on_sale&ajax_barcode=" + barcode + "&ajax_sales_id=" + sales_id, true);
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
             <!-- sales item add -->
             <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body" ><div id="salesitem_error_msg"></div>
                                <h4 class="card-title">Sales</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                            <div class="form-row">
                                            <div class="form-group col-md-6">
                                                 <?php 
                                                    $sql_create_sales_item_id="SELECT salesid FROM salesitem ORDER BY salesid DESC LIMIT 1";
                                                    $result_create_sales_item_id=mysqli_query($con,$sql_create_sales_item_id) or die ("Error in Creating Sales ID".mysqli_error($con));
                                                    if(mysqli_num_rows($result_create_sales_item_id)==1)
                                                    {
                                                        $row_create_sales_item_id=mysqli_fetch_assoc($result_create_sales_item_id);
                                                        $sales_item_id=++$row_create_sales_item_id["salesid"];
                                                    }
                                                    else
                                                    {
                                                        $sales_item_id="SAL001";
                                                    }
                                                    ?>
                                                <label>Sales ID</label>
                                                <input type="text" name="txt_sales_item_id" id="txt_sales_item_id"  class="form-control" value="<?php echo (isset($_SESSION["SALES_ID"])) ? $_SESSION["SALES_ID"] : $sales_item_id; ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Model</label>
                                                <input type="text" autofocus name="txt_model_barcode" id="txt_model_barcode" class="form-control" tabindex="2" onchange="add_sales_item_on_sale()" required>
                                            </div>                                           
                                        </div>  
                                    </form>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- sales item view -->
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Item Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Model Name</th>
                                                    <th>Quantity</th>
                                                    <th>Sales Price</th>
                                                    <th>Action</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                if(isset($_SESSION["SALES_ID"])){
                                                $sql_sales_item_details="SELECT * FROM salesitem where salesid='$_SESSION[SALES_ID]'";
                                                $result_sales_item_details=mysqli_query($con,$sql_sales_item_details) or die ("Error getting purchase item details".mysqli_error($con));
                                                $x=1;
                                                $total_amount=0;
                                                while($row_get_sales_item_details=mysqli_fetch_assoc($result_sales_item_details))
                                                {   
                                                    $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_sales_item_details[modelno]'";
                                                    $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);

                                                    $sql_get_sales_price="SELECT salesprice FROM modelprice WHERE modelno='$row_get_sales_item_details[modelno]'";
                                                    $result_get_sales_price=mysqli_query($con,$sql_get_sales_price) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_sales_price=mysqli_fetch_assoc($result_get_sales_price);
                                                        
                                                    echo'<tr>
                                                            <td>'.$x.'</td>
                                                            <td>'.$row_get_model_name["modelname"].'</td>
                                                            <td>'.$row_get_sales_item_details["quantity"].'</td>
                                                            <td>'.$row_get_sales_price["salesprice"].'</td>
                                                            <td>
                                                                 <a href="index.php?page=salesitem.php&option=edit&sales_item_id='.$row_get_sales_item_details['salesid'].'&sales_item_modelno='.$row_get_sales_item_details['modelno'].'"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i> </button></a>
                                                                <a href="index.php?page=salesitem.php&option=delete&sales_item_id='.$row_get_sales_item_details['salesid'].'&sales_item_modelno='.$row_get_sales_item_details['modelno'].'"><button type="button" class="btn btn-danger"><i class="fas fa-trash"></i> </button></a>
                                                            </td>
                                                        </tr>';
                                                        $sub_total=$row_get_sales_item_details["quantity"] * $row_get_sales_price["salesprice"];
                                                        $total_amount=$total_amount + $sub_total;
                                                        $x++ ;
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
            if(isset($_SESSION["SALES_ID"])){
                    //get enterrby staff name
                    $sql_get_enterby="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
                    $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                    $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                    //get branch name
                    $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_enterby[branchid]'";
                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                    $row_get_branch=mysqli_fetch_assoc($result_get_branch);
            ?>
                    <!-- Sales bill add -->
    			         <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Bill</h4>
                                    <div class="basic-form">
                                        <form method="POST" action="" autocomplete="off">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                
                                                    <label>Sales ID</label>
                                                    <input type="text" name="txt_sales_id" id="txt_sales_id" class="form-control" value="<?php echo $_SESSION["SALES_ID"] ?>" readonly >
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Sales Date</label>
                                                    <input type="Date" name="txt_sales_date" id="txt_sales_date" class="form-control" value="<?php echo date("Y-m-d") ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Customer</label>
                                                    <select name="txt_customer_id" id="txt_customer_id" class="form-control chzn-select" required>
                                                        <option value="">Select Customer</option>
                                                        <?php
                                                        $sql_get_customer="SELECT * FROM customer";
                                                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                                                        while ($row_get_customer=mysqli_fetch_assoc($result_get_customer)) 
                                                        {
                                                            echo '<option value="'.$row_get_customer["custid"].'">'.$row_get_customer["cusname"]."-".$row_get_customer["tpno"].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <div align="right"><a href="index.php?page=customer.php&option=add&url_id=sales" onMouseOver="style.color='red'" onMouseOut="style.color='blue'"><i class="fas fa-plus"></i> Add New Customer</a></div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Enter By</label>
                                                    <select name="txt_enterby" id="txt_enterby" class="form-control" readonly>
                                                    <option value="<?php echo $row_get_enterby["staffid"]?>"><?php echo $row_get_enterby["staffname"]?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Branch Name</label>
                                                    <select name="txt_branch_id" id="txt_branch_id" class="form-control" readonly>
                                                    <option value="<?php echo $row_get_branch["branchid"]?>"><?php echo $row_get_branch["branchname"]?></option>
                                                    </select>
                                                </div>
                                                <div class="form-row col-md-6">
                                                    <div class="form-group col-md-6">
                                                    <label>Discount Type</label>
                                                    <select name="txt_discount_type" id="txt_discount_type" class="form-control">
                                                        <option>Amount</option>
                                                        <option>Percentage</option>
                                                    </select>
                                                    <!-- <input type="text" name="txt_discount_type" id="txt_discount_type" class="form-control"  placeholder="Discount Percentage" required> -->
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                    <label>Discount</label>
                                                    <input type="number" max="" min="0" name="txt_discount" id="txt_discount" class="form-control" oninput="calc_discount()"  required>
                                                    </div>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <label>Total Amount</label>
                                                    <input type="text" name="txt_total_amount" id="txt_total_amount" class="form-control" value="<?php echo $total_amount ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Sales Amount</label>
                                                    <input type="text" name="txt_sales_amount" id="txt_sales_amount" class="form-control"  readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Received Amount</label>
                                                    <input type="text" name="txt_received_amount" id="txt_received_amount" oninput="calc_balance()" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Refund Amount</label>
                                                    <input type="text" name="txt_refund_amount" id="txt_refund_amount" class="form-control"  readonly>
                                                </div>
                                                </div>
                                                <div>
                                                    <button type="submit" name="btn_save_sales" id="btn_save_sales" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                                    <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                    <a href="index.php?page=sales.php&option=cancel_sale"><button type="button" name="btn_cancel_sale" id="btn_cancel_sale" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
           
			<?php
        }
        }
        elseif ($_GET["option"]=="view" & $system_user_type=="Manager") 
        {
        ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=sales.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Sales ID</th>
                                                    <th>Sales Date</th>
                                                    <th>Customer</th>
                                                    <th>Enter By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_sales_details_view="SELECT salesid,salesdate,custid,enterby,branchid FROM sales";
                                                $result_sales_details_view=mysqli_query($con,$sql_sales_details_view) or die("Error in sales details view".mysqli_error($con));
                                                while ($row_sales_details_view=mysqli_fetch_assoc($result_sales_details_view)) 
                                                {   //get customer name
                                                    $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_sales_details_view[custid]'";
                                                    $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting customer".mysqli_error($con));
                                                    $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                                                    //get staff name
                                                    $sql_get_staff="SELECT * FROM staff WHERE staffid='$row_sales_details_view[enterby]'";
                                                    $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in get Category".mysqli_error($con));
                                                    $row_get_staff=mysqli_fetch_assoc($result_get_staff);

                                                    echo '<tr>
                                                            <td>'.$row_sales_details_view["salesid"].'</td>
                                                            <td>'.$row_sales_details_view["salesdate"].'</td>
                                                            <td>'.$row_get_customer["cusname"].'</td>
                                                            <td>'.$row_get_staff["staffname"].'</td>
                                                            <td>
                                                                <a href="index.php?page=sales.php&option=fullview&sales_id='.$row_sales_details_view["salesid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
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
        elseif ($_GET["option"]=="view" & $system_user_type!="Manager") 
        {
        ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=sales.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Sales ID</th>
                                                    <th>Sales Date</th>
                                                    <th>Customer</th>
                                                    <th>Enter By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // get branch id
                                                $sql_get_enterby="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
                                                $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                                                $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                                                $branchid=$row_get_enterby["branchid"];

                                                $sql_sales_details_view="SELECT salesid,salesdate,custid,enterby,branchid FROM sales WHERE branchid='$branchid'";
                                                $result_sales_details_view=mysqli_query($con,$sql_sales_details_view) or die("Error in sales details view".mysqli_error($con));
                                                while ($row_sales_details_view=mysqli_fetch_assoc($result_sales_details_view)) 
                                                {   //get customer name
                                                    $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_sales_details_view[custid]'";
                                                    $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting customer".mysqli_error($con));
                                                    $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                                                    //get staff name
                                                    $sql_get_staff="SELECT * FROM staff WHERE staffid='$row_sales_details_view[enterby]'";
                                                    $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in get Category".mysqli_error($con));
                                                    $row_get_staff=mysqli_fetch_assoc($result_get_staff);

                                                    echo '<tr>
                                                            <td>'.$row_sales_details_view["salesid"].'</td>
                                                            <td>'.$row_sales_details_view["salesdate"].'</td>
                                                            <td>'.$row_get_customer["cusname"].'</td>
                                                            <td>'.$row_get_staff["staffname"].'</td>
                                                            <td>
                                                                <a href="index.php?page=sales.php&option=fullview&sales_id='.$row_sales_details_view["salesid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
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
        elseif ($_GET["option"]=="fullview") 
        {   
            $get_sales_id=$_GET["sales_id"];
            $sql_sales_fullview="SELECT * FROM sales WHERE salesid='$get_sales_id'";
            $result_sales_fullviewl=mysqli_query($con,$sql_sales_fullview)or die("Error in geting sales fullview details".mysqli_error($con));
            $row_sales_fullview=mysqli_fetch_assoc($result_sales_fullviewl);

            //get enterrby staff name
            $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_sales_fullview[enterby]'";
            $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
            $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
            //get branch name
            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_sales_fullview[branchid]'";
            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
            //get customer name
            $sql_get_customer="SELECT * FROM customer WHERE custid='$row_sales_fullview[custid]'";
            $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
            $row_get_customer=mysqli_fetch_assoc($result_get_customer);
            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Full Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <tr><th style="width: 50%">Sales ID</th><td><?php echo $row_sales_fullview["salesid"] ?></td></tr>
                                            <tr><th>Sales Date</th><td><?php echo $row_sales_fullview["salesdate"] ?></td></tr>
                                            <tr><th>Customer</th><td><?php echo $row_get_customer["cusname"] ?></td></tr>
                                            <tr><th>Enter By</th><td><?php echo $row_get_enterby["staffname"] ?></td></tr>
                                            <tr><th>Branch</th><td><?php echo $row_get_branch["branchname"] ?></td></tr>
                                            <?php
                                            if($row_sales_fullview["discount_type"]=="Percentage")
                                            {
                                                echo '<tr><th>Discount</th><td>'.$row_sales_fullview["discount"].' %</td></tr>';
                                            }
                                            else
                                            {
                                                echo '<tr><th>Discount</th><td>Rs '.$row_sales_fullview["discount"].'</td></tr>';
                                            }
                                            ?>
                                            <tr><th>Sales Amount</th><td><?php echo $row_sales_fullview["sales_amount"] ?></td></tr>
                                            <tr>
                                                <td colspan="2">
                                                    <center>
                                                        <a href="index.php?page=sales.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
                                                        <a href="javascript:window.open('index.php?page=invoice.php&option=sales_invoice&salesid=<?php echo $get_sales_id; ?>','_blank')"><button type="button"class="btn btn-success"><i class="fas fa-print"></i> Print Invoice</button></a>
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
             <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Item Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Model Name</th>
                                                    <th>Quantity</th>
                                                    <th>Sales Price</th> 
                                                    <th>Total Amount</th>                                            
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                $sql_sales_item_details="SELECT * FROM salesitem where salesid='$get_sales_id'";
                                                $result_sales_item_details=mysqli_query($con,$sql_sales_item_details) or die ("Error getting purchase item details".mysqli_error($con));
                                                $x=1;
                                                $total_amount=0;
                                                while($row_get_sales_item_details=mysqli_fetch_assoc($result_sales_item_details))
                                                {   //get model name
                                                    $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_sales_item_details[modelno]'";
                                                    $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);
                                                    //get sales price
                                                    $sql_get_sales_price="SELECT salesprice FROM modelprice WHERE modelno='$row_get_sales_item_details[modelno]'";
                                                    $result_get_sales_price=mysqli_query($con,$sql_get_sales_price) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_sales_price=mysqli_fetch_assoc($result_get_sales_price);
                                                    //calculate total amount
                                                    $sub_total=$row_get_sales_item_details["quantity"] * $row_get_sales_price["salesprice"];
                                                    $total_amount=$total_amount + $sub_total;   

                                                    echo'<tr>
                                                            <td>'.$x.'</td>
                                                            <td>'.$row_get_model_name["modelname"].'</td>
                                                            <td>'.$row_get_sales_item_details["quantity"].'</td>
                                                            <td>'.$row_get_sales_price["salesprice"].'</td>
                                                            <td>'.$total_amount.'</td>
                                                        </tr>';
                                                        
                                                        $x++ ;
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
        elseif ($_GET["option"]=="cancel_sale") {
            $sql_salesitem_delete="DELETE FROM salesitem WHERE salesid='$_SESSION[SALES_ID]'";
            $result_salesitem_delete=mysqli_query($con,$sql_salesitem_delete)or die("Error in salesitem delete".mysqli_error($con));
            if($result_salesitem_delete)
            {   unset($_SESSION["SALES_ID"]);
                echo '<script>
                alert("Sale Cancelled!!");
                window.location.href="index.php?page=sales.php&option=view";
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