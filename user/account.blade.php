@extends('layouts.theme')
@section('main-content')
	<!-- account -->
	<section id="account" class="coupon-page-main-block account-page-main">
		<div class="container">
			<div class="forum-page-header">
				<div class="forum-page-heading-block">
					<h5 class="forum-page-heading">My Account</h5>
				</div>
			</div>
			<!-- breadcrumb -->
			<div id="breadcrumb" class="breadcrumb-main-block">
				<div class="breadcrumb-block">
					<ol class="breadcrumb">
						<li><a href="{{url('/')}}" title="Home">Home</a></li>
						<li class="active">My Account</li>
					</ol>
				</div>
			</div>
			<!-- breadcrumb end -->
			<div class="account-page">
				<div class="coupon-dtl-outer">
					<div class="row">
						<div class="col-md-9">
							<div class="account-main-block account-box">
								<div class="row">
									<div class="col-lg-3">
										<div class="ac-profile-block">
											<div class="ac-profile-img">
												<img src="{{asset('images/user/'.$auth->image)}}" alt="User">
											</div>
											<h6 class="ac-username">{{$auth->name}}</h6>
											<div class="ac-post">{{$auth->is_admin == '1' ? 'Administrator' : 'User'}}</div>
										</div>
									</div>
									<div class="col-lg-9">
										<div class="ac-profile-dtl">
											<h6 class="ac-holder-name">{{$auth->name}}</h6>
											<div class="join-date">Joined Coupon on {{$auth->created_at->format('jS F, Y')}}</div>
											<div class="ac-holder-info">
												<p>{{$auth->brief}}</p>
											</div>
										</div>
										<div class="row">
											@if(count($followers)>0)
												<div class="col-md-4">
													<div class="ac-btn">
														<a id="ac-followers-btn" href="#ac-followers" class="btn btn-primary" title="Followers" data-scroll>Followers</a>
													</div>
												</div>
											@endif											
											@if(count($followings) >0)
												<div class="col-md-4">
													<div class="ac-btn">
														<a id="ac-followings-btn" href="#ac-followings" class="btn btn-primary" title="Followings" data-scroll>Followings</a>
													</div>
												</div>
											@endif
											<div class="col-md-4">
												<div class="ac-btn">
													<a href="{{url('user/profile-edit')}}" class="btn btn-primary" title="Edit Profile">Edit Profile</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="ac-facts account-box text-center">
								<div class="row">
									<div class="col-lg-3 col-md-6">
										<div class="facts-block">
											<h1 class="fact-heading">{{$deal}}</h1>
											<h6 class="fact-name">Deals Posted</h6>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="facts-block">
											<h1 class="fact-heading">{{$coupon}}</h1>
											<h6 class="fact-name">Coupons Posted</h6>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="facts-block">
											<h1 class="fact-heading">{{count($followers)}}</h1>
											<h6 class="fact-name">Followers</h6>
										</div>
									</div>
									<div class="col-lg-3 col-md-6">
										<div class="facts-block">
											<h1 class="fact-heading">{{count($followings)}}</h1>
											<h6 class="fact-name">Followings</h6>
										</div>
									</div>
								</div>
							</div>
							@if(isset($post) && count($post) > 0)
								<div class="ac-post-block account-box">
									<h6 class="ac-page-heading">Your Recent Posts</h6>
									<div class="row">									
										@foreach($post as $key => $item)
											<div class="col-lg-4 col-md-6">
												<div class="deal-block recent-deals">
													<div class="deal-img">
														<a href="{{url('post/'.$item->uni_id.'/'.$item->slug)}}" title="{{$item->title}}"><img src="{{$item->image != null ? asset('images/coupon/'.$item->image) : asset('images/store/'.$item->store->image)}}" class="img-fluid" alt="Deal"></a>
													</div>
													<div class="deal-dtl">
														@if($item->is_featured == 1)
															<div class="deal-badge red-badge">Featured</div>
														@elseif($item->is_exclusive == 1)
															<div class="deal-badge green-badge">Exclusive</div>
														@endif
														<div class="deal-merchant">{{$item->store->title}}
														</div>
														<h6 class="deal-title"><a href="{{url('post/'.$item->uni_id.'/'.$item->slug)}}" title="{{$item->title}}">{{str_limit($item->title, 60)}}</a></h6>
														<div class="deal-price-block">
															<div class="row">
																<div class="col-6">
																	<div class="deal-price">
																		@if($item->price)
																			<sup><i class="{{$settings->currency}}" aria-hidden="true"></i></sup> {{$item->price}}
																			@else
																				{{$item->discount ? $item->discount."% Off" : ''}}
																			@endif
																	</div>
																	{{-- <div class="deal-disc">{{$item->discount ? $item->discount."% Off" : ''}}</div> --}}
																</div>
																<div class="col-6 text-right">
																		<div class="rating">
									                    <div class="set-rating" data-rateyo-rating="{{$item->rating>0 ? $item->rating : '0'}}"></div>
									                  </div>
																</div>
															</div>
														</div>
													</div>
													<div class="deal-footer">
														<div class="row">
															<div class="col-5">
																<div class="comments-icon">
																	<i class="far fa-comments"></i><a href="{{url('post/'.$item->uni_id.'/'.$item->slug)}}" title="Comments">{{$item->comments()->count()}}</a>
																</div>
																<div class="comments-icon">
																	<i class="fa fa-eye"></i>{{$item->views()->count()}}
																</div>
															</div>
															<div class="col-7">
																<div class="deal-user">
																	<div class="row">
																		<div class="col-4">
																			<div class="user-img">
																				<a href="{{url('profile/'.$item->user_id)}}" title="User"><img src="{{asset('images/user/'.$item->user->image)}}" class="img-fluid" alt="User"></a>
																			</div>
																		</div>
																		<div class="col-sm-8">
																			<div class="user-name">
																				<a href="{{url('profile/'.$item->user_id)}}" title="User">{{strtok($item->user->name,' ')}}</a>
																			</div>
																			<div class="deal-time">{{$item->created_at->diffForHumans()}}</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							@endif
							@if(isset($followers) && count($followers)>0)
								<div id="ac-followers" class="ac-followers-block account-box">
									<h6 class="ac-page-heading">Followers</h6>
									<div class="row">										
										@foreach($followers as $key => $item)
											<div class="col-xs-1">
												<div class="ac-profile-img">
													<a href="{{url('profile/'.$item->id)}}" title="{{$item->name}}"><img src="{{asset('images/user/'.$item->image)}}" class="img-fluid" alt="User"></a>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							@endif
							@if(isset($followings) && count($followings)>0)
								<div id="ac-followings" class="ac-followers-block account-box">
									<h6 class="ac-page-heading">Followings</h6>
									<div class="row">
										@foreach($followings as $key => $item)
											<div class="col-xs-1">
												<div class="ac-profile-img">
													<a href="{{url('profile/'.$item->id)}}" title="{{$item->name}}"><img src="{{asset('images/user/'.$item->image)}}" class="img-fluid" alt="User"></a>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							@endif
						</div>
						<div class="col-md-3">
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