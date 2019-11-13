@extends('layouts.app')

@section('content')
<section class="login-wrapper">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Verify Your Email Address') }}</div>

					<div class="card-body">
						<br/>
						@if (session('resent'))
							<div class="alert alert-success" role="alert">
								{{ __('A fresh verification link has been sent to your email address.') }}
							</div>
						@endif

						{{ __('Before proceeding, please check your email for a verification link.') }}
						{{ __('If you did not receive the email') }}, <a href="{{ route($resendRoute) }}">{{ __('click here to request another') }}</a>.
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
