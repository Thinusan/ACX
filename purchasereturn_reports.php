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
include("Config.php");
if($system_user_type=="Manager" || $system_user_type=="Branch Manager")
{
?>
<!--Header -->

<!-- //Header -->
<!-- return reports start-->
<script type="text/javascript">
function report_enable_enddate()
    {
        var select_start_date=document.getElementById("txt_start_date").value;

        if(select_start_date!="")
        {
            document.getElementById("txt_end_date").readOnly=false;
            document.getElementById("txt_end_date").value="";
            document.getElementById("txt_end_date").min=select_start_date;
        }
        else
        {
            document.getElementById("txt_end_date").readOnly=true;
            document.getElementById("txt_end_date").value="";
        }
    }
</script>
<script type="text/javascript">
    function generate_return_overall_report()
    {
        let start_date=document.getElementById("txt_start_date").value;
        let end_date=document.getElementById("txt_end_date").value;

        if(start_date!="" & end_date!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("return_overall_report").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_return_overall_report&ajax_start_date=" + start_date + "&ajax_end_date=" + end_date , true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("return_overall_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Startdate OR Enddate Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print overall return report start-->
<script type="text/javascript">
    function return_overall_report_print()
    {
        var start_date=document.getElementById("txt_start_date").value;
        var end_date=document.getElementById("txt_end_date").value;
        var url="print.php?print=purchasereturn_reports.php&option=return_overall&print_start_date=" + start_date + "&print_end_date=" + end_date;
        if(start_date!="" & end_date!="" )
        {
            window.open(url,"_blank");
        }
        else
        {
            document.getElementById("return_overall_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Startdate OR Enddate Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print overall return report end-->
<script type="text/javascript">
    function generate_return_report_branchwise()
    {
        let start_date=document.getElementById("txt_start_date").value;
        let end_date=document.getElementById("txt_end_date").value;
        let branchid=document.getElementById("txt_branch").value;

        if(start_date!="" & end_date!="" & branchid!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("return_report_branchwise").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_return_report_branchwise&ajax_start_date=" + start_date + "&ajax_end_date=" + end_date + "&ajax_branchid=" + branchid , true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("return_report_branchwise").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Startdate or Enddate or Branch Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print return report branchwise start-->
<script type="text/javascript">
    function return_report_branchwise_print()
    {
        var start_date=document.getElementById("txt_start_date").value;
        var end_date=document.getElementById("txt_end_date").value;
        var branchid=document.getElementById("txt_branch").value;
        var url="print.php?print=purchasereturn_reports.php&option=return_branchwise&print_start_date=" + start_date + "&print_end_date=" + end_date + "&print_branch_id=" + branchid;
        if(start_date!="" & end_date!="" & branchid!="" )
        {
            window.open(url,"_blank");
        }
        else
        {
            document.getElementById("return_report_branchwise").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Startdate or Enddate or Branch Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print return report branchwise end-->
<script type="text/javascript">
    function generate_return_report_supplierwise_branchwise()
    {
        let start_date=document.getElementById("txt_start_date").value;
        let end_date=document.getElementById("txt_end_date").value;
        let sup_id=document.getElementById("txt_supplier").value;
        let branchid=document.getElementById("txt_branch").value;
        if(start_date!="" & end_date!="" &  sup_id!="" & branchid!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("return_report_supplierwise_branchwise").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_return_report_supplierwise_branchwise&ajax_start_date=" + start_date + "&ajax_end_date=" + end_date + "&ajax_supplierid=" + sup_id + "&ajax_branchid=" + branchid, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("return_report_supplierwise_branchwise").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Startdate or Enddate or Branch or Supplier Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print return report supplierwise start-->
<script type="text/javascript">
    function return_report_supplierwise_branchwise_print()
    {
        var start_date=document.getElementById("txt_start_date").value;
        var end_date=document.getElementById("txt_end_date").value;
        var sup_id=document.getElementById("txt_supplier").value;
        var branchid=document.getElementById("txt_branch").value;
        var url="print.php?print=purchasereturn_reports.php&option=return_supplierwise_branchwise&print_start_date=" + start_date + "&print_end_date=" + end_date + "&print_supplier_id=" + sup_id + "&print_branch_id=" + branchid;
        if(start_date!="" & end_date!="" & sup_id!="" & branchid!="" )
        {
            window.open(url,"_blank");
        }
        else
        {
            document.getElementById("return_report_supplierwise_branchwise").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Startdate or Enddate or Branch or Supplier Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print return report supplierwise end-->
<!-- return reports end-->
<?php
if(isset($_GET["page"]))
{
    echo '<body>';
}
else
{
    if($_GET["option"]=="return_overall")
    {
        echo '<body onload="generate_return_overall_report()">';
    }
    else if($_GET["option"]=="return_branchwise")
    {
        echo '<body onload="generate_return_report_branchwise()">';
    }
    else if($_GET["option"]=="return_supplierwise_branchwise")
    {
        echo '<body onload="generate_return_report_supplierwise_branchwise()">';
    }
}
// option directory start

    if(isset($_GET["option"]))
        // overall return report
    {    if($_GET["option"]=="return_overall" & $system_user_type=="Manager")
        {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Overall Return Report</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">                                                  
                                                <div class="form-group col-md-6">
                                                    <?php
                                                    if(isset($_GET["print_start_date"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_start_date" id="txt_start_date" value="'.$_GET["print_start_date"].'">';
                                                        echo '<center>FROM : '.$_GET["print_start_date"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Start Date</label>
                                                    <input type="Date" max="<?php echo Date("Y-m-d") ?>" name="txt_start_date" id="txt_start_date" class="form-control"  onchange="report_enable_enddate()" required>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php
                                                    if(isset($_GET["print_end_date"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_end_date" id="txt_end_date" value="'.$_GET["print_end_date"].'">';
                                                        echo '<center>TO : '.$_GET["print_end_date"].'</center>';
                                                    }
                                                    else
                                                    { 
                                                    ?>
                                                    <label>End Date</label>
                                                    <input type="Date"  name="txt_end_date" id="txt_end_date" class="form-control" required readonly>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-6" >
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {

                                                    }
                                                    else{
                                                    ?>
                                                    <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_return_overall_report()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                    <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="return_overall_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="return_overall_report"></div>
                        </div>
                    </div>
                </div>
            <?php
        }
        elseif($_GET["option"]=="return_overall" & $system_user_type!="Branch Manager")
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
        }
        // branchwise puchase report
        elseif($_GET["option"]=="return_branchwise")
        {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Branchwise return Report</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row col-md-12">                                                  
                                                <div class="form-group col-md-4">
                                                    <?php
                                                    if(isset($_GET["print_start_date"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_start_date" id="txt_start_date" value="'.$_GET["print_start_date"].'">';
                                                        echo '<center>FROM : '.$_GET["print_start_date"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Start Date</label>
                                                    <input type="Date" max="<?php echo Date("Y-m-d") ?>" name="txt_start_date" id="txt_start_date" class="form-control"  onchange="report_enable_enddate()" required>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <?php
                                                    if(isset($_GET["print_end_date"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_end_date" id="txt_end_date" value="'.$_GET["print_end_date"].'">';
                                                        echo '<center>TO : '.$_GET["print_end_date"].'</center>';
                                                    }
                                                    else
                                                    { 
                                                    ?>
                                                    <label>End Date</label>
                                                    <input type="Date"  name="txt_end_date" id="txt_end_date" class="form-control" required readonly>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-4">
                                                <?php
                                                    if(isset($_GET["print_branch_id"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_branch" id="txt_branch" value="'.$_GET["print_branch_id"].'">';
                                                        $sql_get_branch_name="SELECT branchname FROM branch WHERE branchid='$_GET[print_branch_id]'";
                                                        $result_get_branch_name=mysqli_query($con,$sql_get_branch_name) or die ("Error in get Model".mysqli_error($con));
                                                        $row_get_branch_name=mysqli_fetch_assoc($result_get_branch_name);
                                                        echo '<center>BRANCH : '.$row_get_branch_name["branchname"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Branch</label>
                                                        <select  name="txt_branch" id="txt_branch" class="form-control" required>
                                                            <?php
                                                                if($system_user_type=="Manager")
                                                                {   echo'<option disabled selected hidden>Select Branch Name</option>';
                                                                    $sql_get_branch_name="SELECT * FROM branch";
                                                                    $result_get_branch_name=mysqli_query($con,$sql_get_branch_name) or die ("Error in get Model".mysqli_error($con));
                                                                    while ($row_get_branch_name=mysqli_fetch_assoc($result_get_branch_name)) 
                                                                    {
                                                                        echo '<option value="'.$row_get_branch_name["branchid"].'">'.$row_get_branch_name["branchname"].'</option>';
                                                                    }
                                                                }
                                                                else
                                                                {   
                                                                    $sql_get_branch="SELECT branchid FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
                                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                                    $row_get_branch=mysqli_fetch_assoc($result_get_branch);

                                                                    $sql_get_branch_name="SELECT branchname FROM branch WHERE branchid='$row_get_branch[branchid]'";
                                                                    $result_get_branch_name=mysqli_query($con,$sql_get_branch_name) or die ("Error in get Model".mysqli_error($con));
                                                                    $row_get_branch_name=mysqli_fetch_assoc($result_get_branch_name);

                                                                    echo '<option value="'.$row_get_branch["branchid"].'" selected>'.$row_get_branch_name["branchname"].'</option>';
                                                                }
                                                            ?>
                                                        <select>
                                                    <?php
                                                    }
                                                    ?>    
                                                </div>
                                                <div class="form-group col-md-4" >
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {

                                                    }
                                                    else{
                                                    ?>
                                                    <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_return_report_branchwise()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                    <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="return_report_branchwise_print()"> <i class="fa fa-print"></i> Print</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="return_report_branchwise"></div>
                        </div>
                    </div>
                </div>
            <?php
        }// overall supplierwise report
        elseif($_GET["option"]=="return_supplierwise_branchwise")
        {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Branchwise & supplierwise return Report</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row col-md-12">                                                  
                                                <div class="form-group col-md-3">
                                                    <?php
                                                    if(isset($_GET["print_start_date"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_start_date" id="txt_start_date" value="'.$_GET["print_start_date"].'">';
                                                        echo '<center>FROM : '.$_GET["print_start_date"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Start Date</label>
                                                    <input type="Date" max="<?php echo Date("Y-m-d") ?>" name="txt_start_date" id="txt_start_date" class="form-control"  onchange="report_enable_enddate()" required>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <?php
                                                    if(isset($_GET["print_end_date"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_end_date" id="txt_end_date" value="'.$_GET["print_end_date"].'">';
                                                        echo '<center>TO : '.$_GET["print_end_date"].'</center>';
                                                    }
                                                    else
                                                    { 
                                                    ?>
                                                    <label>End Date</label>
                                                    <input type="Date"  name="txt_end_date" id="txt_end_date" class="form-control" required readonly>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-3">
                                                <?php
                                                    if(isset($_GET["print_supplier_id"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_supplier" id="txt_supplier" value="'.$_GET["print_supplier_id"].'">';
                                                        $sql_get_supplier_name="SELECT supname FROM suppliers WHERE supid='$_GET[print_supplier_id]'";
                                                        $result_get_supplier_name=mysqli_query($con,$sql_get_supplier_name) or die ("Error in get Model".mysqli_error($con));
                                                        $row_get_supplier_name=mysqli_fetch_assoc($result_get_supplier_name);
                                                        echo '<center>SUPPLIER : '.$row_get_supplier_name["supname"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Supplier</label>
                                                        <select  name="txt_supplier" id="txt_supplier" class="form-control" required>
                                                            <option disabled selected hidden>Select Supplier Name</option>
                                                            <?php
                                                                $sql_get_supplier_name="SELECT * FROM suppliers";
                                                                $result_get_supplier_name=mysqli_query($con,$sql_get_supplier_name) or die ("Error in get Model".mysqli_error($con));
                                                                while ($row_get_supplier_name=mysqli_fetch_assoc($result_get_supplier_name)) 
                                                                {
                                                                    echo '<option value="'.$row_get_supplier_name["supid"].'">'.$row_get_supplier_name["supname"].'</option>';
                                                                }
                                                            ?>
                                                        <select>
                                                    <?php
                                                    }
                                                    ?>    
                                                </div>
                                                <div class="form-group col-md-3">
                                                <?php
                                                    if(isset($_GET["print_branch_id"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_branch" id="txt_branch" value="'.$_GET["print_branch_id"].'">';
                                                        $sql_get_branch_name="SELECT branchname FROM branch WHERE branchid='$_GET[print_branch_id]'";
                                                        $result_get_branch_name=mysqli_query($con,$sql_get_branch_name) or die ("Error in get Model".mysqli_error($con));
                                                        $row_get_branch_name=mysqli_fetch_assoc($result_get_branch_name);
                                                        echo '<center>BRANCH : '.$row_get_branch_name["branchname"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Branch</label>
                                                        <select  name="txt_branch" id="txt_branch" class="form-control" required>
                                                            <option disabled selected hidden>Select Branch Name</option>
                                                             <?php
                                                                if($system_user_type=="Manager")
                                                                {   echo'<option disabled selected hidden>Select Branch Name</option>';
                                                                    $sql_get_branch_name="SELECT * FROM branch";
                                                                    $result_get_branch_name=mysqli_query($con,$sql_get_branch_name) or die ("Error in get Model".mysqli_error($con));
                                                                    while ($row_get_branch_name=mysqli_fetch_assoc($result_get_branch_name)) 
                                                                    {
                                                                        echo '<option value="'.$row_get_branch_name["branchid"].'">'.$row_get_branch_name["branchname"].'</option>';
                                                                    }
                                                                }
                                                                else
                                                                {   
                                                                    $sql_get_branch="SELECT branchid FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
                                                                    $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
                                                                    $row_get_branch=mysqli_fetch_assoc($result_get_branch);

                                                                    $sql_get_branch_name="SELECT branchname FROM branch WHERE branchid='$row_get_branch[branchid]'";
                                                                    $result_get_branch_name=mysqli_query($con,$sql_get_branch_name) or die ("Error in get Model".mysqli_error($con));
                                                                    $row_get_branch_name=mysqli_fetch_assoc($result_get_branch_name);

                                                                    echo '<option value="'.$row_get_branch["branchid"].'" selected>'.$row_get_branch_name["branchname"].'</option>';
                                                                }
                                                            ?>
                                                        <select>
                                                    <?php
                                                    }
                                                    ?>    
                                                </div>
                                                <div class="form-group col-md-4" >
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {

                                                    }
                                                    else{
                                                    ?>
                                                    <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_return_report_supplierwise_branchwise()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                    <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="return_report_supplierwise_branchwise_print()"> <i class="fa fa-print"></i> Print</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="return_report_supplierwise_branchwise"></div>
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