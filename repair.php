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
				<li>Repair</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_repair"]))
{
    $sql_insert_repair="INSERT INTO repair(repairid,date,custid,enterby,pay_status,estdate,branchid)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_repair_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_customer_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_enterby"])."',
                                '".mysqli_real_escape_string($con,"Pending")."',
                                '".mysqli_real_escape_string($con,$_POST["txt_estimated_date"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."')";
    $result_insert_repair=mysqli_query($con,$sql_insert_repair) or die("Error in inserting in repair".mysqli_error($con));
    if($result_insert_repair)
    {    //get customer name
            $sql_get_customer="SELECT tpno FROM customer WHERE custid='$_POST[txt_customer_id]'";
            $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
            $row_get_customer=mysqli_fetch_assoc($result_get_customer);     

        //alert customer about repair
            $user = "94769669804";
            $password = "3100";
            $text = urlencode("ACX Phone Shop, Your device repair reference ID is ".$_POST["txt_repair_id"]." and estimated date is ".$_POST["txt_estimated_date"]);
            $to = "94".$row_get_customer["tpno"];
             
            $baseurl ="http://www.textit.biz/sendmsg";
            $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
            $ret = file($url);
             
            $res= explode(":",$ret[0]);

            unset($_SESSION["REPAIR_ID"]);
            echo '<script>
                    alert("Successful Added!!");
                    window.location.href="index.php?page=repair.php&option=view";
                </script>';
    }
}
if(isset($_POST["btn_save_repair_item"]))
{   $sql_get_repair_item_id="SELECT * FROM repairitem Where repairid='$_POST[txt_repair_item_id]'";
    $result_get_repair_item_id=mysqli_query($con,$sql_get_repair_item_id) or die ("Error in Getting Repair ID".mysqli_error($con));
    $row_get_repair_item_id=mysqli_fetch_assoc($result_get_repair_item_id);

    if($row_get_repair_item_id["imei_number"]!=$_POST["txt_imei_number"]){

        $sql_insert_repair_item="INSERT INTO repairitem(repairid,imei_number,device_name,description,repair_status)
                            VALUES('".mysqli_real_escape_string($con,$_POST["txt_repair_item_id"])."',
                                    '".mysqli_real_escape_string($con,$_POST["txt_imei_number"])."',
                                    '".mysqli_real_escape_string($con,$_POST["txt_device_name"])."',
                                    '".mysqli_real_escape_string($con,$_POST["txt_description"])."',
                                    '".mysqli_real_escape_string($con,"Pending")."')";
        $result_insert_repair_item=mysqli_query($con,$sql_insert_repair_item) or die("Error in inserting in repair item".mysqli_error($con));
        if($result_insert_repair_item)
        {   
            $_SESSION["REPAIR_ID"]=$_POST["txt_repair_item_id"];
            echo '<script>
                    alert("Successful Added!!");
                    window.location.href="index.php?page=repair.php&option=add";
                </script>';
        }
    }
    else
    {
            echo '<script>
            alert("Same device already added.Check IMEI Number");
            window.location.href="index.php?page=repair.php&option=add";
            </script>';
    }
}
if(isset($_POST["btn_edit_repair"]))
{
    $sql_update_repair="UPDATE repair SET 
                        date='".mysqli_real_escape_string($con,$_POST["txt_date"])."',
                        custid='".mysqli_real_escape_string($con,$_POST["txt_customer_id"])."',
                        estdate='".mysqli_real_escape_string($con,$_POST["txt_estimated_date"])."',
                        branchid='".mysqli_real_escape_string($con,$_POST["txt_branch_id"])."'
                        WHERE repairid='".mysqli_real_escape_string($con,$_POST["txt_repair_id"])."'";
     $result_update_repair=mysqli_query($con,$sql_update_repair) or die("Error in updating in repair".mysqli_error($con));
    if($result_update_repair)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=repair.php&option=view";
            </script>';
    }
}
if(isset($_POST["btn_cancel_repair"]))
{
    $sql_repairitem_delete="DELETE FROM repairitem WHERE repairid='$_SESSION[REPAIR_ID]'";
    $result_repairitem_delete=mysqli_query($con,$sql_repairitem_delete)or die("Error in repairitemitem delete".mysqli_error($con));
    if($result_repairitem_delete)
    {   unset($_SESSION["REPAIR_ID"]);
        echo '<script>
        alert("Repair Cancelled!!");
        window.location.href="index.php?page=repair.php&option=view";
        </script>';
    }
    
}
if(isset($_POST["btn_update_repair_item_status_amount"]))
{   
    $sql_update_repair_status="UPDATE repairitem SET
                                        repair_status='".mysqli_real_escape_string($con,"Done")."',
                                        rep_amount='".mysqli_real_escape_string($con,$_POST["txt_amount"])."'
                                        WHERE repairid='".mysqli_real_escape_string($con,$_POST["txt_repair_item_id"])."' AND imei_number='".mysqli_real_escape_string($con,$_POST["txt_imei_number"])."'";
    $result_update_repair_status=mysqli_query($con,$sql_update_repair_status) or die("Error in updating in repair status".mysqli_error($con));
    if($result_update_repair_status)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=repair.php&option=fullview&repair_id='.$_POST["txt_repair_item_id"].'";
            </script>';
    }
    
}
if(isset($_POST["btn_update_repair_payment"]))
{   echo '<input type="hidden" id="txt_repairid" value="'.$_POST["txt_repair_id"].'">';
    $sql_update_repair_payment="UPDATE repair SET
                                        pay_status='".mysqli_real_escape_string($con,"Paid")."',
                                        payment_date='".mysqli_real_escape_string($con,$_POST["txt_payment_date"])."',
                                        amount='".mysqli_real_escape_string($con,$_POST["txt_repair_payment"])."'
                                        WHERE repairid='".mysqli_real_escape_string($con,$_POST["txt_repair_id"])."'";
    $result_update_repair_payment=mysqli_query($con,$sql_update_repair_payment) or die("Error in updating in repair status".mysqli_error($con));
    if($result_update_repair_payment)
    {
       echo '<script>
                alert("Successful Added!!");
                let repairid=document.getElementById("txt_repairid").value;
                window.location.href="index.php?page=invoice.php&option=repair_invoice&repairid=" + repairid;
            </script>';
    }
    
}
?>
<script type="text/javascript">
    function update_repair_payment()
    {
        let repair_id=document.getElementById("btn_repair_payment").value;
        if(repair_id!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("payment").innerHTML =xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=update_repair_payment&ajax_repair_id=" + repair_id, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("payment").innerHTML='';
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
                                <h4 class="card-title">Repair</h4>
                                    <div class="basic-form">
                                        <form method="POST" action="" autocomplete="off">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                <?php 
                                                $sql_create_repair_item_id="SELECT repairid FROM repairitem ORDER BY repairid DESC LIMIT 1";
                                                $result_create_repair_item_id=mysqli_query($con,$sql_create_repair_item_id) or die ("Error in Creating id".mysqli_error($con));
                                                if(mysqli_num_rows($result_create_repair_item_id)==1)
                                                {
                                                    $row_create_repair_item_id=mysqli_fetch_assoc($result_create_repair_item_id);
                                                    $repair_item_id=++$row_create_repair_item_id["repairid"];
                                                }
                                                else
                                                {
                                                    $repair_item_id="REP001";
                                                }
                                                ?>
                                                <label>Repair ID</label>
                                                <input type="text" name="txt_repair_item_id" id="txt_repair_item_id" class="form-control" value="<?php echo (isset($_SESSION["REPAIR_ID"])) ? $_SESSION["REPAIR_ID"] : $repair_item_id; ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>IMEI Number</label>
                                                    <input type="text" autofocus name="txt_imei_number" id="txt_imei_number" class="form-control" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Device Name</label>
                                                    <input type="text" class="form-control" name="txt_device_name" id="txt_device_name" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Description</label>
                                                    <input type="text" name="txt_description" id="txt_description" class="form-control" placeholder="Description" required>
                                                </div>
                                                <div>
                                                    <button type="submit" name="btn_save_repair_item" id="btn_save_repair_item" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                                                    <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                </div>
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
                                    <h4 class="card-title">Repair Item Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="check">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Device Name</th>
                                                    <th>IMEI Number</th>
                                                    <th>Description</th>
                                                    <th>Action</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                if(isset($_SESSION["REPAIR_ID"])){
                                                
                                                    $sql_get_repair_item_details="SELECT * FROM repairitem WHERE repairid='$_SESSION[REPAIR_ID]'";
                                                    $result_get_repair_item_details=mysqli_query($con,$sql_get_repair_item_details) or die ("Error in getting get repair item detailsr".mysqli_error($con));
                                                    $x=1;
                                                    while($row_get_repair_item_details=mysqli_fetch_assoc($result_get_repair_item_details))
                                                    {      
                                                        echo'<tr>
                                                                <td>'.$x.'</td>
                                                                <td>'.$row_get_repair_item_details["device_name"].'</td>
                                                                <td>'.$row_get_repair_item_details["imei_number"].'</td>
                                                                <td>'.$row_get_repair_item_details["description"].'</td>
                                                                <td>
                                                                    <a href="index.php?page=repairitem.php&option=edit&repair_item_id='.$row_get_repair_item_details['repairid'].'&repair_item_imei_no='.$row_get_repair_item_details['imei_number'].'"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i> </button></a>
                                                                    <a href="index.php?page=repairitem.php&option=delete&repair_item_id='.$row_get_repair_item_details['repairid'].'&repair_item_imei_no='.$row_get_repair_item_details['imei_number'].'"><button type="button" class="btn btn-danger"><i class="fas fa-trash"></i> </button></a>
                                                                </td>
                                                            </tr>';
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
            if(isset($_SESSION["REPAIR_ID"])){
            
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
                                <h4 class="card-title">Repair details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <?php 
                                            $sql_create_repair_id="SELECT repairid FROM repair ORDER BY repairid DESC LIMIT 1";
                                            $result_create_repair_id=mysqli_query($con,$sql_create_repair_id) or die ("Error in Creating id".mysqli_error($con));
                                            if(mysqli_num_rows($result_create_repair_id)==1)
                                            {
                                                $row_create_repair_id=mysqli_fetch_assoc($result_create_repair_id);
                                                $repair_id=++$row_create_repair_id["repairid"];
                                            }
                                            else
                                            {
                                                $repair_id="REP001";
                                            }
                                            ?>
                                                <label>Repair ID</label>
                                                <input type="text" name="txt_repair_id" id="txt_repair_id" class="form-control" value="<?php echo $repair_id ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Date</label>
                                                <input type="Date" name="txt_date" id="txt_date" class="form-control" value="<?php echo date("Y-m-d") ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Customer Name</label>
                                                <select name="txt_customer_id" id="txt_customer_id" class="form-control chzn-select" required>
                                                <option>Select Customer</option>
                                                <?php
                                                    $sql_get_customer="SELECT * FROM customer";
                                                    $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Category".mysqli_error($con));
                                                    while ($row_get_customer=mysqli_fetch_assoc($result_get_customer)) 
                                                    {
                                                        echo '<option value="'.$row_get_customer["custid"].'">'.$row_get_customer["nicno"].'-'.$row_get_customer["cusname"].'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <div align="right"><a href="index.php?page=customer.php&option=add&url_id=repair" onMouseOver="style.color='red'" onMouseOut="style.color='blue'"><i class="fas fa-plus"></i> Add New Customer</a></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Estimated Date</label>
                                                <input type="Date" name="txt_estimated_date" id="txt_estimated_date" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Branch Name</label>
                                                <select name="txt_branch_id" id="txt_branch_id" class="form-control" readonly>
                                                <option value="<?php echo $row_get_branch["branchid"]?>" selected><?php echo $row_get_branch["branchname"]?></option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Enter By</label>
                                                <select name="txt_enterby" id="txt_enterby" class="form-control" readonly>
                                                <option value="<?php echo $row_get_enterby["staffid"]?>" selected><?php echo $row_get_enterby["staffname"]?></option>
                                                </select>
                                            </div>
                                        </div>
                                            <div>
                                            <button type="submit" name="btn_save_repair" id="btn_save_repair" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                            <a href="index.php?page=repair.php&option=cancel_repair"> <button type="button" name="btn_cancel_repair" id="btn_cancel_repair" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
                                    <h4 class="card-title">Repair Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=repair.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Repair ID</th>
                                                    <th>Customer</th>
                                                    <th>Estimated Date</th>
                                                    <th>Payment Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_repair_details_view="SELECT repairid,custid,estdate,pay_status FROM repair";
                                                $result_repair_details_view=mysqli_query($con,$sql_repair_details_view)or die("Error in repair details view".mysqli_error($con));
                                                while ($row_repair_details_view=mysqli_fetch_assoc($result_repair_details_view)) 
                                                {   
                                                    //get customer name
                                                    $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_repair_details_view[custid]'";
                                                    $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting customer".mysqli_error($con));
                                                    $row_get_customer=mysqli_fetch_assoc($result_get_customer);

                                                    echo '<tr>
                                                            <td>'.$row_repair_details_view["repairid"].'</td>
                                                            <td>'.$row_get_customer["cusname"].'</td>
                                                            <td>'.$row_repair_details_view["estdate"].'</td>
                                                            <td>'.$row_repair_details_view["pay_status"].'</td>
                                                            <td>
                                                                <a href="index.php?page=repair.php&option=fullview&repair_id='.$row_repair_details_view["repairid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
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
                                    <h4 class="card-title">Repair Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=repair.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Repair ID</th>
                                                    <th>Customer</th>
                                                    <th>Estimated Date</th>
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
                                                $sql_repair_details_view="SELECT repairid,custid,estdate,pay_status FROM repair WHERE branchid='$branchid'";
                                                $result_repair_details_view=mysqli_query($con,$sql_repair_details_view)or die("Error in repair details view".mysqli_error($con));
                                                while ($row_repair_details_view=mysqli_fetch_assoc($result_repair_details_view)) 
                                                {   
                                                    //get customer name
                                                    $sql_get_customer="SELECT cusname FROM customer WHERE custid='$row_repair_details_view[custid]'";
                                                    $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in getting customer".mysqli_error($con));
                                                    $row_get_customer=mysqli_fetch_assoc($result_get_customer);

                                                    echo '<tr>
                                                            <td>'.$row_repair_details_view["repairid"].'</td>
                                                            <td>'.$row_get_customer["cusname"].'</td>
                                                            <td>'.$row_repair_details_view["estdate"].'</td>
                                                            <td>'.$row_repair_details_view["pay_status"].'</td>
                                                            <td>
                                                                <a href="index.php?page=repair.php&option=fullview&repair_id='.$row_repair_details_view["repairid"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>
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
            $get_repair_id=$_GET["repair_id"];
            $sql_repair_fullview="SELECT * FROM repair WHERE repairid='$get_repair_id'";
            $result_repair_fullview=mysqli_query($con,$sql_repair_fullview)or die("Error in geting repair fullview details".mysqli_error($con));
            $row_repair_fullview=mysqli_fetch_assoc($result_repair_fullview);

            //get branch name
            $sql_get_branch="SELECT * FROM branch WHERE branchid='$row_repair_fullview[branchid]'";
            $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
            $row_get_branch=mysqli_fetch_assoc($result_get_branch);

            //get customer name
            $sql_get_customer="SELECT * FROM customer WHERE custid='$row_repair_fullview[custid]'";
            $result_get_customer=mysqli_query($con,$sql_get_customer) or die ("Error in get Customer".mysqli_error($con));
            $row_get_customer=mysqli_fetch_assoc($result_get_customer);

            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Repair Full Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <tr><th style="width: 50%">Repair ID</th><td><?php echo $row_repair_fullview["repairid"] ?></td></tr>
                                            <tr><th>Date</th><td><?php echo $row_repair_fullview["date"] ?></td></tr>
                                            <tr><th>Customer</th><td><?php echo $row_get_customer["cusname"] ?></td></tr>
                                            <tr><th>Branch</th><td><?php echo $row_get_branch["branchname"] ?></td></tr>
                                            <tr><th>Payment Status</th><td><?php echo $row_repair_fullview["pay_status"] ?></td></tr>
                                            <tr><th>Estimated Date</th><td><?php echo $row_repair_fullview["estdate"] ?></td></tr>
                                            <?php
                                                if($row_repair_fullview["pay_status"]=="Paid")
                                                {
                                                    echo '<tr><th>Payment Date</th><td>'.$row_repair_fullview["payment_date"].'</td></tr>';
                                                }
                                            ?>  
                                            <tr>
                                                <td colspan="2">
                                                    <center>
                                                        <a href="index.php?page=repair.php&option=view"> <button type="button" name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>
                                                        <?php
                                                            if($row_repair_fullview["pay_status"]=="Pending")
                                                            {
                                                                echo '<button type="button" id="btn_repair_payment" name="btn_repair_payment" class="btn btn-primary" value="'.$get_repair_id.'" tabindex="2" onclick="update_repair_payment()"><i class="fas fa-dollar"></i> Payment</button>';
                                                            }elseif($row_repair_fullview["pay_status"]=="Paid")
                                                            {
                                                                ?>
                                                                <a href="javascript:window.open('index.php?page=invoice.php&option=repair_invoice&repairid=<?php echo $get_repair_id; ?>','_blank')"><button type="button"class="btn btn-success"><i class="fas fa-print"></i> Print Invoice</button></a>
                                                                <?php
                                                            }
                                                            
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
                                    <h4 class="card-title">Repair Item Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="check">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Device Name</th>
                                                    <th>IMEI Number</th>
                                                    <th>Description</th>
                                                    <th>Repair Status</th>
                                                    <th>Action / Amount</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>                                            
                                                <?php
                                                    $sql_get_repair_item_details="SELECT * FROM repairitem WHERE repairid='$get_repair_id'";
                                                    $result_get_repair_item_details=mysqli_query($con,$sql_get_repair_item_details) or die ("Error in getting get repair item detailsr".mysqli_error($con));
                                                    $x=1;
                                                    while($row_get_repair_item_details=mysqli_fetch_assoc($result_get_repair_item_details))
                                                    {      
                                                        echo'<tr>
                                                                <td>'.$x.'</td>
                                                                <td>'.$row_get_repair_item_details["device_name"].'</td>
                                                                <td>'.$row_get_repair_item_details["imei_number"].'</td>
                                                                <td>'.$row_get_repair_item_details["description"].'</td>
                                                                <td>'.$row_get_repair_item_details["repair_status"].'</td>';

                                                                    if($row_get_repair_item_details["repair_status"]=="Pending"){
                                                                    echo '<td><a href="index.php?page=repair.php&option=update_repair_item_status_amount&repair_item_id='.$row_get_repair_item_details["repairid"].'&repair_item_imei_no='.$row_get_repair_item_details["imei_number"].'">
                                                                    <button type="button" class="btn btn-success" >Repair Done <i class="fas fa-check"></i></button></a></td>';
                                                                    }else
                                                                    {
                                                                         echo '<td>'.$row_get_repair_item_details["rep_amount"].'</td>';
                                                                    }
                                                                    
                                                            echo '</tr>';
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
            <div id="payment"></div>

            
        <?php
        }
        elseif ($_GET["option"]=="delete" & $system_user_type=="Branch Manager") 
        {
            $get_repair_id=$_GET["repair_id"];
            $sql_repair_delete="DELETE FROM repair WHERE repairid='$get_repair_id'";
            $result_repair_delete=mysqli_query($con,$sql_repair_delete)or die("Error in repair delete".mysqli_error($con));
            if($result_repair_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=repair.php&option=view";
                    </script>';
            }

		}
        else if($_GET["option"]=="delete" & $system_user_type!="Branch Manager")
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
        }
        elseif ($_GET["option"]=="cancel_repair") 
        {
            $sql_repairitem_delete="DELETE FROM repairitem WHERE repairid='$_SESSION[REPAIR_ID]'";
            $result_repairitem_delete=mysqli_query($con,$sql_repairitem_delete)or die("Error in repairitemitem delete".mysqli_error($con));
            if($result_repairitem_delete)
            {   unset($_SESSION["REPAIR_ID"]);
                echo '<script>
                alert("Repair Cancelled!!");
                window.location.href="index.php?page=repair.php&option=view";
                </script>';
            }
        }
        elseif ($_GET["option"]=="update_repair_item_status_amount") 
        {   $get_repair_id=$_GET["repair_item_id"];
            $get_imei_number=$_GET["repair_item_imei_no"];?>
             <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Device Repair Cost</h4>
                                    <div class="basic-form">
                                        <form method="POST" action="" autocomplete="off">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                <label>Repair ID</label>
                                                <input type="text" name="txt_repair_item_id" id="txt_repair_item_id" class="form-control" value="<?php echo $get_repair_id ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>IMEI Number</label>
                                                    <input type="text" name="txt_imei_number" id="txt_imei_number" value="<?php echo $get_imei_number ?>"class="form-control" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Amount</label>
                                                    <input type="text" class="form-control" name="txt_amount" id="txt_amount" required>
                                                </div>
                                            </div>
                                                <div>
                                                    <button type="submit" name="btn_update_repair_item_status_amount" id="btn_update_repair_item_status_amount" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                                                    <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                                    <a href="index.php?page=repair.php&option=fullview&repair_id=<?php echo $get_repair_id?>"> <button type="button" name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                                </div>    
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php    
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
	