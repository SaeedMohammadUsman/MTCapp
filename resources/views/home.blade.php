
{{-- Add here extra stylesheets --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
    <h1>{{ __('menu.dashboard') }}</h1>
@stop

@section('content')

    <div class="row">
        <!-- Departments -->

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-bars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.department') }}</span>
                    <!--   {{-- <span class="info-box-number">1,410</span> --}}-->
                    <span class="info-box-number">{{ $departmentCount }}</span>
                    <a href="{{ url('departments') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Vendors -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-store"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.vendor_list') }}</span>
                    {{-- <span class="info-box-number">780</span> --}}
                    <span class="info-box-number">{{ $vendorCount }}</span>

                    <a href="{{ url('vendors') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Purchase Orders -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.purchase_orders') }}</span>
                    {{-- <span class="info-box-number">325</span> --}}
                    <span class="info-box-number">{{ $purchaseOrderCount }}</span>

                    <a href="{{ url('purchase_orders') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customers -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.customers') }}</span>
                    {{-- <span class="info-box-number">2,120</span> --}}

                    <span class="info-box-number">{{ $customerCount }}</span>
                    <a href="{{ url('customers') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- Customer Orders -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.customer_orders') }}</span>
                    {{-- <span class="info-box-number">450</span> <!-- Example number --> --}}
                    <span class="info-box-number">{{ $customerOrderCount }}</span>
                    <a href="{{ url('customer-orders') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-tags"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.items') }}</span>
                    {{-- <span class="info-box-number">1,200</span> <!-- Example number --> --}}
                    <span class="info-box-number">{{ $itemCount }}</span>

                    <a href="{{ url('items') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <!-- Received Goods -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.received_goods') }}</span>
                    {{-- <span class="info-box-number">600</span> <!-- Example number --> --}}
                    <span class="info-box-number">{{ $rescivedGoodsCouunt }}</span>
                    <a href="{{ url('received-goods') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Stock Transactions -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-warehouse"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.stock_transactions') }}</span>
                    {{-- <span class="info-box-number">150</span> <!-- Example number --> --}}
                    <span class="info-box-number">{{ $stockTransactionsCount }}</span>
                    <a href="{{ url('stock-transactions') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- Account -->
        
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-university"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.accounts') }}</span>
                    {{-- <span class="info-box-number">50</span> <!-- Example number --> --}}
                    <span class="info-box-number">{{ $accountCount }}</span>
                    <a href="{{ url('accounts') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

       

        <!-- Transactions -->
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-exchange-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.transactions') }}</span>
                    {{-- <span class="info-box-number">1,000</span> <!-- Example number --> --}}
                    <span class="info-box-number">{{ $transactionCount }}</span>
                    <a href="{{ url('transactions') }}" class="small-box-footer">{{ __('menu.management') }} <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Reports -->
    </div>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
