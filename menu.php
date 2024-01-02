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
include("config.php");
$branchname="";
if(isset($_SESSION["LOGIN_USER_NAME"]))
{
	 $system_login_username=$_SESSION["LOGIN_USER_NAME"];
		// get user branch id
		$sql_get_user="SELECT * FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
		$result_get_user=mysqli_query($con,$sql_get_user) or die ("Error in getting enterby".mysqli_error($con));
		$row_get_user=mysqli_fetch_assoc($result_get_user);
		
		//get branch name
		$sql_get_branch="SELECT * FROM branch WHERE branchid='$row_get_user[branchid]'";
		$result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
		$row_get_branch=mysqli_fetch_assoc($result_get_branch);

		$branchname=$row_get_branch["branchname"];
}
?>
<div class="navbar-inner">
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<!-- logo -->
			<div class="col-md-3 logo_agile"><img src="images/logo.png" alt=" " class="img-fluid" href="index.php" width="200px" height="100px"></div>
		<!-- //logo -->
					<?php
					if($system_user_type=="Cashier" || $system_user_type=="Sales Person" || $system_user_type=="Technician")
					{
					?>
					<ul class="navbar-nav ml-auto text-center ">
						<li class="nav-item active mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php">Home
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2" style="margin-top: 0;">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-style: hidden;">
								Retail
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=model.php&option=view">Model</a>
								<a class="dropdown-item" href="index.php?page=Customer.php&option=view">Customer</a>
								<a class="dropdown-item" href="index.php?page=sales.php&option=view">Sales</a>
								<a class="dropdown-item" href="index.php?page=repair.php&option=view">Repair</a>
								<a class="dropdown-item" href="index.php?page=customer_repair_status_check.php&option=customer_check_repair_status">Repair Status Check</a>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?page=profile.php">Profile</a>
							</a>
						</li>
						<li class="nav-item mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php?page=about.php">About Us</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?page=contact.php">Contact Us</a>
						</li>
						
						<?php
						if(isset($_SESSION["LOGIN_USER_NAME"]))
						{
							echo '<li class="nav-item">
									<a href="logout.php"  class="nav-link" style="color:#ff4a30;">
										Log Out <i class="fas fa-sign-out-alt mr-2"></i></a>
									</li>';
						}
						else
						{
							echo '<li class="nav-item">
									<a href="login.php" class="nav-link" style="color:#0879c9;">
										Log In <i class="fas fa-sign-in-alt mr-2"></i></a>
									</li>';
						}
						?>
					</ul>
					<?php
					}elseif($system_user_type=="Branch Manager")
					{
					?>
					<ul class="navbar-nav ml-auto text-center ">
						<li class="nav-item active mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php">Home
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Retail
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=model.php&option=view">Model</a>
								<a class="dropdown-item" href="index.php?page=Customer.php&option=view">Customer</a>
								<a class="dropdown-item" href="index.php?page=sales.php&option=view">Sales</a>
								<a class="dropdown-item" href="index.php?page=repair.php&option=view">Repair</a>
								<a class="dropdown-item" href="index.php?page=customer_repair_status_check.php&option=customer_check_repair_status">Repair Status Check</a>
							</div>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Management
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=brand.php&option=view">Brand</a>
								<a class="dropdown-item" href="index.php?page=category.php&option=view">Category</a>
								<a class="dropdown-item" href="index.php?page=purchase.php&option=view">Purchase</a>
								<a class="dropdown-item" href="index.php?page=purchasereturn.php&option=view">Purchase Return</a>
								<a class="dropdown-item" href="index.php?page=suppliers.php&option=view">Suppliers</a>
							</div>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Report
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=sales_reports.php&option=sales_branchwise">Branch Sales Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=repair_reports.php&option=repair_branchwise">Branch Repair Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=purchase_reports.php&option=purchase_branchwise">Branch Purchase Report</a>
								<a class="dropdown-item" href="index.php?page=purchase_reports.php&option=purchase_supplierwise_branchwise">Branch Purchase Report - Supplierwise</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=purchasereturn_reports.php&option=return_branchwise">Branch Purchase Return Report</a>
								<a class="dropdown-item" href="index.php?page=purchasereturn_reports.php&option=return_supplierwise_branchwise">Branch Purchase Return Report - Supplierwise</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=supplier_ledger_reports.php&option=supplier_ledger_branchwise_supplierwise">Branch Supplier Ledger Report - Supplierwise</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=business_reports.php&option=daily_business_branchwise">Branch Daily Business Report</a>
								<a class="dropdown-item" href="index.php?page=business_reports.php&option=monthly_business_branchwise">Branch Monthly Business Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=stock_reports.php&option=stock_branchwise">Branch Stock Report</a>
								<a class="dropdown-item" href="index.php?page=stock_reports.php&option=stock_branchwise_supplierwise">Branch Stock Report - Supplierwise</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=product_analysis_reports.php&option=product_analysis_categorywise">Product Analysis Report - Categorywise</a>
							</div>
						</li>
						<li class="nav-item mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php?page=profile.php">Profile</a>
							</a>
						</li>
						<li class="nav-item mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php?page=about.php">About Us</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?page=contact.php">Contact Us</a>
						</li>
						<?php
						if(isset($_SESSION["LOGIN_USER_NAME"]))
						{
							echo '<li class="nav-item">
									<a href="logout.php"  class="nav-link" style="color:#ff4a30;">
										Log Out <i class="fas fa-sign-out-alt mr-2"></i></a>
									</li>';
						}
						else
						{
							echo '<li class="nav-item">
									<a href="login.php" class="nav-link" style="color:#0879c9;">
										Log In <i class="fas fa-sign-in-alt mr-2"></i></a>
									</li>';
						}
						?>
					</ul>
					<?php
					}elseif($system_user_type=="Manager")
					{
					?>
					<ul class="navbar-nav ml-auto text-center ">
						<li class="nav-item active mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php">Home
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Retail
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=model.php&option=view">Model</a>
								<a class="dropdown-item" href="index.php?page=Customer.php&option=view">Customer</a>
								<a class="dropdown-item" href="index.php?page=sales.php&option=view">Sales</a>
								<a class="dropdown-item" href="index.php?page=repair.php&option=view">Repair</a>
								<a class="dropdown-item" href="index.php?page=customer_repair_status_check.php&option=customer_check_repair_status">Repair Status Check</a>
							</div>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Management
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=staff.php&option=view">Staff</a>
								<a class="dropdown-item" href="index.php?page=branch.php&option=view">Branch</a>
								<a class="dropdown-item" href="index.php?page=brand.php&option=view">Brand</a>
								<a class="dropdown-item" href="index.php?page=category.php&option=view">Category</a>
								<a class="dropdown-item" href="index.php?page=suppliers.php&option=view">Suppliers</a>
								<a class="dropdown-item" href="index.php?page=purchase.php&option=view">Purchase</a>
								<a class="dropdown-item" href="index.php?page=purchasereturn.php&option=view">Purchase Return</a>
							</div>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Report
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="index.php?page=sales_reports.php&option=overall_sales">Overall Sales Report</a>
								<a class="dropdown-item" href="index.php?page=sales_reports.php&option=sales_branchwise">Branchwise Sales Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=repair_reports.php&option=repair_overall">Overall Repair Report</a>
								<a class="dropdown-item" href="index.php?page=repair_reports.php&option=repair_branchwise">Branchwise Repair Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=purchase_reports.php&option=purchase_overall">Overall Purchase Report</a>
								<a class="dropdown-item" href="index.php?page=purchase_reports.php&option=purchase_branchwise">Branchwise Purchase Report</a>
								<a class="dropdown-item" href="index.php?page=purchase_reports.php&option=purchase_supplierwise_branchwise">Branchwise & Supplierwise Purchase Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=purchasereturn_reports.php&option=return_overall">Overall Purchase Return Report</a>
								<a class="dropdown-item" href="index.php?page=purchasereturn_reports.php&option=return_branchwise">Supplierwise Purchase Return Report</a>
								<a class="dropdown-item" href="index.php?page=purchasereturn_reports.php&option=return_supplierwise_branchwise">Supplierwise & Supplierwise Purchase Return Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=supplier_ledger_reports.php&option=overall_supplier_ledger">Overall Supplier Ledger Report</a>
								<a class="dropdown-item" href="index.php?page=supplier_ledger_reports.php&option=supplier_ledger_branchwise_supplierwise">Branchwise & Supplierwise Supplier Ledger Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=business_reports.php&option=daily_business_overall">Overall Daily Business Report</a>
								<a class="dropdown-item" href="index.php?page=business_reports.php&option=daily_business_branchwise">Branchwise Daily Business Report</a>
								<a class="dropdown-item" href="index.php?page=business_reports.php&option=monthly_business_overall">Overall Monthly Business Report</a>
								<a class="dropdown-item" href="index.php?page=business_reports.php&option=monthly_business_branchwise">Branchwise Monthly Business Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=stock_reports.php&option=stock_overall">Overall Stock Report</a>
								<a class="dropdown-item" href="index.php?page=stock_reports.php&option=stock_branchwise_supplierwise">Branchwise & Supplierwise Stock Report</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php?page=product_analysis_reports.php&option=product_analysis_overoall">Overall Product Analysis Report</a>
								<a class="dropdown-item" href="index.php?page=product_analysis_reports.php&option=product_analysis_categorywise">Product Analysis Report - Categorywise</a>
							</div>
						</li>
						<li class="nav-item mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php?page=profile.php">Profile</a>
							</a>
						</li>
						<li class="nav-item mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php?page=about.php">About Us</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?page=contact.php">Contact Us</a>
						</li>
						
						<?php
						if(isset($_SESSION["LOGIN_USER_NAME"]))
						{
							echo '<li class="nav-item">
									<a href="logout.php"  class="nav-link" style="color:#ff4a30;">
										Log Out <i class="fas fa-sign-out-alt mr-2"></i></a>
									</li>';
						}
						else
						{
							echo '<li class="nav-item">
									<a href="login.php" class="nav-link" style="color:#0879c9;">
										Log In <i class="fas fa-sign-in-alt mr-2"></i></a>
									</li>';
						}
						?>
					</ul>
					<?php
					}
					elseif($system_user_type=="guest")
					{
					?>
					<ul class="navbar-nav ml-auto text-center ">
						<li class="nav-item active mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php">Home
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?page=customer_repair_status_check.php&option=customer_check_repair_status">Repair Status Check</a>
							</a>
						</li>
						<li class="nav-item mr-lg-2 mb-lg-0 mb-2">
							<a class="nav-link" href="index.php?page=about.php">About Us</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="index.php?page=contact.php">Contact Us</a>
						</li>
						
						<?php
						if(isset($_SESSION["LOGIN_USER_NAME"]))
						{
							echo '<li class="nav-item">
									<a href="logout.php"  class="nav-link" style="color:#ff4a30;">
										Log Out <i class="fas fa-sign-out-alt mr-2"></i></a>
									</li>';
						}
						else
						{
							echo '<li class="nav-item">
									<a href="login.php" class="nav-link" style="color:#0879c9;">
										Log In <i class="fas fa-sign-in-alt mr-2"></i></a>
									</li>';
						}
						?>
					</ul>
					<?php
					}
					?>
				</div>
			</nav>
		</div>
