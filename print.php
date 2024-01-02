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
date_default_timezone_set('Asia/Colombo');
//include("connection.php");
?>
<html>
<head>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
        function hideURLbar(){ window.scrollTo(0,1); } </script>

<!-- //custom-theme -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/JiSlider.css" rel="stylesheet"> 
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" property="" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" property="" />
<link href="css/font-awesome.css" rel="stylesheet"> 


  <script language="javascript">
    document.onmousedown=disableclick;
    status="Right Click Disabled";
    function disableclick(event)
    {
      if(event.button==2)
       {
         alert(status);
         return false;    
       }
    }
    </script>
<script type="text/javascript">
// for print button
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
    }
</script>
<body oncontextmenu="return false" style=" background-image: url('');background-repeat: no-repeat;background-size: 100% 100%;">
<!--Header -->
   
      <center><img src="print_image/report_header.jpg?<?php echo date("H:i:s"); ?>" width="100%"><hr/></center>
        <?php

           if(isset($_GET['print'])) // if get print
           {
            $filename=$_GET['print'];
            include($filename);
           }
        ?> 
<!-- //Header -->
<div><br>
 <center><button type="button" name="printpagebutton" id="printpagebutton" class="btn btn-success" onclick="printpage()"><i class="fa fa-print"></i> Print Report</button></center> <!-- print button, but it was not visible in printed paper  -->
</div>
</body>
</html>
<?php
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