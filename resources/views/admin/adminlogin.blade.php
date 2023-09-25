<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>coinswings </title>
    <!-- plugins:css -->
 
    <link rel="stylesheet" href="{{ asset('admin/css/vertical-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png')}}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <h2 class="fw-dark text-center mt-4">  <img src="{{ asset('assets/img/coinswings.png') }}"style="width:150px;height:100px"></h2>
                                <h4 class="fw-light mt-3 text-center">Sign in to continue.</h4>
                            </div>


                            @if(Session::has('error'))
                            <p class="text-danger">{{Session::get('danger')}}</p>
                            @endif
                              <form action="{{ route('adminlogindata') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="loginid" class="form-control" placeholder="Login-Id" required />
                                </div>
                                <div class="form-group">
                                     <input type="password" name="loginpassword" class="form-control" placeholder="Password" required />
                                </div>
                                <div class="mt-5 text-center">
                                    <button class="btn btn-primary " type="submit">SIGN IN</button>
                                </div>
                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

 
    <script src="{{ asset('admin/js/template.js')}}"></script>
 
    <!-- endinject -->
</body>

</html>

