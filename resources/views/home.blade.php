{{-- @extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <p>Welcome to this beautiful admin panel.</p>
    
@stop
@section('content')
@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop --}}



    {{-- Add here extra stylesheets --}}
    @extends('adminlte::page')

    @section('title', 'Dashboard')
    
    @section('content_header')
        <h1>Dashboard</h1>
    @stop
    
    @section('content')
   
    <div class="row">
        <!-- Departments -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-bars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.department') }}</span>
                    {{-- <span class="info-box-number">1,410</span> --}}
                    <span class="info-box-number">{{ $departmentCount }}</span>
                    <a href="{{ url('departments') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Vendors -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-store"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.vendor_list') }}</span>
                    {{-- <span class="info-box-number">780</span> --}}
                    <span class="info-box-number">{{ $vendorCount }}</span>
                    
                    <a href="{{ url('vendors') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Purchase Orders -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.purchase_orders') }}</span>
                    {{-- <span class="info-box-number">325</span> --}}
                    <span class="info-box-number">{{ $purchaseOrderCount }}</span>

                    <a href="{{ url('purchase_orders') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Customers -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.customers') }}</span>
                    <span class="info-box-number">2,120</span>
                    <a href="{{ url('customers') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Customer Orders -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.customer_orders') }}</span>
                    <span class="info-box-number">450</span> <!-- Example number -->
                    <a href="{{ url('customer-orders') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Items -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-tags"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.items') }}</span>
                    <span class="info-box-number">1,200</span> <!-- Example number -->
                    <a href="{{ url('items') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Received Goods -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.received_goods') }}</span>
                    <span class="info-box-number">600</span> <!-- Example number -->
                    <a href="{{ url('received-goods') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Stock Transactions -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-warehouse"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.stock_transactions') }}</span>
                    <span class="info-box-number">150</span> <!-- Example number -->
                    <a href="{{ url('stock-transactions') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Categories -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-tags"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.categories') }}</span>
                    <span class="info-box-number">300</span> <!-- Example number -->
                    <a href="{{ url('categories') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Accounts -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-university"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.accounts') }}</span>
                    <span class="info-box-number">50</span> <!-- Example number -->
                    <a href="{{ url('accounts') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Transactions -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-exchange-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.transactions') }}</span>
                    <span class="info-box-number">1,000</span> <!-- Example number -->
                    <a href="{{ url('transactions') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    
        <!-- Users -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-user-friends"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('menu.users') }}</span>
                    <span class="info-box-number">120</span> <!-- Example number -->
                    <a href="{{ url('users') }}" class="small-box-footer">{{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
   
  
    @stop
    
    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
    @stop
    
    @section('js')
        <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
    @stop
    