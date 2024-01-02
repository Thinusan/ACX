<?php
if(!isset($_SESSION))
{
    session_start();
}

include("config.php");

if(isset($_SESSION["VERIFY_USERNAME"]))
{
   
    if(isset($_POST["btn_verify_code"]))
    {
        $username=$_SESSION["VERIFY_USERNAME"];
        $enter_code=$_POST["txt_mcode"];

        $sql_code="SELECT mcode FROM login WHERE userid='$username'";
        $result_code=mysqli_query($con,$sql_code) or die("Error in sql_code".mysqli_error($con));
        $row_code=mysqli_fetch_assoc($result_code);
        if($row_code["mcode"]==$enter_code)
        {
            unset($_SESSION["VERIFY_USERNAME"]);
            $_SESSION["FORGET_CHANGE_PASSWORD_USERNAME"]=$username;

           header("Location: http://localhost/ACX/changepassword.php");

        }
        else
        {
            echo'<script>alert("Sorry code is wrong!!!")</script>';
        }
    }

?>

<div class="container" id="container">
	<div class="form-container sign-in-container" id="sign_in">
		<form method="POST" action="">
			<h1>Verification</h1>
			<input type="text" placeholder="Enter your 4 digits verification code " name="txt_mcode" id="txt_mcode"/>
			<button type="submit" name="btn_verify_code" id="btn_verify_code">Verify Code</button>
				</form>
	</div>
			
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-right" >
				<h1>Its time to Verify</h1>
				<p>Enter 4 Digit Code sent to Your Mobile Number</p>
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
<?php
}
else
{
	header("Location: login.php");
}
?>