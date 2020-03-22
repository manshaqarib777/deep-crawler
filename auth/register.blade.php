@extends('layouts.theme')
@section('main-content')
	<!-- Register -->
	<section id="forum" class="coupon-page-main-block">
		<div class="container">
			<div class="forum-page-header">
				<div class="forum-page-heading-block">
					<h5 class="forum-page-heading">Login</h5>
				</div>
			</div>
			<!-- breadcrumb -->
			<div id="breadcrumb" class="breadcrumb-main-block">
				<div class="breadcrumb-block">
					<ol class="breadcrumb">
	          <li><a href="{{url('/')}}" title="Home">Home</a></li>
	          <li class="active">Register</li>
	        </ol>
				</div>
			</div>
			<!-- breadcrumb end -->
			<div class="forum-page-header">
				<div class="row">
					<div class="offset-md-2 col-md-8">
						<div class="login-page-form">
							<div class="forum-page-heading-block">
								<h5 class="forum-page-heading">Register</h5>
							</div>
							<form class="login-form" method="POST" action="{{ route('register') }}">
								{{ csrf_field() }}

								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									{{-- <label for="name" class="control-label">Name</label> --}}
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>

									@if ($errors->has('name'))
										<span class="help-block">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('email1') ? ' has-error' : '' }}">
									{{-- <label for="email" class="control-label">E-Mail Address</label> --}}
									<input id="email" type="email" class="form-control" name="email1" value="{{ old('email1') }}" placeholder="Email" required>

									@if ($errors->has('email1'))
										<span class="help-block">
											<strong>{{ $errors->first('email1') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									{{-- <label for="password" class="control-label">Password</label> --}}
									<input id="password" type="password" class="form-control" name="password"  placeholder="Password" required>
	 
									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group">
									{{-- <label for="password-confirm" class="control-label">Confirm Password</label> --}}

									<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary">
										Register
									</button>
								</div>
								<div class="or-text text-center">Or</div>
			      		<div class="form-group">
			      			<div class="row">
				      			<div class="col-md-6">
				      				<div class="form-group">
						      			<a href="{{ url('/auth/facebook') }}" class="btn btn-primary fb-btn" title="Register With Facebook"><i class="fab fa-facebook-f"></i>Register With Facebook</a>
						      		</div>
				      			</div>
				      			<div class="col-md-6">
				      				<div class="form-group">
						      			<a href="{{ url('/auth/google') }}" class="btn btn-primary gplus-btn" title="Register With Google"><i class="fab fa-google"></i>Register With Google</a>
						      		</div>
				      			</div>
				      		</div>
			      		</div>
			      		<div class="form-group text-right">
			      			<a href="{{route('login')}}">Already have an account? Login Now</a>
			      		</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
