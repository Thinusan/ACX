<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php
if(!isset($_SESSION))
{
    session_start();
}
if(isset($_SESSION['LOGIN_USER_NAME']) && isset($_SESSION['LOGIN_USER_TYPE']))
{
	$username=$_SESSION['LOGIN_USER_NAME'];
	$usertype=$_SESSION['LOGIN_USER_TYPE'];
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
	<title>ACX Phone Shop</title>
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
	<meta name="keywords" content="Electro Store Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design"
	/>
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- //Meta tag Keywords -->

	<!-- Custom-Files -->
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<!-- Bootstrap css -->
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<!-- Main css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/fontawesome-all.css">
	<!-- Font-Awesome-Icons-CSS -->
	<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all" />
	<!-- pop-up-box -->
	<link href="css/menu.css" rel="stylesheet" type="text/css" media="all" />
	<!-- menu style -->
	 <link href="plugins/tables/css/datatable/dataTables.bootstrap4.min.css" rel="stylesheet">
	 	<!-- //Custom-Files -->

	<!-- web fonts -->
	<link href="//fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i&amp;subset=latin-ext" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese"
	    rel="stylesheet">
	<!-- select search dropdown -->
	<!-- Styles -->
	<link rel="stylesheet" href="assets/plugins/chosen/chosen.min.css" />
	<!-- Or for RTL support -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

</head>

<body>
	<!-- top-header -->
	


<!-- header-bottom-->
	<div class="header-bot">
		<div class="container">
			<div class="row header-bot_inner_wthreeinfo_header_mid">
				<!-- logo -->
			<!-- 	<div class="col-md-3 logo_agile">
					<h1 class="text-center">
						<a href="index.html" class="font-weight-bold font-italic">
							<img src="images/logo2.png" alt=" " class="img-fluid">Electro Store
						</a>
					</h1>
				</div -->
				<!-- //logo -->
				<!-- header-bot -->
				<div class="col-md-9 header mt-4 mb-md-0 mb-4">
					<div class="row">
						<!-- search -->
						<!-- <div class="col-10 agileits_search">
							<form class="form-inline" action="#" method="post">
								<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" required>
								<button class="btn my-2 my-sm-0" type="submit">Search</button>
							</form>
						</div> -->
						<!-- //search -->
						<!-- cart details -->
						<!-- <div class="col-2 top_nav_right text-center mt-sm-0 mt-2">
							<div class="wthreecartaits wthreecartaits2 cart cart box_1">
								<form action="#" method="post" class="last">
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="display" value="1">
									<button class="btn w3view-cart" type="submit" name="submit" value="">
										<i class="fas fa-cart-arrow-down"></i>
									</button>
								</form>
							</div>
						</div> -->
						<!-- //cart details -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- script validation-->
	<script type="text/javascript">
	function delete_record()
	{
		var x=confirm('Are you sure do you want to delete this record?');
		if(x)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	// call onclick="return delete_record()"


<script type="text/javascript"/>
	 //text validation
	function isTextKey(evt) // only text to allow the input field
   	{
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (((charCode >64 && charCode < 91)||(charCode >96 && charCode < 123)||charCode ==8 || charCode ==127||charCode ==32||charCode ==46)&&(!(evt.ctrlKey&&(charCode==118||charCode==86))))
         return true;
		
      	 return false;
	// call onkeypress="return isTextKey(event)"
   	}
	//number validation	
	function isNumberKey(evt) // only numbers to allow the input field
   	{
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
         return false;

      	 return true;
	//call onkeypress="return isNumberKey(event)"
   	}
</script>

<script type="text/javascript">
	//mobile number validation
   function phonenumber(txt_mobile_number) // Mobile No 
	{
		var phoneno = /^\d{10}$/;
		if(document.getElementById(txt_mobile_number).value=="")
		{
		}
		else
		{
			if( document.getElementById(txt_mobile_number).value.match(phoneno))
			{
				hand(txt_mobile_number);
				land(txt_land_number);

			}
			else
			{
				alert("Enter 10 digit Mobile Number");
				document.getElementById(txt_mobile_number).value="";
				document.getElementById(txt_mobile_number).focus()=true;		
				return false;
			}
		}
		//call onblur="phonenumber('txt_customer_mobile_number')"
	}
	function hand(txt_mobile_number)
	{
		var str = document.getElementById(txt_mobile_number).value;
		var res = str.substring(0, 2);
		if(res=="07")
		{
			return true;
		}
		else
		{
				alert("Enter 10 digit of Mobile Number start with 07xxxxxxxx");
				document.getElementById(txt_mobile_number).value="";
				document.getElementById(txt_mobile_number).focus()=true;			
				return false;
		}
		
	}
	//land phone number validation
   function landphonenumber(txt_land_number) // Land No 
	{
		var landno = /^\d{10}$/;
		if(document.getElementById(txt_land_number).value=="")
		{
		}
		else
		{
			if( document.getElementById(txt_land_number).value.match(landno))
			{
				land(txt_land_number);
			}
			else
			{
				alert("Enter 10 digit Land Phone Number");
				document.getElementById(txt_land_number).value="";
				document.getElementById(txt_land_number).focus()=true;		
				return false;
			}
		}	 
	}
	function land(txt_land_number)
	{
		var str = document.getElementById(txt_land_number).value;
		var res = str.substring(0, 2);
		if(res=="02")
		{
			return true;
		}
		else
		{
				alert("Enter 10 digit of Land Phone Number Ex 021xxxxxxx");
				document.getElementById(txt_land_number).value="";
				document.getElementById(txt_land_number).focus()=true;			
				return false;
		}
		//call onblur="landphonenumber('txt_customer_mobile_number')"
	}
</script>
<script type="text/javascript">
	//nic validation
	function nicnumber(txt_nic_number)
	{
		var nic=document.getElementById(txt_nic_number).value;
		if(nic.length==10)
		{
			var nicformat1=/^[0-9]{9}[a-zA-Z0-9]{1}$/;
			if(nic.match(nicformat1))
			{
				var nicformat2=/^[0-9]{9}[vVxX]{1}$/;
				if(nic.match(nicformat2))
				{
					//calculatedob(nic);
				}
				else
				{
					alert("Last character must be V/v/X/x");
					document.getElementById(txt_nic_number).value="";
					document.getElementById(txt_nic_number).focus();
				}
			}
			else
			{
				alert("First 9 characters must be numbers");
				document.getElementById(txt_nic_number).value="";	
				document.getElementById(txt_nic_number).focus();
			}	
		}
		else if(nic.length==12)
		{		
			var nicformat3=/^[0-9]{12}$/;
			if(nic.match(nicformat3))
			{
				//calculatedob(nic);
			}
			else
			{
				alert("All 12 characters must be number");
				document.getElementById(txt_nic_number).value="";
				document.getElementById(txt_nic_number).focus();
			}
		}
		else if(nic.length==0)
		{
			// document.getElementById("txt_dob").value="";
			// document.getElementById("txt_gender").value ="NO";
		}
		else
		{
			alert("NIC No must be 10 or 12 Characters");
			document.getElementById(txt_nic_number).value="";
			document.getElementById(txt_nic_number).focus();
		}
		//call onblur="nicnumber('txt_customer_nic_number')"
	}
</script>


	<!-- shop locator (popup) -->
	<!-- //header-bottom -->
	<!-- navigation -->
		<?php
			include("menu.php");
		?>
	<!-- //navigation -->

	<!-- banner -->
		<?php
			if(isset($_GET["page"]))
			{
				include("page_banner.php");
			}
			else
			{

				include("home_banner.php");
			}
		?>
	<!-- //banner -->

	<!-- body -->
		<?php
			if(isset($_GET["page"]))
			{	if($_GET["page"]!="")
				{	if(file_exists($_GET["page"]))
					{
						$page=$_GET["page"];
						include($page);
					}else
					{
						echo'<div class="card-body">
	                            <div class="alert alert-danger" role="alert">
	                            	<center><h1><b>OOPS!</b></h1></center>
	                                <center><h3>- 404 Not Found -</h3></center>
	                                <center><h4>Sorry, Requested page not found!</h4></center>
	                            </div>
                    		</div>'; 
					}
				}else
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
				include("body.php");
			}
		?>
	<!-- //body -->

	

	<!-- footer -->
	<footer>
		<!-- footer third section -->
		<div class="w3l-middlefooter-sec">
			<div class="container py-md-5 py-sm-4 py-3">
				<div class="row footer-info w3-agileits-info">
		
				
					<!-- //contact us-->
					<div class="col-md-7 col-sm-4 footer-grids mt-sm-0 mt-2">
						<h3 class="text-white font-weight-bold mb-3" align="center">Contact Us</h3>
						<ul align="center">
							<li class="mb-3">
								<i class="fas fa-map-marker"></i>No-06, New Market (Upstairs), Jaffna</li>
							<li class="mb-3">
								<i class="fas fa-mobile"></i> 077 751 9440</li>
							<li class="mb-3">
								<i class="fas fa-phone"></i> 021 222 6151</li>
							<li class="mb-3">
								<i class="fas fa-envelope-open"></i>
								<a href="mailto:example@mail.com"> acxsuren@yahoo.com</a>
							</li>
						</ul>
					</div>
				
					<div class="col-md-3 col-sm-6 footer-grids w3l-agileits mt-md-0 mt-4">
						<!-- social icons -->
							<h3 class="text-white font-weight-bold mb-3" align="center">Follow Us on </h3>
							<div class="social" align="center">
								<ul>
									<li>
										<a class="icon fb" href="https://www.facebook.com/">
											<i class="fab fa-facebook-f"></i>
										</a>
										<a class="icon tw" href="https://www.twitter.com/">
											<i class="fab fa-twitter"></i>
										</a>
										<a class="icon tw" href="https://www.instagram.com/">
											<i class="fab fa-instagram"></i>
										</a>
								</ul>
							</div>
					</div>
	

			</div>
		</div>		
	</div>
</div>
		<!-- //footer fourth section (text) -->
	</footer>
	<!-- //footer -->
	<!-- copyright -->
	<div class="copy-right py-3">
		<div class="container">
			<p class="text-center text-white"><b>- © 2022 ACX Phone Shop. All rights reserved -</b></p>
		</div>
	</div>
	<!-- //copyright -->

	<!-- js-files -->
	

	<!-- jquery -->
	<script src="js/jquery-2.2.3.min.js"></script>
	<!-- //jquery -->

	<script src="plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/tables/js/datatable-init/datatable-basic.min.js"></script>

	<!-- nav smooth scroll -->
	<script>
		$(document).ready(function () {
			$(".dropdown").hover(
				function () {
					$('.dropdown-menu', this).stop(true, true).slideDown("fast");
					$(this).toggleClass('open');
				},
				function () {
					$('.dropdown-menu', this).stop(true, true).slideUp("fast");
					$(this).toggleClass('open');
				}
			);
		});
	</script>
	<!-- //nav smooth scroll -->

	<!-- popup modal (for location)-->
	<script src="js/jquery.magnific-popup.js"></script>
	<script>
		$(document).ready(function () {
			$('.popup-with-zoom-anim').magnificPopup({
				type: 'inline',
				fixedContentPos: false,
				fixedBgPos: true,
				overflowY: 'auto',
				closeBtnInside: true,
				preloader: false,
				midClick: true,
				removalDelay: 300,
				mainClass: 'my-mfp-zoom-in'
			});

		});
	</script>
	<!-- //popup modal (for location)-->

	<!-- cart-js -->
	<script src="js/minicart.js"></script>
	<script>
		paypals.minicarts.render(); //use only unique class names other than paypals.minicarts.Also Replace same class name in css and minicart.min.js

		paypals.minicarts.cart.on('checkout', function (evt) {
			var items = this.items(),
				len = items.length,
				total = 0,
				i;

			// Count the number of each item in the cart
			for (i = 0; i < len; i++) {
				total += items[i].get('quantity');
			}

			if (total < 3) {
				alert('The minimum order quantity is 3. Please add more to your shopping cart before checking out');
				evt.preventDefault();
			}
		});
	</script>
	<!-- //cart-js -->

	<!-- password-script -->
	<script>
		window.onload = function () {
			document.getElementById("password1").onchange = validatePassword;
			document.getElementById("password2").onchange = validatePassword;
		}

		function validatePassword() {
			var pass2 = document.getElementById("password2").value;
			var pass1 = document.getElementById("password1").value;
			if (pass1 != pass2)
				document.getElementById("password2").setCustomValidity("Passwords Don't Match");
			else
				document.getElementById("password2").setCustomValidity('');
			//empty string means no validation error
		}
	</script>
	<!-- //password-script -->
	
	<!-- scroll seller -->
	<script src="js/scroll.js"></script>
	<!-- //scroll seller -->

	<!-- smoothscroll -->
	<script src="js/SmoothScroll.min.js"></script>
	<!-- //smoothscroll -->

	<!-- start-smooth-scrolling -->
	<script src="js/move-top.js"></script>
	<script src="js/easing.js"></script>
	<script>
		jQuery(document).ready(function ($) {
			$(".scroll").click(function (event) {
				event.preventDefault();

				$('html,body').animate({
					scrollTop: $(this.hash).offset().top
				}, 1000);
			});
		});
	</script>
	<!-- //end-smooth-scrolling -->

	<!-- smooth-scrolling-of-move-up -->
	<script>
		$(document).ready(function () {
			/*
			var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
			};
			*/
			$().UItoTop({
				easingType: 'easeOutQuart'
			});

		});
	</script>
	
	<!-- //smooth-scrolling-of-move-up -->

	<!-- for bootstrap working -->
	<script src="js/bootstrap.js"></script>
	<!-- //for bootstrap working -->
	<!-- select search dropdown -->
	



	      <!-- PAGE LEVEL SCRIPT-->

<script src="assets/plugins/inputlimiter/jquery.inputlimiter.1.3.1.min.js"></script>
<script src="assets/plugins/chosen/chosen.jquery.min.js"></script>
<script src="assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<script src="assets/plugins/autosize/jquery.autosize.min.js"></script>
       <script src="assets/js/formsInit.js"></script>
        <script>
            $(function () { formInit(); });
        </script>
        
     <!--END PAGE LEVEL SCRIPT-->

	
</body>

</html>