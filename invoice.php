<!--Header -->
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
// page priviledge
if($system_user_type=="Manager" || $system_user_type=="Branch Manager" || $system_user_type=="Cashier" || $system_user_type=="Sales Person" || $system_user_type=="Technician" )
{
?>
<!-- //Header -->

<!-- //Header -->
<?php
include("config.php");
?>
<script type="text/javascript">
    function generate_sales_invoice()
    {
        let salesid = document.getElementById("txt_salesid").value;

        if(salesid!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("sales_invoice").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=generate_sales_invoice&ajax_sales_id=" + salesid , true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("sales_invoice").innerHTML='';
        }
    }
</script>
<!-- print sales invoice start-->
<script type="text/javascript">
    function sales_invoice_print()
    {
        let salesid = document.getElementById("txt_salesid").value;
        var url="print_invoice.php?print=invoice.php&option=sales_invoice&print_salesid=" + salesid ;
        window.open(url,"_blank");
    }
</script>
<!-- print sales invoice end-->
<script type="text/javascript">
    function generate_repair_invoice()
    {
        let repairid = document.getElementById("txt_repairid").value;

        if(repairid!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("repair_invoice").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=generate_repair_invoice&ajax_repair_id=" + repairid , true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("repair_invoice").innerHTML='';
        }
    }
</script>
<!-- print repair invoice start-->
<script type="text/javascript">
    function repair_invoice_print()
    {
        let repairid = document.getElementById("txt_repairid").value;
        var url="print_invoice.php?print=invoice.php&option=repair_invoice&print_repairid=" + repairid ;
        window.open(url,"_blank");
    }
</script>
<!-- print repair invoice end-->
<?php
if(isset($_GET["page"]))
{
    echo '<body>';
}
else
{
    if($_GET["option"]=="sales_invoice")
    {
        echo '<body onload="generate_sales_invoice()">';
    }
    if($_GET["option"]=="repair_invoice")
    {
        echo '<body onload="generate_repair_invoice()">';
    }
}
if(isset($_GET["option"]))
	{
		if($_GET["option"]=="sales_invoice" )	
		{ 				
			?>
				<div class="col-lg-12">
				                <div class="card">
				                    <div class="card-body">
				                    	<div class="form-group col-md-6" >
                                            <?php
                                            if(isset($_GET["print"]))
                                            {

                                            }
                                            else{
                                            ?>
                                            <button type="button"  name="btn_print_sales_invoice" id="btn_print_sales_invoice" class="btn btn-success" tabindex="2" onclick="sales_invoice_print()"> <i class="fa fa-print"></i> Print</button>
                                            <a href="index.php?page=sales.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                            <?php
                                            }
                                            ?>
                                        </div>
		                    	
											<div class="form-group col-md-6">
												<?php
                                                if(isset($_GET["print_salesid"]))
                                                {
                                                     echo '<input type="hidden" name="txt_salesid" id="txt_salesid" value="'.$_GET["print_salesid"].'">';
                                                }
                                                else
                                                {
                                                ?>
                                                    <input type="hidden" name="txt_salesid" id="txt_salesid" class="form-control" value="<?php echo $_GET["salesid"] ?>">
                                                <?php
                                                }
                                                ?>
											</div>
											<div id="sales_invoice"></div>
			                            </div>
		                            </div>    	 
					              </div>
					             <script type="text/javascript">
					              generate_sales_invoice('sales_invoice');  
					            </script>
					         <?php
		}
		elseif($_GET["option"]=="repair_invoice")	
		{ 				
			?>		<div class="col-lg-12">
	                    <div class="card">
	                        <div class="card-body">
	                            <div class="form-group col-md-6" >
	                                        <?php
	                                        if(isset($_GET["print"]))
	                                        {

	                                        }
	                                        else{
	                                        ?>
	                                        <button type="button"  name="btn_print_repair_invoice" id="btn_print_repair_invoice" class="btn btn-success" tabindex="2" onclick="repair_invoice_print()"> <i class="fa fa-print"></i> Print</button>
	                                        <a href="index.php?page=repair.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
	                                        <?php
	                                        }
	                                        ?>
	                                    </div>
	                                    <div class="form-group col-md-6">
                                            <?php
                                            if(isset($_GET["print_repairid"]))
                                            {
                                                 echo '<input type="hidden" name="txt_repairid" id="txt_repairid" value="'.$_GET["print_repairid"].'">';
                                            }
                                            else
                                            {
                                            ?>
                                                <input type="hidden" name="txt_repairid" id="txt_repairid" class="form-control" value="<?php echo $_GET["repairid"] ?>">
                                            <?php
                                            }
                                            ?>
                                        </div>
                                      <div id="repair_invoice"></div>
	                                </div>
	                              </div>
	                            </div>
	                     <script type="text/javascript">
	                      generate_repair_invoice('repair_invoice');  
	                    </script>
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