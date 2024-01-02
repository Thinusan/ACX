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
<!-- product analysis start-->
<!-- print overall product analysis start-->
<script type="text/javascript">
    function product_analysis_overoall_report_print()
    {
        var url="print.php?print=product_analysis_reports.php&option=product_analysis_overoall";
        window.open(url,"_blank");
    }
</script>
<!-- print overall product analysis end-->
<script type="text/javascript">
    function generate_categorywise_product_analysis_report()
    {
        let categoryid=document.getElementById("txt_category").value;
        if(categoryid!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("product_analysis_categorywise_report").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_categorywise_product_analysis_report&ajax_category_id=" + categoryid , true);
            xmlhttp.send();
        }
        else
        {   
            document.getElementById("product_analysis_categorywise_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Category Should Be Selected ! -</h3></center></div>';
        }
    }
</script>
<!-- print categorywise product analysis start-->
<script type="text/javascript">
    function product_analysis_categorywise_report_print()
    {   
        let categoryid=document.getElementById("txt_category").value;
        var url="print.php?print=product_analysis_reports.php&option=product_analysis_categorywise&print_category_id=" + categoryid;
        if(categoryid!="")
        {
            window.open(url,"_blank");
        }
        else
        {
            document.getElementById("product_analysis_categorywise_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Category Should Be Selected ! -</h3></center></div>';
        }
    }
</script>
<!-- print categorywise product analysis end-->
<!-- product analysis end-->
<?php
if(isset($_GET["page"]))
{
    echo '<body>';
}
else
{
    if($_GET["option"]=="product_analysis_overoall")
    {
        echo '<body onload="generate_overall_product_analysis_report()">';
    }
    else if($_GET["option"]=="product_analysis_categorywise")
    {
        echo '<body onload="generate_categorywise_product_analysis_report()">';
    }
}
// option directory start

    if(isset($_GET["option"]))
        // overall daily product analysis
    {    if($_GET["option"]=="product_analysis_overoall")
        {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Overall Product Analysis</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">                                                  
                                                <div class="form-group col-md-6" >
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {

                                                    }
                                                    else{
                                                    ?>
                                                    <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="product_analysis_overoall_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {
                                                        echo '<center>DATE :'.date('y-m-d').'</center>';
                                                    }
                                                    else{}
                                                    ?>
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="product_analysis_overoall_report">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="check">
                                <thead>
                                    <tr align="center">
                                        <th>#</th>
                                        <th>Model ID</th>
                                        <th>Model Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Specification</th>
                                        <th>Barcode</th> 
                                        <th>Sales Price</th>                                                
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_get_model_details="SELECT * FROM model  ORDER BY modelno  ASC";
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
                                    //get model price
                                    $sql_get_modelprice_details="SELECT * FROM modelprice WHERE modelno='$row_get_model_details[modelno]' AND enddate IS NULL";
                                    $result_modelprice_details=mysqli_query($con,$sql_get_modelprice_details) or die ("Error in getting modelprice details".mysqli_error($con));
                                    $row_get_modelprice_details=mysqli_fetch_assoc($result_modelprice_details);
                                    echo   '<tr align="center">
                                                <td>'.$x.'</td>
                                                <td>'.$row_get_model_details["modelno"].'</td>
                                                <td>'.$row_get_model_details["modelname"].'</td>
                                                <td>'.$row_get_category["catname"].'</td> 
                                                <td>'.$row_get_brand["brandname"].'</td>
                                                <td>'.$row_get_model_details["specification"].'</td> 
                                                <td>'.$row_get_model_details["barcode"].'</td>';
                                                if($row_get_modelprice_details["modelno"]!="")
                                                {
                                                    echo '<td>'.$row_get_modelprice_details["salesprice"].'</td>';
                                                }
                                                else
                                                {
                                                    echo '<td>Model Blocked</td>';
                                                }                                         
                                    echo    '</tr>';
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
            <?php
        }
        else if($_GET["option"]=="product_analysis_categorywise")
        {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Categorywise Product Analysis Report</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">
                                                <div class="form-group col-md-6" >
                                                <?php
                                                    if(isset($_GET["print_category_id"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_category" id="txt_category" value="'.$_GET["print_category_id"].'">';
                                                        $sql_get_category_name="SELECT catname FROM category WHERE catid='$_GET[print_category_id]'";
                                                        $result_get_category_name=mysqli_query($con,$sql_get_category_name) or die ("Error in get Model".mysqli_error($con));
                                                        $row_get_category_name=mysqli_fetch_assoc($result_get_category_name);
                                                        echo '<center>CATEGORY : '.$row_get_category_name["catname"].'</center>';
                                                    }
                                                    else{
                                                    ?>
                                                    <label>Category</label>
                                                        <select  name="txt_category" id="txt_category" class="form-control" required>
                                                            <?php
                                                                echo'<option selected disabled hidden value="">Select Category Name</option>';
                                                                $sql_get_category_name="SELECT * FROM category";
                                                                $result_get_category_name=mysqli_query($con,$sql_get_category_name) or die ("Error in get Model".mysqli_error($con));
                                                                while ($row_get_category_name=mysqli_fetch_assoc($result_get_category_name)) 
                                                                {
                                                                    echo '<option value="'.$row_get_category_name["catid"].'">'.$row_get_category_name["catname"].'</option>';
                                                                }
                                                            ?>
                                                        <select>
                                                    <?php
                                                    }
                                                ?>    
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {
                                                        echo '<center>DATE :'.date('y-m-d').'</center>';
                                                    }
                                                    else{}
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-6" >
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {

                                                    }
                                                    else{
                                                    ?>
                                                    <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_categorywise_product_analysis_report()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                    <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="product_analysis_categorywise_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="product_analysis_categorywise_report"></div>
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