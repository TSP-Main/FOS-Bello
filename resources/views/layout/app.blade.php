<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title')</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        @include('layout.style')

        <style>
            .content-wrapper {
                   margin-right: 0;
               }
           
               .main-header {
                   margin-right: 0;
               }
        </style>

    </head>

    <body class="hold-transition light-skin sidebar-mini theme-primary fixed">
        <div class="wrapper">
            <div id="loader"></div>

            <!-- Topbar -->
            @include('layout.navbar')

            <!-- Left Sidebar -->
            @include('layout.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" >
                <div class="container" >
                <!-- Main content -->
                    @yield('content')
                <!-- /.content -->
                </div>
            </div>
            <!-- /.content-wrapper -->

            <!-- Rightbar -->
            @yield('rightbar')
            <!-- Footer -->
            @include('layout.footer')

        </div>

        @include('layout.script')

        @yield('script')
    </body>

</html>
