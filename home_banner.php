<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		<!-- Indicators-->
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
		</ol>
		<div class="carousel-inner">
			<div class="carousel-item item1 active">
				<div class="container">
					<div class="w3l-space-banner">
						
					</div>
				</div>
			</div>
			<div class="carousel-item item2">
				<div class="container">
					<div class="w3l-space-banner">
						
					</div>
				</div>
			</div>
			<div class="carousel-item item3">
				<div class="container">
					<div class="w3l-space-banner">
						
					</div>
				</div>
			</div>
			<div class="carousel-item item4">
				<div class="container">
					<div class="w3l-space-banner">
						
					</div>
				</div>
			</div>
		</div>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>

<!-- product -->
	<div class="ads-grid py-sm-5 py-4">
		<div class="container py-xl-4 py-lg-2">
			<!-- tittle heading -->
			<!-- <h3 class="tittle-w3l text-center mb-lg-5 mb-sm-4 mb-3">
				<span>O</span>ur
				<span>N</span>ew
				<span>P</span>roducts</h3> -->
			<!-- //tittle heading -->
			<div class="row">
				<!-- product left -->
				<div class="agileinfo-ads-displaymb-lg-5 mb-sm-4 mb-3">
					<div class="wrapper">
						<!-- first section -->
						<?php 
						include("config.php");
						$sql_category_details_view="SELECT catid,catname FROM category WHERE showcase='Yes'";
                        $result_category_details_view=mysqli_query($con,$sql_category_details_view)or die("Error in category details view".mysqli_error($con));
                        while ($row_category_details_view=mysqli_fetch_assoc($result_category_details_view)) 
                        {
                        	echo '<div class="product-sec1 px-sm-4 px-3 py-sm-5  py-3 mb-4">
							<h3 class="heading-tittle text-center font-italic">New Brand '.$row_category_details_view["catname"].'</h3>
							<div class="row">';

							// get model price and model no
							$sql_get_model_price="SELECT * FROM modelprice WHERE  enddate IS NULL";
                            $result_get_model_price=mysqli_query($con,$sql_get_model_price)or die("Error in getting category name".mysqli_error($con));
                            while($row_get_model_price=mysqli_fetch_assoc($result_get_model_price))
                            {
	                        	$sql_model_details_view="SELECT * FROM model WHERE catid='$row_category_details_view[catid]' AND modelno='$row_get_model_price[modelno]' ORDER BY modelno DESC ";
	                            $result_model_details_view=mysqli_query($con,$sql_model_details_view)or die("Error in model details view".mysqli_error($con));
	                            while ($row_model_details_view=mysqli_fetch_assoc($result_model_details_view)) 
	                            {
	                            	
	                            	echo'<div class="col-md-4 product-men mt-5">
										<div class="men-pro-item simpleCart_shelfItem">
											<div class="men-thumb-item text-center">
												<img src="product_image/'.$row_model_details_view["productimage"].'?'.date("H:i:s").'" alt="" width="100px" height="200px">
												
												<span class="product-new-top">New</span>
											</div>
											<div class="item-info-product text-center border-top mt-4">
												<h4 class="pt-1">
													<label>'.$row_model_details_view["modelname"].'</label>
												</h4>
												<div class="info-product-price my-2">
													<span class="item_price">Rs '.$row_get_model_price["salesprice"].'</span>
												</div>
											</div>
										</div>
									</div>';
	                            }
	                        }
                            echo '</div></div>';
                        }
						?>
						
<!-- product -->