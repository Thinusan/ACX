<?php
if(!isset($_SESSION))
{
    session_start();
}

include("config.php");

if(isset($_POST["btn_verify"]))
{
    $username=$_POST["txt_user_name"];
    $mobile_number=$_POST["txt_mobile_number"];

    $sql_username="SELECT * FROM login WHERE userid='$username'";
    $result_username=mysqli_query($con,$sql_username) or die("Error in sql_username".mysqli_error($con));
//check username

    if(mysqli_num_rows($result_username)>0)
    {//get mobile number
        $row_username=mysqli_fetch_assoc($result_username);


        $sql_mobile="SELECT tpno FROM staff WHERE tpno='$mobile_number'";
        $result_mobile=mysqli_query($con,$sql_mobile) or die("Error in sql_mobile".mysqli_error($con));
        $row_mobile=mysqli_fetch_assoc($result_mobile);

    //check mobile number   
        if($row_mobile["tpno"]==$mobile_number)
        {//send verification code
            $verify_code=rand(1000,9999);
            $sql_update_code="UPDATE login SET mcode='$verify_code' WHERE userid='$username'";
            $result_update_code=mysqli_query($con,$sql_update_code) or die("Error in sql_update_code".mysqli_error($con));

            $user = "94769669804";
            $password = "3100";
            $text = urlencode("ACX Phone Shop, Your verification code is ".$verify_code);
            $to = "94".$row_mobile["tpno"];
             
            $baseurl ="http://www.textit.biz/sendmsg";
            $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
            $ret = file($url);
             
            $res= explode(":",$ret[0]);
             
            if (trim($res[0])=="OK")
            {
            	echo'<script>alert("if come");</script>';
                if(isset($_SESSION["FORGET_USERNAME"]))
                {
                    unset($_SESSION["FORGET_USERNAME"]);
                }

                $_SESSION["VERIFY_USERNAME"]=$row_username["userid"];
                
                header("Location: http://localhost/ACX/verification.php");
            }
            else
            {
                 echo'<script>alert("Please check your internet connection!!!");</script>';
            }
        }
        else
        {
            echo'<script>alert("Sorry, Mobile number is worng!!!");</script>';
        }
    }
    else
    {
        echo'<script>alert("Sorry, There is no such username!!!");</script>';
    }
}
?>
<html>
<title>ACX Phone Shop</title>
<div class="container" id="container">
	<div class="form-container sign-in-container">
		<form action="" method="POST">
			<h1>Forgot Password</h1>
			<input type="text" placeholder="Username" name="txt_user_name" id="txt_user_name"/>
			<input type="text" placeholder="Mobile Number" name="txt_mobile_number" id="txt_mobile_number"/>
			<button type="submit" name="btn_verify" id="btn_verify">Verify</button>
		</form>
	</div>

	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-right">
				<h1>OOPS!</h1>
				<h2>Have you forgot your password?</h2>
				<p>Enter your USERNAME and MOBILE NUMBER</p>
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
	margin: 0;
}

h2 {
	text-align: center;
}

p {
	font-size: 14px;
	font-weight: 100;
	line-height: 20px;
	letter-spacing: 0.5px;
	margin: 20px 0 30px;
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
	padding: 12px 45px;
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

button.ghost {
	background-color: transparent;
	border-color: #FFFFFF;
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
	right: 0;
	width: 50%;
	z-index: 2;
}

.container.right-panel-active .sign-up-container {
	transform: translateX(100%);
	opacity: 1;
	z-index: 5;
	animation: show 0.6s;
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
	right: 50%;
	width: 50%;
	height: 100%;
	overflow: hidden;
	transition: transform 0.6s ease-in-out;
	z-index: 100;
}

.container.right-panel-active .overlay-container{
	transform: translateX(-100%);
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

.container.right-panel-active .overlay {
  	transform: translateX(50%);
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

.overlay-left {
	transform: translateX(-20%);
}

.container.right-panel-active .overlay-left {
	transform: translateX(0);
}

.overlay-right {
	right: 0;
	transform: translateX(0);
}

.container.right-panel-active .overlay-right {
	transform: translateX(20%);
}

.social-container {
	margin: 20px 0;
}

.social-container a {
	border: 1px solid #DDDDDD;
	border-radius: 50%;
	display: inline-flex;
	justify-content: center;
	align-items: center;
	margin: 0 5px;
	height: 40px;
	width: 40px;
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
</html>

