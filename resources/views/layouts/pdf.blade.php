<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Layout</title>
   
    <!-- Link to AdminLTE CSS for PDF Generation -->
    <link rel="stylesheet" href="{{ public_path('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3 {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
        }

        /* Wrapper Row */
        .table-wrapper {
            width: 100%;
          
            margin-bottom: 10px;
        }

        .table-wrapper td {
            padding: 15px;
            vertical-align: top;
        }

        /* Table Styles */
        table {
            width: 100%;
           /* border: 1px solid #ddd; */
            border-radius: 5px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            font-size: 12px;
            padding: 8px;
            border-bottom: 2px solid #ddd;
            color: #333;
        }

        .table thead {
            background-color: #f4f4f4;
        }

       

        /* Footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* border-top: 2px solid #ddd; */
            border-bottom: 2px solid #ddd;
            
        }

        .footer-table td {
            padding: 15px;
            vertical-align: top;
        }

        .footer-table .signature {
            border-top: 2px solid #333;
            margin-top: 20px;
            margin-bottom: 20px;
            width: 150px;
        }

        /* Custom Styles */
        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }

        .text-muted {
            color: #555;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Content Section -->
    <div class="container">
        @yield('content')
    </div>
</body>

</html>
