@include('user.include.header')

@section('content')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Edit your Details</h4>
        <p class="sub-title">You can add or edit your details here.</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title" style="visibility:hidden;">
                                <h5>Two Column Form</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            {{-- @include('partials.alert_box', ['msg' => $emsg, 'type' => $etype]) --}}

                            <form class="p-20" method="post" action="" enctype="multipart/form-data">
                                @csrf 

                                <h5 class="underline mt-n">Account Info</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name13">First Name</label>
                                            <input type="text" name="firstname" class="form-control input-lg" id="name13" placeholder="Enter Your Full Name" value="{{ $uinfo[0]->firstname }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name13">Last Name</label>
                                            <input type="text" name="lastname" class="form-control input-lg" id="name13" placeholder="Enter Your Full Name" value="{{ $uinfo[0]->lastname }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username13">Bitcoin Account</label>
                                            <input type="text" name="accountno" class="form-control input-lg" id="username13" placeholder="Enter Desired Username" value="{{ $uinfo[0]->accountno }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact13">Contact Number</label>
                                            <input type="text" class="form-control input-lg" id="contact13" placeholder="Enter Your Mobile Phone Number" name="mobile" required value="{{ $uinfo[0]->mobile }}">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="underline mt-30">Address Details</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="addr">Street Address</label>
                                            <input type="text" class="form-control input-lg" id="addr" placeholder="Enter Your Street Address" name="address" value="{{ $uinfo[0]->address }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail13">Email address</label>
                                            <input type="email" name="email" class="form-control input-lg" id="exampleInputEmail13" placeholder="Enter Your Email Id" value="{{ $uinfo[0]->emailid }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control input-lg" id="city" placeholder="Enter Your City" name="city" value="{{ $uinfo[0]->city }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <input type="text" class="form-control input-lg" id="state" placeholder="Enter Your State" name="state" value="{{ $uinfo[0]->state }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control input-lg" id="country" placeholder="Enter Your Country" name="country" value="{{ $uinfo[0]->country }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="zip">Zip Code</label>
                                            <input type="text" class="form-control input-lg" id="zip" placeholder="Enter Your Zip Code" name="pincode" value="{{ $uinfo[0]->pincode }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" required> I accept <a href="#" class="color-primary">terms & conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="btn-group pull-right mt-10" role="group">
                                           <a href="/dashboard "> <button type="button" class="btn btn-gray btn-wide"><i class="fa fa-times"></i>Cancel</button></a>
                                            <button type="submit" class="btn bg-black btn-wide" name="updateprofile"><i class="fa fa-arrow-right"></i>Submit</button>
                                        </div>
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
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="users/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="users/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('#transactionTable').DataTable();
    });

</script>