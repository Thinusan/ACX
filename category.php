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
<div class="services-breadcrumb">
	<div class="agile_inner_breadcrumb">
		<div class="container">
			<ul class="w3_short">
				<li>
					<a href="index.php">Home</a>
					<i>|</i>
				</li>
				<li>Category</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_category"]))
{
    $sql_insert_category="INSERT INTO category(catid,catname,showcase)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_category_id"])."',
                               '".mysqli_real_escape_string($con,$_POST["txt_category_name"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_showcase"])."')";
    $result_insert_category=mysqli_query($con,$sql_insert_category) or die("Error in inserting in category".mysqli_error($con));
    if($result_insert_category)
    {   if(isset($_GET["url_id"]))
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
                        window.location.href="index.php?page=category.php&option=view";
                    </script>';
        }
    }
}
if(isset($_POST["btn_edit_category"]))
{
    $sql_update_category="UPDATE category SET 
                            catname='".mysqli_real_escape_string($con,$_POST["txt_category_name"])."',
                            showcase='".mysqli_real_escape_string($con,$_POST["txt_showcase"])."'
                            WHERE catid='".mysqli_real_escape_string($con,$_POST["txt_category_id"])."'";
     $result_update_category=mysqli_query($con,$sql_update_category) or die("Error in updating in category".mysqli_error($con));
    if($result_update_category)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=category.php&option=view";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_category()
    {
        let category=document.getElementById("txt_category_name").value;
        if(category!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result_category=xmlhttp.responseText.trim();
                    if(result_category=="true")
                    {   
                        alert("Same Category Already Exists");
                        document.getElementById("txt_category_name").value="";
                        document.getElementById("txt_category_name").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_category&ajax_category=" + category, true);
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
                                <h4 class="card-title">Category Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="">
                                         <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <?php 
                                            $sql_create_category_id="SELECT catid FROM category ORDER BY catid DESC LIMIT 1";
                                            $result_create_category_id=mysqli_query($con,$sql_create_category_id) or die ("Error in Creating id".mysqli_error($con));
                                            if(mysqli_num_rows($result_create_category_id)==1)
                                            {
                                                $row_create_category_id=mysqli_fetch_assoc($result_create_category_id);
                                                $category_id=++$row_create_category_id["catid"];
                                            }
                                            else
                                            {
                                                $category_id="CAT001";
                                            }
                                            ?>
                                                <label>Category ID</label>
                                                <input type="text" readonly name="txt_category_id" id="txt_category_id" class="form-control" value="<?php echo $category_id ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Category Name</label>
                                                <input type="text" name="txt_category_name" id="txt_category_name" class="form-control" onblur="check_category()" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Showcase in Homepage</label>
                                                <select type="text" name="txt_showcase" id="txt_showcase"class="form-control" required>
                                                    <option selected>No</option>
                                                    <option>Yes</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div>
                                        <button type="submit" name="btn_save_category" id="btn_save_category" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <?php
                                         if(isset($_GET["url_id"]))
                                        {   if($_GET["url_id"]=="model")
                                            {
                                                echo '<a href="index.php?page=model.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                            }else{}
                                        }
                                        else
                                        {
                                                echo '<a href="index.php?page=category.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
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
                                    <h4 class="card-title">Category Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=category.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Category ID</th>
                                                    <th>Category Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_category_details_view="SELECT catid,catname FROM category";
                                                $result_category_details_view=mysqli_query($con,$sql_category_details_view)or die("Error in category details view".mysqli_error($con));
                                                while ($row_category_details_view=mysqli_fetch_assoc($result_category_details_view)) 
                                                {  
                                                    //check catid in model
                                                    $sql_get_model_details="SELECT modelno FROM model WHERE catid='$row_category_details_view[catid]'";
                                                    $result_get_model_details=mysqli_query($con,$sql_get_model_details)or die("Error in category details view".mysqli_error($con));

                                                    echo '<tr>
                                                            <td>'.$row_category_details_view["catid"].'</td>
                                                            <td>'.$row_category_details_view["catname"].'</td>
                                                            <td>
                                                                <a href="index.php?page=category.php&option=edit&category_id='.$row_category_details_view["catid"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>';
                                                               if($system_user_type=="Manager")
                                                                {
                                                                    if(mysqli_num_rows($result_get_model_details) == 0)
                                                                    {    
                                                                        ?>
                                                                            <a href="index.php?page=category.php&option=delete&category_id=<?php echo $row_category_details_view["catid"]; ?>" onclick="return confirm('Are you sure, You want to delete this record?')"><button type="button"class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button></a>
                                                                        <?php
                                                                    }else{}
                                                                }else{}
                                                    echo' </td>
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
            $get_category_id=$_GET["category_id"];
            $sql_get_category_detail="SELECT * FROM category WHERE catid='$get_category_id'";
            $result_get_category_detail=mysqli_query($con,$sql_get_category_detail)or die("Error in geting category details".mysqli_error($con));
            $row_get_category_detail=mysqli_fetch_assoc($result_get_category_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Category Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Category Id</label>
                                            <input type="text" readonly name="txt_category_id" id="txt_category_id" class="form-control" value="<?php echo $row_get_category_detail["catid"] ?>" readonly>
                                        </div>
                                         <div class="form-group col-md-6">
                                            <label>Category Name</label>
                                            <input type="text" name="txt_category_name" id="txt_category_name" class="form-control" placeholder="Example:A.W.S.Kumara" value="<?php echo $row_get_category_detail["catname"] ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Showcase in Homepage</label>
                                            <select type="text" name="txt_showcase" id="txt_showcase"class="form-control" required>
                                                <option selected hidden><?php echo $row_get_category_detail["showcase"] ?></option>
                                                <option >No</option>
                                                <option>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" name="btn_edit_category" id="btn_edit_category" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=category.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
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
            $get_category_id=$_GET["category_id"];
            $sql_category_delete="DELETE FROM category WHERE catid='$get_category_id'";
            $result_category_delete=mysqli_query($con,$sql_category_delete)or die("Error in category delete".mysqli_error($con));
            if($result_category_delete)
            {
                echo '<script>
                        alert("Successful Deleted!!");
                        window.location.href="index.php?page=category.php&option=view";
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