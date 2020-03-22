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
						@if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif

						<form class="login-form" method="POST" action="{{ route('password.email') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label for="email" class="control-label">E-Mail Address</label>
								<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary">
									Send Password Reset Link
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
