


 <!--header section start-->
 @if($pdfData)
  @foreach($pdfData as $eachData)
  <div class="onepage">
 <header>
	<div class="container">
		<div class="col-6">
			<h1>PRODUKTKATALOG</h1>
			<p>
			Produkter tilgjengelig :  {{$eachData['project_avail_date']}} - {{$eachData['project_avail_end_date']}}<br/>
			Status : {{$eachData['project_status']}} <br/>
			
			</p>
		</div>
	
		<div class="col-11" >
			<!-- <img src="{{ URL::asset('images/logo.png') }}"> -->
			<!-- <div class="logo">
			<h2>Digits</h2>
		</div> -->
		</div>
	</div>
 </header>
  <!--header section end-->
  
  <div class="home-section">
	<div class="container">
	<div class="left-home-section">
			<ul>
				<!-- <li><span> <br/><br/></span> </li> -->
				<li><span >Kundenavn : {{$eachData['customer']['customer_name']}}</span> </li>
				<li ><span style="white-space: nowrap;" >Prosjektnavn : {{$eachData['project_name']}}</span> <br/><span >Prosjetadresse : {{$eachData['project_address']}} <br/></span> </li>
			</ul>
		 
		</div>
		
		<div class="right-home-section">
		@if(!empty($eachData['project_image']))
			<img height="400" src="https://d10rdxeixe5doh.cloudfront.net/uploads/projects/{{$eachData['project_image']}}">
		
		@else
			<img height="400" src="{{ URL::asset('images/default.jpg')}}">
		@endif
		</div>
	</div>
  </div>
  <div style="page-break-after:always">&nbsp;</div> 
</div>
<!-- page one-->
  <div style="page-break-after:always">&nbsp;</div> 
	<div class="listing-section"> 
	
		<div class="container">
			<h3>PRODUKTKATALOG</h3><!-- <img src="{{ URL::asset('images/logo.png') }}">-->	
         	@foreach($eachData['products'] as $k=>$eachprod)
				<div class="col-12">
					<div class="one">

					@if(!empty($eachprod['product_image']))

						<img height="440" src="d10rdxeixe5doh.cloudfront.net/uploads/products/{{$eachprod['product_image']}}" >
					@else
					<img height="440" src="{{ URL::asset('images/default.jpg')}}">
					@endif
					</div>
					<div class="two">
						<ul class="listing-ul">
							<li>
							<div><h5>{{$k+1}} {{$eachprod['category']['category_name']}}</h5></div>
								<!-- <div><h5> {{$eachprod['product_name']}} </h5></div> -->
								<div><h5> BYGNINGSDEL : {{$eachprod['building_part']}} </h5></div>
								<div><h5>PRODUKT ID : {{$eachprod['product_id']}} </h5></div>
							</li>
							
							<li>
								<div>
									<h6>PLASSERING I BYGG</h6>
									<span class="wordspacing"> {{$eachprod['location_building']}}</span>
								</div>
								<div>
									<h6>BESKRIVELSE </h6>
									<span> {{$eachprod['description']}}</span>
								</div>
							</li>
							
							<li>
								<div>
									<h6>MENGDE  </h6>
									<span>{{$eachprod['quantity']}} {{$eachprod['unitqnt']}}</span>
								</div>
								<div>
									<h6>PRODUKTINFO</h6>
									<span>{{$eachprod['product_info']}}</span>
								</div>
							</li>
							
							<li>
								<div>
									<h6>DIMENSJONER  </h6>
									<span>L: {{$eachprod['length']}} {{$eachprod['unit']}}<br/>W: {{$eachprod['width']}} {{$eachprod['unit']}} <br/>H: {{$eachprod['height']}} {{$eachprod['unit']}}</span>
								</div>
								<div>
									<h6>POTENSIALE </h6>
									<span>{{$eachprod['reuse']}}</span>
								</div>
							</li>
							
							
							<li>
								<div>
									<h6>PROD. ÅR  </h6>
									<span>{{$eachprod['production_year']}}</span>
								</div>
								<div>
									<h6> VURDERING </h6>
									<span>{{$eachprod['evaluvation']}}</span>
								</div>
							</li>
						
							<li>
								<div>
									<h6>PRODUSENT </h6>
									<span class="wordspacing">{{$eachprod['brand_name']}}</span>
								</div>
								<div>
									<h6>FORUTSETNING </h6>
									<span>{{$eachprod['precondition']}}</span>
								</div>
							</li>
							
							<li>
								<div>
									<h6>DOKUMENTASJON </h6>
									<span>{{($eachprod['documentation'] == '1') ? "YES" : "NO"}}</span>
								</div>
								<div>
									<h6>ANBEFALING </h6>
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