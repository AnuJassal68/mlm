@include('user.include.header')

<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Your Profile</h4>
        <p class="sub-title">You can see account details here.</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Two Column Form</h5>
                            </div>
                        </div>
                        <div class="panel-body">


                            <h5 class="underline mt-n">Account Info</h5>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-12 header">
                                            <div class="form-group">
                                                <h2 style="padding:0;margin:0">{{ $uinfo->firstname }} {{ $uinfo->middlename }} {{ $uinfo->lastname }}</h2>
                                                <span style="color:#999;font-size:16px">
                                                    {{ ($uinfo->packageid > 0 ? $uinfo->loginid : '-- <em>account not active</em> --') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            @if ($uinfo->referalid > 0)
                                            <label><i class="fa fa-user fa-fw"></i> Introducer : </label>{{ $ruinfo->firstname }} {{ $ruinfo->middlename }} {{ $ruinfo->lastname }} ({{ $ruinfo->loginid }})<br>
                                            @endif
                                            <label><i class="fa fa-envelope fa-fw"></i> Email ID : </label>{{ $uinfo->emailid }}
                                            <label><i class="fa fa-phone fa-fw"></i> Mobile No. :</label>{{ $uinfo->mobile }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 header">
                                    @if (file_exists("uploads/thumb_$uinfo->photo") && !empty($uinfo->photo))
                                    <div class="pull-right round-wrap"><a class="ex" style="background-image: url(uploads/thumb_{{ $uinfo->photo }})"></a></div>
                                    @endif
                                </div>
                            </div>
                            <h5 class="underline mt-30">ADDRESS DETAILS</h5>
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table">
                                        <thead>
                                            <th colspan="2">Postal Address</th>
                                        </thead>
                                        <tbody>
                                            @if ($uinfo->address)
                                            <tr>
                                                <td width="20%"><strong>Address </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->address) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->city)
                                            <tr>
                                                <td><strong>City </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->city) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->state)
                                            <tr>
                                                <td><strong>State </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->state) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->pincode)
                                            <tr>
                                                <td><strong>Pincode </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->pincode) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->country)
                                            <tr>
                                                <td><strong>Country </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->country) }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if ($uinfo->c_address)
                                    <table class="table">
                                        <thead>
                                            <th colspan="2">Permanent Address</th>
                                        </thead>
                                        <tbody>
                                            @if ($uinfo->c_address)
                                            <tr>
                                                <td width="20%"><strong>Address </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->c_address) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->c_city)
                                            <tr>
                                                <td><strong>City </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->c_city) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->c_state)
                                            <tr>
                                                <td><strong>State </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->c_state) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->c_pincode)
                                            <tr>
                                                <td><strong>Pincode </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->c_pincode) }}</td>
                                            </tr>
                                            @endif
                                            @if ($uinfo->c_country)
                                            <tr>
                                                <td><strong>Country </strong></td>
                                                <td> &nbsp;: {{ ucwords($uinfo->c_country) }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                            @if ($uinfo->photo || $uinfo->adhaar_proof || $uinfo->cancelled_cheque || $uinfo->address_proof)
                            <h5 class="underline mt-n">Account Info</h5>
                            <div class="row">
                                @if ($uinfo->photo)
                                <div class="col-lg-3">
                                    <label>Photo</label>
                                    <img src="uploads/thumb_{{ $uinfo->photo }}" class="img-responsive">
                                </div>
                                @endif
                                @if ($uinfo->adhaar_proof)
                                <div class="col-lg-3">
                                    <label>Adhaar Card</label>
                                    <img src="uploads/thumb_{{ $uinfo->adhaar_proof }}" class="img-responsive">
                                </div>
                                @endif
                                @if ($uinfo->cancelled_cheque)
                                <div class="col-lg-3">
                                    <label>Cancelled Cheque</label>
                                    <img src="uploads/thumb_{{ $uinfo->cancelled_cheque }}" class="img-responsive">
                                </div>
                                @endif
                                @if ($uinfo->address_proof)
                                <div class="col-lg-3">
                                    <label>Address Proof</label>
                                    <img src="uploads/thumb_{{ $uinfo->address_proof }}" class="img-responsive">
                                </div>
                                @endif
                            </div>
                            @endif
                            @if (session('user_id') == $uid)
                            <a href="{{ route('updateProfile') }}" class="btn btn-danger">Edit Profile</a><br />&nbsp;
                            @endif
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

@include('user.include.footer')