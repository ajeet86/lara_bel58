@extends('layouts.app')
@section('title', 'My Account | School Shark')
@section('description', 'My Account')
@section('css')
<link href="{{URL::asset('/public/admn/css/plugincss/jasny-bootstrap.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('/public/admn/css/bootstrapValidator.min.css')}}" rel="stylesheet">
@stop
@section('content')
<section class="dashboard-body product-wrapper">
    <div class="container-fluid">
        <div class="row">
            @include('layouts.user_sidebar')
            <div class="col-lg-9">
                <div class="dash-container">
                    <div class="row">
                        @if(session()->has('success'))
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        </div>
                        @endif
                        @if(session()->has('error'))
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="abc">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="personal">

                                    <div class="card-block m-t-35">
                                        <div>
                                            <h4>Account Information</h4>
                                            <hr class="divider-hr">
                                        </div>
                                        <form class="form-horizontal login_validator" id="tryitForm" action="{{ url('/my_account') }}"
                                              method="post">
                                            <div class="row">
                                                {{ csrf_field() }}
                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-lg-3 text-lg-right">
                                                            <label for="postal_code" class="col-form-label">Paypal Email Address *
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-8">
                                                            <div class="input-group">
                                                                <input name="paypal_email" type="text"
                                                                       value="{{ isset($user->paypal_email) ? $user->paypal_email : old('paypal_email') }}" 
                                                                       class="form-control" required="">
                                                            </div>
                                                            @if ($errors->has('paypal_email'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('paypal_email') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-lg-3 text-lg-right">

                                                        </div>
                                                        <div class="col-xl-6 col-lg-8 text-lg-left">
                                                            <button class="btn btn-primary all-btn-theme" type="submit">
                                                                <i class="fa fa-user"></i>
                                                                {{ 'Save' }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="acc_trans_history">
                                <h3>Transaction History</h3>
                                <table id="cart" class="table table-hover table-condensed table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Order No</th>
                                            <th>Item Name</th>
                                            <th>Item Quantity</th>
                                            <th>Item Amount</th>
                                            <th>Credited Amount</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = 1)
                                        @if(!$account_history->isEmpty())
                                            @foreach($account_history as $history)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $history->order_no }}</td>
                                                    <td>{{ $history->item_name }}</td>
                                                    <td>{{ $history->quantity }}</td>
                                                    <td>$ {{ $history->item_amount }}</td>
                                                    <td>$ {{ $history->amount }}</td>
                                                    <td>{{ $history->message }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($history->created_at)) }}</td>
                                                </tr>
                                                @php($i++)
                                            @endforeach
                                        @else
                                        <tr><td colspan="8">No Record Found !!!</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $account_history->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('pagescript')
<script src="{{URL::asset('/public/admn/js/pluginjs/jasny-bootstrap.js')}}"></script>
<script src="{{URL::asset('/public/admn/js/holder.js')}}"></script>
<script src="{{URL::asset('/public/admn/js/bootstrapValidator.min.js')}}"></script>
<script src="{{URL::asset('/public/admn/js/pages/validation.js')}}"></script>
@stop