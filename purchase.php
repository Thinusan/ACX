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
				<li>Purchase</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_purchase"]))
{   // create sup_ledger_id 
    $sql_create_sup_ledger_id ="SELECT sup_ledger_id FROM supplierledger ORDER BY sup_ledger_id DESC LIMIT 1";
    $result_create_sup_ledger_id =mysqli_query($con,$sql_create_sup_ledger_id ) or die ("Error in Creating id".mysqli_error($con));
        if(mysqli_num_rows($result_create_sup_ledger_id )==1)
        {
            $row_create_sup_ledger_id =mysqli_fetch_assoc($result_create_sup_ledger_id );
            $sup_ledger_id =++$row_create_sup_ledger_id ["sup_ledger_id"];
        }
        else
        {
            $sup_ledger_id ="LED001"; //purchase pay reference
        }

    // insert in purchase
    $sql_insert_purchase="INSERT INTO purchase(purid,purdate,billno,supid,enterby,branchid,total_amount,paid_amount,status,ledger_ref)
                          VALUES('".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_purchase_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_bill_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_supplier_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_enterby"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_total_amount"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_paid_amount"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_status"])."',
                                '".mysqli_real_escape_string($con,$sup_ledger_id)."')";
    $result_insert_purchase=mysqli_query($con,$sql_insert_purchase) or die("Error in inserting in purchase".mysqli_error($con));

    // insert in stock
    $purchase_id=$_POST['txt_purchase_id'];
    $sql_get_purchase_details= "SELECT * FROM purchaseitem WHERE purid='$purchase_id'";
    $result_get_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purshase details:".mysqli_error($con));
    while($row_get_purchase_details=mysqli_fetch_assoc($result_get_purchase_details))
    {
        $sql_insert_stock="INSERT INTO stock(purid,modelno,quantity,branchid)
        values('".mysqli_real_escape_string($con,$purchase_id)."',
                '".mysqli_real_escape_string($con,$row_get_purchase_details["modelno"])."',
                '".mysqli_real_escape_string($con,$row_get_purchase_details["noofitems"])."',
                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."')";
        $result_insert_stock=mysqli_query($con,$sql_insert_stock) or die ("error in.sql inserting stock:".mysqli_error($con));
    }

    // insert in supplierledger -purchase total amount
    $sql_insert_supplier_ledger_1="INSERT INTO supplierledger(sup_ledger_id,ledger_date,pur_ret_ref_id,supid,branchid,type,payment_mode,credit,debit)
                          VALUES('".mysqli_real_escape_string($con,$sup_ledger_id)."',
                                '".mysqli_real_escape_string($con,$_POST["txt_purchase_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_supplier_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,"Purchase")."',
                                '".mysqli_real_escape_string($con,"None")."',
                                '".mysqli_real_escape_string($con,$_POST["txt_total_amount"])."',
                                '".mysqli_real_escape_string($con,"")."')";
    $result_insert_supplier_ledger_1=mysqli_query($con,$sql_insert_supplier_ledger_1) or die("Error in inserting in supplierledger 1".mysqli_error($con));
    
    // insert in supplierledger -paid amount
    $sql_insert_supplier_ledger_2="INSERT INTO supplierledger(sup_ledger_id,ledger_date,pur_ret_ref_id,supid,branchid,type,payment_mode,credit,debit)
                          VALUES('".mysqli_real_escape_string($con,(++$sup_ledger_id))."',
                                '".mysqli_real_escape_string($con,$_POST["txt_purchase_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_supplier_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,"Payment")."',
                                '".mysqli_real_escape_string($con,$_POST["txt_payment_mode"])."',
                                '".mysqli_real_escape_string($con,"")."',
                                '".mysqli_real_escape_string($con,$_POST["txt_paid_amount"])."')";
    $result_insert_supplier_ledger_2=mysqli_query($con,$sql_insert_supplier_ledger_2) or die("Error in inserting in supplierledger 2".mysqli_error($con));

    if($result_insert_purchase && $result_insert_stock && $result_insert_supplier_ledger_1 && $result_insert_supplier_ledger_2)
    {   
        unset($_SESSION["PURCHASE_ID"]);
        echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=purchase.php&option=view";
            </script>';
    }
   
}
if(isset($_POST["btn_save_purchaseitem"]))
{   
    // seperate model id from barcode field
    $result=explode("-",$_POST["txt_model_barcode"]);
    $modelid=$result[0];

    $sql_get_purchase_item_id="SELECT * FROM purchaseitem Where purid='$_POST[txt_purchase_item_id]'";
    $result_get_purchase_item_id=mysqli_query($con,$sql_get_purchase_item_id) or die ("Error in Getting Purchase ID".mysqli_error($con));
    $row_get_purchase_item_id=mysqli_fetch_assoc($result_get_purchase_item_id);

    if($row_get_purchase_item_id["modelno"]!=$modelid){

        $sql_insert_purchaseitem="INSERT INTO purchaseitem(purid,modelno,noofitems,unitprice)
                            VALUES('".mysqli_real_escape_string($con,$_POST["txt_purchase_item_id"])."',
                                    '".mysqli_real_escape_string($con,$modelid)."',
                                    '".mysqli_real_escape_string($con,$_POST["txt_noofitems"])."',
                                    '".mysqli_real_escape_string($con,$_POST["txt_unit_price"])."')";
        $result_insert_purchaseitem=mysqli_query($con,$sql_insert_purchaseitem) or die("Error in inserting in purchaseitem".mysqli_error($con));

        if($result_insert_purchaseitem)
        {   
                $_SESSION["PURCHASE_ID"]=$_POST["txt_purchase_item_id"];
                echo'<div class="card-body">
                        <div class="alert alert-success" role="alert">
                            <center><h4>Successfully Added</h4></center>
                        </div>
                    </div>';
                echo '<script>
                        window.setInterval(function(){window.location.href="index.php?page=purchase.php&option=add"},1000);
                    </script>';
        }
    }
    else
    {       $quantity=$_POST["txt_noofitems"]; // if same item added in purchase 
            $sql_update_purchaseitem="UPDATE purchaseitem SET  
                                    noofitems='".mysqli_real_escape_string($con,($quantity + $_POST["txt_noofitems"]))."'
                                    WHERE purid='".mysqli_real_escape_string($con,$_POST["txt_purchase_item_id"])."' AND modelno='".mysqli_real_escape_string($con,$modelid)."'";
            $result_update_purchaseitem=mysqli_query($con,$sql_update_purchaseitem) or die("Error in updating in purchaseitem".mysqli_error($con));
            if($result_update_purchaseitem)
            {   
                echo'<div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <center><h4>Same Model Already Added</h4></center>
                        </div>
                    </div>';
                echo '<script>
                        window.setInterval(function(){window.location.href="index.php?page=purchase.php&option=add"},1200);
                    </script>';
            }
    }

}
if(isset($_POST["btn_edit_purchase"]))
{
    $sql_update_purchase="UPDATE purchase SET 
                        purdate='".mysqli_real_escape_string($con,$_POST["txt_purchase_date"])."',
                        billno='".mysqli_real_escape_string($con,$_POST["txt_bill_number"])."',
                        supid='".mysqli_real_escape_string($con,$_POST["txt_supplier_id"])."',
                        enterby='".mysqli_real_escape_string($con,$_POST["txt_enterby"])."',
                        branchid='".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."'
                        WHERE purid='".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."'";
    $result_update_purchase=mysqli_query($con,$sql_update_purchase) or die("Error in updating in purchase".mysqli_error($con));

    $ledger_ref=$_POST["txt_ledger_ref"];

    $sql_update_sup_ledger="UPDATE supplierledger SET 
                            supid='".mysqli_real_escape_string($con,$_POST["txt_supplier_id"])."'
                            WHERE sup_ledger_id IN ('".mysqli_real_escape_string($con,$ledger_ref)."','".mysqli_real_escape_string($con,++$ledger_ref)."')";
    $result_update_sup_ledger=mysqli_query($con,$sql_update_sup_ledger) or die("Error in updating in suppplier ledger".mysqli_error($con));

    if($result_update_purchase && $result_update_sup_ledger)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=purchase.php&option=view";
            </script>';
    }
}
if(isset($_POST["btn_edit_purchase_payment"]))
{   
    // create sup_ledger_id 
    $sql_create_sup_ledger_id ="SELECT sup_ledger_id FROM supplierledger ORDER BY sup_ledger_id DESC LIMIT 1";
    $result_create_sup_ledger_id =mysqli_query($con,$sql_create_sup_ledger_id ) or die ("Error in Creating id".mysqli_error($con));
        if(mysqli_num_rows($result_create_sup_ledger_id )==1)
        {
            $row_create_sup_ledger_id =mysqli_fetch_assoc($result_create_sup_ledger_id );
            $sup_ledger_id =++$row_create_sup_ledger_id ["sup_ledger_id"];
        }
        else
        {
            $sup_ledger_id ="LED001"; //purchase pay reference
        }

    // update total amount in purchase
    $total_amount=0;
    $paying_amount=$_POST["txt_pay_paying_amount"];
    $purchase_payment_id=$_POST["txt_pay_purchase_id"];

    $sql_get_purchase_detail="SELECT * FROM purchase WHERE purid='$purchase_payment_id'";
    $result_get_purchase_detail=mysqli_query($con,$sql_get_purchase_detail)or die("Error in geting purchase details".mysqli_error($con));
    $row_get_purchase_detail=mysqli_fetch_assoc($result_get_purchase_detail);

    $total_amount=$row_get_purchase_detail["paid_amount"] + $paying_amount;

    $sql_update_purchase_payment="UPDATE purchase SET 
                        paid_amount='".mysqli_real_escape_string($con,$total_amount)."',
                        status='".mysqli_real_escape_string($con,$_POST["txt_pay_status"])."'
                        WHERE purid='".mysqli_real_escape_string($con,$_POST["txt_pay_purchase_id"])."'";
    $result_update_purchase_payment=mysqli_query($con,$sql_update_purchase_payment) or die("Error in updating in purchase payment".mysqli_error($con));
    
    // insert in supplier ledger
    $sql_insert_supplier_ledger_1="INSERT INTO supplierledger(sup_ledger_id,ledger_date,pur_ret_ref_id,supid,branchid,type,payment_mode,credit,debit)
                          VALUES('".mysqli_real_escape_string($con,$sup_ledger_id)."',
                                '".mysqli_real_escape_string($con,date('Y.m.d'))."',
                                '".mysqli_real_escape_string($con,$purchase_payment_id)."',
                                '".mysqli_real_escape_string($con,$row_get_purchase_detail["supid"])."',
                                '".mysqli_real_escape_string($con,$row_get_purchase_detail["branchid"])."',
                                '".mysqli_real_escape_string($con,"Payment")."',
                                '".mysqli_real_escape_string($con,$_POST["txt_payment_mode"])."',
                                '".mysqli_real_escape_string($con,"")."',
                                '".mysqli_real_escape_string($con,$paying_amount)."')";
    $result_insert_supplier_ledger_1=mysqli_query($con,$sql_insert_supplier_ledger_1) or die("Error in inserting in supplierledger 1".mysqli_error($con));

                               

    if($result_update_purchase_payment && $result_insert_supplier_ledger_1)
    {
        echo '<script>
                alert("Successfully Updated Purchase Payment!!");
                window.location.href="index.php?page=purchase.php&option=view";
            </script>';
    }

}

?>
<script type="text/javascript">
    function check_sales_price()
    {
        let barcode=document.getElementById("txt_model_barcode").value;
        if(barcode!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result=xmlhttp.responseText.trim()
                    const salesprice_modelname=result.split(",");
                    let sales_price=salesprice_modelname[1];
                    let modelname=salesprice_modelname[0];
                    let modelno=salesprice_modelname[2];
                    if(modelname!="" & sales_price!="" & modelno!="")
                    {
                        document.getElementById("txt_unit_price").max=sales_price;
                        document.getElementById("sales_price").innerHTML = "Sales Price : Rs " + sales_price;

                        document.getElementById("txt_model_barcode").value= modelno + "-" + modelname ;
                        document.getElementById("txt_model_barcode").readOnly= true;
                        
                    }
                    else if(modelname!="" & sales_price=="")
                    {
                        document.getElementById("purchaseitem_error_msg").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Model Blocked -</h3></center><center><h4>Sales Price Unavailable</h4></center></div>';
                        document.getElementById("txt_model_barcode").value= "";
                        document.getElementById("txt_model_barcode").focus();
                    }
                    else
                    {   
                        document.getElementById("purchaseitem_error_msg").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Model Not Found -</h3></center><center><h4>Add New Model</h4></center></div>';
                        document.getElementById("txt_model_barcode").value= "";
                        document.getElementById("add_new_model").focus();
                    }
                }
                
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_purchase_salse_price&ajax_barcode=" + barcode, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("sales_price").innerHTML='';
        }
    }
</script>
<script type="text/javascript">
    function check_sup_acc_bal()
    {
        let supplier_id=document.getElementById("txt_supplier_id").value;;
        if(supplier_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let sup_acc_bal=xmlhttp.responseText.trim();
                    document.getElementById("sup_acc_balance").innerHTML = sup_acc_bal;
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_supllier_account_balance&ajax_supplier_id=" + supplier_id, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("sup_acc_balance").innerHTML='';
        }
    }
</script>
<script type="text/javascript">
    function set_status()
    {   
        var total_amount = document.getElementById('txt_pay_total_amount').value;
        var paid_amount = document.getElementById('txt_pay_paid_amount').value;
        var balance_amount = document.getElementById('txt_pay_balance_amount').value;
        var paying_amount = document.getElementById('txt_pay_paying_amount').value;

        if(paying_amount==balance_amount)
        {
            document.getElementById('txt_pay_status').value = "Paid" ;
        }
        else
        {
            document.getElementById('txt_pay_status').value = "Pending" ;
        }


    }   
</script>
<script type="text/javascript">
    function calc_balance()
    {   
        var total_amount = document.getElementById('txt_total_amount').value;
        var paid_amount = document.getElementById('txt_paid_amount').value;
        // if(paid_amount<=0)
        // {
        //     document.getElementById('txt_balance').value = 0;
        // }
        if(paid_amount === '')
        {
            document.getElementById('txt_balance').value = 0;
        }
        else
        {
            document.getElementById('txt_balance').value = total_amount - paid_amount;
        }

        if(paid_amount==total_amount)
        {
            document.getElementById('txt_status').value = "Paid" ;
        }
        else
        {
            document.getElementById('txt_status').value = "Pending" ;
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
                            <div class="card-body"><div id="purchaseitem_error_msg"></div>
                                <h4 class="card-title">Purchase</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                           <div class="form-group col-md-6">
                                                    <?php 
                                                    $sql_create_purchase_item_id="SELECT purid FROM purchaseitem ORDER BY purid DESC LIMIT 1";
                                                    $result_create_purchase_item_id=mysqli_query($con,$sql_create_purchase_item_id) or die ("Error in Creating id".mysqli_error($con));
                                                    if(mysqli_num_rows($result_create_purchase_item_id)==1)
                                                    {
                                                        $row_create_purchase_item_id=mysqli_fetch_assoc($result_create_purchase_item_id);
                                                        $purchase_item_id=++$row_create_purchase_item_id["purid"];
                                                    }
                                                    else
                                                    {
                                                        $purchase_item_id="PUR001";
                                                    }
                                                    ?>
                                                <label>Purchase ID</label>
                                                <input type="text" name="txt_purchase_item_id" id="txt_purchase_item_id" class="form-control" value="<?php echo (isset($_SESSION["PURCHASE_ID"])) ? $_SESSION["PURCHASE_ID"] : $purchase_item_id; ?>" readonly>
                                           </div>
                                           <div class="form-group col-md-6">
                                                <label>Model</label>
                                                <input type="text" autofocus name="txt_model_barcode" id="txt_model_barcode" class="form-control" tabindex="2" onchange="check_sales_price()" >
                                                
                                                <div align="right"><a id="add_new_model" href="index.php?page=model.php&option=add&url_id=purchase" onMouseOver="style.color='red'" onMouseOut="style.color='blue'"><i class="fas fa-plus"></i> Add New Model</a></div>
                                           </div>
                                           <div class="form-group col-md-6">
                                                <label>Number of Items</label>
                                                <input type="text" name="txt_noofitems" id="txt_noofitems"class="form-control"  required>
                                           </div>
                                           <div class="form-group col-md-6">
                                                <label>Unit Price</label>
                                                <input type="text"  name="txt_unit_price" id="txt_unit_price"class="form-control" required>
                                                <div id="sales_price"></div>
                                           </div>

                                        </div>
                                       <div>
                                        <button type="submit" name="btn_save_purchaseitem" id="btn_save_purchaseitem" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                                        <a href="index.php?page=purchase.php&option=add"><button type="button" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            
              <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Purchase Item Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="check">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Model Name</th>
                                                    <th>No of Items</th>
                                                    <th>Unit Price</th>
                                                    <th>Action</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                if(isset($_SESSION["PURCHASE_ID"])){
                                                $sql_purchase_item_details="SELECT * FROM purchaseitem where purid='$_SESSION[PURCHASE_ID]'";
                                                $result_purchase_item_details=mysqli_query($con,$sql_purchase_item_details) or die ("Error getting purchase item details".mysqli_error($con));
                                                $x=1;
                                                $total_amount=0;
                                                while($row_get_purchase_item_details=mysqli_fetch_assoc($result_purchase_item_details))
                                                {   
                                                    $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_purchase_item_details[modelno]'";
                                                    $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);
                                                        
                                                    echo'<tr>
                                                            <td>'.$x.'</td>
                                                            <td>'.$row_get_model_name["modelname"].'</td>
                                                            <td>'.$row_get_purchase_item_details["noofitems"].'</td>
                                                            <td>'.$row_get_purchase_item_details["unitprice"].'</td>
                                                            <td>
                                                                 <a href="index.php?page=purchaseitem.php&option=edit&purchase_item_id='.$row_get_purchase_item_details['purid'].'&purchase_item_modelno='.$row_get_purchase_item_details['modelno'].'"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i> </button></a>
                                                                <a href="index.php?page=purchaseitem.php&option=delete&purchase_item_id='.$row_get_purchase_item_details['purid'].'&purchase_item_modelno='.$row_get_purchase_item_details['modelno'].'"><button type="button" class="btn btn-danger"><i class="fas fa-trash"></i> </button></a>
                                                            </td>
                                                        </tr>';
                                                        $sub_total=$row_get_purchase_item_details["noofitems"] * $row_get_purchase_item_details["unitprice"];
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
            if(isset($_SESSION["PURCHASE_ID"])){
            
                $sql_get_enterby="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
                $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);

                $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_enterby[branchid]'";
                $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                $row_get_branch=mysqli_fetch_assoc($result_get_branch);
            
            ?>
			<div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Purchase Bill</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                    <label>Purchase ID</label>
                                                    <input type="text" name="txt_purchase_id" id="txt_purchase_id" class="form-control" value="<?php echo $_SESSION["PURCHASE_ID"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Purchase Date</label>
                                                    <input type="Date" name="txt_purchase_date" id="txt_purchase_date" class="form-control" value="<?php echo date("Y-m-d") ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Supplier Bill No</label>
                                                    <input type="text" name="txt_bill_number" id="txt_bill_number"class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Supplier Name</label>
                                                    <select name="txt_supplier_id" id="txt_supplier_id" class="form-control chzn-select" tabindex="2" onchange="check_sup_acc_bal()" required>
                                                    <option>Select Supplier</option>
                                                    <?php
                                                        $sql_get_suppliers="SELECT * FROM suppliers";
                                                        $result_get_suppliers=mysqli_query($con,$sql_get_suppliers) or die ("Error in get Category".mysqli_error($con));
                                                        while ($row_get_suppliers=mysqli_fetch_assoc($result_get_suppliers)) 
                                                        {
                                                            echo '<option value="'.$row_get_suppliers["supid"].'">'.$row_get_suppliers["supname"].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                <div align="right"><a href="index.php?page=suppliers.php&option=add&url_id=purchase" onMouseOver="style.color='red'" onMouseOut="style.color='blue'"><i class="fas fa-plus"></i> Add New Supplier</a></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Enter By</label>
                                                    <select name="txt_enterby" id="txt_enterby" class="form-control" required>
                                                    <option value="<?php echo $row_get_enterby["staffid"]?>"><?php echo $row_get_enterby["staffname"]?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Branch Name</label>
                                                    <select name="txt_branch_id" id="txt_branch_id" class="form-control" required>
                                                    <option value="<?php echo $row_get_branch["branchid"]?>"><?php echo $row_get_branch["branchname"]?></option>
                                                    </select>
                                                </div>
                                            <div class="form-row col-md-12">
                                                <div class="form-group col-md-4">
                                                        <label>Total Amount</label>
                                                        <input type="text" name="txt_total_amount" id="txt_total_amount" class="form-control" value="<?php echo $total_amount ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                        <label>Payment Mode</label>
                                                        <Select type="text" name="txt_payment_mode" id="txt_payment_mode" class="form-control" required>
                                                            <option selected disabled hidden>Select Payment Mode</option>
                                                            <option>Cash</option>
                                                            <option>Cheque</option>
                                                            <option>Card</option>
                                                        </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                        <label>Paying Amount</label>
                                                        <input type="number" min="0" max="<?php echo $total_amount ?>" name="txt_paid_amount" id="txt_paid_amount" class="form-control" oninput="calc_balance()" required>
                                                        <div id="sup_acc_balance"></div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Status</label>
                                                    <input type="text" name="txt_status" id="txt_status" class="form-control" readonly> 
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Balance</label>
                                                    <input type="number"  name="txt_balance" id="txt_balance" class="form-control" readonly> 
                                            </div>
                                        </div>                                            
                                </div>
                                            <div>
                                                <button type="submit" name="btn_save_purchase" id="btn_save_purchase" class="btn btn-success" ><i class="fa fa-save"></i> Save</button>
                                                <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                <a href="index.php?page=purchase.php&option=cancel_purchase"><button type="button" name="btn_cancel_purchase" id="btn_cancel_purchase" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
                                    <h4 class="card-title">Purchase Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                    <a href="index.php?page=purchase.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Purchase ID</th>
                                                    <th>Purchase Date</th>
                                                    <th>Total Amount</th>
                                                    <th>Payment Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_purchase_details_view="SELECT * FROM purchase";
                                                $result_purchase_details_view=mysqli_query($con,$sql_purchase_details_view)or die("Error in purchase details view".mysqli_error());
                                                while ($row_purchase_details_view=mysqli_fetch_assoc($result_purchase_details_view)) 
                                                {   //get staff name
                                                    $sql_get_staff="SELECT * FROM staff WHERE staffid='$row_purchase_details_view[enterby]'";
                                                    $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in get Category".mysqli_error($con));
                                                    $row_get_staff=mysqli_fetch_assoc($result_get_staff);

                                                    echo '<tr>
                                                            <td>'.$row_purchase_details_view["purid"].'</td>
                                                            <td>'.$row_purchase_details_view["purdate"].'</td>
                                                            <td>'.$row_purchase_details_view["total_amount"].'</td>
                                                            <td>'.$row_purchase_details_view["status"].'</td>
                                                            <td>';                               
                                                               
                                                               echo '<a href="index.php?page=purchase.php&option=fullview&purchase_id='.$row_purchase_details_view["purid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>&nbsp'; 
                                                               echo '</td>
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
                                    <h4 class="card-title">Purchase Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                    <a href="index.php?page=purchase.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Purchase ID</th>
                                                    <th>Purchase Date</th>
                                                    <th>Total Amount</th>
                                                    <th>Payment Status</th>
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
                                                $sql_purchase_details_view="SELECT * FROM purchase WHERE branchid='$branchid'";
                                                $result_purchase_details_view=mysqli_query($con,$sql_purchase_details_view)or die("Error in purchase details view".mysqli_error());
                                                while ($row_purchase_details_view=mysqli_fetch_assoc($result_purchase_details_view)) 
                                                {   //get staff name
                                                    $sql_get_staff="SELECT * FROM staff WHERE staffid='$row_purchase_details_view[enterby]'";
                                                    $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in get Category".mysqli_error($con));
                                                    $row_get_staff=mysqli_fetch_assoc($result_get_staff);

                                                    echo '<tr>
                                                            <td>'.$row_purchase_details_view["purid"].'</td>
                                                            <td>'.$row_purchase_details_view["purdate"].'</td>
                                                            <td>'.$row_purchase_details_view["total_amount"].'</td>
                                                            <td>'.$row_purchase_details_view["status"].'</td>
                                                            <td>';                               
                                                               
                                                               echo '<a href="index.php?page=purchase.php&option=fullview&purchase_id='.$row_purchase_details_view["purid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>&nbsp'; 
                                                               echo '</td>
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
            $get_purchase_id=$_GET["purchase_id"];
            $sql_get_purchase_detail="SELECT * FROM purchase WHERE purid='$get_purchase_id'";
            $result_get_purchase_detail=mysqli_query($con,$sql_get_purchase_detail)or die("Error in geting purchase details".mysqli_error($con));
            $row_get_purchase_detail=mysqli_fetch_assoc($result_get_purchase_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Purchase Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                         <div class="form-group col-md-6">
                                                    <label>Purchase ID</label>
                                                    <input type="text" name="txt_purchase_id" id="txt_purchase_id"class="form-control" value="<?php echo $row_get_purchase_detail["purid"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Purchase Date</label>
                                                    <input type="Date" name="txt_purchase_date" id="txt_purchase_date"class="form-control" value="<?php echo $row_get_purchase_detail["purdate"] ?>"  readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Supplier Bill No</label>
                                                    <input type="text" name="txt_bill_number" id="txt_bill_number"class="form-control" value="<?php echo $row_get_purchase_detail["billno"] ?>" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Supplier Id</label>
                                                    <select name="txt_supplier_id" id="txt_supplier_id" class="form-control" required>
                                                    <?php
                                                        $sql_get_suppliers="SELECT * FROM suppliers";
                                                        $result_get_suppliers=mysqli_query($con,$sql_get_suppliers) or die ("Error in get Category".mysqli_error($con));
                                                        while ($row_get_suppliers=mysqli_fetch_assoc($result_get_suppliers)) 
                                                        {
                                                            if($row_get_suppliers["supid"]==$row_get_purchase_detail["supid"])
                                                            {
                                                                echo '<option value="'.$row_get_suppliers["supid"].'" selected>'.$row_get_suppliers["supname"].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$row_get_suppliers["supid"].'">'.$row_get_suppliers["supname"].'</option>';
                                                            }
                                                        }
                                                    ?>

                                                    </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Enter By</label>
                                                    <select name="txt_enterby" id="txt_enterby" class="form-control"  readonly>
                                                    <?php
                                                    $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_detail[enterby]'";
                                                    $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in get Category".mysqli_error($con));
                                                    while($row_get_enterby=mysqli_fetch_assoc($result_get_enterby))
                                                    {
                                                            if($row_get_enterby["staffid"]==$row_get_purchase_detail["staffid"])
                                                            {
                                                                echo '<option value="'.$row_get_enterby["staffid"].'" selected>'.$row_get_enterby["staffname"].'</option>';
                                                            }
                                                            else
                                                            {
                                                                echo '<option value="'.$row_get_enterby["staffid"].'">'.$row_get_enterby["staffname"].'</option>';
                                                            }
                                                    }   
                                                    ?>
                                                    </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Branch Name</label>
                                                    <select name="txt_branch_id" id="txt_branch_id" class="form-control" readonly>
                                                    <?php
                                                    $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_detail[branchid]'";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                    while($row_get_branch=mysqli_fetch_assoc($result_get_branch))
                                                    {
                                                            if($row_get_branch["branchid"]==$row_get_purchase_detail["branchid"])
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
                                                    <label>Total Amount</label>
                                                    <input type="" name="txt_total_amount" id="txt_total_amount" class="form-control" value="<?php echo $row_get_purchase_detail["total_amount"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Paid Amount</label>
                                                    <input type="number" min="0"  max="<?php echo $row_get_purchase_detail["total_amount"] ?>" name="txt_paid_amount" id="txt_paid_amount" class="form-control" value="<?php echo $row_get_purchase_detail["paid_amount"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Status</label>
                                                    <input type="text" name="txt_status" id="txt_status" class="form-control" value="<?php echo $row_get_purchase_detail["status"] ?>"readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Supplier Ledger Reference</label>
                                                    <input type="text" name="txt_ledger_ref" id="txt_ledger_ref" class="form-control" value="<?php echo $row_get_purchase_detail["ledger_ref"] ?>"readonly>
                                            </div>
                                        </div>                 
                                    <div>
                                        <button type="submit" name="btn_edit_purchase" id="btn_edit_purchase" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_pg_edit_reset" id="btn_pg_edit_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=purchase.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
            $get_purchase_id=$_GET["purchase_id"];
            $sql_purchase_fullview="SELECT * FROM purchase WHERE purid='$get_purchase_id'";
            $result_purchase_fullview=mysqli_query($con,$sql_purchase_fullview)or die("Error in geting purchase fullview details".mysqli_error($con));
            $row_purchase_fullview=mysqli_fetch_assoc($result_purchase_fullview);

            //get enterrby staff name
            $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_purchase_fullview[enterby]'";
            $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
            $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
            //get branch name
            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_purchase_fullview[branchid]'";
            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in getting branchname".mysqli_error($con));
            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
            //get supplier name
            $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_purchase_fullview[supid]'";
            $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in getting Suppliers".mysqli_error($con));
            $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Purchase Full Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <tr><th style="width: 50%">Purchase ID</th><td><?php echo $row_purchase_fullview["purid"] ?></td></tr>
                                            <tr><th>Purchase date</th><td><?php echo $row_purchase_fullview["purdate"] ?></td></tr>
                                            <tr><th>Bill No</th><td><?php echo $row_purchase_fullview["billno"] ?></td></tr>
                                            <tr><th>Supplier</th><td><?php echo $row_get_suppiers["supname"] ?></td></tr>
                                            <tr><th>Enter By</th><td><?php echo $row_get_enterby["staffname"] ?></td></tr>
                                            <tr><th>Branch </th><td><?php echo $row_get_branch["branchname"] ?></td></tr>
                                            <tr><th>Total Amount</th><td>Rs <?php echo $row_purchase_fullview["total_amount"] ?></td></tr>
                                            <tr><th>Paid Amount</th><td>Rs <?php echo $row_purchase_fullview["paid_amount"] ?></td></tr>
                                            <tr><th>Payment Status</th><td><?php echo $row_purchase_fullview["status"] ?></td></tr>
                                            <tr>
                                                <td colspan="2">
                                                    <center>
                                                        <a href="index.php?page=purchase.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
                                                        <?php
                                                         if($row_purchase_fullview["status"]=="Pending")
                                                        {
                                                        echo '<a href="index.php?page=purchase.php&option=edit&purchase_id='.$row_purchase_fullview["purid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>&nbsp';
                                                        echo '<a href="index.php?page=purchase.php&option=payment&purchase_payment_id='.$row_purchase_fullview["purid"].'"><button type="button"class="btn btn-success"><i class="fas fa-dollar"></i> Payment</button></a>';
                                                        }else{}
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
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Purchase Item Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Model Name</th>
                                                    <th>No of Items</th>
                                                    <th>Unit Price</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                $sql_purchase_item_details="SELECT * FROM purchaseitem where purid='$get_purchase_id'";
                                                $result_purchase_item_details=mysqli_query($con,$sql_purchase_item_details) or die ("Error getting purchase item details".mysqli_error($con));
                                                $x=1;
                                                $total_amount=0;
                                                while($row_get_purchase_item_details=mysqli_fetch_assoc($result_purchase_item_details))
                                                {   
                                                    $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_purchase_item_details[modelno]'";
                                                    $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);
                                                        
                                                    echo'<tr>
                                                            <td>'.$x.'</td>
                                                            <td>'.$row_get_model_name["modelname"].'</td>
                                                            <td>'.$row_get_purchase_item_details["noofitems"].'</td>
                                                            <td>'.$row_get_purchase_item_details["unitprice"].'</td>
                                                        </tr>';
                                                        $sub_total=$row_get_purchase_item_details["noofitems"] * $row_get_purchase_item_details["unitprice"];
                                                        $total_amount=$total_amount + $sub_total;
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
            elseif ($_GET["option"]=="payment") 
            {
                $get_purchase_id=$_GET["purchase_payment_id"];
                $sql_get_purchase_detail="SELECT * FROM purchase WHERE purid='$get_purchase_id'";
                $result_get_purchase_detail=mysqli_query($con,$sql_get_purchase_detail)or die("Error in geting purchase details".mysqli_error($con));
                $row_get_purchase_detail=mysqli_fetch_assoc($result_get_purchase_detail);
                ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Purchase Payment</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                         <div class="form-group col-md-6">
                                                    <label>Purchase ID</label>
                                                    <input type="text" name="txt_pay_purchase_id" id="txt_pay_purchase_id"class="form-control" value="<?php echo $row_get_purchase_detail["purid"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Total Amount</label>
                                                    <input type="" name="txt_pay_total_amount" id="txt_pay_total_amount" class="form-control" value="<?php echo $row_get_purchase_detail["total_amount"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Paid Amount</label>
                                                    <input type="number" min="0"  max="<?php echo $row_get_purchase_detail["total_amount"] ?>" name="txt_pay_paid_amount" id="txt_pay_paid_amount" class="form-control" value="<?php echo $row_get_purchase_detail["paid_amount"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Balance Amount</label>
                                                    <?php
                                                    $balance=0;
                                                    $balance=$row_get_purchase_detail["total_amount"]-$row_get_purchase_detail["paid_amount"];
                                                    ?>
                                                    <input type="number" min="0"  name="txt_pay_balance_amount" id="txt_pay_balance_amount" class="form-control" value="<?php echo $balance ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Status</label>
                                                    <input type="text" name="txt_pay_status" id="txt_pay_status" class="form-control" readonly>
                                            </div>
                                            <div class="form-row col-md-6">
                                            <div class="form-group col-md-6">
                                                    <label>Payment Mode</label>
                                                    <Select type="text" name="txt_payment_mode" id="txt_payment_mode" class="form-control" required>
                                                            <option>Select Payment Mode</option>
                                                            <option>Cash</option>
                                                            <option>Cheque</option>
                                                            <option>Card</option>
                                                    </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Paying Amount</label>
                                                    <input type="number" min="0" max="<?php echo $balance ?>" name="txt_pay_paying_amount" id="txt_pay_paying_amount" class="form-control" oninput="set_status()">
                                            </div>
                                            </div>
                                        </div>                 
                                    <div>
                                        <button type="submit" name="btn_edit_purchase_payment" id="btn_edit_purchase_payment" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_pg_edit_reset" id="btn_pg_edit_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=purchase.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
        }
        elseif ($_GET["option"]=="cancel_purchase") 
        {
            $sql_purchaseitem_delete="DELETE FROM purchaseitem WHERE purid='$_SESSION[PURCHASE_ID]'";
            $result_purchaseitem_delete=mysqli_query($con,$sql_purchaseitem_delete)or die("Error in purchaseitem delete".mysqli_error($con));
            if($result_purchaseitem_delete)
            {   unset($_SESSION["PURCHASE_ID"]);
                echo '<script>
                alert("Purchase Cancelled!!");
                window.location.href="index.php?page=purchase.php&option=view";
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