<!-- resources/views/change_password.blade.php -->
@include('user.include.header')

@section('content')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Change Password</h4>
        <p class="sub-title">You can change your password anytime</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5 style="visibility:hidden;">Horizontal Form</h5>
                                @if(session('emsg'))
                                    <div class="alert alert-{{ session('etype') }}">
                                        {{ session('emsg') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" method="post" action="{{ route('changePassword') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Current Password</label>
                                    <div class="col-sm-6">
                                        <input type="password" name="cpassword" class="form-control input-lg" id="inputEmail3" placeholder="Current Password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">New Password</label>
                                    <div class="col-sm-6">
                                        <input type="password" name="npassword" class="form-control input-lg" id="inputEmail3" placeholder="Type New Password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Retype Password</label>
                                    <div class="col-sm-6">
                                        <input type="password" name="rnpassword" class="form-control input-lg" id="inputEmail3" placeholder="Retype New Password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-5 col-sm-10">
                                        <button type="submit" name="changeloginpassword" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="path-to-bootstrap-datepicker.js"></script>
@include('user.include.footer')
