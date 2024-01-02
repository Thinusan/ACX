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
include("config.php");

    if(isset($_SESSION["LOGIN_USER_NAME"]))
    {   // get branch id
       $sql_get_enterby="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
       $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
       $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
       $branchid=$row_get_enterby["branchid"];
    }

if(isset($_GET["frompage"]))
{
	if($_GET["frompage"]=="check_purchase_salse_price")
	{
		$barcode=$_GET["ajax_barcode"];
        //get model no for barcode
        $sql_get_model_no="SELECT modelno,modelname FROM model WHERE barcode='$barcode'";
        $result_get_model_no=mysqli_query($con,$sql_get_model_no) or die ("Error in getting Model".mysqli_error($con));
        $row_get_model_no=mysqli_fetch_assoc($result_get_model_no);

		$sql_get_sales_price="SELECT salesprice FROM modelprice WHERE modelno='$row_get_model_no[modelno]' AND enddate IS NULL";
        $result_get_sales_price=mysqli_query($con,$sql_get_sales_price) or die ("Error in get Model".mysqli_error($con));
        $row_get_sales_price=mysqli_fetch_assoc($result_get_sales_price);
        
        $salesprice_modelname=array(0=>"$row_get_model_no[modelname]",1=>"$row_get_sales_price[salesprice]",2=>"$row_get_model_no[modelno]");
        echo implode(",",$salesprice_modelname);
        
	}
    if($_GET["frompage"]=="check_current_password")
    {   
        $username=$_SESSION["LOGIN_USER_NAME"];
        $current_password=md5($_GET["ajax_current_password"]);
        $sql_password="SELECT * FROM login WHERE userid='$username' AND password='$current_password'";
        $result_password=mysqli_query($con,$sql_password) or die("Error in getting password".mysqli_error($con));
        if(mysqli_num_rows($result_password)==1)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
	if($_GET["frompage"]=="check_max_sales_quantity")
	{
		$model_id=$_GET["ajax_model_id"];
		$sales_id=$_GET["ajax_sales_id"];
		$sql_get_stock_quantity="SELECT SUM(quantity) AS total_stock_quantity FROM stock WHERE modelno='$model_id' AND branchid='$branchid'";
        $result_get_stock_quantity=mysqli_query($con,$sql_get_stock_quantity) or die ("Error in get Model".mysqli_error($con));
        $row_get_stock_quantity=mysqli_fetch_assoc($result_get_stock_quantity);

        echo  $row_get_stock_quantity["total_stock_quantity"];
    	
    }
    if($_GET["frompage"]=="check_max_return_quantity")
	{
		$model_id=$_GET["ajax_model_id"];
		$purchase_id=$_GET["ajax_purchase_id"];
		$return_id=$_GET["ajax_return_id"];

		$sql_get_stock_quantity="SELECT quantity FROM stock WHERE modelno='$model_id' AND purid='$purchase_id' AND branchid='$branchid'";
        $result_get_stock_quantity=mysqli_query($con,$sql_get_stock_quantity) or die ("Error in getting stock quantity".mysqli_error($con));
        $row_get_stock_quantity=mysqli_fetch_assoc($result_get_stock_quantity);

        $sql_get_returnitem_quantity="SELECT noofitems FROM purchasereturnitem WHERE (modelno='$model_id' AND purid='$purchase_id') AND returnid='$return_id'";
        $result_get_returnitem_quantity=mysqli_query($con,$sql_get_returnitem_quantity) or die ("Error in getting returnitem quantity".mysqli_error($con));
        $row_get_returnitem_quantity=mysqli_fetch_assoc($result_get_returnitem_quantity);

        $view_stock_quantity=($row_get_stock_quantity["quantity"] - $row_get_returnitem_quantity["noofitems"]);

        echo  $view_stock_quantity;
        
    	
    }
    if($_GET["frompage"]=="check_supllier_account_balance")
	{
		$supplier_id=$_GET["ajax_supplier_id"];
		$sql_get_sup_acc_balance="SELECT (SUM(credit) - SUM(debit)) AS total_sup_acc_bal FROM supplierledger WHERE supid='$supplier_id'";
        $result_get_sup_acc_balance=mysqli_query($con,$sql_get_sup_acc_balance) or die ("Error in getting supplier account balance".mysqli_error($con));
        $row_get_sup_acc_balance=mysqli_fetch_assoc($result_get_sup_acc_balance);

        if($row_get_sup_acc_balance["total_sup_acc_bal"]>0)
	        {
	        echo "To Pay : ". $row_get_sup_acc_balance["total_sup_acc_bal"];
	        }else{
	      	echo "To Be Paid : ". $row_get_sup_acc_balance["total_sup_acc_bal"];
        	}
	}
	if($_GET["frompage"]=="check_purchase_under_supplier")
	{	
		echo'<option value="">Select Purchase ID</option>';

		$supplier_id=$_GET["ajax_supplier_id"];
        $sql_get_supid_from_stock="SELECT purid FROM stock WHERE quantity!=0";
        $result_get_supid_from_stock=$result_get_purid=mysqli_query($con,$sql_get_supid_from_stock) or die ("Error getting Purchase id from stock".mysqli_error($con));
        while($row_get_supid_from_stock=mysqli_fetch_assoc($result_get_supid_from_stock))
        {
            $sql_get_purid="SELECT purid,purdate FROM purchase WHERE supid='$supplier_id' AND branchid='$branchid' AND purid='$row_get_supid_from_stock[purid]'";
    		$result_get_purid=mysqli_query($con,$sql_get_purid) or die ("Error getting Purchase id under supplier id".mysqli_error($con));
    		while($row_get_purid=mysqli_fetch_assoc($result_get_purid))
    			{
                    echo '<option value="'.$row_get_purid["purid"].'">'.$row_get_purid["purid"]." - ".$row_get_purid["purdate"].'</option>';
                }
        }
	}
	if($_GET["frompage"]=="check_model_under_purchase")
	{	
		echo'<option value="">Select Model </option>';
		$purchase_id=$_GET["ajax_purchase_id"];
		$sql_get_modelno_from_stock="SELECT modelno FROM stock WHERE  purid='$purchase_id' AND quantity!=0";
        $result_get_modelno_from_stock=mysqli_query($con,$sql_get_modelno_from_stock) or die ("Error in getting modelno from stock".mysqli_error($con));
        while($row_get_modelno_from_stock=mysqli_fetch_assoc($result_get_modelno_from_stock))
			{	
				$sql_get_model_name_barcode="SELECT * FROM model WHERE modelno='$row_get_modelno_from_stock[modelno]'";
                $result_get_model_name_barcode=mysqli_query($con,$sql_get_model_name_barcode) or die ("Error in get Model".mysqli_error($con));
                while ($row_get_model_name_barcode=mysqli_fetch_assoc($result_get_model_name_barcode))
                	{
                		echo '<option value="'.$row_get_modelno_from_stock["modelno"].'">'.$row_get_model_name_barcode["modelno"]." - ".$row_get_model_name_barcode["modelname"].'</option>';
            		}
            }
	}
	if($_GET["frompage"]=="update_repair_payment")
	{	
		$repair_id=$_GET["ajax_repair_id"];
		$sql_get_repair_total="SELECT SUM(rep_amount) AS total_rep_amount FROM repairitem WHERE repairid='$repair_id'";
		$result_get_repair_total=mysqli_query($con,$sql_get_repair_total) or die ("Error getting total repair amount".mysqli_error($con));
		$row_get_repair_total=mysqli_fetch_assoc($result_get_repair_total);
		echo '<div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Repair Payment</h4>
                                    <div class="basic-form">
                                        <form method="POST" action="">
                                            <div class="form-row col-md-12">
                                                <div class="form-group col-md-4">
                                                <label>Repair Id</label>
                                                <input type="text" name="txt_repair_id" id="txt_repair_item_id" class="form-control" value="'.$repair_id.'" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                <label>Payment Date</label>
                                                <input type="text" name="txt_payment_date" id="txt_payment_date" class="form-control" value="'.date("Y-m-d").'" readonly>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Amount</label>
                                                    <input type="text" class="form-control" name="txt_repair_payment" id="txt_repair_payment" value="'.$row_get_repair_total["total_rep_amount"].'" readonly>
                                                </div>
                                            
                                                <div class="form-group col-md-4">
                                                    <button type="submit" name="btn_update_repair_payment" id="btn_update_repair_payment" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                                                    <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                </div>
                                            </div>    
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
	}	
	if($_GET["frompage"]=="customer_check_repair_status")
    {
        $repair_id=$_GET["ajax_repairid"];
        $nicno=$_GET["ajax_nicno"];
        // check customer
        $sql_get_customer="SELECT custid FROM customer WHERE nicno='$nicno'";
        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting stock quantity".mysqli_error($con));
        $row_get_cutomer=mysqli_fetch_assoc($result_get_customer);
        // check repair id for cust id
        $sql_get_repair_details="SELECT custid FROM repair WHERE repairid='$repair_id' AND custid='$row_get_cutomer[custid]'";
        $result_get_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error getting repair details".mysqli_error($con));
        //check repair item from repairid
        $sql_get_repair_item_details="SELECT * FROM repairitem WHERE repairid='$repair_id'";
        $result_get_repair_item_details=mysqli_query($con,$sql_get_repair_item_details) or die ("Error getting repair details".mysqli_error($con));
        if(mysqli_num_rows($result_get_repair_details)!=0 & mysqli_num_rows($result_get_customer)!=0)
        {   $x=1;
            While($row_get_repair_item_details=mysqli_fetch_assoc($result_get_repair_item_details))
            {
                echo '<div class="table-responsive">
                        <table class="table table-striped table-bordered" id="check">
                            <thead>
                                <tr align="center">
                                    <th>#</th>
                                    <th>Repair ID</th>
                                    <th>Device Name</th>
                                    <th>Repair Status</th>                                     
                                </tr>
                            </thead>
                            <tbody>';
                            echo   '<tr align="center">
                                            <td>'.$x.'</td>
                                            <td>'.$row_get_repair_item_details["repairid"].'</td>
                                            <td>'.$row_get_repair_item_details["device_name"].'</td>
                                            <td>'.$row_get_repair_item_details["repair_status"].'</td>                                             
                                        </tr>';
                                    $x++ ;                         
                        echo  '</tbody>
                        </table>
                      </div>';
            }
        }
        elseif(mysqli_num_rows($result_get_customer)==0 & mysqli_num_rows($result_get_repair_details)!=0)
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h3>- Please Check Your NIC Number -</h3></center>
                    </div>
                </div>';
        }
        elseif(mysqli_num_rows($result_get_repair_details)==0 & mysqli_num_rows($result_get_customer)!=0)
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h3>- Please Check Your Repair Reference Number -</h3></center>
                    </div>
                </div>'; 
        }
        elseif(mysqli_num_rows($result_get_repair_details)==0 & mysqli_num_rows($result_get_customer)==0)
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h3>- Please Check Your NIC Number and  Repair Reference Number -</h3></center>
                    </div>
                </div>'; 
        }
    }
    if($_GET["frompage"]=="check_cus_nic_no")
    {
        $nicno=$_GET["ajax_nicno"];
        $sql_get_customer="SELECT * FROM customer WHERE nicno='$nicno'";
        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting customer".mysqli_error($con));
        if(mysqli_num_rows($result_get_customer)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    if($_GET["frompage"]=="check_cus_mobile_no")
    {
        $mobileno=$_GET["ajax_mobile_no"];
        $sql_get_customer="SELECT * FROM customer WHERE tpno='$mobileno'";
        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting customer".mysqli_error($con));
        if(mysqli_num_rows($result_get_customer)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    if($_GET["frompage"]=="check_staff_nic_no")
    {
        $nicno=$_GET["ajax_nicno"];
        $sql_get_staff="SELECT * FROM staff WHERE nicno='$nicno'";
        $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in getting staff".mysqli_error($con));
        if(mysqli_num_rows($result_get_staff)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    if($_GET["frompage"]=="check_staff_mobile_no")
    {
        $mobileno=$_GET["ajax_mobile_no"];
        $sql_get_staff="SELECT * FROM customer WHERE tpno='$mobileno'";
        $result_get_staff=mysqli_query($con,$sql_get_staff) or die ("Error in getting staff".mysqli_error($con));
        if(mysqli_num_rows($result_get_staff)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    if($_GET["frompage"]=="check_category")
    {
        $category=$_GET["ajax_category"];
        $sql_get_category="SELECT * FROM category WHERE catname='$category'";
        $result_get_category=mysqli_query($con,$sql_get_category) or die ("Error in getting category".mysqli_error($con));
        if(mysqli_num_rows($result_get_category)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    if($_GET["frompage"]=="check_brand")
    {
        $brand=$_GET["ajax_brand"];
        $sql_get_brand="SELECT * FROM brand WHERE brandname='$brand'";
        $result_get_brand=mysqli_query($con,$sql_get_brand) or die ("Error in getting brand".mysqli_error($con));
        if(mysqli_num_rows($result_get_brand)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
     if($_GET["frompage"]=="check_model_name")
    {
        $modelname=$_GET["ajax_model_name"];
        $sql_get_modelname="SELECT * FROM model WHERE modelname='$modelname'";
        $result_get_modelname=mysqli_query($con,$sql_get_modelname) or die ("Error in getting modelname".mysqli_error($con));
        if(mysqli_num_rows($result_get_modelname)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
     if($_GET["frompage"]=="check_barcode")
    {
        $barcode=$_GET["ajax_barcode"];
        $sql_get_barcode="SELECT * FROM model WHERE barcode='$barcode'";
        $result_get_barcode=mysqli_query($con,$sql_get_barcode) or die ("Error in getting barcode".mysqli_error($con));
        if(mysqli_num_rows($result_get_barcode)!=0)
        {
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    if($_GET["frompage"]=="generate_sales_invoice")
    {   //get sales details
        $salesid=$_GET["ajax_sales_id"];
        $sql_sales_details="SELECT * FROM sales WHERE salesid='$salesid'";
        $result_sales_details=mysqli_query($con,$sql_sales_details)or die("Error in geting sales details details".mysqli_error($con));
        $row_sales_details=mysqli_fetch_assoc($result_sales_details);
        //get customer details 
        $sql_get_customer_detail="SELECT * FROM customer WHERE custid='$row_sales_details[custid]' ";
        $result_get_customer_detail=mysqli_query($con,$sql_get_customer_detail)or die("Error in geting customer details".mysqli_error($con));
        $row_get_customer_detail=mysqli_fetch_assoc($result_get_customer_detail);
        echo'<div class="form-row">
                <div class="form-group col-md-12">
                    <h4 align="right"><b>SALES INVOICE</b></h4>
                    <hr>
                <div>
            </div>';
        echo'<div class="form-row">
                <div class="form-group col-md-6">
                    <label><b>Customer Details :</b></label><br>
                    <label>'.$row_get_customer_detail["cusname"].'</label><br>
                    <label>'.$row_get_customer_detail["address"].'</label><br>
                    <label>'.$row_get_customer_detail["tpno"].'</label>
                </div>
                <div class="form-group col-md-6" align="right"><br>
                    <label><b>SALES ID : '.$salesid.'</b></label><br>
                    <label><b>DATE : '.$row_sales_details["salesdate"].'</b></label><br>
                </div>
            </div>';

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Model No</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th> 
                            <th>Total</th>                                                 
                        </tr>
                    </thead>
                    <tbody>';
                        // get sales item details
                        $sql_get_sales_item_details="SELECT * FROM salesitem WHERE salesid='$salesid' ORDER BY salesid  ASC";
                        $result_sales_item_details=mysqli_query($con,$sql_get_sales_item_details) or die ("Error in getting purchase details".mysqli_error($con));
                        $x=1;
                        $total_amount=0;
                        $discount;
                        while($row_get_sales_item_details=mysqli_fetch_assoc($result_sales_item_details))
                        {   // get sales price
                            $sql_get_sales_price="SELECT salesprice FROM modelprice WHERE modelno='$row_get_sales_item_details[modelno]' AND enddate IS NULL";
                            $result_get_sales_price=mysqli_query($con,$sql_get_sales_price) or die ("Error in get Model".mysqli_error($con));
                            $row_get_sales_price=mysqli_fetch_assoc($result_get_sales_price);
                            // model name
                            $sql_get_model_name="SELECT modelname FROM model WHERE modelno='$row_get_sales_item_details[modelno]'";
                            $result_get_model_name=mysqli_query($con,$sql_get_model_name) or die ("Error getting modelname".mysqli_error($con));
                            $row_get_model_name=mysqli_fetch_assoc($result_get_model_name);

                            echo   '<tr align="center">
                                        <td>'.$x.'</td>
                                        <td>'.$row_get_sales_item_details["modelno"].'</td>
                                        <td>'.$row_get_model_name["modelname"].'</td>
                                        <td>'.$row_get_sales_price["salesprice"].'</td>   
                                        <td>'.$row_get_sales_item_details["quantity"].'</td>
                                        <td>'.(int)$row_get_sales_price["salesprice"] * (int)$row_get_sales_item_details["quantity"].'</td>                                          
                                    </tr>';
                                $x++ ;
                                $total_amount+=((int)$row_get_sales_price["salesprice"] * (int)$row_get_sales_item_details["quantity"]);
                        }   
                            if($row_sales_details["discount_type"]=="Percentage")
                            {
                                $discount=(int)$row_sales_details["total_amount"] * ((int)$row_sales_details["discount"] / 100);
                            }
                            else
                            {
                                $discount=$row_sales_details["discount"];
                            }
                            echo   '<tr >
                                        <td colspan="5" align="right"><b>Sub Total</b></td>
                                        <td align="center">'.$total_amount.'</td>
                                    </tr> 
                                    <tr >
                                        <td colspan="5" align="right"><b>Discount</b></td>
                                        <td align="center">'.$discount.'</td>
                                    </tr>
                                    <tr align="center">
                                        <td colspan="5" align="right"><b>Total</b></td>
                                        <td align="center"><b>'.($total_amount-$discount).'</b></td>
                                    </tr>
                    </tbody>
                </table>
              </div>
            <div >
                    <h6 align="center">- If you need any further clarification, feel free to contact us -<br><b>Thank You For Your Business!</b></h6>
            <div>';

    }
    if($_GET["frompage"]=="generate_repair_invoice")
    {   //get repair details
        $repairid=$_GET["ajax_repair_id"];
        $sql_repair_details="SELECT * FROM repair WHERE repairid='$repairid'";
        $result_repair_details=mysqli_query($con,$sql_repair_details)or die("Error in geting repair details details".mysqli_error($con));
        $row_repair_details=mysqli_fetch_assoc($result_repair_details);
        //get customer details 
        $sql_get_customer_detail="SELECT * FROM customer WHERE custid='$row_repair_details[custid]' ";
        $result_get_customer_detail=mysqli_query($con,$sql_get_customer_detail)or die("Error in geting customer details".mysqli_error($con));
        $row_get_customer_detail=mysqli_fetch_assoc($result_get_customer_detail);
        echo'<div class="form-row">
                <div class="form-group col-md-12">
                    <h4 align="right"><b>REPAIR INVOICE</b></h4>
                    <hr>
                <div>
            </div>';
        echo'<div class="form-row">
                <div class="form-group col-md-6">
                    <label><b>Customer Details :</b></label><br>
                    <label>'.$row_get_customer_detail["cusname"].'</label><br>
                    <label>'.$row_get_customer_detail["address"].'</label><br>
                    <label>'.$row_get_customer_detail["tpno"].'</label>
                </div>
                <div class="form-group col-md-6" align="right"><br>
                    <label><b>REPAIR ID : '.$repairid.'</b></label><br>
                    <label><b>DATE : '.date('Y-m-d').'</b></label><br>
                </div>
            </div>';

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>IMEI Number</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Amount</th>                                                 
                        </tr>
                    </thead>
                    <tbody>';
                        // get repair item details
                        $sql_get_repair_item_details="SELECT * FROM repairitem WHERE repairid='$repairid' ORDER BY repairid  ASC";
                        $result_repair_item_details=mysqli_query($con,$sql_get_repair_item_details) or die ("Error in getting purchase details".mysqli_error($con));
                        $x=1;
                        while($row_get_repair_item_details=mysqli_fetch_assoc($result_repair_item_details))
                        {   
                            echo   '<tr align="center">
                                        <td>'.$x.'</td>
                                        <td>'.$row_get_repair_item_details["imei_number"].'</td>
                                        <td>'.$row_get_repair_item_details["device_name"].'</td>
                                        <td>'.$row_get_repair_item_details["description"].'</td>   
                                        <td>'.$row_get_repair_item_details["rep_amount"].'</td>                                          
                                    </tr>';
                                $x++ ;
                        }   
                            echo   '<tr align="center">
                                        <td colspan="4" align="right"><b>Total</b></td>
                                        <td align="center"><b>'.$row_repair_details["amount"].'</b></td>
                                    </tr>
                    </tbody>
                </table>
              </div>
            <div >
                    <h6 align="center">- If you need any further clarification, feel free to contact us -<br><b>Thank You For Your Business!</b></h6>
            <div>';

    }
    if($_GET["frompage"]=="add_sales_item_on_sale")
    {   
        $barcode=$_GET["ajax_barcode"];
        $salesid=$_GET["ajax_sales_id"];
        //get model no for barcode
        $sql_get_model_no="SELECT modelno,modelname FROM model WHERE barcode='$barcode'";
        $result_get_model_no=mysqli_query($con,$sql_get_model_no) or die ("Error in get Model".mysqli_error($con));
        $row_get_model_no=mysqli_fetch_assoc($result_get_model_no);

        // get model which has active sales price
        $sql_get_modelno_from_modelprice="SELECT modelno FROM modelprice WHERE modelno='$row_get_model_no[modelno]' AND enddate IS NULL";
        $result_get_modelno_from_modelprice=mysqli_query($con,$sql_get_modelno_from_modelprice)or die("Error in geting modelno from modelprice details".mysqli_error($con));
        $row_get_modelno_from_modelprice=mysqli_fetch_assoc($result_get_modelno_from_modelprice);
        
        //get model from stock
        $sql_get_modelno_from_stock="SELECT DISTINCT modelno FROM stock WHERE  branchid='$branchid' AND quantity!=0 AND modelno='$row_get_modelno_from_modelprice[modelno]'";
        $result_get_modelno_from_stock=mysqli_query($con,$sql_get_modelno_from_stock) or die ("Error in getting modelno from stock".mysqli_error($con));
            
        if(mysqli_num_rows($result_get_modelno_from_stock)!=0 )
        {
            $sql_get_sales_item="SELECT * FROM salesitem Where salesid='$salesid'";
            $result_get_sales_item=mysqli_query($con,$sql_get_sales_item) or die ("Error in Getting sales item".mysqli_error($con));
            $row_get_sales_item=mysqli_fetch_assoc($result_get_sales_item);

            //max quantity
            $sql_get_max_from_stock="SELECT SUM(quantity) AS maxquantity FROM stock WHERE modelno='$row_get_model_no[modelno]'";
            $result_get_max_from_stock=mysqli_query($con,$sql_get_max_from_stock) or die ("Error in getting modelno from stock".mysqli_error($con));
            $row_get_max_from_stock=mysqli_fetch_assoc($result_get_max_from_stock);

            //max from sales item
            $sql_get_max_from_sales_item="SELECT SUM(quantity) AS salesitem_quantity FROM salesitem Where salesid='$salesid' AND modelno='$row_get_model_no[modelno]'";
            $result_get_max_from_sales_item=mysqli_query($con,$sql_get_max_from_sales_item) or die ("Error in Getting sales item".mysqli_error($con));
            $row_get_get_max_from_sales_item=mysqli_fetch_assoc($result_get_max_from_sales_item);
                
            if($row_get_sales_item["modelno"]!=$row_get_model_no["modelno"])
                {

                        $sql_insert_sales_item="INSERT INTO salesitem(salesid,modelno,quantity)
                                            VALUES('".mysqli_real_escape_string($con,$salesid)."',
                                                    '".mysqli_real_escape_string($con,$row_get_model_no["modelno"])."',
                                                    '".mysqli_real_escape_string($con,"1")."')";
                        $result_insert_sales_item=mysqli_query($con,$sql_insert_sales_item) or die("Error in inserting in sales".mysqli_error($con));
                        if($result_insert_sales_item)
                        {   
                            $_SESSION["SALES_ID"]=$salesid;
                            echo "true";
                        }
                }
                elseif($row_get_max_from_stock["maxquantity"]>=$row_get_get_max_from_sales_item["salesitem_quantity"])
                {   // if same model selected for sale
                    $sql_update_salesitem="UPDATE salesitem SET  
                                    quantity='".mysqli_real_escape_string($con,($row_get_sales_item["quantity"]+1))."'
                                    WHERE salesid='".mysqli_real_escape_string($con,$salesid)."' AND modelno='".mysqli_real_escape_string($con,$row_get_sales_item["modelno"])."'";
                                    
                    $result_update_salesitem=mysqli_query($con,$sql_update_salesitem) or die("Error in updating in salesitem".mysqli_error($con));
                    if($result_update_salesitem)
                    {   
                        echo "true";
                    }
                }
                else
                {
                    echo"out_of_stock";
                }
            
        }
        elseif(mysqli_num_rows($result_get_model_no)==0)
        {
            echo"false";
        }
        elseif(mysqli_num_rows($result_get_modelno_from_modelprice)==0)
        {
            echo"model_blocked";
        }
        else
        {
            echo"out_of_stock";
        } 
               
                                                    
        }

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

