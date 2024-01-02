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
if($system_user_type=="Manager" || $system_user_type=="Branch Manager" )
{
include("config.php");
     // get branch id
    $sql_get_enterby="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
    $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
    $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
    $login_branchid=$row_get_enterby["branchid"];
if(isset($_GET["frompage"]))
{	
	if($_GET["frompage"]=="generate_purchase_overall_report")
	{	
		$start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Enter By</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Payment Status</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate>='$start_date' AND purdate<='$end_date' ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["status"].'</td>                                               
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
	}
    if($_GET["frompage"]=="generate_purchase_report_branchwise")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Enter By</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Payment Status</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate>='$start_date' AND purdate<='$end_date' AND branchid='$branchid' ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td> 
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["status"].'</td>                                               
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_purchase_report_supplierwise_branchwise")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $sup_id=$_GET["ajax_supplierid"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Enter By</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Payment Status</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate>='$start_date' AND purdate<='$end_date' AND supid='$sup_id' AND branchid='$branchid' ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td> 
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["status"].'</td>                                               
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
     if($_GET["frompage"]=="generate_overall_sales_report")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Sales ID</th>
                            <th>Sales Date</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th>Enter By</th>
                            <th>Total Amount</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Sales Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_sales_details="SELECT * FROM sales WHERE salesdate>='$start_date' AND salesdate<='$end_date' ORDER BY salesid  ASC";
                    $result_sales_details=mysqli_query($con,$sql_get_sales_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_amount=0;
                    while($row_get_sales_details=mysqli_fetch_assoc($result_sales_details))
                    {   //get customer name
                        $sql_get_customer="SELECT * FROM customer WHERE custid='$row_get_sales_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_sales_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_sales_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_sales_details["salesid"].'</td>
                                    <td>'.$row_get_sales_details["salesdate"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_customer["cusname"].'</td>  
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_sales_details["total_amount"].'</td> 
                                    <td>'.$row_get_sales_details["discount_type"].'</td> 
                                    <td>'.$row_get_sales_details["discount"].'</td> 
                                    <td>'.$row_get_sales_details["sales_amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                            $total_amount+=$row_get_sales_details["sales_amount"];
                    }
                        echo   '<tr align="center">
                                    <td colspan="9"><b>Total Sales Amount</b></td>
                                    <td>'.$total_amount.'</td>
                                </tr>                         
                </tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_sales_report_branchwise")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Sales ID</th>
                            <th>Sales Date</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th>Enter By</th>
                            <th>Total Amount</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Sales Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_sales_details="SELECT * FROM sales WHERE salesdate>='$start_date' AND salesdate<='$end_date' AND branchid='$branchid' ORDER BY salesid  ASC";
                    $result_sales_details=mysqli_query($con,$sql_get_sales_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_amount=0;
                    while($row_get_sales_details=mysqli_fetch_assoc($result_sales_details))
                    {   //get customer name
                        $sql_get_customer="SELECT * FROM customer WHERE custid='$row_get_sales_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_sales_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_sales_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_sales_details["salesid"].'</td>
                                    <td>'.$row_get_sales_details["salesdate"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_customer["cusname"].'</td>  
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_sales_details["total_amount"].'</td> 
                                    <td>'.$row_get_sales_details["discount_type"].'</td> 
                                    <td>'.$row_get_sales_details["discount"].'</td> 
                                    <td>'.$row_get_sales_details["sales_amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                            $total_amount+=$row_get_sales_details["sales_amount"];
                    }
                        echo   '<tr align="center">
                                    <td colspan="9"><b>Total Sales Amount</b></td>
                                    <td>'.$total_amount.'</td>
                                </tr>                         
                </tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_supplier_ledger_branchwise_supplierwise_report")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $sup_id=$_GET["ajax_supplierid"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Ledger ID</th>
                            <th>Ledger Date</th>
                            <th>Purchase/Return Ref</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Payment Type</th>
                            <th>Payment Mode</th>
                            <th>Credit</th>
                            <th>Debit</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_supplierledger_details="SELECT * FROM supplierledger WHERE ledger_date>='$start_date' AND ledger_date<='$end_date' AND supid='$sup_id' AND branchid='$branchid' ORDER BY sup_ledger_id  ASC";
                    $result_supplierledger_details=mysqli_query($con,$sql_get_supplierledger_details) or die ("Error in getting supplierledger details".mysqli_error($con));
                    $x=1;
                    $total_credit=0;
                    $total_debit=0;
                    while($row_get_supplierledger_details=mysqli_fetch_assoc($result_supplierledger_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_supplierledger_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_supplierledger_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_supplierledger_details["sup_ledger_id"].'</td>
                                    <td>'.$row_get_supplierledger_details["ledger_date"].'</td>
                                    <td>'.$row_get_supplierledger_details["pur_ret_ref_id"].'</td>
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_supplierledger_details["type"].'</td> 
                                    <td>'.$row_get_supplierledger_details["payment_mode"].'</td>
                                    <td>'.$row_get_supplierledger_details["credit"].'</td> 
                                    <td>'.$row_get_supplierledger_details["debit"].'</td>                                                
                                </tr>';
                            $x++ ;     
                            $total_credit+=(int)$row_get_supplierledger_details["credit"];
                            $total_debit+=(int)$row_get_supplierledger_details["debit"];
                    } 
                        echo   '<tr align="center">
                                        <td colspan="8"><b>Total Credit and Debit</b></td>
                                        <td><b>'.$total_credit.'</b></td>
                                        <td><b>'.$total_debit.'</b></td> 
                                </tr> 
                                <tr align="center">';
                                        if($total_credit>$total_debit){
                                        echo'<td colspan="8"><b>Balance</b></td>
                                        <td colspan="2" style="color:red;"><b>'.($total_credit - $total_debit).'</b></td>';
                                        }else
                                        {
                                        echo'<td colspan="8"><b>Balance</b></td>
                                        <td colspan="2" style="color:green;"><b>'.($total_credit - $total_debit).'</b></td>';  
                                        }
                        echo   '</tr>                     
                </tbody>
                </table>
              </div>';
    }
    if ($_GET["frompage"]=="generate_overall_supplier_ledger_report") 
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Ledger ID</th>
                            <th>Ledger Date</th>
                            <th>Purchase/Return Ref</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Payment Type</th>
                            <th>Payment Mode</th>
                            <th>Credit</th>
                            <th>Debit</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_supplierledger_details="SELECT * FROM supplierledger WHERE ledger_date>='$start_date' AND ledger_date<='$end_date' ORDER BY sup_ledger_id  ASC";
                    $result_supplierledger_details=mysqli_query($con,$sql_get_supplierledger_details) or die ("Error in getting supplierledger details".mysqli_error($con));
                    $x=1;
                    $total_credit=0;
                    $total_debit=0;
                    while($row_get_supplierledger_details=mysqli_fetch_assoc($result_supplierledger_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_supplierledger_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_supplierledger_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_supplierledger_details["sup_ledger_id"].'</td>
                                    <td>'.$row_get_supplierledger_details["ledger_date"].'</td>
                                    <td>'.$row_get_supplierledger_details["pur_ret_ref_id"].'</td>
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_supplierledger_details["type"].'</td> 
                                    <td>'.$row_get_supplierledger_details["payment_mode"].'</td>
                                    <td>'.$row_get_supplierledger_details["credit"].'</td> 
                                    <td>'.$row_get_supplierledger_details["debit"].'</td>                                                
                                </tr>';
                            $x++ ;     
                            $total_credit+=(int)$row_get_supplierledger_details["credit"];
                            $total_debit+=(int)$row_get_supplierledger_details["debit"];
                    } 
                        echo   '<tr align="center">
                                        <td colspan="8"><b>Total Credit and Debit</b></td>
                                        <td><b>'.$total_credit.'</b></td>
                                        <td><b>'.$total_debit.'</b></td> 
                                </tr> 
                                <tr align="center">';
                                        if($total_credit>$total_debit){
                                        echo'<td colspan="8"><b>Balance</b></td>
                                        <td colspan="2" style="color:red;"><b>'.($total_credit - $total_debit).'</b></td>';
                                        }else
                                        {
                                        echo'<td colspan="8"><b>Balance</b></td>
                                        <td colspan="2" style="color:green;"><b>'.($total_credit - $total_debit).'</b></td>';  
                                        }
                        echo   '</tr>                     
                </tbody>
                </table>
              </div>';        
    }
    if ($_GET["frompage"]=="generate_stock_overall_report") 
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Branch</th>
                            <th>Model Name</th>
                            <th>Quantity</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT purid FROM purchase WHERE purdate>='$start_date' AND purdate<='$end_date'";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {
                        $sql_get_stock_details="SELECT * FROM stock WHERE purid='$row_get_purchase_details[purid]' AND quantity!=0 ORDER BY purid  ASC";
                        $result_stock_details=mysqli_query($con,$sql_get_stock_details) or die ("Error in getting stock details".mysqli_error($con));
                        $x=1;
                        while($row_get_stock_details=mysqli_fetch_assoc($result_stock_details))
                        {   
                            //get branch name
                            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_stock_details[branchid]'";
                            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                            //get model name
                            $sql_get_model="SELECT * FROM model WHERE modelno='$row_get_stock_details[modelno]'";
                            $result_get_model=mysqli_query($con,$sql_get_model) or die ("Error in get Category".mysqli_error($con));
                            $row_get_model=mysqli_fetch_assoc($result_get_model);
                            echo   '<tr align="center">
                                        <td>'.$x.'</td>
                                        <td>'.$row_get_stock_details["purid"].'</td>
                                        <td>'.$row_get_branch["branchname"].'</td>
                                        <td>'.$row_get_model["modelname"].'</td>
                                        <td>'.$row_get_stock_details["quantity"].'</td>                                         
                                    </tr>';
                                $x++ ;
                        }
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
     if ($_GET["frompage"]=="generate_stock_report_branchwise") 
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Branch</th>
                            <th>Model Name</th>
                            <th>Quantity</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT purid FROM purchase WHERE purdate>='$start_date' AND purdate<='$end_date' AND branchid='$branchid'";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {
                        $sql_get_stock_details="SELECT * FROM stock WHERE purid='$row_get_purchase_details[purid]' AND quantity!=0 ORDER BY purid  ASC" ;
                        $result_stock_details=mysqli_query($con,$sql_get_stock_details) or die ("Error in getting stock details".mysqli_error($con));
                        $x=1;
                        while($row_get_stock_details=mysqli_fetch_assoc($result_stock_details))
                        {   
                            //get branch name
                            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_stock_details[branchid]'";
                            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                            //get model name
                            $sql_get_model="SELECT * FROM model WHERE modelno='$row_get_stock_details[modelno]'";
                            $result_get_model=mysqli_query($con,$sql_get_model) or die ("Error in get Category".mysqli_error($con));
                            $row_get_model=mysqli_fetch_assoc($result_get_model);
                            echo   '<tr align="center">
                                        <td>'.$x.'</td>
                                        <td>'.$row_get_stock_details["purid"].'</td>
                                        <td>'.$row_get_branch["branchname"].'</td>
                                        <td>'.$row_get_model["modelname"].'</td>
                                        <td>'.$row_get_stock_details["quantity"].'</td>                                         
                                    </tr>';
                                $x++ ;
                        }
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if ($_GET["frompage"]=="generate_stock_branchwise_supplierwise_report") 
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $sup_id=$_GET["ajax_supplierid"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Branch</th>
                            <th>Model Name</th>
                            <th>Quantity</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT purid FROM purchase WHERE purdate>='$start_date' AND purdate<='$end_date' AND branchid='$branchid' AND supid='$sup_id'";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {
                        $sql_get_stock_details="SELECT * FROM stock WHERE purid='$row_get_purchase_details[purid]' AND quantity!=0 ORDER BY purid  ASC" ;
                        $result_stock_details=mysqli_query($con,$sql_get_stock_details) or die ("Error in getting stock details".mysqli_error($con));
                        $x=1;
                        while($row_get_stock_details=mysqli_fetch_assoc($result_stock_details))
                        {   
                            //get branch name
                            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_stock_details[branchid]'";
                            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                            $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                            //get model name
                            $sql_get_model="SELECT * FROM model WHERE modelno='$row_get_stock_details[modelno]'";
                            $result_get_model=mysqli_query($con,$sql_get_model) or die ("Error in get Category".mysqli_error($con));
                            $row_get_model=mysqli_fetch_assoc($result_get_model);
                            echo   '<tr align="center">
                                        <td>'.$x.'</td>
                                        <td>'.$row_get_stock_details["purid"].'</td>
                                        <td>'.$row_get_branch["branchname"].'</td>
                                        <td>'.$row_get_model["modelname"].'</td>
                                        <td>'.$row_get_stock_details["quantity"].'</td>                                         
                                    </tr>';
                                $x++ ;
                        }
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_return_overall_report")
    {   
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Enter By</th>
                            <th>Return Amount</th>                                            
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date>='$start_date' AND return_date<='$end_date' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_return_details["return_amount"].'</td> 
                                </tr>';
                            $x++ ;
                    
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_return_report_branchwise")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Enter By</th>
                            <th>Return Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date>='$start_date' AND return_date<='$end_date' AND branchid='$branchid' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_return_details["return_amount"].'</td>                                               
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_return_report_supplierwise_branchwise")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $sup_id=$_GET["ajax_supplierid"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Enter By</th>
                            <th>Return Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date>='$start_date' AND return_date<='$end_date' AND supid='$sup_id' AND branchid='$branchid' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));
                        
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_return_details["return_amount"].'</td>                                               
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_repair_overall_report")
    {   
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Repair ID</th>
                            <th>Repair Date</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th>Enter By</th>
                            <th>Payment Status</th>
                            <th>Repair Status</th>
                            <th>Estimated Date</th>
                            <th>Payment Date</th>
                            <th>Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_repair_details="SELECT * FROM repair WHERE date>='$start_date' AND date<='$end_date' ORDER BY repairid ASC";
                    $result_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error in getting repair details".mysqli_error($con));
                    $x=1;
                    while($row_get_repair_details=mysqli_fetch_assoc($result_repair_details))
                    {   //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_repair_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_repair_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get branch name
                        $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_get_repair_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get repair item
                        $sql_get_repairitem="SELECT * FROM repairitem WHERE repairid='$row_get_repair_details[repairid]'";
                        $result_get_repairitem=mysqli_query($con,$sql_get_repairitem) or die ("Error in get repairitem".mysqli_error($con));
                        //$row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem);

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_repair_details["repairid"].'</td>
                                    <td>'.$row_get_repair_details["date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_customer["cusname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_repair_details["pay_status"].'</td> 
                                    <td>';
                                    while($row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem))
                                    {
                                    echo $row_get_repairitem["device_name"].'-'.$row_get_repairitem["repair_status"].'<br>';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["estdate"].'</td>
                                    <td>';
                                    if($row_get_repair_details["payment_date"]!="")
                                        {
                                            echo $row_get_repair_details["payment_date"];
                                        }
                                        else
                                        {
                                            echo "N/A";
                                        }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_repair_report_branchwise")
    {
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $branchid=$_GET["ajax_branchid"];

        echo '<div class="table-responsive">
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Repair ID</th>
                            <th>Repair Date</th>
                            <th>Branch</th>
                            <th>Payment Status</th>
                            <th>Repair Status</th>
                            <th>Estimated Date</th>
                            <th>Payment Date</th>
                            <th>Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_repair_details="SELECT * FROM repair WHERE date>='$start_date' AND date<='$end_date' AND branchid='$branchid' ORDER BY repairid ASC";
                    $result_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error in getting repair details".mysqli_error($con));
                    $x=1;
                    while($row_get_repair_details=mysqli_fetch_assoc($result_repair_details))
                    {   //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_repair_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_repair_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get branch name
                        $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_get_repair_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get repair item
                        $sql_get_repairitem="SELECT * FROM repairitem WHERE repairid='$row_get_repair_details[repairid]'";
                        $result_get_repairitem=mysqli_query($con,$sql_get_repairitem) or die ("Error in get repairitem".mysqli_error($con));
                        //$row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem);

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_repair_details["repairid"].'</td>
                                    <td>'.$row_get_repair_details["date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_customer["cusname"].'</td> 
                                    <td>'.$row_get_enterby["staffname"].'</td>
                                    <td>'.$row_get_repair_details["pay_status"].'</td> 
                                    <td>';
                                    while($row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem))
                                    {
                                    echo $row_get_repairitem["device_name"].'-'.$row_get_repairitem["repair_status"].'<br>';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["estdate"].'</td>
                                    <td>';
                                    if($row_get_repair_details["payment_date"]!="")
                                        {
                                            echo $row_get_repair_details["payment_date"];
                                        }
                                        else
                                        {
                                            echo "N/A";
                                        }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                    }                         
                echo  '</tbody>
                </table>
              </div>';
    }
    if($_GET["frompage"]=="generate_overall_daily_business_report")
    {   
        $date=$_GET["ajax_today_date"];
        // sales
        echo '<div class="table-responsive">
                <h4 class="card-title">Sales</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Sales ID</th>
                            <th>Sales Date</th>
                            <th>Branch</th>
                            <th>Total Amount</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Sales Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_sales_details="SELECT * FROM sales WHERE salesdate='$date' ORDER BY salesid  ASC";
                    $result_sales_details=mysqli_query($con,$sql_get_sales_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_sales_amount=0;
                    while($row_get_sales_details=mysqli_fetch_assoc($result_sales_details))
                    {   //get customer name
                        $sql_get_customer="SELECT * FROM customer WHERE custid='$row_get_sales_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_sales_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_sales_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_sales_details["salesid"].'</td>
                                    <td>'.$row_get_sales_details["salesdate"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_sales_details["total_amount"].'</td> 
                                    <td>'.$row_get_sales_details["discount_type"].'</td> 
                                    <td>'.$row_get_sales_details["discount"].'</td> 
                                    <td>'.$row_get_sales_details["sales_amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                            $total_sales_amount+=(int)$row_get_sales_details["sales_amount"];
                    }
                        echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center">'.$total_sales_amount.'</td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Sales Amount = '.$total_sales_amount.'</b></h5></td>
                                </tr>

                </tbody>
                </table>
        </div>';
        //repair
        echo '<div class="table-responsive">
                <h4 class="card-title">Repair</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Repair ID</th>
                            <th>Repair Date</th>
                            <th>Branch</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                            <th>Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_repair_details="SELECT * FROM repair WHERE payment_date='$date' ORDER BY repairid ASC";
                    $result_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error in getting repair details".mysqli_error($con));
                    $x=1;
                    $total_repair_amount=0;
                    while($row_get_repair_details=mysqli_fetch_assoc($result_repair_details))
                    {   //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_repair_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_repair_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get branch name
                        $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_get_repair_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get repair item
                        $sql_get_repairitem="SELECT * FROM repairitem WHERE repairid='$row_get_repair_details[repairid]'";
                        $result_get_repairitem=mysqli_query($con,$sql_get_repairitem) or die ("Error in get repairitem".mysqli_error($con));
                        //$row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem);

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_repair_details["repairid"].'</td>
                                    <td>'.$row_get_repair_details["date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_repair_details["pay_status"].'</td>
                                    <td>';
                                    if($row_get_repair_details["payment_date"]!="")
                                        {
                                            echo $row_get_repair_details["payment_date"];
                                        }
                                        else
                                        {
                                            echo "N/A";
                                        }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["amount"].'</td>                                              
                                </tr>';
                            $total_repair_amount+=(int)$row_get_repair_details["amount"];
                            $x++ ;
                    }                         
                         echo   '<tr>
                                    <td colspan="6" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_repair_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Repair Amount = '.$total_repair_amount.'</b></h5></td>
                                </tr>                       
                    </tbody>
                </table>
                <div>';
        // purchase
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate='$date' ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_purchase_amount=0;
                    $total_purchase_paid_amount=0;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td>
                                    <td>'.$row_get_purchase_details["status"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td>                                             
                                </tr>';
                                $total_purchase_amount+=(int)$row_get_purchase_details["total_amount"];
                                $total_purchase_paid_amount+=(int)$row_get_purchase_details["paid_amount"];
                            $x++ ;
                    } 
                            echo   '<tr>
                                        <td colspan="8" align="left"><b>Total</b></td>
                                        <td align="center"><b>'.$total_purchase_amount.'</b></td>
                                        <td align="center"><b>'.$total_purchase_paid_amount.'</b></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="center"><h5><b>Total Purchase Amount = '.$total_purchase_amount.'</b></h5></td>
                                        <td colspan="5" align="center"><h5><b>Total Paid Amount = '.$total_purchase_paid_amount.'</b></h5></td>
                                    </tr>                       
                    </tbody>
                </table>
              </div>';
        //return
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase Return</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Return Amount</th>                                            
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date='$date' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    $total_return_amount=0;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_return_details["return_amount"].'</td> 
                                </tr>';
                            $total_return_amount+=(int)$row_get_return_details["return_amount"];
                            $x++ ;
                    
                    }                         
                         echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_return_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Purchase Return Amount = '.$total_return_amount.'</b></h5></td>
                                </tr>                        
                    </tbody>
                </table>
              </div>';
              // Business analysis
              echo '<div class="table-responsive">
                <h4 class="card-title">Business Analysis</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Activity</th>
                            <th>Amount</th>                                           
                        </tr>
                    </thead>
                    <tbody>';
                    // calculate stock value
                    $x=1;
                    $present_total_stock_value=0;
                    $sql_get_stock_opening_balance="SELECT opening_balance FROM stock_ledger WHERE ledger_date='$date'";
                    $result_get_stock_opening_balance=mysqli_query($con,$sql_get_stock_opening_balance) or die ("Error getting opening balance from stock ledger".mysqli_error($con));
                    $row_get_stock_opening_balance=mysqli_fetch_assoc($result_get_stock_opening_balance);

                    $sql_get_stock="SELECT * FROM stock WHERE quantity!=0";
                    $result_get_stock=mysqli_query($con,$sql_get_stock) or die ("Error getting details from stock".mysqli_error($con)); 
                    while($row_get_stock=mysqli_fetch_assoc($result_get_stock))
                    {   //get purchase id on the date
                        $sql_get_purchase_details="SELECT purid FROM purchase WHERE purdate='$date'";
                        $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                        while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                        {  
                            $get_unit_price="SELECT unitprice FROM purchaseitem WHERE modelno='$row_get_stock[modelno]' AND purid='$row_get_purchase_details[purid]'";
                            $result_get_unit_price=mysqli_query($con,$get_unit_price) or die ("Error getting unit price".mysqli_error($con));
                            $row_get_unit_price=mysqli_fetch_assoc($result_get_unit_price);
                            // calculate sub stock value
                            $sub_stock_value=$row_get_stock["quantity"] * $row_get_unit_price["unitprice"];
                            $present_total_stock_value+=$sub_stock_value;
                        }
                    }
                    $opening_stock_value=(int)$row_get_stock_opening_balance["opening_balance"];
                    

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Opening Stock Value</b></h6></td>
                                    <td><h6><b>'.$opening_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Sales Amount</td>
                        //             <td>'.$total_sales_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Repair Amount</td>
                        //             <td>'.$total_repair_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Amount</td>
                        //             <td>'.$total_purchase_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Paid Amount</td>
                        //             <td>'.$total_purchase_paid_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Return Amount</td>
                        //             <td>'.$total_return_amount.'</td>
                        //         </tr>'; 
                        //         $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Present Stock Value</b></h6></td>
                                    <td><h6><b>'.$present_total_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Cost of Sales = (Opening Stock Value + Total Purchase Amount - Present Stock Value)</b></h6></td>
                                    <td><h6><b>';
                                    echo ($opening_stock_value + $total_purchase_amount ) - $present_total_stock_value;
                        echo       '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Total Income = (Total Sales Amount + Total Repair Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_sales_amount + $total_repair_amount;
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Liablity Gained by Purchase = (Total Purchase Amount - Total Purchase Paid Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_purchase_amount - $total_purchase_paid_amount;
                        echo        '</b></h6></td>
                                </tr>';
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Gross Profit of the Day = (Total Income - (Opening Stock Value + Total Purchase Amount - Present Stock Value))</b></h6></td>
                                    <td><h6><b>';
                                    echo ($total_sales_amount + $total_repair_amount) - ($opening_stock_value + $total_purchase_amount - $present_total_stock_value );
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '</tr>  
                    </tbody>
                </table>
              </div>';


    }
    if($_GET["frompage"]=="generate_branchwise_daily_business_report")
    {   
        $date=$_GET["ajax_today_date"];
        $branchid=$_GET["ajax_branchid"];
        // sales
        echo '<div class="table-responsive">
                <h4 class="card-title">Sales</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Sales ID</th>
                            <th>Sales Date</th>
                            <th>Branch</th>
                            <th>Total Amount</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Sales Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_sales_details="SELECT * FROM sales WHERE salesdate='$date' AND branchid='$branchid' ORDER BY salesid  ASC";
                    $result_sales_details=mysqli_query($con,$sql_get_sales_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_sales_amount=0;
                    while($row_get_sales_details=mysqli_fetch_assoc($result_sales_details))
                    {   //get customer name
                        $sql_get_customer="SELECT * FROM customer WHERE custid='$row_get_sales_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_sales_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_sales_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_sales_details["salesid"].'</td>
                                    <td>'.$row_get_sales_details["salesdate"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_sales_details["total_amount"].'</td> 
                                    <td>'.$row_get_sales_details["discount_type"].'</td> 
                                    <td>'.$row_get_sales_details["discount"].'</td> 
                                    <td>'.$row_get_sales_details["sales_amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                            $total_sales_amount+=(int)$row_get_sales_details["sales_amount"];
                    }
                        echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center">'.$total_sales_amount.'</td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Sales Amount = '.$total_sales_amount.'</b></h5></td>
                                </tr>

                </tbody>
                </table>
        </div>';
        //repair
        echo '<div class="table-responsive">
                <h4 class="card-title">Repair</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Repair ID</th>
                            <th>Repair Date</th>
                            <th>Branch</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                            <th>Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_repair_details="SELECT * FROM repair WHERE payment_date='$date' AND branchid='$branchid' ORDER BY repairid ASC";
                    $result_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error in getting repair details".mysqli_error($con));
                    $x=1;
                    $total_repair_amount=0;
                    while($row_get_repair_details=mysqli_fetch_assoc($result_repair_details))
                    {   //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_repair_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_repair_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get branch name
                        $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_get_repair_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get repair item
                        $sql_get_repairitem="SELECT * FROM repairitem WHERE repairid='$row_get_repair_details[repairid]'";
                        $result_get_repairitem=mysqli_query($con,$sql_get_repairitem) or die ("Error in get repairitem".mysqli_error($con));
                        //$row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem);

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_repair_details["repairid"].'</td>
                                    <td>'.$row_get_repair_details["date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_repair_details["pay_status"].'</td>
                                    <td>';
                                    if($row_get_repair_details["payment_date"]!="")
                                        {
                                            echo $row_get_repair_details["payment_date"];
                                        }
                                        else
                                        {
                                            echo "N/A";
                                        }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["amount"].'</td>                                              
                                </tr>';
                            $total_repair_amount+=(int)$row_get_repair_details["amount"];
                            $x++ ;
                    }                         
                         echo   '<tr>
                                    <td colspan="6" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_repair_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Repair Amount = '.$total_repair_amount.'</b></h5></td>
                                </tr>                       
                    </tbody>
                </table>
                <div>';
        // purchase
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate='$date' AND branchid='$branchid' ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_purchase_amount=0;
                    $total_purchase_paid_amount=0;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td>
                                    <td>'.$row_get_purchase_details["status"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td>                                             
                                </tr>';
                                $total_purchase_amount+=(int)$row_get_purchase_details["total_amount"];
                                $total_purchase_paid_amount+=(int)$row_get_purchase_details["paid_amount"];
                            $x++ ;
                    } 
                            echo   '<tr>
                                        <td colspan="8" align="left"><b>Total</b></td>
                                        <td align="center"><b>'.$total_purchase_amount.'</b></td>
                                        <td align="center"><b>'.$total_purchase_paid_amount.'</b></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="center"><h5><b>Total Purchase Amount = '.$total_purchase_amount.'</b></h5></td>
                                        <td colspan="5" align="center"><h5><b>Total Paid Amount = '.$total_purchase_paid_amount.'</b></h5></td>
                                    </tr>                       
                    </tbody>
                </table>
              </div>';
        //return
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase Return</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Return Amount</th>                                            
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date='$date' AND branchid='$branchid' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    $total_return_amount=0;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_return_details["return_amount"].'</td> 
                                </tr>';
                            $total_return_amount+=(int)$row_get_return_details["return_amount"];
                            $x++ ;
                    
                    }                         
                         echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_return_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Purchase Return Amount = '.$total_return_amount.'</b></h5></td>
                                </tr>                        
                    </tbody>
                </table>
              </div>';
              // Business analysis
              echo '<div class="table-responsive">
                <h4 class="card-title">Business Analysis</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Activity</th>
                            <th>Amount</th>                                           
                        </tr>
                    </thead>
                    <tbody>';
                    // calculate stock value
                    $x=1;
                    $present_total_stock_value=0;
                    $sql_get_stock_opening_balance="SELECT opening_balance FROM stock_ledger WHERE ledger_date='$date' AND branchid='$branchid'";
                    $result_get_stock_opening_balance=mysqli_query($con,$sql_get_stock_opening_balance) or die ("Error getting opening balance from stock ledger".mysqli_error($con));
                    $row_get_stock_opening_balance=mysqli_fetch_assoc($result_get_stock_opening_balance);

                    $sql_get_stock="SELECT * FROM stock WHERE quantity!=0 AND branchid='$branchid'";
                    $result_get_stock=mysqli_query($con,$sql_get_stock) or die ("Error getting details from stock".mysqli_error($con)); 
                    while($row_get_stock=mysqli_fetch_assoc($result_get_stock))
                    {   //get purchase id on the date
                        $sql_get_purchase_details="SELECT purid FROM purchase WHERE purdate='$date' AND branchid='$branchid'";
                        $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                        while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                        {  
                        $get_unit_price="SELECT unitprice FROM purchaseitem WHERE modelno='$row_get_stock[modelno]' AND purid='$row_get_purchase_details[purid]'";
                        $result_get_unit_price=mysqli_query($con,$get_unit_price) or die ("Error getting unit price".mysqli_error($con));
                        $row_get_unit_price=mysqli_fetch_assoc($result_get_unit_price);
                        // calculate sub stock value
                        $sub_stock_value=$row_get_stock["quantity"] * $row_get_unit_price["unitprice"];
                        $present_total_stock_value+=$sub_stock_value;
                        }
                    }
                    $opening_stock_value=(int)$row_get_stock_opening_balance["opening_balance"];
                    

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Opening Stock Value</b></h6></td>
                                    <td><h6><b>'.$opening_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Sales Amount</td>
                        //             <td>'.$total_sales_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Repair Amount</td>
                        //             <td>'.$total_repair_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Amount</td>
                        //             <td>'.$total_purchase_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Paid Amount</td>
                        //             <td>'.$total_purchase_paid_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Return Amount</td>
                        //             <td>'.$total_return_amount.'</td>
                        //         </tr>'; 
                        //         $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Present Stock Value</b></h6></td>
                                    <td><h6><b>'.$present_total_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Cost of Sales = (Opening Stock Value + Total Purchase Amount - Present Stock Value)</b></h6></td>
                                    <td><h6><b>';
                                    echo ($opening_stock_value + $total_purchase_amount ) - $present_total_stock_value;
                        echo       '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Total Income = (Total Sales Amount + Total Repair Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_sales_amount + $total_repair_amount;
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Liablity Gained by Purchase = (Total Purchase Amount - Total Purchase Paid Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_purchase_amount - $total_purchase_paid_amount;
                        echo        '</b></h6></td>
                                </tr>';
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Gross Profit of the Day = (Total Income - (Opening Stock Value + Total Purchase Amount - Present Stock Value))</b></h6></td>
                                    <td><h6><b>';
                                    echo ($total_sales_amount + $total_repair_amount) - ($opening_stock_value + $total_purchase_amount - $present_total_stock_value );
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '</tr>  
                    </tbody>
                </table>
              </div>';
    }// overall monthly business report
    if($_GET["frompage"]=="generate_overall_monthly_business_report")
    {   
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $today_date=date("Y-m-d");
        $month_end_date;
        // sales
        echo '<div class="table-responsive">
                <h4 class="card-title">Sales</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Sales ID</th>
                            <th>Sales Date</th>
                            <th>Branch</th>
                            <th>Total Amount</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Sales Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_sales_details="SELECT * FROM sales WHERE salesdate>='$start_date' AND salesdate<='$end_date' ORDER BY salesid  ASC";
                    $result_sales_details=mysqli_query($con,$sql_get_sales_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_sales_amount=0;
                    while($row_get_sales_details=mysqli_fetch_assoc($result_sales_details))
                    {   //get customer name
                        $sql_get_customer="SELECT * FROM customer WHERE custid='$row_get_sales_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_sales_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_sales_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_sales_details["salesid"].'</td>
                                    <td>'.$row_get_sales_details["salesdate"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_sales_details["total_amount"].'</td> 
                                    <td>'.$row_get_sales_details["discount_type"].'</td> 
                                    <td>'.$row_get_sales_details["discount"].'</td> 
                                    <td>'.$row_get_sales_details["sales_amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                            $total_sales_amount+=(int)$row_get_sales_details["sales_amount"];
                    }
                        echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center">'.$total_sales_amount.'</td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Sales Amount = '.$total_sales_amount.'</b></h5></td>
                                </tr>

                </tbody>
                </table>
        </div>';
        //repair
        echo '<div class="table-responsive">
                <h4 class="card-title">Repair</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Repair ID</th>
                            <th>Repair Date</th>
                            <th>Branch</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                            <th>Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_repair_details="SELECT * FROM repair WHERE payment_date>='$start_date' AND payment_date<='$end_date' ORDER BY repairid ASC";
                    $result_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error in getting repair details".mysqli_error($con));
                    $x=1;
                    $total_repair_amount=0;
                    while($row_get_repair_details=mysqli_fetch_assoc($result_repair_details))
                    {   //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_repair_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_repair_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get branch name
                        $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_get_repair_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get repair item
                        $sql_get_repairitem="SELECT * FROM repairitem WHERE repairid='$row_get_repair_details[repairid]'";
                        $result_get_repairitem=mysqli_query($con,$sql_get_repairitem) or die ("Error in get repairitem".mysqli_error($con));
                        //$row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem);

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_repair_details["repairid"].'</td>
                                    <td>'.$row_get_repair_details["date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_repair_details["pay_status"].'</td>
                                    <td>';
                                    if($row_get_repair_details["payment_date"]!="")
                                        {
                                            echo $row_get_repair_details["payment_date"];
                                        }
                                        else
                                        {
                                            echo "N/A";
                                        }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["amount"].'</td>                                              
                                </tr>';
                            $total_repair_amount+=(int)$row_get_repair_details["amount"];
                            $x++ ;
                    }                         
                         echo   '<tr>
                                    <td colspan="6" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_repair_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Repair Amount = '.$total_repair_amount.'</b></h5></td>
                                </tr>                       
                    </tbody>
                </table>
                <div>';
        // purchase
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate>='$start_date'AND purdate<='$end_date'  ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_purchase_amount=0;
                    $total_purchase_paid_amount=0;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td>
                                    <td>'.$row_get_purchase_details["status"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td>                                             
                                </tr>';
                                $total_purchase_amount+=(int)$row_get_purchase_details["total_amount"];
                                $total_purchase_paid_amount+=(int)$row_get_purchase_details["paid_amount"];
                            $x++ ;
                    } 
                            echo   '<tr>
                                        <td colspan="8" align="left"><b>Total</b></td>
                                        <td align="center"><b>'.$total_purchase_amount.'</b></td>
                                        <td align="center"><b>'.$total_purchase_paid_amount.'</b></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="center"><h5><b>Total Purchase Amount = '.$total_purchase_amount.'</b></h5></td>
                                        <td colspan="5" align="center"><h5><b>Total Paid Amount = '.$total_purchase_paid_amount.'</b></h5></td>
                                    </tr>                       
                    </tbody>
                </table>
              </div>';
        //return
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase Return</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Return Amount</th>                                            
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date>='$start_date'AND return_date<='$end_date' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    $total_return_amount=0;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_return_details["return_amount"].'</td> 
                                </tr>';
                            $total_return_amount+=(int)$row_get_return_details["return_amount"];
                            $x++ ;
                    
                    }                         
                         echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_return_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Purchase Return Amount = '.$total_return_amount.'</b></h5></td>
                                </tr>                        
                    </tbody>
                </table>
              </div>';
              // Business analysis
              echo '<div class="table-responsive">
                <h4 class="card-title">Business Analysis</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Activity</th>
                            <th>Amount</th>                                           
                        </tr>
                    </thead>
                    <tbody>';
                    // calculate stock value
                    $x=1;
                    $present_total_stock_value=0;
                    // get stock opening balance
                    $sql_get_stock_opening_balance="SELECT opening_balance FROM stock_ledger WHERE ledger_date<='$start_date' ORDER BY ledger_date DESC LIMIT 1";
                    $result_get_stock_opening_balance=mysqli_query($con,$sql_get_stock_opening_balance) or die ("Error getting opening balance from stock ledger".mysqli_error($con));
                    $row_get_stock_opening_balance=mysqli_fetch_assoc($result_get_stock_opening_balance);

                    if($today_date<=$end_date )
                    {
                        $sql_get_stock="SELECT * FROM stock WHERE quantity!=0";
                        $result_get_stock=mysqli_query($con,$sql_get_stock) or die ("Error getting details from stock".mysqli_error($con)); 
                        while($row_get_stock=mysqli_fetch_assoc($result_get_stock))
                        {
                            $get_unit_price="SELECT unitprice FROM purchaseitem WHERE modelno='$row_get_stock[modelno]' AND purid='$row_get_stock[purid]'";
                            $result_get_unit_price=mysqli_query($con,$get_unit_price) or die ("Error getting unit price".mysqli_error($con));
                            $row_get_unit_price=mysqli_fetch_assoc($result_get_unit_price);
                            // calculate sub stock value
                            $sub_stock_value=$row_get_stock["quantity"] * $row_get_unit_price["unitprice"];
                            $present_total_stock_value+=$sub_stock_value;

                        }
                    }
                    else
                    {   
                        $month_end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
                        $sql_get_stock_month_end_balance="SELECT opening_balance FROM stock_ledger WHERE ledger_date='$month_end_date'";
                        $result_get_stock_month_end_balance=mysqli_query($con,$sql_get_stock_month_end_balance) or die ("Error getting month_end balance from stock ledger".mysqli_error($con));
                        $row_get_stock_month_end_balance=mysqli_fetch_assoc($result_get_stock_month_end_balance);
                        $present_total_stock_value=$row_get_stock_month_end_balance["opening_balance"];
                    }
                    $opening_stock_value=(int)$row_get_stock_opening_balance["opening_balance"];
                    

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Opening Stock Value</b></h6></td>
                                    <td><h6><b>'.$opening_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Sales Amount</td>
                        //             <td>'.$total_sales_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Repair Amount</td>
                        //             <td>'.$total_repair_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Amount</td>
                        //             <td>'.$total_purchase_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Paid Amount</td>
                        //             <td>'.$total_purchase_paid_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Return Amount</td>
                        //             <td>'.$total_return_amount.'</td>
                        //         </tr>'; 
                        //         $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Present Stock Value</b></h6></td>
                                    <td><h6><b>'.$present_total_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Cost of Sales = (Opening Stock Value + Total Purchase Amount - Present Stock Value)</b></h6></td>
                                    <td><h6><b>';
                                    echo ($opening_stock_value + $total_purchase_amount ) - $present_total_stock_value;
                        echo       '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Total Income = (Total Sales Amount + Total Repair Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_sales_amount + $total_repair_amount;
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Liablity Gained by Purchase = (Total Purchase Amount - Total Purchase Paid Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_purchase_amount - $total_purchase_paid_amount;
                        echo        '</b></h6></td>
                                </tr>';
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Gross Profit Of the Month = (Total Income - (Opening Stock Value + Total Purchase Amount - Present Stock Value))</b></h6></td>
                                    <td><h6><b>';
                                    echo ($total_sales_amount + $total_repair_amount) - ($opening_stock_value + $total_purchase_amount - $present_total_stock_value );
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '</tr>  
                    </tbody>
                </table>
              </div>';
    }// branchwise monthly business
    if($_GET["frompage"]=="generate_branchwise_monthly_business_report")
    {   
        $start_date=$_GET["ajax_start_date"];
        $end_date=$_GET["ajax_end_date"];
        $today_date=date("Y-m-d");
        $month_end_date;
        $branchid=$_GET["ajax_branchid"];
        // sales
        echo '<div class="table-responsive">
                <h4 class="card-title">Sales</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Sales ID</th>
                            <th>Sales Date</th>
                            <th>Branch</th>
                            <th>Total Amount</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Sales Amount</th>                                                  
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_sales_details="SELECT * FROM sales WHERE salesdate>='$start_date' AND salesdate<='$end_date' AND branchid='$branchid' ORDER BY salesid  ASC";
                    $result_sales_details=mysqli_query($con,$sql_get_sales_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_sales_amount=0;
                    while($row_get_sales_details=mysqli_fetch_assoc($result_sales_details))
                    {   //get customer name
                        $sql_get_customer="SELECT * FROM customer WHERE custid='$row_get_sales_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_sales_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_sales_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_sales_details["salesid"].'</td>
                                    <td>'.$row_get_sales_details["salesdate"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_sales_details["total_amount"].'</td> 
                                    <td>'.$row_get_sales_details["discount_type"].'</td> 
                                    <td>'.$row_get_sales_details["discount"].'</td> 
                                    <td>'.$row_get_sales_details["sales_amount"].'</td>                                              
                                </tr>';
                            $x++ ;
                            $total_sales_amount+=(int)$row_get_sales_details["sales_amount"];
                    }
                        echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center">'.$total_sales_amount.'</td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Sales Amount = '.$total_sales_amount.'</b></h5></td>
                                </tr>

                </tbody>
                </table>
        </div>';
        //repair
        echo '<div class="table-responsive">
                <h4 class="card-title">Repair</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Repair ID</th>
                            <th>Repair Date</th>
                            <th>Branch</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                            <th>Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_repair_details="SELECT * FROM repair WHERE payment_date>='$start_date' AND payment_date<='$end_date' AND branchid='$branchid' ORDER BY repairid ASC";
                    $result_repair_details=mysqli_query($con,$sql_get_repair_details) or die ("Error in getting repair details".mysqli_error($con));
                    $x=1;
                    $total_repair_amount=0;
                    while($row_get_repair_details=mysqli_fetch_assoc($result_repair_details))
                    {   //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_repair_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_repair_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get branch".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get branch name
                        $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_get_repair_details[custid]'";
                        $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get customer".mysqli_error($con));
                        $row_get_customer=mysqli_fetch_assoc($result_get_customer);
                        //get repair item
                        $sql_get_repairitem="SELECT * FROM repairitem WHERE repairid='$row_get_repair_details[repairid]'";
                        $result_get_repairitem=mysqli_query($con,$sql_get_repairitem) or die ("Error in get repairitem".mysqli_error($con));
                        //$row_get_repairitem=mysqli_fetch_assoc($result_get_repairitem);

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_repair_details["repairid"].'</td>
                                    <td>'.$row_get_repair_details["date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td>
                                    <td>'.$row_get_repair_details["pay_status"].'</td>
                                    <td>';
                                    if($row_get_repair_details["payment_date"]!="")
                                        {
                                            echo $row_get_repair_details["payment_date"];
                                        }
                                        else
                                        {
                                            echo "N/A";
                                        }
                        echo       '</td>
                                    <td>'.$row_get_repair_details["amount"].'</td>                                              
                                </tr>';
                            $total_repair_amount+=(int)$row_get_repair_details["amount"];
                            $x++ ;
                    }                         
                         echo   '<tr>
                                    <td colspan="6" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_repair_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Repair Amount = '.$total_repair_amount.'</b></h5></td>
                                </tr>                       
                    </tbody>
                </table>
                <div>';
        // purchase
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Purchase ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Date</th>
                            <th>Bill Number</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Payment Status</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>                                                   
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_purchase_details="SELECT * FROM purchase WHERE purdate>='$start_date'AND purdate<='$end_date' AND branchid='$branchid' ORDER BY purid ASC";
                    $result_purchase_details=mysqli_query($con,$sql_get_purchase_details) or die ("Error in getting purchase details".mysqli_error($con));
                    $x=1;
                    $total_purchase_amount=0;
                    $total_purchase_paid_amount=0;
                    while($row_get_purchase_details=mysqli_fetch_assoc($result_purchase_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_purchase_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_purchase_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_purchase_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_purchase_details["purid"].'</td>
                                    <td>'.$row_get_purchase_details["ledger_ref"].'</td>
                                    <td>'.$row_get_purchase_details["purdate"].'</td>
                                    <td>'.$row_get_purchase_details["billno"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td>
                                    <td>'.$row_get_purchase_details["status"].'</td>
                                    <td>'.$row_get_purchase_details["total_amount"].'</td> 
                                    <td>'.$row_get_purchase_details["paid_amount"].'</td>                                             
                                </tr>';
                                $total_purchase_amount+=(int)$row_get_purchase_details["total_amount"];
                                $total_purchase_paid_amount+=(int)$row_get_purchase_details["paid_amount"];
                            $x++ ;
                    } 
                            echo   '<tr>
                                        <td colspan="8" align="left"><b>Total</b></td>
                                        <td align="center"><b>'.$total_purchase_amount.'</b></td>
                                        <td align="center"><b>'.$total_purchase_paid_amount.'</b></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="center"><h5><b>Total Purchase Amount = '.$total_purchase_amount.'</b></h5></td>
                                        <td colspan="5" align="center"><h5><b>Total Paid Amount = '.$total_purchase_paid_amount.'</b></h5></td>
                                    </tr>                       
                    </tbody>
                </table>
              </div>';
        //return
        echo '<div class="table-responsive">
                <h4 class="card-title">Purchase Return</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Return ID</th>
                            <th>Sup. Ledger Ref</th>
                            <th>Purchase Ref</th>
                            <th>Return Date</th>
                            <th>Branch</th>
                            <th>Supplier</th>
                            <th>Return Amount</th>                                            
                        </tr>
                    </thead>
                    <tbody>';
                    $sql_get_return_details="SELECT * FROM purchasereturn WHERE return_date>='$start_date'AND return_date<='$end_date' AND branchid='$branchid' ORDER BY returnid ASC";
                    $result_return_details=mysqli_query($con,$sql_get_return_details) or die ("Error in getting return details".mysqli_error($con));
                    $x=1;
                    $total_return_amount=0;
                    while($row_get_return_details=mysqli_fetch_assoc($result_return_details))
                    {   //get supplier name
                        $sql_get_suppiers="SELECT * FROM suppliers WHERE supid='$row_get_return_details[supid]'";
                        $result_get_suppiers=mysqli_query($con,$sql_get_suppiers) or die ("Error in get Category".mysqli_error($con));
                        $row_get_suppiers=mysqli_fetch_assoc($result_get_suppiers);
                        //get enterrby staff name
                        $sql_get_enterby="SELECT * FROM staff WHERE staffid='$row_get_return_details[enterby]'";
                        $result_get_enterby=mysqli_query($con,$sql_get_enterby) or die ("Error in getting enterby".mysqli_error($con));
                        $row_get_enterby=mysqli_fetch_assoc($result_get_enterby);
                        //get branch name
                        $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_return_details[branchid]'";
                        $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                        $row_get_branch=mysqli_fetch_assoc($result_get_branch);
                        //get purchase reference
                        $sql_get_purchase="SELECT DISTINCT purid FROM purchasereturnitem WHERE returnid='$row_get_return_details[returnid]'";
                        $result_get_purchase=mysqli_query($con,$sql_get_purchase) or die ("Error in get Category".mysqli_error($con));

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td>'.$row_get_return_details["returnid"].'</td>
                                    <td>'.$row_get_return_details["ledger_ref"].'</td>
                                    <td>';
                                    while($row_get_purchase=mysqli_fetch_assoc($result_get_purchase))
                                    {
                                    echo $row_get_purchase["purid"].' ';
                                    }
                        echo       '</td>
                                    <td>'.$row_get_return_details["return_date"].'</td>
                                    <td>'.$row_get_branch["branchname"].'</td> 
                                    <td>'.$row_get_suppiers["supname"].'</td> 
                                    <td>'.$row_get_return_details["return_amount"].'</td> 
                                </tr>';
                            $total_return_amount+=(int)$row_get_return_details["return_amount"];
                            $x++ ;
                    
                    }                         
                         echo   '<tr>
                                    <td colspan="7" align="left"><b>Total</b></td>
                                    <td align="center"><b>'.$total_return_amount.'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="8" align="center"><h5><b>Total Purchase Return Amount = '.$total_return_amount.'</b></h5></td>
                                </tr>                        
                    </tbody>
                </table>
              </div>';
              // Business analysis
              echo '<div class="table-responsive">
                <h4 class="card-title">Business Analysis</h4>
                <table class="table table-striped table-bordered" id="check">
                    <thead>
                        <tr align="center">
                            <th>#</th>
                            <th>Activity</th>
                            <th>Amount</th>                                           
                        </tr>
                    </thead>
                    <tbody>';
                    // calculate stock value
                    $x=1;
                    $present_total_stock_value=0;
                    $sql_get_stock_opening_balance="SELECT opening_balance FROM stock_ledger WHERE ledger_date<='$start_date' AND branchid='$branchid' ORDER BY ledger_date DESC LIMIT 1";
                    $result_get_stock_opening_balance=mysqli_query($con,$sql_get_stock_opening_balance) or die ("Error getting opening balance from stock ledger".mysqli_error($con));
                    $row_get_stock_opening_balance=mysqli_fetch_assoc($result_get_stock_opening_balance);

                    if($today_date<=$end_date )
                    {
                        $sql_get_stock="SELECT * FROM stock WHERE quantity!=0 AND branchid='$branchid'";
                        $result_get_stock=mysqli_query($con,$sql_get_stock) or die ("Error getting details from stock".mysqli_error($con)); 
                        while($row_get_stock=mysqli_fetch_assoc($result_get_stock))
                        {
                            $get_unit_price="SELECT unitprice FROM purchaseitem WHERE modelno='$row_get_stock[modelno]' AND purid='$row_get_stock[purid]'";
                            $result_get_unit_price=mysqli_query($con,$get_unit_price) or die ("Error getting unit price".mysqli_error($con));
                            $row_get_unit_price=mysqli_fetch_assoc($result_get_unit_price);
                            // calculate sub stock value
                            $sub_stock_value=$row_get_stock["quantity"] * $row_get_unit_price["unitprice"];
                            $present_total_stock_value+=$sub_stock_value;

                        }
                    }
                    else
                    {   
                        $month_end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
                        $sql_get_stock_month_end_balance="SELECT opening_balance FROM stock_ledger WHERE ledger_date='$month_end_date' AND branchid='$branchid'";
                        $result_get_stock_month_end_balance=mysqli_query($con,$sql_get_stock_month_end_balance) or die ("Error getting month_end balance from stock ledger".mysqli_error($con));
                        $row_get_stock_month_end_balance=mysqli_fetch_assoc($result_get_stock_month_end_balance);
                        $present_total_stock_value=$row_get_stock_month_end_balance["opening_balance"];
                    }
                    $opening_stock_value=(int)$row_get_stock_opening_balance["opening_balance"];
                    

                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Opening Stock Value</b></h6></td>
                                    <td><h6><b>'.$opening_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Sales Amount</td>
                        //             <td>'.$total_sales_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Repair Amount</td>
                        //             <td>'.$total_repair_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Amount</td>
                        //             <td>'.$total_purchase_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Purchase Paid Amount</td>
                        //             <td>'.$total_purchase_paid_amount.'</td>
                        //         </tr>';
                        //         $x++;
                        // echo   '<tr align="center">
                        //             <td>'.$x.'</td>
                        //             <td align="left">Total Return Amount</td>
                        //             <td>'.$total_return_amount.'</td>
                        //         </tr>'; 
                        //         $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Present Stock Value</b></h6></td>
                                    <td><h6><b>'.$present_total_stock_value.'</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Cost of Sales = (Opening Stock Value + Total Purchase Amount - Present Stock Value)</b></h6></td>
                                    <td><h6><b>';
                                    echo ($opening_stock_value + $total_purchase_amount ) - $present_total_stock_value;
                        echo       '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Total Income = (Total Sales Amount + Total Repair Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_sales_amount + $total_repair_amount;
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Liablity Gained by Purchase = (Total Purchase Amount - Total Purchase Paid Amount)</b></h6></td>
                                    <td><h6><b>';
                                    echo $total_purchase_amount - $total_purchase_paid_amount;
                        echo        '</b></h6></td>
                                </tr>';
                        echo   '<tr align="center">
                                    <td>'.$x.'</td>
                                    <td align="left"><h6><b>Gross Profit Of the Month = (Total Income - (Opening Stock Value + Total Purchase Amount - Present Stock Value))</b></h6></td>
                                    <td><h6><b>';
                                    echo ($total_sales_amount + $total_repair_amount) - ($opening_stock_value + $total_purchase_amount - $present_total_stock_value );
                        echo        '</b></h6></td>
                                </tr>';
                                $x++;
                        echo   '</tr>  
                    </tbody>
                </table>
              </div>';
    }
     if($_GET["frompage"]=="generate_categorywise_product_analysis_report")
    {   
        $categoryid=$_GET["ajax_category_id"];
                    echo '<div class="table-responsive">
                            <table class="table table-striped table-bordered " id="check">
                                <thead>
                                    <tr align="center">
                                        <th>#</th>
                                        <th>Model ID</th>
                                        <th>Model Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Specification</th>
                                        <th>Barcode</th>                                                 
                                    </tr>
                                </thead>
                                <tbody>';
                                $sql_get_model_details="SELECT * FROM model WHERE catid='$categoryid' ORDER BY modelno  ASC";
                                $result_model_details=mysqli_query($con,$sql_get_model_details) or die ("Error in getting model details".mysqli_error($con));
                                $x=1;
                                while($row_get_model_details=mysqli_fetch_assoc($result_model_details))
                                {   //get brand name
                                    $sql_get_brand="SELECT * FROM brand WHERE brandid='$row_get_model_details[brandid]'";
                                    $result_get_brand=mysqli_query($con,$sql_get_brand) or die ("Error in get Category".mysqli_error($con));
                                    $row_get_brand=mysqli_fetch_assoc($result_get_brand);
                                    //get category name
                                    $sql_get_category="SELECT catname FROM category WHERE catid='$row_get_model_details[catid]'";
                                    $result_get_category=mysqli_query($con,$sql_get_category) or die ("Error in get Category".mysqli_error($con));
                                    $row_get_category=mysqli_fetch_assoc($result_get_category);
                                    echo   '<tr align="center">
                                                <td>'.$x.'</td>
                                                <td>'.$row_get_model_details["modelno"].'</td>
                                                <td>'.$row_get_model_details["modelname"].'</td>
                                                <td>'.$row_get_category["catname"].'</td> 
                                                <td>'.$row_get_brand["brandname"].'</td>
                                                <td>'.$row_get_model_details["specification"].'</td> 
                                                <td>'.$row_get_model_details["barcode"].'</td>                                             
                                            </tr>';
                                        $x++ ;
                                }    
                   echo         '</tbody>
                            </table>
                          </div>';
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