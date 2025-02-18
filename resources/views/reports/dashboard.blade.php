@extends('adminlte::page')

@section('title', __('menu.report_dashboard'))

@section('content_header')
    <h1>{{ __('menu.report_dashboard') }}</h1>
@stop

<style>
    .report-card {
        border-radius: 12px;
        transition: all 0.3s ease-in-out;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    }

    .report-card .icon i {
        transition: transform 0.3s ease-in-out;
    }

    .report-card:hover .icon i {
        transform: scale(1.1);
    }

    /* Ensures all headings stay on one line */
    .report-title {
        min-height: 24px;
        /* Consistent height */
        white-space: nowrap;
        /* Prevents multi-line text */
        overflow: hidden;
        text-overflow: ellipsis;
    }
    canvas {
    max-height: 250px !important; /* Ensures all charts have the same height */
}
.chart-container {
    height: 250px; /* Adjust this value as needed */
}
</style>
@section('content')
    <div class="row">
        <!-- Stock Transactions Report -->
        <div class="col-md-4 mb-3">
            <div class="card report-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-warehouse fa-3x text-info"></i>
                    </div>
                    <h5 class="fw-bold text-dark report-title">{{ __('menu.stock_transaction_report') }}</h5>
                    <p class="text-muted">{{ __('menu.reports') }}</p>
                    <a href="{{ url('reports/stock-transactions') }}" class="btn btn-outline-info btn-sm">
                        {{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Report -->
        <div class="col-md-4 mb-3">
            <div class="card report-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold text-dark report-title">{{ __('menu.customer_report') }}</h5>
                    <p class="text-muted">{{ __('menu.reports') }}</p>
                    <a href="{{ url('reports/customer-report') }}" class="btn btn-outline-success btn-sm">
                        {{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Report -->
        <div class="col-md-4 mb-3">
            <div class="card report-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon mb-3">
                        <i class="fas fa-university fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold text-dark report-title">{{ __('menu.account_report') }}</h5>
                    <p class="text-muted">{{ __('menu.reports') }}</p>
                    <a href="{{ url('reports/finance-report') }}" class="btn btn-outline-warning btn-sm">
                        {{ __('menu.management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    
     <!-- Graphs Row -->
     <div class="row">
        <!-- Stock Transactions Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">{{ __('menu.stock_transaction_report') }}</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="stockTransactionsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Customers Report Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">{{ __('menu.customer_report') }}</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="customerChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Accounts Report Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">{{ __('menu.account_report') }}</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="accountChart"></canvas>
                </div>
            </div>
        </div>
    </div>

@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Stock Transactions Chart
            new Chart(document.getElementById("stockTransactionsChart"), {
                type: "bar",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                    datasets: [{
                        label: "{{ __('menu.stock_transaction_report') }}",
                        data: [12, 19, 8, 17, 14, 10],
                        backgroundColor: "rgba(54, 162, 235, 0.6)"
                    }]
                }
            });

            // Customer Report Chart
            new Chart(document.getElementById("customerChart"), {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                    datasets: [{
                        label: "{{ __('menu.customer_report') }}",
                        data: [5, 10, 15, 20, 25, 30],
                        borderColor: "rgba(40, 167, 69, 0.9)",
                        fill: false
                    }]
                }
            });

            // Account Report Chart
            new Chart(document.getElementById("accountChart"), {
                type: "pie",
                data: {
                    labels: ["Income", "Expense", "Savings"],
                    datasets: [{
                        data: [3000, 1500, 1000],
                        backgroundColor: ["rgba(255, 193, 7, 0.8)", "rgba(220, 53, 69, 0.8)", "rgba(40, 167, 69, 0.8)"]
                    }]
                }
            });
        });
    </script>
@stop
