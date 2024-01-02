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
    $system_user_type="Guest";
}
include("config.php");
?>
<!--Header -->

<!-- //Header -->
<!-- customer repair status check start-->
<script type="text/javascript">
    function customer_check_repair_status()
    {
        let nicno=document.getElementById("txt_nicno").value;
        let repairid=document.getElementById("txt_repair_id").value;

        if(nicno!="" & repairid!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("customer_check_repair_status").innerHTML=xmlhttp.responseText.trim();
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=customer_check_repair_status&ajax_nicno=" + nicno + "&ajax_repairid=" + repairid , true);
            xmlhttp.send();
        }
        else
        {   
            document.getElementById("customer_check_repair_status").innerHTML='<div class="alert alert-danger" role="alert"><center><h3>- NIC No or Repair Reference Number cannot be Empty ! -</h3></center></div>';
        }
    }
</script>
<!-- customer repair status check end-->
<?php
if(isset($_GET["option"]))
    {    if($_GET["option"]=="customer_check_repair_status")
        {
            ?>
            <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <center><h4 class="card-title">Repair Status</h4></center>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">                                                  
                                                <div class="form-group col-md-6">
                                                    
                                                    <label>Enter Your NIC Number</label>
                                                    <input type="text"  name="txt_nicno" id="txt_nicno" class="form-control" onblur="nicnumber('txt_nicno')" required>
                                                   
                                                </div>
                                                <div class="form-group col-md-6">
                                                    
                                                    <label>Enter Repair Reference Number</label>
                                                    <input type="text"  name="txt_repair_id" id="txt_repair_id" class="form-control" required>
                                                    
                                                </div>
                                                <div class="form-group col-md-6" >
                                                    
                                                    <button type="button"  name="btn_check_status" id="btn_check_status" class="btn btn-success" tabindex="2" onclick="customer_check_repair_status()" > <i class="fa fa-check"></i> Check</button>
                                                   
                                                </div>
                                        </div>         
                                    </form>
                                </div>
                            <div id="customer_check_repair_status"></div>
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
?>