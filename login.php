<?php
if(!isset($_SESSION))
{
    session_start();
}

include("config.php");
	if(isset($_POST["btn_login"]))
	{
		$username=$_POST["txt_username"];
		$password=md5($_POST["txt_password"]); // encrypted password

		$sql_username="SELECT * FROM login WHERE userid='$username'";
		$result_username=mysqli_query($con,$sql_username) or die("Error in getting userid".mysqli_error($con));
		if(mysqli_num_rows($result_username)==1)
		{
			$row_username=mysqli_fetch_assoc($result_username);
			$sql_password="SELECT * FROM login WHERE userid='$username' AND password='$password'";

			$result_password=mysqli_query($con,$sql_password) or die("Error in getting password".mysqli_error($con));
			if(mysqli_num_rows($result_password)==1)
			{
				$sql_update="UPDATE login SET attempt=0 WHERE userid='$username'";
				$result_update=mysqli_query($con,$sql_update) or die("Error in updating login details".mysqli_error($con));
				if($row_username["status"]=="Active")
				{	$_SESSION["LOGIN_USER_NAME"]=$row_username["userid"];
					$_SESSION["LOGIN_USER_TYPE"]=$row_username["usertype"];
					//stock opening balance add start
					$date=date('y-m-d');
					// get branch id
                    $sql_get_branch="SELECT branchid FROM staff WHERE nicno='$_SESSION[LOGIN_USER_NAME]'";
	                $result_get_branch=mysqli_query($con,$sql_get_branch) or die ("Error in get Category".mysqli_error($con));
	                $row_get_branch=mysqli_fetch_assoc($result_get_branch);

					$sql_get_stock_ledger="SELECT * FROM stock_ledger WHERE ledger_date='$date' AND branchid='$row_get_branch[branchid]'";
					$result_get_stock_ledger=mysqli_query($con,$sql_get_stock_ledger) or die ("Error getting details from stock ledger".mysqli_error($con));

					if(mysqli_num_rows($result_get_stock_ledger)==0)
					{	
						// create stock ledger id
                        $sql_create_stock_ledger_id="SELECT stock_ledger_id FROM stock_ledger ORDER BY stock_ledger_id DESC LIMIT 1";
                        $result_create_stock_ledger_id=mysqli_query($con,$sql_create_stock_ledger_id) or die ("Error in Creating stock ledger id".mysqli_error($con));
                        if(mysqli_num_rows($result_create_stock_ledger_id)==1)
                        {
                            $row_create_stock_ledger_id=mysqli_fetch_assoc($result_create_stock_ledger_id);
                            $stock_ledger_id=++$row_create_stock_ledger_id["stock_ledger_id"];
                        }
                        else
                        {
                            $stock_ledger_id="LED001";
                        }
                       	// stock value calculate
		                $total_stock_value=0;
                        $sql_get_stock="SELECT * FROM stock WHERE branchid='$row_get_branch[branchid]' AND quantity!=0";
                        $result_get_stock=mysqli_query($con,$sql_get_stock) or die ("Error getting details from stock".mysqli_error($con)); 
                        while($row_get_stock=mysqli_fetch_assoc($result_get_stock))
                        {
                        	$get_unit_price="SELECT unitprice FROM purchaseitem WHERE modelno='$row_get_stock[modelno]' AND purid='$row_get_stock[purid]'";
                        	$result_get_unit_price=mysqli_query($con,$get_unit_price) or die ("Error getting unit price".mysqli_error($con));
                        	$row_get_unit_price=mysqli_fetch_assoc($result_get_unit_price);
                        	// calculate sub stock value
                        	$sub_stock_value=$row_get_stock["quantity"] * $row_get_unit_price["unitprice"];
                        	$total_stock_value+=$sub_stock_value;

                        }

						$sql_update_stock_ledger="INSERT INTO stock_ledger(stock_ledger_id,ledger_date,branchid,opening_balance)
												 VALUES ('".mysqli_real_escape_string($con,$stock_ledger_id)."',
						                                 '".mysqli_real_escape_string($con,$date)."',
						                                 '".mysqli_real_escape_string($con,$row_get_branch["branchid"])."',
						                                 '".mysqli_real_escape_string($con,$total_stock_value)."')";
						$result_insert_stock_ledger=mysqli_query($con,$sql_update_stock_ledger) or die("Error in inserting in stock ledger".mysqli_error($con));
					}
					else
					{

					}
					//stock opening balance add end
					
					echo '<script> window.location.href="index.php"; </script>';
				}
				elseif($row_username["status"]=="Supended")
				{
					echo'<script>alert("Sorry,your account is deleted");</script>';
				}
			}
			elseif($row_username["attempt"]<3) // try to login with wrong password
			{
				$sql_update="UPDATE login SET attempt=attempt+1 WHERE userid='$username'";
				$result_update=mysqli_query($con,$sql_update) or die("Error in updating login details".mysqli_error($con));
				echo'<script>alert("your password is wrong");</script>';
		}
		else // enter wrong password more than three times
		{
			$_SESSION["FORGET_USER_NAME"]=$row_username["userid"];
			echo'<script>alert("you have entered wrong password more than 3 times,please recover your password");
						window.location.href="forgotpassword.php";
				</script>';
		}
			
	}
	else
	{
		echo'<script>alert("Your username is wrong");</script>';
	}

}
?>
<title>ACX Phone Shop</title>
<div class="container" id="container">
	<div class="form-container sign-in-container" id="sign_in">
		<form method="POST" action="" autocomplete="off">
			<h1>Log In</h1>
			<input type="text" maxlength="12" placeholder="User Name" name="txt_username" id="txt_user Name"/>
			<input type="password" placeholder="Password" name="txt_password" id="txt_password"/>
			<a id="forgot_password_click" href="http://localhost/ACX/forgotpassword.php">Forgot your password?</a>
			<table>
				<th>
					<button name="btn_cancel" id="btn_cancel">Cancel</button>
				<th>
					<button type="submit" name="btn_login" id="btn_login">Log In</button>
				</th>
			</table>
		</form>
	</div>
			
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-right" >
				<h1>Hello!</h1>
				<p>Enter your account credentials to login to the system</p>
				<h3>- ACX Phone Shop -</h3>
			</div>
		</div>
	</div>
</div>


<style>

@import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

* {
	box-sizing: border-box;
}

body {
	background: #f6f5f7;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	font-family: 'Montserrat', sans-serif;
	height: 100vh;
	margin: -20px 0 50px;
}

h1 {
	font-weight: bold;
	margin: 20px;

}

h2 {
	text-align: center;
}

p {
	font-size: 16px;
	font-weight: 100;
	line-height: 20px;
	letter-spacing: 0.5px;
	margin: 20px 0 37px;
}

span {
	font-size: 12px;
}

a {
	color: #333;
	font-size: 14px;
	text-decoration: none;
	margin: 15px 0;
}

button {
	border-radius: 20px;
	border: 1px solid #FF4B2B;
	background-color: #FF4B2B;
	color: #FFFFFF;
	font-size: 12px;
	font-weight: bold;
	padding: 12px 35px;
	letter-spacing: 1px;
	text-transform: uppercase;
	transition: transform 80ms ease-in;

}
	
button:active {
	transform: scale(0.95);
}

button:focus {
	outline: none;
}

form {
	background-color: #FFFFFF;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 50px;
	height: 100%;
	text-align: center;
}

input {
	background-color: #eee;
	border: none;
	padding: 12px 15px;
	margin: 8px 0;
	width: 100%;
}

.container {
	background-color: #fff;
	border-radius: 10px;
  	box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
			0 10px 10px rgba(0,0,0,0.22);
	position: relative;
	overflow: hidden;
	width: 768px;
	max-width: 100%;
	min-height: 480px;
}

.form-container {
	position: absolute;
	top: 0;
	height: 100%;
	transition: all 0.6s ease-in-out;
}

.sign-in-container {
	left: 0;
	width: 50%;
	z-index: 2;
}

@keyframes show {
	0%, 49.99% {
		opacity: 0;
		z-index: 1;
	}
	
	50%, 100% {
		opacity: 1;
		z-index: 5;
	}
}

.overlay-container {
	position: absolute;
	top: 0;
	left: 50%;
	width: 50%;
	height: 100%;
	overflow: hidden;
	transition: transform 0.6s ease-in-out;
	z-index: 100;
}

.overlay {
	background: #FF416C;
	background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
	background: linear-gradient(to right, #FF4B2B, #FF416C);
	background-repeat: no-repeat;
	background-size: cover;
	background-position: 0 0;
	color: #FFFFFF;
	position: relative;
	left: -100%;
	height: 100%;
	width: 200%;
  	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.overlay-panel {
	position: absolute;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 40px;
	text-align: center;
	top: 0;
	height: 100%;
	width: 50%;
	transform: translateX(0);
	transition: transform 0.6s ease-in-out;
}

.overlay-right {
	right: 0;
	transform: translateX(0);
}

footer {
    background-color: #222;
    color: #fff;
    font-size: 14px;
    bottom: 0;
    position: fixed;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 999;
}

footer p {
    margin: 10px 0;
}

footer i {
    color: red;
}

footer a {
    color: #3c97bf;
    text-decoration: none;
}
</style>


