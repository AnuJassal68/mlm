@include('include.header')
       <div id="login" class="padding ptb-xs-60 page-signin">
    <div class="container">
        <div class="row">
            <div class="main-body">
                <div class="body-inner">
                    <div class="card bg-white">
                        <form class="form-horizontal" action="{{ url('login') }}" method="post">
                            @csrf
                            <div class="card-content">
                                <section class="logo text-center">
                                    <h2>Login</h2>
                                </section>
                                @if(session('emsg'))
                                <div class="alert alert-{{ session('etype') }}">
                                    {{ session('emsg') }}
                                </div>
                                @endif
                                <fieldset>
                                    <div class="form-group">
                                        <div class="ui-input-group">
                                            <input type="text" required class="form-control" id="email" name="emailid" value="{{ old('emailid') }}">
                                            <span class="input-bar"></span>
                                            <label>Username</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="ui-input-group">
                                            <input type="password" required class="form-control" id="pwd" name="loginpassword">
                                            <span class="input-bar"></span>
                                            <label>Password</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="card-action no-border text-right">
                                <button type="submit" name="submitlogin" style="background: none;"><a class="color-primary">SIGN IN</a></button>
                            </div>
                        </form>
                    </div>
                    <div class="additional-info">
                        <a href="{{ url('signup') }}">Register</a><span class="divider-h"></span><a href="{{route('forget-password')}}">Forgot your password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	@include('include.footer')

