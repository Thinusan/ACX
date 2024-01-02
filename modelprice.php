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
if($system_user_type=="Manager")
{
?>
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
				<li>modelprice</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_modelprice"]))
{   $model_id=$_POST["txt_model_number"];
    $sql_get_modelprice_detail="SELECT * FROM modelprice WHERE modelno='$model_id' ORDER BY startdate DESC";
    $result_get_modelprice_detail=mysqli_query($con,$sql_get_modelprice_detail)or die("Error in geting modelprice details".mysqli_error($con));
    $row_get_modelprice_detail=mysqli_fetch_assoc($result_get_modelprice_detail);
    // check startdate double entry
    if($row_get_modelprice_detail["startdate"]==$_POST["txt_start_date"])
    {
        $sql_modelprice_delete="DELETE FROM modelprice WHERE modelno='$model_id' AND startdate='".$_POST["txt_start_date"]."'";
        $result_modelprice_delete=mysqli_query($con,$sql_modelprice_delete)or die("Error in modelprice delete".mysqli_error($con));
    }

    $sql_insert_modelprice="INSERT INTO modelprice(modelno,salesprice,startdate)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_sales_price"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_start_date"])."')";
    $result_insert_modelprice=mysqli_query($con,$sql_insert_modelprice) or die("Error in inserting in modelprice".mysqli_error($con));
    if($result_insert_modelprice)
    {
        echo '<script>
                alert("Successful Added!!");
                window.location.href="index.php?page=model.php&option=fullview&model_id='.$model_id.'";
            </script>';
    }
}
if(isset($_POST["btn_edit_modelprice"]))
{   
    $model_id=$_GET["modelprice_id"];
    $sql_get_modelprice_detail="SELECT * FROM modelprice WHERE modelno='$model_id' ORDER BY startdate DESC";
    $result_get_modelprice_detail=mysqli_query($con,$sql_get_modelprice_detail)or die("Error in geting modelprice details".mysqli_error($con));
    $row_get_modelprice_detail=mysqli_fetch_assoc($result_get_modelprice_detail);

    if($row_get_modelprice_detail["salesprice"]==$_POST["txt_sales_price"]){
        echo '<script>
                alert("Same Price already added,Add different price!");
                window.location.href="index.php?page=model.php&option=fullview&model_id='.$model_id.'";
            </script>';
            die();
    }

    if($row_get_modelprice_detail["startdate"]==$_POST["txt_start_date"])
    {
        $sql_modelprice_delete="DELETE FROM modelprice WHERE modelno='$model_id' AND startdate='".$_POST["txt_start_date"]."'";
        $result_modelprice_delete=mysqli_query($con,$sql_modelprice_delete)or die("Error in modelprice delete".mysqli_error($con));

        $sql_insert_modelprice="INSERT INTO modelprice(modelno,salesprice,startdate)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_sales_price"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_start_date"])."')";
    }
    else
    {
        $sql_update_modelprice="UPDATE modelprice SET 
                        enddate='".mysqli_real_escape_string($con,date('Y.m.d',strtotime("yesterday")))."'
                            WHERE modelno='".mysqli_real_escape_string($con,$_POST["txt_model_number"])."'";
        $result_update_modelprice=mysqli_query($con,$sql_update_modelprice) or die("Error in updating in modelprice".mysqli_error($con));

        $sql_insert_modelprice="INSERT INTO modelprice(modelno,salesprice,startdate)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_sales_price"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_start_date"])."')";

    }

    $result_insert_modelprice=mysqli_query($con,$sql_insert_modelprice) or die("Error in inserting in modelprice".mysqli_error($con));

    
    if($result_insert_modelprice)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=model.php&option=fullview&model_id='.$model_id.'";
            </script>';
    }
}
	if(isset($_GET["option"]))
	{
		if($_GET["option"]=="add")
		{
			
			?>
			<div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Model Price Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Model Number</label>
                                                <input type="text" name="txt_model_number" id="txt_model_number"class="form-control" value="<?php echo $_GET["model_id"] ?>"  readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Sales Price</label>
                                                <input type="text" name="txt_sales_price" id="txt_sales_price" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Start Date</label>
                                                <input type="Date" name="txt_start_date" id="txt_start_date" class="form-control" value="<?php echo date('Y-m-d') ?>" readonly>
                                            </div>
                                        </div>
                                            <div>
                                            <button type="submit" name="btn_save_modelprice" id="btn_save_modelprice" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                            <?php
                                            echo '<a href="index.php?page=model.php&option=fullview&model_id='.$_GET["model_id"].'"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                            ?>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
			<?php
        }
        elseif ($_GET["option"]=="edit") 
        {
            $get_modelprice_id=$_GET["modelprice_id"];
            $get_modelprice_startdate=$_GET["modelprice_startdate"];
            $sql_get_modelprice_detail="SELECT * FROM modelprice WHERE modelno='$get_modelprice_id' AND startdate='$get_modelprice_startdate' AND enddate IS NULL";
            $result_get_modelprice_detail=mysqli_query($con,$sql_get_modelprice_detail)or die("Error in geting modelprice details".mysqli_error($con));
            $row_get_modelprice_detail=mysqli_fetch_assoc($result_get_modelprice_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Modelprice Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="">
                                    <div class="form-row">
                                      <div class="form-group col-md-6">
                                                <label>Model Number</label>
                                                <input type="text" readonly name="txt_model_number" id="txt_model_number"class="form-control" value="<?php echo $get_modelprice_id; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Start Date</label>
                                                <input type="Date" readonly name="txt_start_date" id="txt_start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Current Sales Price</label>
                                                <input type="text" readonly class="form-control" value="<?php echo $row_get_modelprice_detail["salesprice"]; ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>New Sales Price</label>
                                                <input type="text" name="txt_sales_price" id="txt_sales_price" class="form-control" required>
                                            </div>
                                    </div>
                                        <div>
                                            <button type="submit" name="btn_edit_modelprice" id="btn_edit_modelprice" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                            <a href="index.php?page=model.php&option=fullview&model_id=<?php echo $get_modelprice_id?>"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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