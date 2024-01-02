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
				<li>return</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_return_item"]))
{   
    $sql_get_returnitem="SELECT * FROM purchasereturnitem Where returnid='$_POST[txt_return_item_id]' AND purid='$_POST[txt_purchase_id]'";
    $result_get_returnitem=mysqli_query($con,$sql_get_returnitem) or die ("Error in Getting sales id".mysqli_error($con));
    $row_get_returnitem=mysqli_fetch_assoc($result_get_returnitem);

        if($row_get_returnitem["modelno"]!=$_POST["txt_model_number"])
        {
            $sql_insert_return="INSERT INTO purchasereturnitem(returnid,purid,modelno,noofitems)
                                VALUES('".mysqli_real_escape_string($con,$_POST["txt_return_item_id"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                        '".mysqli_real_escape_string($con,$_POST["txt_noofitems"])."')";
            $result_insert_return=mysqli_query($con,$sql_insert_return) or die("Error in inserting in return".mysqli_error($con));
            if($result_insert_return)
            {   
                $_SESSION["RETURN_ID"]=$_POST["txt_return_item_id"];
                $_SESSION["SUPPLIER_ID"]=$_POST["txt_supplier_id"];
                echo '<script>
                        alert("Successful Added!!");
                        window.location.href="index.php?page=purchasereturn.php&option=add";
                    </script>';
            }
        }
        else
        {   //if already added in return
            $quantity=$_POST["txt_noofitems"];
            $sql_update_purchasereturnitem="UPDATE purchasereturnitem SET  
                            noofitems='".mysqli_real_escape_string($con,($row_get_returnitem["noofitems"]+$quantity))."'
                            WHERE returnid='".mysqli_real_escape_string($con,$_POST["txt_return_item_id"])."' AND purid='".mysqli_real_escape_string($con,$_POST["txt_purchase_id"])."' AND modelno='".mysqli_real_escape_string($con,$row_get_returnitem["modelno"])."'";
                            
            $result_update_purchasereturnitem=mysqli_query($con,$sql_update_purchasereturnitem) or die("Error in updating in purchase return item".mysqli_error($con));
            if($result_update_purchasereturnitem)
            {   
                echo '<script>
                        alert("Already same model Added!! Quantity Updated");
                        window.location.href="index.php?page=purchasereturn.php&option=add";
                    </script>';
            }
        }
}
if(isset($_POST["btn_save_purchase_return"]))
{   // reduce stock start
    $return_id=$_SESSION["RETURN_ID"];
    $sql_get_return_item_details="SELECT modelno,noofitems,purid FROM purchasereturnitem WHERE returnid='$return_id'";
    $result_get_return_items_details=mysqli_query($con,$sql_get_return_item_details) or die("Error in sql_sales_item_details".mysqli_error($con));
    while($row_return_item_details=mysqli_fetch_assoc($result_get_return_items_details))
    {
        $return_item_modelno=$row_return_item_details["modelno"];
        $return_item_quantity=$row_return_item_details["noofitems"];
        $return_item_purchase_id=$row_return_item_details["purid"];

        
            $product_return_purchase=array();
            $sql_get_stock_details="SELECT purid,modelno,quantity FROM stock WHERE purid='$return_item_purchase_id' AND modelno='$return_item_modelno'";
            $result_get_stock_details=mysqli_query($con,$sql_get_stock_details) or die("Error in sql_stock".mysqli_error($con));
            $x=0;
            while($row_get_stock_details=mysqli_fetch_assoc($result_get_stock_details))
            {
                if($return_item_quantity>0)
                {
                    $product_return_purchase[$x][0]=$row_get_stock_details["purid"];
                    $product_return_purchase[$x][1]=$return_item_quantity;
                    $return_item_quantity=$return_item_quantity-$return_item_quantity;               
                    
                    $x++;
                }
                else
                {
                    break;
                }
                
            }

            for ($i=0; $i < count($product_return_purchase); $i++) 
            { 
                $redused_quantity=$product_return_purchase[$i][1]; 
                $sql_update_stock="UPDATE stock SET 
                                quantity=quantity-$redused_quantity
                                WHERE purid='".mysqli_real_escape_string($con,$product_return_purchase[$i][0])."' AND 
                                modelno='".mysqli_real_escape_string($con,$return_item_modelno)."'";
                $result_update_stock=mysqli_query($con,$sql_update_stock) or die("Error in sql_update".mysqli_error($con));                       
            }
    }
    // reduce stock end

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

    
    // insert in purchase return
    $sql_insert_purchase_return="INSERT INTO purchasereturn(returnid,supid,return_date,enterby,branchid,return_amount,ledger_ref)
                          VALUES('".mysqli_real_escape_string($con,$_POST["txt_return_id"])."',                        
                                '".mysqli_real_escape_string($con,$_SESSION["SUPPLIER_ID"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_return_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_enterby"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_return_amount"])."',
                                '".mysqli_real_escape_string($con,$sup_ledger_id)."')";
    $result_insert_purchase_return=mysqli_query($con,$sql_insert_purchase_return) or die("Error in inserting in purchase".mysqli_error($con));

    // insert in supplierledger -purchase return amount
    $sql_insert_supplier_ledger_1="INSERT INTO supplierledger(sup_ledger_id,ledger_date,pur_ret_ref_id,supid,branchid,type,payment_mode,credit,debit)
                          VALUES('".mysqli_real_escape_string($con,$sup_ledger_id)."',
                                '".mysqli_real_escape_string($con,$_POST["txt_return_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_return_id"])."',
                                '".mysqli_real_escape_string($con,$_SESSION["SUPPLIER_ID"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."',
                                '".mysqli_real_escape_string($con,"Return")."',
                                '".mysqli_real_escape_string($con,"None")."',
                                '".mysqli_real_escape_string($con,"")."',
                                '".mysqli_real_escape_string($con,$_POST["txt_return_amount"])."')";
    $result_insert_supplier_ledger_1=mysqli_query($con,$sql_insert_supplier_ledger_1) or die("Error in inserting in supplierledger 1".mysqli_error($con));

    if($result_insert_purchase_return && $result_insert_supplier_ledger_1)
    {
        unset($_SESSION["RETURN_ID"]);
        unset($_SESSION["SUPPLIER_ID"]);
        echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=purchasereturn.php&option=view";
            </script>';  
    }
}
?>
<script type="text/javascript">
    function check_purchase_under_supplier()
    {
        let supplier_id=document.getElementById("txt_supplier_id").value;
        if(supplier_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("txt_purchase_id").innerHTML=xmlhttp.responseText.trim();
                    document.getElementById("txt_purchase_id").focus();
                }

            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_purchase_under_supplier&ajax_supplier_id=" + supplier_id, true);
            xmlhttp.send();
        }
        else
        {
                document.getElementById("txt_purchase_id").value='';
        }
    }
</script>
<script type="text/javascript">
    function check_model_under_purchase()
    {   document.getElementById("div_stock_quantity").innerHTML="";
        let purchase_id=document.getElementById("txt_purchase_id").value;
        if(purchase_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {   
                    document.getElementById("txt_model_number").innerHTML=xmlhttp.responseText.trim();
                    document.getElementById("txt_model_number").focus();

                }

            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_model_under_purchase&ajax_purchase_id=" + purchase_id, true);
            xmlhttp.send();
        }
        else
        {
                document.getElementById("txt_model_number").value='';
        }
    }
</script>
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
                    if(stock_quantity>0)
                    {
                        document.getElementById("txt_noofitems").setAttribute("max",stock_quantity);
                        document.getElementById("div_stock_quantity").style.color="Blue";
                        document.getElementById("div_stock_quantity").innerHTML = "Stock Quantity : " + stock_quantity;
                        document.getElementById("txt_noofitems").focus();
                    }
                    else
                    {
                        document.getElementById("txt_noofitems").setAttribute("max","0");
                        document.getElementById("div_stock_quantity").style.color="red";
                        document.getElementById("div_stock_quantity").innerHTML = "Out of Stock";
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
		if($_GET["option"]=="add")
		{
		
			?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Purchase Return Item Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplte="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <?php 
                                                $sql_create_return_id="SELECT returnid FROM purchasereturn ORDER BY returnid DESC LIMIT 1";
                                                $result_create_return_id=mysqli_query($con,$sql_create_return_id) or die ("Error in Creating id".mysqli_error($con));
                                                if(mysqli_num_rows($result_create_return_id)==1)
                                                {
                                                    $row_create_return_id=mysqli_fetch_assoc($result_create_return_id);
                                                    $return_item_id=++$row_create_return_id["returnid"];
                                                }
                                                else
                                                {
                                                    $return_item_id="RET001";
                                                }
                                                ?>
                                                <label>Purchase Return ID</label>
                                                <input type="text" name="txt_return_item_id" id="txt_return_item_id" class="form-control"  value="<?php echo (isset($_SESSION["RETURN_ID"])) ? $_SESSION["RETURN_ID"] : $return_item_id; ?>" readonly >
                                            </div>
                                             <div class="form-group col-md-6">
                                                <label>Supplier Name</label>
                                                <select name="txt_supplier_id" id="txt_supplier_id" class="form-control" tabindex="2" onchange="check_purchase_under_supplier()" required>
                                                    
                                                    <?php
                                                        if(isset($_SESSION["SUPPLIER_ID"]))
                                                        {   
                                                            echo'<option value="">Select Supplier</option>';
                                                            $sql_get_suppliers="SELECT * FROM suppliers WHERE supid='$_SESSION[SUPPLIER_ID]'";
                                                            $result_get_suppliers=mysqli_query($con,$sql_get_suppliers) or die ("Error in getting supplier".mysqli_error($con));
                                                            while ($row_get_suppliers=mysqli_fetch_assoc($result_get_suppliers))
                                                                {
                                                                    echo '<option value="'.$row_get_suppliers["supid"].'">'.$row_get_suppliers["supname"].'</option>';
                                                                }
                                                        }
                                                        else
                                                        {   
                                                            echo'<option value="">Select Supplier</option>';
                                                            $sql_get_suppliers="SELECT * FROM suppliers";
                                                            $result_get_suppliers=mysqli_query($con,$sql_get_suppliers) or die ("Error in getting supplier".mysqli_error($con));
                                                            while ($row_get_suppliers=mysqli_fetch_assoc($result_get_suppliers)) 
                                                                {
                                                                    echo '<option value="'.$row_get_suppliers["supid"].'">'.$row_get_suppliers["supname"].'</option>';
                                                                }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Purchase ID</label>
                                                <select name="txt_purchase_id" id="txt_purchase_id" class="form-control" tabindex="2" onchange="check_model_under_purchase()" required>
                                                    <option>Select Purchase ID</option>                                               
                                                </select> 
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Model</label>
                                                <select name="txt_model_number" id="txt_model_number" class="form-control"  tabindex="2" onchange="check_max_return_quantity()" required>
                                                    <option>Select Model</option>                                               
                                                </select> 
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Return Quantity</label>
                                                <input type="number" min="1" max="" name="txt_noofitems" id="txt_noofitems" class="form-control" required>
                                                <label><b id="div_stock_quantity" style=""></b></b></label>
                                            </div>                                            
                                        </div>
                                        <div>
                                            <button type="submit" name="btn_save_return_item" id="btn_save_return_item" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>  
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
                                    <h4 class="card-title">Purchase Return Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="check">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Purchase ID</th>
                                                    <th>Model No</th>
                                                    <th>No of Items</th>
                                                    <th>Unit Price</th>
                                                    <th>Action</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                if(isset($_SESSION["RETURN_ID"])){
                                                $sql_return_item_details="SELECT * FROM purchasereturnitem where returnid='$_SESSION[RETURN_ID]'";
                                                $result_return_item_details=mysqli_query($con,$sql_return_item_details) or die ("Error getting return item details".mysqli_error($con));
                                                $x=1;
                                                $total_amount=0;
                                                while($row_get_return_item_details=mysqli_fetch_assoc($result_return_item_details))
                                                {   // get model name
                                                    $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_return_item_details[modelno]'";
                                                    $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);
                                                    //get unitprice
                                                    $sql_get_unitprice="SELECT unitprice FROM purchaseitem WHERE purid='$row_get_return_item_details[purid]'";
                                                    $result_get_unitprice=mysqli_query($con,$sql_get_unitprice) or die ("Error getting unitprice".mysqli_error($con));
                                                    $row_get_unitprice=mysqli_fetch_assoc($result_get_unitprice);  

                                                    echo'<tr>
                                                            <td>'.$x.'</td>
                                                            <td>'.$row_get_return_item_details["purid"].'</td>
                                                            <td>'.$row_get_model_name["modelname"].'</td>
                                                            <td>'.$row_get_return_item_details["noofitems"].'</td>
                                                            <td>'.$row_get_unitprice["unitprice"].'</td>
                                                            <td>
                                                                 <a href="index.php?page=purchasereturnitem.php&option=edit&return_item_id='.$row_get_return_item_details['returnid'].'&return_item_modelno='.$row_get_return_item_details['modelno'].'&purchase_id='.$row_get_return_item_details["purid"].'"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i> </button></a>
                                                                <a href="index.php?page=purchasereturnitem.php&option=delete&return_item_id='.$row_get_return_item_details['returnid'].'&return_item_modelno='.$row_get_return_item_details['modelno'].'&purchase_id='.$row_get_return_item_details["purid"].'"><button type="button" class="btn btn-danger"><i class="fas fa-trash"></i> </button></a>
                                                            </td>
                                                        </tr>';
                                                        $sub_total=$row_get_return_item_details["noofitems"] * $row_get_unitprice["unitprice"];
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
            if(isset($_SESSION["RETURN_ID"])){
            
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
                                <h4 class="card-title">Purchase Return Bill</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplte="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                    <label>Purchase Return ID</label>
                                                    <input type="text" name="txt_return_id" id="txt_return_id" class="form-control" value="<?php echo $_SESSION["RETURN_ID"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                    <label>Return Date</label>
                                                    <input type="Date" name="txt_return_date" id="txt_return_date" class="form-control" value="<?php echo date("Y-m-d") ?>" >
                                            </div>
                                            <div class="form-row col-md-12">
                                                <div class="form-group col-md-4">
                                                        <label>Total Amount</label>
                                                        <input type="text" name="txt_return_amount" id="txt_return_amount" class="form-control" value="<?php echo $total_amount ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Enter By</label>
                                                    <select name="txt_enterby" id="txt_enterby" class="form-control" required>
                                                    <option value="<?php echo $row_get_enterby["staffid"]?>"><?php echo $row_get_enterby["staffname"]?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Branch Name</label>
                                                    <select name="txt_branch_id" id="txt_branch_id" class="form-control" required>
                                                    <option value="<?php echo $row_get_branch["branchid"]?>"><?php echo $row_get_branch["branchname"]?></option>
                                                    </select>
                                                </div>
                                                
                                            </div>                                            
                                        </div>
                                            <div>
                                            <button type="submit" name="btn_save_purchase_return" id="btn_save_purchase_return" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                            <a href="index.php?page=purchasereturn.php&option=cancel_purchase_return"> <button type="button" name="btn_cancel_purchase_return" id="btn_cancel_purchase_return" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
			<?php
        }
        }
        elseif($_GET["option"]=="view" & $system_user_type=="Manager") 
        {
        ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Purchase Return Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                       <a href="index.php?page=purchasereturn.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Purchase Return ID</th>
                                                    <th>Date</th>
                                                    <th>Supplier</th>
                                                    <th>Branch</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_return_details_view="SELECT returnid,return_date,supid,branchid FROM purchasereturn";
                                                $result_return_details_view=mysqli_query($con,$sql_return_details_view)or die("Error in return details view".mysqli_error($con));
                                                while ($row_return_details_view=mysqli_fetch_assoc($result_return_details_view)) 
                                                {   //get supplier name
                                                    $sql_get_suppliers="SELECT supname from suppliers WHERE supid='$row_return_details_view[supid]'";
                                                    $result_get_suppliers=mysqli_query($con,$sql_get_suppliers) or die ("Error in getting supplier name".mysqli_error($con));
                                                    $row_get_suppliers=mysqli_fetch_assoc($result_get_suppliers);
                                                    //get branch name
                                                    $sql_get_branch="SELECT branchname FROM branch WHERE branchid='$row_return_details_view[branchid]'";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                    $row_get_branch=mysqli_fetch_assoc($result_get_branch);

                                                    echo '<tr>
                                                            <td>'.$row_return_details_view["returnid"].'</td>
                                                            <td>'.$row_return_details_view["return_date"].'</td>
                                                            <td>'.$row_get_suppliers["supname"].'</td>
                                                            <td>'.$row_get_branch["branchname"].'</td>
                                                            <td>
                                                                <a href="index.php?page=purchasereturn.php&option=fullview&return_id='.$row_return_details_view["returnid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
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
        elseif($_GET["option"]=="view" & $system_user_type!="Manager") 
        {
        ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Purchase Return Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                       <a href="index.php?page=purchasereturn.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Purchase Return ID</th>
                                                    <th>Date</th>
                                                    <th>Supplier</th>
                                                    <th>Branch</th>
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

                                                $sql_return_details_view="SELECT returnid,return_date,supid,branchid FROM purchasereturn WHERE branchid='$branchid'";
                                                $result_return_details_view=mysqli_query($con,$sql_return_details_view)or die("Error in return details view".mysqli_error($con));
                                                while ($row_return_details_view=mysqli_fetch_assoc($result_return_details_view)) 
                                                {   //get supplier name
                                                    $sql_get_suppliers="SELECT supname from suppliers WHERE supid='$row_return_details_view[supid]'";
                                                    $result_get_suppliers=mysqli_query($con,$sql_get_suppliers) or die ("Error in getting supplier name".mysqli_error($con));
                                                    $row_get_suppliers=mysqli_fetch_assoc($result_get_suppliers);
                                                    //get branch name
                                                    $sql_get_branch="SELECT branchname FROM branch WHERE branchid='$row_return_details_view[branchid]'";
                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                    $row_get_branch=mysqli_fetch_assoc($result_get_branch);

                                                    echo '<tr>
                                                            <td>'.$row_return_details_view["returnid"].'</td>
                                                            <td>'.$row_return_details_view["return_date"].'</td>
                                                            <td>'.$row_get_suppliers["supname"].'</td>
                                                            <td>'.$row_get_branch["branchname"].'</td>
                                                            <td>
                                                                <a href="index.php?page=purchasereturn.php&option=fullview&return_id='.$row_return_details_view["returnid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
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
            $get_return_id=$_GET["return_id"];
            $sql_purchase_return_fullview="SELECT * FROM purchasereturn WHERE returnid='$get_return_id'";
            $result_purchase_return_fullview=mysqli_query($con,$sql_purchase_return_fullview)or die("Error in geting purchase fullview details".mysqli_error($con));
            $row_purchase_return_fullview=mysqli_fetch_assoc($result_purchase_return_fullview);

            //get enterrby staff name
            $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_purchase_return_fullview[enterby]'";
            $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
            $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
            //get branch name
            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_purchase_return_fullview[branchid]'";
            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
            //get supplier name
            $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_purchase_return_fullview[supid]'";
            $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
            $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Return Full Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <tr><th style="width: 50%">Purchase Return ID</th><td><?php echo $row_purchase_return_fullview["returnid"] ?></td></tr>
                                            <tr><th>Return date</th><td><?php echo $row_purchase_return_fullview["return_date"] ?></td></tr>
                                            <tr><th>Supplier</th><td><?php echo $row_get_suppiers["supname"] ?></td></tr>
                                            <tr><th>Enter By</th><td><?php echo $row_get_enterby["staffname"] ?></td></tr>
                                            <tr><th>Branch </th><td><?php echo $row_get_branch["branchname"] ?></td></tr>
                                            <tr><th>Total Amount</th><td><?php echo $row_purchase_return_fullview["return_amount"] ?></td></tr>
                                            <tr>
                                                <td colspan="2">
                                                    <center>
                                                        <a href="index.php?page=purchasereturn.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
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
                                    <h4 class="card-title">Return Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="check">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Purchase ID</th>
                                                    <th>Model No</th>
                                                    <th>No of Items</th>
                                                    <th>Unit Price</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                $sql_return_item_details="SELECT * FROM purchasereturnitem where returnid='$get_return_id'";
                                                $result_return_item_details=mysqli_query($con,$sql_return_item_details) or die ("Error getting return item details".mysqli_error($con));
                                                $x=1;
                                                $total_amount=0;
                                                while($row_get_return_item_details=mysqli_fetch_assoc($result_return_item_details))
                                                {   // get model name
                                                    $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_return_item_details[modelno]'";
                                                    $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                                                    $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);
                                                    //get unitprice
                                                    $sql_get_unitprice="SELECT unitprice FROM purchaseitem WHERE purid='$row_get_return_item_details[purid]'";
                                                    $result_get_unitprice=mysqli_query($con,$sql_get_unitprice) or die ("Error getting unitprice".mysqli_error($con));
                                                    $row_get_unitprice=mysqli_fetch_assoc($result_get_unitprice);  

                                                    echo'<tr>
                                                            <td>'.$x.'</td>
                                                            <td>'.$row_get_return_item_details["purid"].'</td>
                                                            <td>'.$row_get_model_name["modelname"].'</td>
                                                            <td>'.$row_get_return_item_details["noofitems"].'</td>
                                                            <td>'.$row_get_unitprice["unitprice"].'</td>
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
        elseif ($_GET["option"]=="cancel_purchase_return" ) 
        {
            $sql_purchase_return_item_delete="DELETE FROM purchasereturnitem WHERE returnid='$_SESSION[RETURN_ID]'";
            $result_purchase_return_item_delete=mysqli_query($con,$sql_purchase_return_item_delete)or die("Error in purchaseitem delete".mysqli_error($con));
            if($result_purchase_return_item_delete)
            {   unset($_SESSION["RETURN_ID"]);
                unset($_SESSION["SUPPLIER_ID"]);
                echo '<script>
                alert("Purchase Return Cancelled!!");
                window.location.href="index.php?page=purchasereturn.php&option=view";
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