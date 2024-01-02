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
if($system_user_type=="Manager" || $system_user_type=="Branch Manager")
{
?>
<!--Header -->
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_brand"]))
{
    $sql_insert_brand="INSERT INTO brand(brandid,brandname)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_brand_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_brand_name"])."')";
    $result_insert_brand=mysqli_query($con,$sql_insert_brand) or die("Error in inserting in brand".mysqli_error($con));
    if($result_insert_brand)
    {
        if(isset($_GET["url_id"]))
        {   if($_GET["url_id"]=="model")
            {
                echo '<script>
                    alert("Successful Added!!");
                    window.location.href="index.php?page=model.php&option=add";
                </script>';
            }
        }
        else
        {
                echo '<script>
                        alert("Successful Added!!");
                        window.location.href="index.php?page=brand.php&option=view";
                      </script>';
        }
    }
}
if(isset($_POST["btn_edit_brand"]))
{
    $sql_update_brand="UPDATE brand SET 
                            brandname='".mysqli_real_escape_string($con,$_POST["txt_brand_name"])."'
                            WHERE brandid='".mysqli_real_escape_string($con,$_POST["txt_brand_id"])."'";
     $result_update_brand=mysqli_query($con,$sql_update_brand) or die("Error in updating in brand".mysqli_error($con));
    if($result_update_brand)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=brand.php&option=view";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_brand()
    {
        let brand=document.getElementById("txt_brand_name").value;
        if(brand!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result_brand=xmlhttp.responseText.trim();
                    if(result_brand=="true")
                    {   
                        alert("Same Brand Already Exists");
                        document.getElementById("txt_brand_name").value="";
                        document.getElementById("txt_brand_name").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_brand&ajax_brand=" + brand, true);
            xmlhttp.send();
        }
        else
        {
            
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
                                <h4 class="card-title">Brand Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <?php 
                                            $sql_create_brand_id="SELECT brandid FROM brand ORDER BY brandid DESC LIMIT 1";
                                            $result_create_brand_id=mysqli_query($con,$sql_create_brand_id) or die ("Error in Creating id".mysqli_error($con));
                                            if(mysqli_num_rows($result_create_brand_id)==1)
                                            {
                                                $row_create_brand_id=mysqli_fetch_assoc($result_create_brand_id);
                                                $brand_id=++$row_create_brand_id["brandid"];
                                            }
                                            else
                                            {
                                                $brand_id="BRA001";
                                            }
                                            ?>
                                                <label>Brand ID</label>
                                                <input type="text" readonly name="txt_brand_id" id="txt_brand_id" class="form-control" value="<?php echo $brand_id ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Brand Name</label>
                                                <input type="text" name="txt_brand_name" id="txt_brand_name"class="form-control" onblur="check_brand()" required>
                                            </div>
                                        </div>
                                        <div>
                                        <button type="submit" name="btn_save_brand" id="btn_save_brand" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <?php
                                        if(isset($_GET["url_id"]))
                                        {   
                                            if($_GET["url_id"]=="model")
                                            {    
                                                echo '<a href="index.php?page=model.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                            }else{}
                                        }
                                        else
                                        {
                                                echo '<a href="index.php?page=brand.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                        }
                                        ?>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
			<?php
        }
        elseif ($_GET["option"]=="view") 
        {
        ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Brand Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=brand.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Brand ID</th>
                                                    <th>Brand Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_brand_details_view="SELECT brandid,brandname FROM brand";
                                                $result_brand_details_view=mysqli_query($con,$sql_brand_details_view)or die("Error in brand details view".mysqli_error($con));
                                                while ($row_brand_details_view=mysqli_fetch_assoc($result_brand_details_view)) 
                                                {
                                                 //check brandid in model
                                                 $sql_get_model_details="SELECT modelno FROM model WHERE brandid='$row_brand_details_view[brandid]'";
                                                 $result_get_model_details=mysqli_query($con,$sql_get_model_details)or die("Error in category details view".mysqli_error($con));
                                                    echo '<tr>
                                                            <td>'.$row_brand_details_view["brandid"].'</td>
                                                            <td>'.$row_brand_details_view["brandname"].'</td>
                                                            <td>
                                                                <a href="index.php?page=brand.php&option=edit&brand_id='.$row_brand_details_view["brandid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>&nbsp';
                                                                if($system_user_type=="Manager")
                                                                {
                                                                    if(mysqli_num_rows($result_get_model_details) == 0)
                                                                    {
                                                                        ?>   
                                                                            <a href="index.php?page=brand.php&option=delete&brand_id=<?php echo $row_brand_details_view["brandid"];?>" onclick="return confirm('Are you sure, You want to delete this record?')"><button type="button"class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button></a>
                                                                        <?php
                                                                    }
                                                                }
                                                            echo '</td>
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
        elseif ($_GET["option"]=="edit") 
        {
            $get_brand_id=$_GET["brand_id"];
            $sql_get_brand_detail="SELECT * FROM brand WHERE brandid='$get_brand_id'";
            $result_get_brand_detail=mysqli_query($con,$sql_get_brand_detail)or die("Error in geting brand details".mysqli_error($con));
            $row_get_brand_detail=mysqli_fetch_assoc($result_get_brand_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Brand Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Brand ID</label>
                                                <input type="text" readonly name="txt_brand_id" id="txt_brand_id" class="form-control" value="<?php echo $row_get_brand_detail["brandid"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Brand Name</label>
                                                <input type="text" name="txt_brand_name" id="txt_brand_name"class="form-control" value="<?php echo $row_get_brand_detail["brandname"] ?>" required>
                                            </div>
                                        </div>
                                    <div>
                                        <button type="submit" name="btn_edit_brand" id="btn_edit_brand" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=brand.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
        }
        elseif ($_GET["option"]=="delete" & $system_user_type=="Manager") 
        {
            $get_brand_id=$_GET["brand_id"];
            $sql_brand_delete="DELETE FROM brand WHERE brandid='$get_brand_id'";
            $result_brand_delete=mysqli_query($con,$sql_brand_delete)or die("Error in brand delete".mysqli_error($con));
            if($result_brand_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=brand.php&option=view";
                    </script>';
            }

        }
        elseif($_GET["option"]=="delete" & $system_user_type!="Manager")
        {
            echo'<div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <center><h1><b>- 401 Unauthorized Access -</b></h1></center>
                        <center><h4>You have <b>NO PERMISSION</b> to acces this page</h4></center>
                    </div>
                </div>';
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