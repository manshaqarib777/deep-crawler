@extends('layouts.theme')
@section('main-content')
<!-- submit -->
	<section id="forum" class="coupon-page-main-block">
		<div class="container">
			<div class="forum-page-header">
				<div class="forum-page-heading-block">
					<h5 class="forum-page-heading">Submit Deals</h5>
				</div>
			</div>
			<!-- breadcrumb -->
			<div id="breadcrumb" class="breadcrumb-main-block">
				<div class="breadcrumb-block">
					<ol class="breadcrumb">
						<li><a href="{{url('/')}}" title="Home">Home</a></li>
						<li class="active">Submit Deals</li>
					</ol>
				</div>
			</div>
			<!-- breadcrumb end -->
			<div class="coupon-page-block categories-page submit-deal-page">
				<div class="coupon-dtl-outer">
					<div class="row">
						<div class="col-lg-9 col-md-8">
							<div class="submit-deal-main-block">
								<div class="form-group post-type">
									<label>Select Type</label>
									<ul class="nav" id="post-type" role="tablist">
									  <li class="nav-item">
									    <a class="nav-link active" id="coupon-tab" data-toggle="pill" href="#coupon" role="tab" aria-controls="coupon" aria-selected="false"><div class="post-type-icon"><i class="fas fa-tag"></i></div>Coupon</a>
									  </li>
									  <li class="nav-item">
									    <a class="nav-link" id="deal-tab" data-toggle="pill" href="#deal" role="tab" aria-controls="deal" aria-selected="false"><div class="post-type-icon"><i class="fas fa-handshake"></i></div>Deal</a>
									  </li>
									  <li class="nav-item">
									    <a class="nav-link" id="discussion-tab" data-toggle="pill" href="#discussion" role="tab" aria-controls="discussion" aria-selected="false"><div class="post-type-icon"><i class="fas fa-comments"></i></div>Discussion</a>
									  </li>
									</ul>
								</div>								
								<div class="form-group">
									<div class="tab-content" id="post-type-content">
									  <div class="tab-pane fade active show" id="coupon" role="tabpanel" aria-labelledby="coupon-tab">									  	
											{!! Form::open(['method' => 'POST', 'action' => 'UserDashboardController@coupon_post', 'files' => true, 'class' => 'submit-deal-form']) !!}											
												<input type="hidden" name="type" value="c"> 										
												<input type="hidden" name="user_id" value="{{$auth->id}}"> 
							          <div class="form-group{{ $errors->has('forum_category_id') ? ' has-error' : ''}}">
						              {!! Form::label('forum_category_id', 'Choose Coupon Category*') !!}
						              {!! Form::select('forum_category_id', $cat_coupon, null, ['class' => 'form-control select2', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('forum_category_id') }}</small>
							          </div> 
												<div class="form-group{{ $errors->has('store_id') ? ' has-error' : '' }}">
						              {!! Form::label('store_id', 'Choose Store*') !!}
						              {!! Form::select('store_id', $all_store, null, ['class' => 'form-control select2', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('store_id') }}</small>
						          	</div> 
						          	<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
						              {!! Form::label('category_id', 'Choose Category*') !!}
						              {!! Form::select('category_id', $all_category, null, ['class' => 'form-control select2', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('category_id') }}</small>
						          	</div> 
						          	<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						              {!! Form::label('title', 'Enter Coupon Name/Title*') !!}
						              {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Detailed Coupon Title', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('title') }}</small>
							          </div>  
							          <div id="ccode" class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
							              {!! Form::label('code', 'Enter Coupon Code*') !!}
							              {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Like SAVE10']) !!}
							              <small class="text-danger">{{ $errors->first('code') }}</small>
							          </div>     
							          <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
							              {!! Form::label('price', 'Coupon Price') !!}
							              {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Enter Actual Price of Coupon']) !!}
							              <small class="text-danger">{{ $errors->first('price') }}</small>
							          </div> 
							          <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
							              {!! Form::label('discount', 'Coupon Discount') !!}
							              {!! Form::text('discount', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Discount']) !!}
							              <small class="text-danger">{{ $errors->first('discount') }}</small>
							          </div>  
							          <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
							              {!! Form::label('link', 'Deal Product URL/Link') !!}
							              {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => 'https://www.foo.com']) !!}
							              <small class="text-danger">{{ $errors->first('link') }}</small>
							          </div>   
							          <div class="form-group{{ $errors->has('expiry') ? ' has-error' : '' }}">
						              {!! Form::label('expiry', 'Coupon Expiry Date') !!}
						              {!! Form::text('expiry', null, ['class' => 'form-control date-pick', 'placeholder' => 'DD/MM/YYYY']) !!}
						              <small class="text-danger">{{ $errors->first('expiry') }}</small>
						         		</div>
						          	<div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
						              {!! Form::label('detail', 'Coupon Description*') !!}
						              {!! Form::textarea('detail', null, ['class' => 'form-control', 'placeholder' => 'Enter Detailed Description','required']) !!}
						              <small class="text-danger">{{ $errors->first('detail') }}</small>
						          	</div>
							          <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
							            {!! Form::label('image', 'Choose Coupon Image') !!} 
							            {!! Form::file('image', ['class' => 'input-file', 'id'=>'image']) !!}
							            {{-- <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Coupon Image">
							              <i class="icon fa fa-check"></i>
							              <span class="js-fileName">Choose a File</span>
							            </label> --}}
							            <p class="info">Choose custom image</p>
							            <small class="text-danger">{{ $errors->first('image') }}</small>
							          </div> 
												<div class="form-group">
													<div class="submit-deal-btn">
														<button type="submit" class="btn btn-primary">Submit</button>
													</div>
												</div>
											{!! Form::close() !!}
									  </div>
										<div class="tab-pane fade active" id="deal" role="tabpanel" aria-labelledby="deal-tab">
											{!! Form::open(['method' => 'POST', 'action' => 'UserDashboardController@coupon_post', 'files' => true, 'class' => 'submit-deal-form']) !!}												
												<input type="hidden" name="type" value="d"> 			
												<input type="hidden" name="user_id" value="{{$auth->id}}"> 
												<div class="form-group{{ $errors->has('forum_category_id') ? ' has-error' : ''}}">
						              {!! Form::label('forum_category_id', 'Choose Deal Category*') !!}
						              {!! Form::select('forum_category_id', $cat_deal, null, ['class' => 'form-control select2', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('forum_category_id') }}</small>
							          </div> 
							          <div class="form-group{{ $errors->has('store_id') ? ' has-error' : '' }}">
						              {!! Form::label('store_id', 'Choose Store*') !!}
						              {!! Form::select('store_id', $all_store, null, ['class' => 'form-control select2', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('store_id') }}</small>
						          	</div> 
						          	<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
						              {!! Form::label('category_id', 'Choose Category*') !!}
						              {!! Form::select('category_id', $all_category, null, ['class' => 'form-control select2', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('category_id') }}</small>
						          	</div> 
						          	<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						              {!! Form::label('title', 'Enter Deal Name/Title*') !!}
						              {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Detailed Deal Title', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('title') }}</small>
							          </div>        
							          <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
						              {!! Form::label('price', 'Deal Price') !!}
						              {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Enter Actual Price of Deal']) !!}
						              <small class="text-danger">{{ $errors->first('price') }}</small>
							          </div> 
							          <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
							              {!! Form::label('discount', 'Deal Discount') !!}
							              {!! Form::text('discount', null, ['class' => 'form-control', 'placeholder' => 'Enter Deal Discount']) !!}
							              <small class="text-danger">{{ $errors->first('discount') }}</small>
							          </div>
							          <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
							              {!! Form::label('link', 'Deal Product URL/Link') !!}
							              {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => 'https://www.foo.com']) !!}
							              <small class="text-danger">{{ $errors->first('link') }}</small>
							          </div>
							          <div class="form-group{{ $errors->has('expiry') ? ' has-error' : '' }}">
						              {!! Form::label('expiry', 'Deal Expiry Date') !!}
						              {!! Form::text('expiry', null, ['class' => 'form-control date-picker']) !!}
						              <small class="text-danger">{{ $errors->first('expiry') }}</small>
						         		</div>
						          	<div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
						              {!! Form::label('detail', 'Deal Description*') !!}
						              {!! Form::textarea('detail', null, ['class' => 'form-control', 'placeholder' => 'Enter Detailed Description','required']) !!}
						              <small class="text-danger">{{ $errors->first('detail') }}</small>
						          	</div>
							          <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
							            {!! Form::label('image', 'Choose Deal Image') !!} - <p class="inline info">Help block text</p>
							            {!! Form::file('image', ['class' => 'input-file', 'id'=>'image']) !!}
							            {{-- <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Deal Image">
							              <i class="icon fa fa-check"></i>
							              <span class="js-fileName">Choose a File</span>
							            </label> --}}
							            <p class="info">Choose custom image</p>
							            <small class="text-danger">{{ $errors->first('image') }}</small>
							          </div> 
												<div class="form-group">
													<div class="submit-deal-btn">
														<button type="submit" class="btn btn-primary">Submit</button>
													</div>
												</div>
											{!! Form::close() !!}
										</div>
										<div class="tab-pane fade" id="discussion" role="tabpanel" aria-labelledby="discussion-tab">											
											{!! Form::open(['method' => 'POST', 'action' => 'UserDashboardController@discussion_post', 'files' => true, 'class' => 'submit-deal-form']) !!}    														
												<input type="hidden" name="type" value="g"> 			
												<input type="hidden" name="user_id" value="{{$auth->id}}"> 
							          <div class="form-group{{ $errors->has('forum_category_id') ? ' has-error' : ''}}">
						              {!! Form::label('forum_category_id', 'Choose Discussion Category*') !!}
						              {!! Form::select('forum_category_id', $cat_discussion, null, ['class' => 'form-control', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('forum_category_id') }}</small>
							          </div> 
						          	<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						              {!! Form::label('title', 'Discussion Title*') !!}
						              {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Discussion Title', 'required']) !!}
						              <small class="text-danger">{{ $errors->first('title') }}</small>
							          </div> 
							          <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
						              {!! Form::label('detail', 'Discussion Description*') !!}
						              {!! Form::textarea('detail', null, ['class' => 'form-control', 'placeholder' => 'Enter Detailed Description','required']) !!}
						              <small class="text-danger">{{ $errors->first('detail') }}</small>
							          </div>
							          <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
							            {!! Form::label('image', 'Discussion Image') !!} - <p class="inline info">Help block text</p>
							            {!! Form::file('image', ['class' => 'input-file', 'id'=>'image']) !!}
							            {{-- <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Discussion Image">
							              <i class="icon fa fa-check"></i>
							              <span class="js-fileName">Choose a File</span>
							            </label>
							            <p class="info">Choose custom image</p> --}}
							            <small class="text-danger">{{ $errors->first('image') }}</small>
							          </div>          
												<div class="form-group">
													<div class="submit-deal-btn">
														<button type="submit" class="btn btn-primary">Submit</button>
													</div>
												</div>
											{!! Form::close() !!}
									  </div>
									</div>
								</div>									
							</div>
						</div>
						<div class="col-lg-3 col-md-4">
							<div class="coupon-sidebar">
      					@include('includes.side-bar')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<!-- end forum -->
@endsection
{{-- @section('custom-scripts')
<script>
$(document).ready(function(){
	 $('.nav-item a[href="#{{ old('tab') }}"]').tab('show');

   $("#link").keyup(function(){
		var url = $(this).val();
	  console.log(url);
	  // var name = "codemzy";
		// var url1 = "http://anyorigin.com/go?url=" + encodeURIComponent(url) + "&callback=?";
		// $.get(url1, function(response) {
		//   console.log(response);
		// });
		// $("#msg").html("Loading Preview Please Wait..");
		// $("img").attr('src', "");
		$('#msg').load('grabber.php?url='+ url);
		// var url = $("#url").val();
	  // $.ajax({
   //  	url: 'https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=' + url + '&screenshot=true',
   //  	context: this,
   //  	type: 'GET',
   //  	dataType: 'json',
   //  	timeout: 60000,
   //  	success: function(result) {
   //  		console.log(result);
   //   		var imgData = result.screenshot.data.replace(/_/g, '/').replace(/-/g, '+');
   //    	$("img").attr('src', 'data:image/jpeg;base64,' + imgData);
   //    	$("#msg").html('');
   //      // $('#title').html(result.title);
   //    },
   //    error:function(e) {
   //    	$("#msg").html("Error to fetch image preview. Please enter full url (eg: http://www.coding4developers.com/)");
   //    }
   //  });
	});
});
</script>
@endsection --}}