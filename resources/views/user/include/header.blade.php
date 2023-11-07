<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Dashboard | </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="users/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
     <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png')}}" />
    <link href="users/dist/css/animate.min.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="users/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="users/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <link href="users/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="users/dist/zTreeStyle/zTreeStyle.css" type="text/css">
    <link href="users/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
    <link href="users/plugins/iCheck/flat/yellow.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="users/plugins/colorbox/colorbox.css" />
    <link rel="stylesheet" href="users/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="users/css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="users/css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="users/css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="shortcut icon" href="users/favicon1.ico" />
    <link rel="stylesheet" href="users/css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="users/css/icheck/skins/line/blue.css">
    <link rel="stylesheet" href="users/css/icheck/skins/line/red.css">
    <link rel="stylesheet" href="users/css/icheck/skins/line/green.css">
    <link rel="stylesheet" href="users/css/main.css" media="screen">
    <script src="users/js/modernizr/modernizr.min.js"></script>

    <style type="text/css">
        .my-primary {
            background-color: #050e4a;
            color: #ffffff;
        }

        .my-secondary {
            background-color: #067d78;
            color: #ffffff;
        }

        .my-third {
            background-color: #f39c12;
            color: #ffffff;
        }

        .my-red {
            background-color: #c50202;
            color: #ffffff;
        }

        .my-green {
            background-color: #055828;
            color: #ffffff;
        }

        .my-blue {
            background-color: #10466b;
            color: #ffffff;
        }

        a.page-numbers {
            margin: 10px;
            background: #10466b;
            color: #fff !important;
            padding: 0 10px 0 10px;
            border-radius: 5px;
        }

        span.current.page-numbers {
            color: #fff;
            background: #3498db;
            padding: 0 10px 0 10px;
            margin: 10px;
            border-radius: 5px;
        }

    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <nav class="navbar top-navbar bg-white box-shadow">
            <div class="container-fluid">
                <div class="row">
                    <div class="navbar-header no-padding">
                        <a class="navbar-brand" href="{{route('user/dashboard')}}">
                            <img src="users/images/cw_color.png" alt="Coinswings | User Panel" class="logo">
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <button type="button" class="navbar-toggle mobile-nav-toggle">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-collapse-1">
                        <ul class="nav navbar-nav" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                            <li class="dropdown">
                                <a href="{{route('user/dashboard')}}" class="dropdown-toggle bg-primary my-primary" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false">Home</a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Investments <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('deposit')}}"><i class="fa fa-qrcode"></i> Add Bitcoins</a></li>
                                    <li><a href="{{route('re-investment')}}"><i class="fa fa-usd"></i> Re-Invest Funds</a></li>
                                    <li><a href="{{route('my-deposits')}}"><i class="fa fa-bitcoin"></i> Requested Deposits</a></li>
                                    <li><a href="{{route('my-investment')}}"><i class="fa fa-list"></i> Investment Summary</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Network Info <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('directs')}}"><i class="fa fa-users"></i> Direct Referrals</a></li>
                                    <li><a href="{{route('network')}}"><i class="fa fa-cubes"></i> My Network</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Payouts <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('incentive')}}"><i class="fa fa-sun-o"></i> Daily Incentive</a></li>
                                    <li><a href="{{url('/level-incentive')}}"><i class="fa fa-sitemap"></i> Level Incentive</a></li>
                                    <li><a href="{{route('request-withdraw')}}"><i class="fa fa-bank"></i> Request Payments</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="{{route('all-statements')}}" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false">Account Statement</a>
                            </li>
                            <li class="dropdown">
                                <a href="{{route('support-ticket')}}" class="" data-toggle="" role="button" aria-haspopup="true" aria-expanded="false">Support</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">coinswings<span class="caret"></span></a>
                                <ul class="dropdown-menu profile-dropdown">
                                    <li class="profile-menu bg-gray">
                                        <div class="">
                                            <img src="http://placehold.it/60/c2c2c2?text=User" alt="John Doe" class="img-circle profile-img">
                                            <div class="profile-name">
                                                <h6>{{session('user_name')}}</h6>
                                                <a href="{{route('profile')}}">View Profile</a>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </li>
                                    <li><a href="{{url('showChangePasswordForm')}}"><i class="fa fa-cog"></i> Change Password</a></li>
                                    <li><a href="{{route('updateProfile')}}"><i class="fa fa-sliders"></i> Account Details</a></li>
                                    {{-- <li role="separator" class="divider"></li> --}}
                                    <li><a href="{{url('/log-out')}}" class="color-danger text-center"><i class="fa fa-sign-out"></i> Logout</a></li>
                                </ul>
                            </li>
                            <!-- /.dropdown -->
                            <li><a href="#" class=""><i class="fa fa-ellipsis-v"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

<div class="row page-title-div">
    <div class="col-md-6">
        <h4 class="title">Hello,@php session('user_name')@endphp<span style="color:black; font-weight:bold;"></span></h4>
        <p class="sub-title" style="color:black; font-weight:bold;">
       
            @if(session()->has('loginid'))
                Referral Link: <span class="text-danger">{{ url('/home').'?ref='.session('loginid') }}</span>
            @else
                Referral Link: {{ url('/home') }}
            @endif
            <span style="color:red; font-weight:bold;"></span>
        </p>
    </div>
</div>


        <div class="row breadcrumb-div my-blue">
            <div class="col-md-6">
                <ul class="breadcrumb">
                    <li><a href="{{route('user/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
                </ul>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{route('support-ticket')}}"><i class="fa fa-comments"></i> Support</a>
                <a href="{{route('updateProfile')}}" class="pl-20"><i class="fa fa-cog"></i> Account Settings</a>
                <a href="{{url('log-in')}}" class="pl-20"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
        </div>





    </div>
