@extends('layouts.theme')
@section('main-content')
<!-- breadcrumb -->
<section id="breadcrumb" class="breadcrumb-main-block">
	<div class="container">
		<div class="breadcrumb-block">
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}" title="Home">Home</a></li>
        <li class="active">Reset Password</li>
      </ol>
    </div>
	</div>
</section>
<!-- breadcrumb end -->
<!-- forum -->
<section id="forum" class="coupon-page-main-block">
	<div class="container">
		<div class="forum-page-header">
			<div class="row">
				<div class="col-md-6">
					<div class="forum-page-heading-block">
						<h5 class="forum-page-heading">Reset Password</h5>
					</div>
				</div>
			</div>
		</div>
		<div class="coupon-page-block categories-page">
			<div class="coupon-dtl-outer">
				<div class="row">
					<div class="offset-md-3 col-md-6">
						<form class="login-form" method="POST" action="{{ route('password.request') }}">
							{{ csrf_field() }}
							<input type="hidden" name="token" value="{{ $token }}">

							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label for="email" class="control-label">E-Mail Address</label>
								<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label for="password" class="control-label">Password</label>
								<input id="password" type="password" class="form-control" name="password" required>

								@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
								<label for="password-confirm" class="control-label">Confirm Password</label>
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

								@if ($errors->has('password_confirmation'))
									<span class="help-block">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary">
									Reset Password
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
