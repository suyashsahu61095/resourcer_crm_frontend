
 <!--header section start-->
 @if($pdfData)
  @foreach($pdfData as $eachData)
 <header>
	<div class="container">
		<div class="col-6">
			<h1>PRODUCT CATALOGUE</h1>
			<p>Project Name : {{$eachData['project_name']}} <br/>
			Project Address : {{$eachData['project_address']}} <br/>
			Customer Name : {{$eachData['customer']['customer_name']}} 
			<br/>

			</p>
		
			<p>Status: {{$eachData['project_status']}} <br/>
			   Products available:  {{$eachData['project_avail_date']}} - {{$eachData['project_avail_end_date']}}
			</p>
			
		</div>
		
		<div class="col-6">
			<!-- <img src="{{ URL::asset('images/logo.png') }}"> -->
			<h2>Digits</h2>
		</div>
		
	</div>
 </header>
  <!--header section end-->
  
  <div class="home-section">
	<div class="container">
		
		<div class="right-home-section">
			<img height="400" src="http://testdigits.s3-website-eu-west-1.amazonaws.com/uploads/projects/{{$eachData['project_image']}}">
		</div>
	</div>
  </div>
  <div style="page-break-after:always">&nbsp;</div> 
	<div class="listing-section">
		<div class="container">
			<h3>PRODUCT CATALOGUE </h3> <!-- <img src="{{ URL::asset('images/logo.png') }}">-->
			@foreach($eachData['products'] as $k=>$eachprod)
				<div class="col-12">
					<div class="one">
						<img height="417" src="http://testdigits.s3-website-eu-west-1.amazonaws.com/uploads/products/{{$eachprod['product_image']}}">
					</div>
					<div class="two">
						<ul class="listing-ul">
							<li>
								<div><h5> {{$eachprod['product_name']}} </h5></div>
								<div><h5> BUILDING PART: {{$eachprod['building_part']}} </h5></div>
								<div><h5>PRODUCT ID .: {{$eachprod['product_id']}} </h5></div>
							</li>
							
							<li>
								<div>
									<h6>LOCATION IN BUILDING</h6>
									<span> {{$eachprod['location_building']}}</span>
								</div>
								<div>
									<h6>DESCRIPTION </h6>
									<span> {{$eachprod['description']}}</span>
								</div>
							</li>
							
							<li>
								<div>
									<h6>QUANTITY  </h6>
									<span>{{$eachprod['quantity']}} {{$eachprod['unitqnt']}}</span>
								</div>
								<div>
									<h6>MODEL DESCRIPTION </h6>
									<span>{{$eachprod['product_info']}}</span>
								</div>
							</li>
							
							<li>
								<div>
									<h6>DIMENSIONS  </h6>
									<span>L: {{$eachprod['length']}} {{$eachprod['unit']}}<br/>W: {{$eachprod['width']}} {{$eachprod['unit']}} <br/>H: {{$eachprod['height']}} {{$eachprod['unit']}}</span>
								</div>
								<div>
									<h6>POTENTIAL </h6>
									<span>{{$eachprod['reuse']}}</span>
								</div>
							</li>
							
							
							<li>
								<div>
									<h6>PRODUCT YEAR  </h6>
									<span>{{$eachprod['production_year']}}</span>
								</div>
								<div>
									<h6>EVALUVATION </h6>
									<span>{{$eachprod['evaluvation']}}</span>
								</div>
							</li>
							
							
							<li>
								<div>
									<h6>BRAND NAME </h6>
									<span>{{$eachprod['brand_name']}}</span>
								</div>
								<div>
									<h6>PRECONDITIONS FOR REUSE </h6>
									<span>{{$eachprod['precondition']}}</span>
								</div>
							</li>
							
							<li>
								<div>
									<h6>DOCUMENTATION </h6>
									<span>{{($eachprod['documentation'] == '1') ? "YES" : "NO"}}</span>
								</div>
								<div>
									<h6>RECOMMENDATION </h6>
									<span>{{$eachprod['recommendation']}}</span>
								</div>
							</li>

						</ul>
						
					</div>
					
				</div>
			@endforeach
		</div>
	</div>
	<div style="page-break-after:always">&nbsp;</div> 
  @endforeach
 @endif 
<!-- 	
 </body>

</html> -->