@extends('layouts.theme')
@section('main-content')
	<!-- profile -->
	<section id="forum" class="coupon-page-main-block">
		<div class="container">
			<div class="forum-page-header">
				<div class="forum-page-heading-block">
					<h5 class="forum-page-heading">Edit Profile</h5>
				</div>
			</div>
			<!-- breadcrumb -->
			<div id="breadcrumb" class="breadcrumb-main-block">
				<div class="breadcrumb-block">
					<ol class="breadcrumb">
						<li><a href="{{url('/')}}" title="Home">Home</a></li>
						<li class="active">Edit Profile</li>
					</ol>
				</div>
			</div>
			<!-- breadcrumb end -->
			<div class="coupon-page-block categories-page edit-profile-page">
				<div class="coupon-dtl-outer">
					<div class="row">
						<div class="col-lg-9 col-md-8">
							<div class="submit-deal-main-block">
								{!! Form::model($auth, ['method' => 'PATCH','action' => ['UserDashboardController@profile_update', $auth->id], 'files' => true, 'class' => 'submit-deal-form contact-form']) !!}
									 {{ csrf_field() }}
									<div class="row">
										<div class="col-md-3 ac-profile-img">
											<img src="{{asset('images/user/'.$auth->image)}}" alt="User">
										</div>
										<div class="col-md-9 form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
					            {!! Form::label('image', 'User Image') !!} - <p class="inline info">Help block text</p>
					            {!! Form::file('image', ['class' => 'input-file', 'id'=>'image']) !!}
					            <p class="info">Choose custom image</p>
					            <small class="text-danger">{{ $errors->first('image') }}</small>
					          </div> 
					        </div>
									<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
										{!! Form::label('name', 'Enter Your Full Name*') !!}
										{!! Form::text('name', null, ['class' => 'form-control']) !!}
										<small class="text-danger">{{ $errors->first('name') }}</small>
									</div> 
									<div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
										{!! Form::label('dob', 'Date of Birth') !!}
										{!! Form::date('dob', null, ['class' => 'form-control']) !!}
										<small class="text-danger">{{ $errors->first('dob') }}</small>
									</div> 
									<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
										{!! Form::label('email', 'Email Address*') !!}
										{!! Form::email('email', null, ['class' => 'form-control', 'disabled']) !!}
										<small class="text-danger">{{ $errors->first('email') }}</small>
									</div> 
									<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
										{!! Form::label('mobile', 'Enter Your Mobile Number*') !!}
										{!! Form::text('mobile', null, ['class' => 'form-control', 'required']) !!}
										<small class="text-danger">{{ $errors->first('mobile') }}</small>
									</div> 
									<div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
										{!! Form::label('gender', 'Choose Your Gender') !!}
										{!! Form::select('gender', ['m' => 'Male', 'f' => 'Female'], null, ['class' => 'form-control select2']) !!}
										<small class="text-danger">{{ $errors->first('gender') }}</small>
									</div> 
									<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
										{!! Form::label('address', 'Enter Your Address') !!}
										{!! Form::textarea('address', null, ['class' => 'form-control']) !!}
										<small class="text-danger">{{ $errors->first('address') }}</small>
									</div> 
									<div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
										{!! Form::label('website', 'Enter Your Website') !!}
										{!! Form::text('website', null, ['class' => 'form-control']) !!}
										<small class="text-danger">{{ $errors->first('website') }}</small>
									</div>
									<div class="form-group{{ $errors->has('brief') ? ' has-error' : '' }}">
										{!! Form::label('brief', 'Biography') !!}
										{!! Form::textarea('brief', null, ['class' => 'form-control']) !!}
										<small class="text-danger">{{ $errors->first('brief') }}</small>
									</div> 
									<div class="form-group">
										<div class="submit-deal-btn">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								{!! Form::close() !!} 
								<div class="form-group">
								  <a data-toggle="collapse" href="#changePassword" role="button" aria-expanded="false" aria-controls="changePassword">
							    	Want to change your password?
						  	  </a>
								</div> 
								<div class="collapse" id="changePassword">
									{!! Form::model($auth, ['method' => 'PATCH','action' => ['UserDashboardController@change_password', $auth->id], 'class' => 'submit-deal-form contact-form']) !!}
								 	{{ csrf_field() }}
									  <div class="form-group">
											<label for="old_password">Enter Old Password</label>
											<input type="password" id="old_password" name="old_password" class="form-control" placeholder="Enter Old Password" required="">
										</div>
										<div class="form-group">
											<label for="new_password">Enter New Password</label>
											<input type="password" id="new_password" class="form-control" name="new_password" placeholder="Enter New Password" required="">
										</div>
										<div class="form-group">
											<label for="new_password_confirmation">Confirm New Password</label>
											<input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirm New Password" required="">
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