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
				<li>Model</li>
			</ul>
		</div>
	</div>
</div>
<!-- //Header -->
<?php
include("config.php");
if(isset($_POST["btn_save_model"]))
{
    if ($_FILES['txt_model_image']['name']!="") 
    {
        $model_number=$_POST["txt_model_number"];//assign user id
        $target_dir = "product_image/";
        $target_file = $target_dir . basename($_FILES["txt_model_image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg")
        {
            echo '<script>alert("Sorry, only JPG, JPEG & PNG files are allowed.");
                    window.location.href="index.php?page=model.php&option=add";
                </script>';
            die();
        }
        else
        {
            $filename=$model_number.".".$image_file_type;
            $fileupload=$target_dir . $filename;
            move_uploaded_file($_FILES["txt_model_image"]["tmp_name"], $fileupload);
        }
    }
    else
    {
        $filename="No Image";
    }

    $sql_insert_model="INSERT INTO model(modelno,modelname,brandid,catid,specification,barcode,productimage)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_model_name"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_brand_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_category_id"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_specification"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_barcode"])."',
                                '".mysqli_real_escape_string($con,$filename)."')";
    $result_insert_model=mysqli_query($con,$sql_insert_model) or die("Error in inserting in model".mysqli_error($con));

    $sql_insert_model_price="INSERT INTO modelprice(modelno,salesprice,startdate)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txt_model_number"])."',
                                '".mysqli_real_escape_string($con,$_POST["txt_sales_price"])."',
                                '".mysqli_real_escape_string($con,date('Y.m.d'))."')";
    $result_insert_model_price=mysqli_query($con,$sql_insert_model_price) or die("Error in inserting in model".mysqli_error($con));

   
    if($result_insert_model && $result_insert_model_price)
    {   if(isset($_GET["url_id"]))
        { if($_GET["url_id"]=="purchase")
            {
                echo '<script>
                        alert("Successful Added!!");
                        window.location.href="index.php?page=purchase.php&option=add";
                    </script>';
            }else{}
        }
        else
        {
            echo '<script>
                    alert("Successful Added!!");
                    window.location.href="index.php?page=model.php&option=view";
                </script>'; 
        }
    }

}
if(isset($_POST["btn_edit_model"]))
{
    $sql_update_model="UPDATE model SET 
                            modelname='".mysqli_real_escape_string($con,$_POST["txt_model_name"])."',
                            brandid='".mysqli_real_escape_string($con,$_POST["txt_brand_id"])."',
                            catid='".mysqli_real_escape_string($con,$_POST["txt_category_id"])."',
                            specification='".mysqli_real_escape_string($con,$_POST["txt_specification"])."',
                            barcode='".mysqli_real_escape_string($con,$_POST["txt_barcode"])."'
                            WHERE modelno='".mysqli_real_escape_string($con,$_POST["txt_model_number"])."'";
    $result_update_model=mysqli_query($con,$sql_update_model) or die("Error in updating in model".mysqli_error($con));
    if($result_update_model)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=model.php&option=view";
            </script>';
    }
}
if(isset($_POST["btn_change_model_image"]))
{   
    $model_number=$_POST["txt_change_model_image_id"];
    if ($_FILES['txt_change_model_image']['name']!="") 
    {//assign user id
        $target_dir = "product_image/";
        $target_file = $target_dir . basename($_FILES["txt_change_model_image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg")
        {
            echo '<script>alert("Sorry, only JPG, JPEG & PNG files are allowed.");
                    window.location.href="index.php?page=model.php&option=change_image&model_id='.$model_number.'";
                </script>';
            die();
        }
        else
        {   $get_product_image="SELECT productimage FROM model WHERE modelno='$model_number'";
            $result_get_product_image=mysqli_query($con,$get_product_image) or die ("Error in Creating id".mysqli_error($con));
            $row_get_product_image=mysqli_fetch_assoc($result_get_product_image);
            if($row_get_product_image["productimage"] != "No Image")
            {
                $filePath="product_image/".$row_get_product_image["productimage"];
                if (file_exists($filePath)) 
                {
                    unlink($filePath);
                }
            }
            $filename=$model_number.".".$image_file_type;
            $fileupload=$target_dir . $filename;
            move_uploaded_file($_FILES["txt_change_model_image"]["tmp_name"], $fileupload);
        }
    }
    else
    {
        echo '<script>alert("Sorry, You have not selected an image.");
                window.location.href="index.php?page=model.php&option=change_image&model_id='.$model_number.'";
            </script>';
        die();
    }
    $sql_update_model_image="UPDATE model SET 
                            productimage='".mysqli_real_escape_string($con,$filename)."'
                            WHERE modelno='".mysqli_real_escape_string($con,$model_number)."'";
    $result_update_model_image=mysqli_query($con,$sql_update_model_image) or die("Error in updating in model".mysqli_error($con));
    if($result_update_model_image)
    {
        echo '<script>
                alert("Successful Updated!!");
                window.location.href="index.php?page=model.php&option=fullview&model_id='.$model_number.'";
            </script>';
    }
}
?>
<script type="text/javascript">
    function check_model_name()
    {
        let model_name=document.getElementById("txt_model_name").value;
        if(model_name!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result_model_name=xmlhttp.responseText.trim();
                    if(result_model_name=="true")
                    {   
                        alert("Same Model Already Exists");
                        document.getElementById("txt_model_name").value="";
                        document.getElementById("txt_model_name").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_model_name&ajax_model_name=" + model_name, true);
            xmlhttp.send();
        }
        else
        {
            
        }
    }
</script>
<script type="text/javascript">
    function check_barcode()
    {
        let barcode=document.getElementById("txt_barcode").value;
        if(barcode!="")
        {
            let xmlhttp = new XMLHttpRequest();     
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    let result_barcode=xmlhttp.responseText.trim();
                    if(result_barcode=="true")
                    {   
                        alert("Same Barcode Already Exists");
                        document.getElementById("txt_barcode").value="";
                        document.getElementById("txt_barcode").focus();
                    }
                    else
                    {

                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=check_barcode&ajax_barcode=" + barcode, true);
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
                                <h4 class="card-title">Model Details</h4>
                                <div class="basic-form">
                                    <form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <?php 
                                            $sql_create_model_no="SELECT modelno FROM model ORDER BY modelno DESC LIMIT 1";
                                            $result_create_model_no=mysqli_query($con,$sql_create_model_no) or die ("Error in Creating id".mysqli_error($con));
                                            if(mysqli_num_rows($result_create_model_no)==1)
                                            {
                                                $row_create_model_no=mysqli_fetch_assoc($result_create_model_no);
                                                $model_no=++$row_create_model_no["modelno"];
                                            }
                                            else
                                            {
                                                $model_no="MOD001";
                                            }
                                            ?>
                                                <label>Model Number</label>
                                                <input type="text" readonly name="txt_model_number" id="txt_model_number" class="form-control" value="<?php echo $model_no ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Brand Name</label>
                                                <select name="txt_brand_id" id="txt_brand_id" class="form-control" required>
                                                <option value="">Select Brand</option>
                                                <?php
                                                    $sql_get_brand="SELECT * FROM brand";
                                                    $result_get_brand=mysqli_query($con,$sql_get_brand) or die ("Error in get Category".mysqli_error($con));
                                                    while ($row_get_brand=mysqli_fetch_assoc($result_get_brand)) 
                                                    {
                                                        echo '<option value="'.$row_get_brand["brandid"].'">'.$row_get_brand["brandname"].'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <?php
                                                if($system_user_type=="Manager" || $system_user_type=="Branch Manager")
                                                {
                                                    ?>
                                                        <div align="right"><a href="index.php?page=brand.php&option=add&url_id=model" onMouseOver="style.color='red'" onMouseOut="style.color='blue'"><i class="fas fa-plus"></i> Add New Brand</a></div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Category Name</label>
                                                <select name="txt_category_id" id="txt_category_id" class="form-control"  required>
                                                <option value="">Select Category</option>
                                                <?php
                                                    $sql_get_category="SELECT * FROM category";
                                                    $result_get_category=mysqli_query($con,$sql_get_category) or die ("Error in get Category".mysqli_error($con));
                                                    while ($row_get_category=mysqli_fetch_assoc($result_get_category)) 
                                                    {
                                                        echo '<option value="'.$row_get_category["catid"].'">'.$row_get_category["catname"].'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <?php
                                                if($system_user_type=="Manager" || $system_user_type=="Branch Manager")
                                                {
                                                    ?>
                                                        <div align="right"><a href="index.php?page=category.php&option=add&url_id=model" onMouseOver="style.color='red'" onMouseOut="style.color='blue'"><i class="fas fa-plus"></i> Add New Category</a></div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Model Name</label>
                                                <input type="text" name="txt_model_name" id="txt_model_name" class="form-control" onblur="check_model_name()" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                            <label>Specification</label>
                                            <input type="text" maxlength="50" name="txt_specification" id="txt_specification" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                            <label>Sales Price</label>
                                            <input type="text" name="txt_sales_price" id="txt_sales_price" class="form-control" required>
                                            </div>
                                             <div class="form-group col-md-6">
                                            <label>Model Image</label>
                                            <input type="file" name="txt_model_image" id="txt_model_image" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Barcode</label>
                                                <input type="text" name="txt_barcode" id="txt_barcode"  maxlength="10" class="form-control" onblur="check_barcode()" required>
                                            </div>
                                        </div>
                                            <div>
                                            <button type="submit" name="btn_save_model" id="btn_save_model" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                            <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                            <?php
                                            if(isset($_GET["url_id"]))
                                            { if($_GET["url_id"]=="purchase")
                                                {
                                                    echo '<a href="index.php?page=purchase.php&option=add"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
                                                }else{}
                                            }
                                            else
                                            {
                                                    echo '<a href="index.php?page=model.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>';
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
                                    <h4 class="card-title">Model Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <a href="index.php?page=model.php&option=add"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Model Number</th>
                                                    <th>Model Name</th>
                                                    <th>Category Name</th>
                                                    <th>Brand Name</th>
                                                    <th>Barcode</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_model_details_view="SELECT modelno,modelname,brandid,catid,barcode FROM model";
                                                $result_model_details_view=mysqli_query($con,$sql_model_details_view)or die("Error in model details view".mysqli_error($con));
                                                while ($row_model_details_view=mysqli_fetch_assoc($result_model_details_view)) 
                                                {//get category name
                                                    $sql_get_category_name="SELECT catname FROM category WHERE catid='$row_model_details_view[catid]'";
                                                    $result_get_category_name=mysqli_query($con,$sql_get_category_name)or die("Error in getting category name".mysqli_error($con));
                                                    $row_get_category_name=mysqli_fetch_assoc($result_get_category_name);
                                                 //get brand name
                                                    $sql_get_brand_name="SELECT brandname FROM brand WHERE brandid='$row_model_details_view[brandid]'";
                                                    $result_get_brand_name=mysqli_query($con,$sql_get_brand_name)or die("Error in getting brand name".mysqli_error($con));
                                                    $row_get_brand_name=mysqli_fetch_assoc($result_get_brand_name);
                                                    echo '<tr>
                                                            <td>'.$row_model_details_view["modelno"].'</td>
                                                            <td>'.$row_model_details_view["modelname"].'</td>
                                                            <td>'.$row_get_category_name["catname"].'</td>
                                                            <td>'.$row_get_brand_name["brandname"].'</td>
                                                            <td>'.$row_model_details_view["barcode"].'</td>
                                                            <td>';
                                                                $sql_get_model_price="SELECT * FROM modelprice WHERE modelno='$row_model_details_view[modelno]' AND enddate IS NULL";
                                                                $result_get_model_price=mysqli_query($con,$sql_get_model_price)or die("Error in getting category name".mysqli_error($con));
                                                                echo '<a href="index.php?page=model.php&option=fullview&model_id='.$row_model_details_view["modelno"].'"><button type="button"class="btn btn-info"><i class="fas fa-th-list"></i> View</button></a>&nbsp';
                                                                if(mysqli_num_rows($result_get_model_price) != 0)
                                                                    {
                                                                        echo '<a href="index.php?page=model.php&option=edit&model_id='.$row_model_details_view["modelno"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>&nbsp';
                                                                        if($system_user_type=="Manager")
                                                                        {
                                                                            ?>
                                                                                <a href="index.php?page=model.php&option=delete&model_id=<?php echo $row_model_details_view["modelno"];?>" onclick="return confirm('Are you sure, You want to block this model?')"><button type="button" class="btn btn-danger"><i class="fas fa-ban"></i> Block</button></a>
                                                                            <?php
                                                                        }else{}  
                                                                    }else{}
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
        {   //get model details
            $get_model_id=$_GET["model_id"];
            $sql_get_model_detail="SELECT * FROM model WHERE modelno='$get_model_id'";
            $result_get_model_detail=mysqli_query($con,$sql_get_model_detail)or die("Error in geting model details".mysqli_error($con));
            $row_get_model_detail=mysqli_fetch_assoc($result_get_model_detail);
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Model Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" autocomplete="off">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                                <label>Model Number</label>
                                                <input type="text" readonly name="txt_model_number" id="txt_model_number" class="form-control" value="<?php echo $row_get_model_detail["modelno"] ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Barcode</label>
                                                <input type="text" name="txt_barcode" id="txt_barcode" class="form-control" value="<?php echo $row_get_model_detail["barcode"] ?>" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Brand Name</label>
                                                <select name="txt_brand_id" id="txt_brand_id" class="form-control" required>
                                                <?php
                                                    $sql_get_brand="SELECT * FROM brand";
                                                    $result_get_brand=mysqli_query($con,$sql_get_brand) or die ("Error in get brand".mysqli_error($con));
                                                    while ($row_get_brand=mysqli_fetch_assoc($result_get_brand)) 
                                                    {
                                                        if($row_get_brand["brandid"]==$row_get_model_detail["brandname"])
                                                        {
                                                            echo '<option value="'.$row_get_brand["brandid"].'" selected>'.$row_get_brand["brandname"].'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$row_get_brand["brandid"].'">'.$row_get_brand["brandname"].'</option>';
                                                        }
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                 <label>Category Name</label>
                                                 <select name="txt_category_id" id="txt_category_id" class="form-control" required>
                                                 <?php
                                                    $sql_get_category="SELECT * FROM category";
                                                    $result_get_category=mysqli_query($con,$sql_get_category) or die ("Error in get Category".mysqli_error($con));
                                                    while ($row_get_category=mysqli_fetch_assoc($result_get_category)) 
                                                    {
                                                        if($row_get_category["catid"]==$row_get_model_detail["catid"])
                                                        {
                                                            echo '<option value="'.$row_get_category["catid"].'" selected>'.$row_get_category["catname"].'</option>';
                                                        }
                                                        else
                                                        {
                                                            echo '<option value="'.$row_get_category["catid"].'">'.$row_get_category["catname"].'</option>';
                                                        }
                                                    }
                                                 ?>
                                                 </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Model Name</label>
                                                <input type="text" name="txt_model_name" id="txt_model_name" class="form-control" value="<?php echo $row_get_model_detail["modelname"] ?>"  onblur="check_model_name()"required>
                                            </div>
                                            <div class="form-group col-md-6">
                                            <label>Specification</label>
                                            <input type="text" maxlength="50" name="txt_specification" id="txt_specification"class="form-control" value="<?php echo $row_get_model_detail["specification"] ?>" required>
                                            </div>
                                        </div> 
                                    <div>
                                        <button type="submit" name="btn_edit_model" id="btn_edit_model" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                        <button type="reset" name="btn_reset" id="btn_reset" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</button>
                                        <a href="index.php?page=model.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }
        elseif ($_GET["option"]=="fullview") 
        {
            $get_model_id=$_GET["model_id"];
            $sql_model_fullview="SELECT * FROM model WHERE modelno='$get_model_id'";
            $result_model_fullviewl=mysqli_query($con,$sql_model_fullview)or die("Error in geting model fullview details".mysqli_error($con));
            $row_model_fullview=mysqli_fetch_assoc($result_model_fullviewl);
            //get category name
             $sql_get_category_name="SELECT catname FROM category WHERE catid='$row_model_fullview[catid]'";
             $result_get_category_name=mysqli_query($con,$sql_get_category_name)or die("Error in getting category name".mysqli_error($con));
             $row_get_category_name=mysqli_fetch_assoc($result_get_category_name);
            //get brand name
             $sql_get_brand_name="SELECT brandname FROM brand WHERE brandid='$row_model_fullview[brandid]'";
             $result_get_brand_name=mysqli_query($con,$sql_get_brand_name)or die("Error in getting brand name".mysqli_error($con));
             $row_get_brand_name=mysqli_fetch_assoc($result_get_brand_name);
            // get model price
            // query **
            $sql_get_model_price="SELECT * FROM modelprice WHERE modelno='$get_model_id'  AND enddate IS NULL";
            $result_get_model_price=mysqli_query($con,$sql_get_model_price)or die("Error in getting modelprice".mysqli_error($con));
            ?>
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Model Full Details</h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered zero-configuration">
                                                <tr><th style="width: 50%">Model Number</th><td><?php echo $row_model_fullview["modelno"] ?></td></tr>
                                                <tr><th>Model Name</th><td><?php echo $row_model_fullview["modelname"] ?></td></tr>
                                                <tr><th>Brand Name</th><td><?php echo $row_get_brand_name["brandname"] ?></td></tr>
                                                <tr><th>Category Name</th><td><?php echo $row_get_category_name["catname"] ?></td></tr>
                                                <tr><th>Barcode</th><td><?php echo $row_model_fullview["barcode"] ?></td></tr>
                                                <tr><th>Specification</th><td><?php echo $row_model_fullview["specification"] ?></td></tr> 
                                                <tr><th>Product Image</th><td><img src="<?php echo 'product_image/'.$row_model_fullview["productimage"].'?'.date("H:i:s").''?>" style="width: 100px;height: 100px;"> &nbsp <?php if(mysqli_num_rows($result_get_model_price) != 0){echo '<a href="index.php?page=model.php&option=change_image&model_id='.$row_model_fullview["modelno"].'"><button type="button"class="btn btn-warning"><i class="fas fa-plus"></i> Change</button></a>';}?></td>                    
                                                <tr>
                                                    <td colspan="2">
                                                        <center>                                                        
                                                            <?php
                                                            echo '<a href="index.php?page=model.php&option=view"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back</button></a>&nbsp';
                                                            // query ** refernece 1
                                                            if(mysqli_num_rows($result_get_model_price) != 0){
                                                                echo '<a href="index.php?page=model.php&option=edit&model_id='.$row_model_fullview["modelno"].'"><button type="button"class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></a>';
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
                                    <h4 class="card-title">Model Price Details</h4>
                                    <div class="col-6" style="padding-bottom: 10px;">
                                        <?php // query ** reference 2
                                         if(mysqli_num_rows($result_get_model_price) == 0){
                                            echo '<a href="index.php?page=modelprice.php&option=add&model_id='.$get_model_id.'"><button type="button"class="btn btn-primary"><i class="fas fa-plus"></i> Add</button></a>';
                                            }else{}
                                        ?>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Model Number</th>
                                                    <th>Model Name</th>
                                                    <th>Sales Price</th>
                                                    <th>Action / End Date</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_get_model_price_2="SELECT * FROM modelprice WHERE modelno='$get_model_id'";
                                                $result_get_model_price_2=mysqli_query($con,$sql_get_model_price_2)or die("Error in getting category name".mysqli_error($con));
                          
                                                while($row_get_model_price_2=mysqli_fetch_assoc($result_get_model_price_2))
                                                {
                                                    echo '<tr>
                                                            <td>'.$row_get_model_price_2["modelno"].'</td>
                                                            <td>'.$row_model_fullview["modelname"].'</td>
                                                            <td>'.$row_get_model_price_2["salesprice"].'</td>';
                                                            if(is_null($row_get_model_price_2["enddate"]))
                                                            {
                                                                if($system_user_type=="Manager")
                                                                {
                                                                    echo '<td>
                                                                            <a href="index.php?page=modelprice.php&option=edit&modelprice_id='.$row_get_model_price_2["modelno"].'&modelprice_startdate='.$row_get_model_price_2["startdate"].'"><button type="button"class="btn btn-warning"><i class="fas fa-plus"></i> Change</button></a>
                                                                          </td>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<td>N/A</td>';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                echo'<td>'.$row_get_model_price_2["enddate"].'</td>';
                                                            }

                                                        echo '</tr>';
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
        elseif ($_GET["option"]=="change_image") 
        {
            $get_product_image_id=$_GET["model_id"];
            ?>
            <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Model Details</h4>
                            <div class="basic-form">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                                    <label>Model ID</label>
                                                    <input type="text" name="txt_change_model_image_id" id="txt_change_model_image_id" class="form-control" value="<?php echo $get_product_image_id ?>" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Change Model Image</label>
                                                    <input type="file" name="txt_change_model_image" id="txt_change_model_image" class="form-control" required>
                                                </div>
                                                <div>
                                                    <button type="submit" name="btn_change_model_image" id="btn_change_model_image" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                                    <a href="index.php?page=model.php&option=fullview&model_id=<?php echo $get_product_image_id;?>"> <button type="button"name="btn_cancel" id="btn_cancel" class="btn btn-danger"><i class="fas fa-close"></i> Cancel</button></a>
                                                </div> 
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
                $get_model_id=$_GET["model_id"];
                $todaydate=date("Y-m-d");
                $sql_get_model_price="SELECT * FROM modelprice WHERE modelno='$get_model_id' AND enddate IS NULL";
                $result_get_model_price=mysqli_query($con,$sql_get_model_price)or die("Error in getting category name".mysqli_error($con));
                $row_get_model_price=mysqli_fetch_assoc($result_get_model_price);
                if($row_get_model_price["startdate"]==$todaydate)
                {
                    $enddate=$todaydate;
                }
                else
                {
                    $enddate=date('Y.m.d',strtotime("yesterday"));
                }

                $sql_model_delete="UPDATE modelprice SET 
                                enddate='".mysqli_real_escape_string($con,$enddate)."'
                                WHERE modelno='$get_model_id'";
                $result_model_delete=mysqli_query($con,$sql_model_delete)or die("Error in model delete".mysqli_error($con));
                if($result_model_delete)
                {
                    echo '<script>
                            alert("Successful Blocked!!");
                            window.location.href="index.php?page=model.php&option=view";
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