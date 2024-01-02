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
<!-- Header -->
<!-- business reports start-->
<script type="text/javascript">
    function generate_overall_daily_business_report()
    {
        let today = new Date();
        let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

        if(date!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("daily_business_overall_report").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_overall_daily_business_report&ajax_today_date=" + date , true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("daily_business_overall_report").innerHTML='';
        }
    }
</script>
<!-- print overall daily business report start-->
<script type="text/javascript">
    function daily_business_overall_report_print()
    {
        let today = new Date();
        let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        var url="print.php?print=business_reports.php&option=daily_business_overall&print_today_date=" + date ;
        window.open(url,"_blank");
    }
</script>
<!-- print overall daily business report end-->
<script type="text/javascript">
    function generate_branchwise_daily_business_report()
    {
        let today = new Date();
        let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        let branchid=document.getElementById("txt_branch").value;
        if(date!="" & branchid!="" )
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("branchwise_daily_business_report").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_branchwise_daily_business_report&ajax_today_date=" + date + "&ajax_branchid=" + branchid , true);
            xmlhttp.send();
        }
        else
        {   
            document.getElementById("branchwise_daily_business_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Branch Should Be Selected ! -</h3></center></div>';
        }
    }
</script>
<!-- print branchwise daily business report start-->
<script type="text/javascript">
    function branchwise_daily_business_report_print()
    {
        let today = new Date();
        let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        let branchid=document.getElementById("txt_branch").value;
        var url="print.php?print=business_reports.php&option=daily_business_branchwise&print_today_date=" + date + "&print_branch_id=" + branchid;
        if(date!="" & branchid!="" )
        {
            window.open(url,"_blank");
        }
        else
        {
            document.getElementById("branchwise_daily_business_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Branch Should Be Selected ! -</h3></center></div>';
        }
    }
</script>
<!-- print branchwise daily business report end-->
<script type="text/javascript">
    function generate_overall_monthly_business_report()
    {
        let get_month = document.getElementById("txt_month").value;
        let month = new Date(get_month);
        let start_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+1;
        let end_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+new Date(month.getFullYear(), month.getMonth()+1, 0).getDate();
        if(start_date!="" & end_date!="" & get_month!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("monthly_business_overall_report").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_overall_monthly_business_report&ajax_start_date=" + start_date + "&ajax_end_date=" + end_date , true);
            xmlhttp.send();
        }
        else
        {
           document.getElementById("monthly_business_overall_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Month Should Be Selected ! -</h3></center></div>';
        }
    }
</script>
<!-- print overall monthly business report start-->
<script type="text/javascript">
    function monthly_business_overall_report_print()
    {
        let get_month = document.getElementById("txt_month").value;
        let month = new Date(get_month);
        let start_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+1;
        let end_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+new Date(month.getFullYear(), month.getMonth()+1, 0).getDate();
        var url="print.php?print=business_reports.php&option=monthly_business_overall&print_start_date=" + start_date + "&print_end_date=" + end_date;
        if(get_month!="")
        {
            window.open(url,"_blank");  
        }else
        {
            document.getElementById("monthly_business_overall_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Month Should Be Selected ! -</h3></center></div>';
        }       
    }
</script>
<!-- print overall monthly business report end-->
<script type="text/javascript">
    function generate_branchwise_monthly_business_report()
    {
        let get_month = document.getElementById("txt_month").value;
        let month = new Date(get_month);
        let start_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+1;
        let end_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+new Date(month.getFullYear(), month.getMonth()+1, 0).getDate();
        let branchid=document.getElementById("txt_branch").value;
        if(start_date!="" & end_date!="" & branchid!="" & get_month!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("monthly_business_branchwise_report").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxreportpage.php?frompage=generate_branchwise_monthly_business_report&ajax_start_date=" + start_date + "&ajax_end_date=" + end_date + "&ajax_branchid=" + branchid, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("monthly_business_branchwise_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Branch OR Month Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- print branchwise monthly business report start-->
<script type="text/javascript">
    function monthly_business_branchwise_report_print()
    {
        let get_month = document.getElementById("txt_month").value;
        let month = new Date(get_month);
        let start_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+1;
        let end_date = month.getFullYear()+'-'+(month.getMonth()+1)+'-'+new Date(month.getFullYear(), month.getMonth()+1, 0).getDate();
        let branchid=document.getElementById("txt_branch").value;
        var url="print.php?print=business_reports.php&option=monthly_business_branchwise&print_start_date=" + start_date + "&print_end_date=" + end_date + "&print_branch_id=" + branchid;
        if(start_date!="" & end_date!="" & branchid!="" & get_month!="")
        {
            window.open(url,"_blank");
        }
        else
        {
            document.getElementById("monthly_business_branchwise_report").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- Branch OR Month Fields Cannot be Empty ! -</h3></center></div>'; 
        }
    }
</script>
<!-- business reports end-->
<?php
if(isset($_GET["page"]))
{
    echo '<body>';
}
else
{
    if($_GET["option"]=="daily_business_overall")
    {
        echo '<body onload="generate_overall_daily_business_report()">';
    }
    else if($_GET["option"]=="daily_business_branchwise")
    {
        echo '<body onload="generate_branchwise_daily_business_report()">';
    }
    elseif($_GET["option"]=="monthly_business_overall")
    {
        echo '<body onload="generate_overall_monthly_business_report()">';
    }
    elseif($_GET["option"]=="monthly_business_branchwise")
    {
        echo '<body onload="generate_branchwise_monthly_business_report()">';
    }
}
// option directory start
    if(isset($_GET["option"]))   
    {   // overall daily business report 
        if($_GET["option"]=="daily_business_overall" & $system_user_type=="Manager")
            {
                ?>
                <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <center><h4 class="card-title">Overall Daily Business Report - <?php echo date('y-m-d') ?></h4></center>
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
                                                        <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="daily_business_overall_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <?php
                                                        if(isset($_GET["print_today_date"]))
                                                        {
                                                            echo '<center>DATE :'.$_GET["print_today_date"].'</center>';
                                                        }
                                                        else{}
                                                        ?>
                                                    </div>
                                            </div>         
                                        </form>
                                    </div>
                                <div id="daily_business_overall_report"></div>
                            </div>
                        </div>
                    </div>
                <script type="text/javascript">
                  generate_overall_daily_business_report('daily_business_overall_report');  
                </script>

                <?php
            }
            elseif($_GET["option"]=="daily_business_overall" & $system_user_type!="Manager")
            {
                echo'<div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                            <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                        </div>
                    </div>';
            }
            // daily business branchwise report
            else if($_GET["option"]=="daily_business_branchwise")
            {
                ?>
                <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <center><h4 class="card-title">Branchwise Daily Business Report - <?php echo date("Y-m-d") ?></h4></center>
                                    <div class="basic-form">
                                        <form method="POST" action="">
                                            <div class="form-row">  
                                                    <div class="form-group col-md-6">
                                                        <?php
                                                        if(isset($_GET["print_today_date"]))
                                                        {
                                                            echo '<center>Date : '.$_GET["print_today_date"].'</center>';
                                                        }
                                                        else
                                                        {
                                                        ?>
                                                            <label>Date</label>
                                                            <input type="date" class="form-control" value="<?php echo date("Y-m-d") ?>" readonly>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
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
                                                                        {   echo'<option value="" disabled selected hidden>Select Branch Name</option>';
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
                                                    <div class="form-group col-md-6" >
                                                        <?php
                                                        if(isset($_GET["print"]))
                                                        {

                                                        }
                                                        else{
                                                        ?>
                                                        <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_branchwise_daily_business_report()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                        <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="branchwise_daily_business_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    
                                            </div>         
                                        </form>
                                    </div>
                                <div id="branchwise_daily_business_report"></div>
                            </div>
                        </div>
                    </div>
                <?php
            } // monthly business overall
            elseif($_GET["option"]=="monthly_business_overall" & $system_user_type=="Manager")
            {
                ?>
                <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <center><h4 class="card-title">Overall Monthly Business Report</h4></center>
                                    <div class="basic-form">
                                        <form method="POST" action="">
                                            <div class="form-row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <?php
                                                        if(isset($_GET["print"]))
                                                        {
                                                            echo '<input type="hidden" name="txt_month" id="txt_month" value="'.$_GET["print_start_date"].'">';
                                                            $month=date('F',strtotime($_GET["print_start_date"]));
                                                            $year=date('Y',strtotime($_GET["print_start_date"]));
                                                            echo '<center><h5>MONTH : '.$month.' / '.$year.'</h5></center>';
                                                        }
                                                        else
                                                        {
                                                        ?>
                                                            <label>Month</label>
                                                            <input type="month" class="form-control" id="txt_month" name="txt_month" required>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <?php
                                                        if(isset($_GET["print"]))
                                                        {
                                                            echo '<center><h5>Date : '.date('Y-m-d').'</h5></center>';
                                                        }
                                                        else
                                                        {
                                                        }
                                                        ?>
                                                    </div>
                                            </div>
                                            <div class="form-row col-md-12 ">                                                  
                                                    <div class="form-group col-md-12" >
                                                        <?php
                                                        if(isset($_GET["print"]))
                                                        {
                                                            
                                                        }
                                                        else{
                                                        ?>
                                                        <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_overall_monthly_business_report()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                        <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="monthly_business_overall_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                            </div>         
                                        </form>
                                    </div>
                                <div id="monthly_business_overall_report"></div>
                            </div>
                        </div>
                    </div>
                <?php
                
            }
            elseif($_GET["option"]=="monthly_business_overall" & $system_user_type!="Manager")
            {
                 echo'<div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                            <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                        </div>
                    </div>';
            }
            // monthly business overall
            elseif($_GET["option"]=="monthly_business_branchwise")
            {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Branchwise Monthly Business Report</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row col-md-12">
                                                <div class="form-group col-md-6">
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
                                                                        {   echo'<option value="" disabled selected hidden>Select Branch Name</option>';
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
                                                <div class="form-group col-md-6">
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {
                                                        echo '<input type="hidden" name="txt_month" id="txt_month" value="'.$_GET["print_start_date"].'">';
                                                        $month=date('F',strtotime($_GET["print_start_date"]));
                                                        $year=date('Y',strtotime($_GET["print_start_date"]));
                                                        echo '<center>MONTH : '.$month.' / '.$year.'</center>';
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                        <label>Month</label>
                                                        <input type="month" class="form-control" id="txt_month" name="txt_month" required>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>                                                 
                                                <div class="form-group col-md-12" >
                                                    <?php
                                                    if(isset($_GET["print"]))
                                                    {
                                                        
                                                    }
                                                    else{
                                                    ?>
                                                    <button type="button"  name="btn_generate_report" id="btn_generate_report" class="btn btn-primary" tabindex="2" onclick="generate_branchwise_monthly_business_report()"> <i class="far fa-file-alt"></i> Generate Report</button>
                                                    <button type="button"  name="btn_print_report" id="btn_print_report" class="btn btn-success" tabindex="2" onclick="monthly_business_branchwise_report_print()"> <i class="fa fa-print"></i> Print</button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="monthly_business_branchwise_report"></div>
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