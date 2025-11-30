<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin._include.meta')
    </head>
    <body class="sb-nav-fixed">
        @include('admin._include.nav')
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                @include('admin._include.sidebar')
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                      @yield('breadcrumb')
                        @yield('content')
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    @include('admin._include.footer')
                </footer>
            </div>
        </div>
        @include('admin._include.script')
    </body>
</html>
