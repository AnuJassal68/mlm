@extends('admin.layout')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                    <div class="box box-info">
                        <div class="box-body">
                            <h5 class="text-center text-primary"> Change Password </h5>
                           
                            @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @elseif (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <form role="form" method="post" action="{{ route('change.password') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="cpassword" class="form-control" placeholder="Your Current Password" required>
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="npassword" class="form-control" placeholder="New Password" required>
                                </div>

                                <div class="form-group">
                                    <label>Re-enter New Password Password</label>
                                    <input type="password" name="rnpassword" class="form-control" placeholder="Re-enter New Password" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="changeloginpassword" class="btn btn-success">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
