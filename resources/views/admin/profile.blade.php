@extends('admin.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
@section('content')
<style>
.lab{
    color: #0b3547;
 
}
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('submit-client', $binfo->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="panel">
                            <div class="panel-heading text-center fs-4 mb-4" style=" color: #0f268e;"> <i class="fa fa-user fa-fw"></i> PROFILE </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4" style="clear:both">

                                        <label class="lab">Registration Date</label>
                                        <br>{{ $regisdate }}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="lab">Referral User</label>
                                        @if ($binfo->referalid > 0)
                                        @php
                                        $rinfo = getquery("tbl_user", " AND id = '".$binfo->referalid."' ", "firstname,lastname,loginid");
                                        @endphp
                                        @foreach ($rinfo as $record)
                                        <br>
                                        {{ $record['firstname'] }} {{ $record['lastname'] }} [{{ $record['loginid'] }}]
                                        @endforeach

                                        @else
                                        <br>.: TOP NODE :.
                                        @endif

                                    </div>
                                </div>
                                <div class="row mt-2 ms-1">
                                    <div class="col-lg-4 p-1">
                                        <label for="inputEmail4" class="form-label lab">Full Name *</label>
                                        <input type="text" class="form-control " id="inputEmail4" name="firstname" value="{{ $binfo->firstname.' '.$binfo->middlename.' '.$binfo->lastname }}" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="inputEmail4" class="form-label text-dark lab">Date Of Birth *</label>
                                        <div class="form-group ">
                                            <div class="row ">
                                                <div class="d-flex">
                                                    <div class="col-xs-4 me-2">
                                                        <select class="form-select" name="dob_dd" style="margin-top: 4px;line-height: 1.1;">
                                                            >
                                                            <option value="">DD</option>
                                                            @for ($i = 1; $i <= 31; $i++) <option {{ ($dobs[0] == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '') }}>{{ $i }}</option>
                                                                @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4 ms-2">

                                                        <select class="form-select" name="dob_mm" style="margin-top: 4px;line-height: 1.1;">
                                                            <option value="">MM</option>
                                                            @for ($i = 1; $i <= 12; $i++) @php $isSelected=(isset($dobs[1]) && $dobs[1]==str_pad($i, 2, '0' , STR_PAD_LEFT)) ? 'selected' : '' ; @endphp <option {{ $isSelected }}>{{ $i }}</option>
                                                                @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4 ms-2">

                                                        <select class="form-select" name="dob_yy" style="margin-top: 4px;line-height: 1.1;">
                                                            <option value="">YYYY</option>
                                                            @php
                                                            $ybet = date("Y") - 18;
                                                            @endphp
                                                            @for ($i = 1950; $i <= $ybet; $i++) @php $isSelected=(isset($dobs[2]) && $dobs[2]==$i) ? 'selected' : '' ; @endphp <option {{ $isSelected }}>{{ $i }}</option>
                                                                @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="inputbit" class="form-label lab">$Bit Coin Account</label>
                                        <input class="form-control" type="text" id="inputbit" name="accountno" placeholder="$Bit Coin Account" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="panel">
                            <div class="panel-heading lab"> <i class="fa fa-home fa-fw"></i> ADDRESS DETAILS </div>
                            <div class="panel-body">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 mt-2 ms-1 mb-2">
                                            <label for="address" class="form-label lab">Address *</label>
                                            <input class="form-control" type="text" name="address" id="address" value="{{ $binfo->address }}">
                                        </div>
                                        <div class="col-lg-12 mt-2 ms-1 mb-2">
                                            <label for="landmark" class="form-label lab">Landmark *</label>
                                            <input class="form-control" type="text" name="landmark" id="landmark" value="{{ $binfo->landmark }}">
                                        </div>
                                        <div class="col-lg-3 mt-2 mb-2">
                                            <label for="country" class="form-label lab">Country *</label>
                                            <select class="form-select" name="country" id="country" style="margin-top: 4px;line-height: 1.1;">
                                                <option value="mohali">mohali</option>
                                                @foreach ($coninfo as $info)
                                                <option value="{{ $info['id'] }}" {{ $binfo->country == $info['title'] ? 'selected' : '' }}>
                                                    {{ $info['title'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mt-2 mb-2">
                                            <label for="state" class="form-label lab">State *</label>
                                            <select class="form-select" name="state" id="state" style="margin-top: 4px;line-height: 1.1;">
                                                <option value="mohali">mohali</option>

                                                <option selected="selected">{{ $binfo->state }}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mt-2 mb-2" id="ocity">
                                            <label for="city" class="form-label lab">City *</label>
                                            <select class="form-select" name="city" id="city" style="margin-top: 4px;line-height: 1.1;">
                                                <option value="mohali">mohali</option>
                                                <option selected="selected">{{ $binfo->city }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 mt-2 mb-2" style="display:none" id="dcity">
                                            <div class="form-group">
                                                <a class="pull-right reset-city"><strong>&times;</strong></a>
                                                <label class="lab">Other City * </label>
                                                <input class="form-control" type="text" name="o_city" value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mt-2 mb-2">
                                            <label for="apincode" class="form-label lab">Pincode *</label>
                                            <input class="form-control" type="text" name="pincode" id="pincode" value="{{ $binfo->pincode }}" style="margin-top: 4px;line-height: 1.1;">
                                        </div>
                                        <div class="row  mb-2">
                                            <div class="col-lg-4 mt-2 ">
                                                <label for="emailid" class="form-label text-success lab">Email-Id verified *</label>
                                                <input class="form-control" type="email" name="email" id="emailid" value="{{ $binfo->emailid }}" required>
                                            </div>
                                            <div class="col-lg-4 mt-2">

                                                <label class="lab">Mobile *</label>
                                                <div class="row  mt-2">
                                                    <div class="d-flex">
                                                        <div class="col-xs-4" style="padding-right:0;width: 35%;">
                                                            <input class="form-control text-center numonly " type="text" name="mobile_code" id="mobile_code" value="{{ $binfo->mobile_code }}" placeholder="+ Code">
                                                        </div>
                                                        <div class="col-xs-8" style="padding-left:0;">
                                                            <input class="form-control numonly" type="text" style="border-left:0" name="mobile" value="{{ $binfo->mobile }}" placeholder="Mobile Number" maxlength="10">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-2">
                                                <label class="lab">Fixed Line Number</label>
                                                <div class="row  mt-2">
                                                    <div class="d-flex">
                                                        <div class="col-xs-4" style="padding-right:0;width: 35%;">
                                                            <input class="form-control numonly" type="text" name="fixedline_code" id="fixedline_code" value="{{ $binfo->fixedline_code }}" placeholder="0 Code">
                                                        </div>
                                                        <div class="col-xs-8" style="padding-left:0;">
                                                            <input class="form-control numonly" type="text" style="border-left:0" name="fixedline" value="{{ $binfo->fixedline }}" maxlength="8">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="panel">
                            <div class="panel-body" style="padding-bottom:5px">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="bactive" id="bactive" class="checkbox" {{ $binfo->bActive == 'Y' ? 'checked' : '' }}>
                                                Active Login
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="lab">Login Id</label>
                                            <input class="form-control disabled" type="text" name="loginid" value="{{ $binfo->loginid }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="lab">Login Password</label>
                                            <input class="form-control" type="text" name="loginpassword" value="{{ $binfo->loginpassword }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lab">Admin Remarks</label>
                                            <textarea name="tremarks" class="form-control">{{ $binfo->tremarks }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body" style="padding-bottom:5px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ">update</button>

                <form action="{{ route('delete-user', ['id' => $binfo->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @if ($mode == 'userlist')
                    <a href="{{ route('userlist') }}" class="btn btn-warning">Cancel</a>
                    @elseif ($mode == 'support')
                    <a href="{{ route('support') }}" class="btn btn-warning">Cancel</a>
                    @elseif($mode == 'support-closed')
                    <!-- Default behavior or fallback route -->
                    <a href="{{ route('support-closed') }}" class="btn btn-warning">Cancel</a>
                    @elseif($mode == 'support-pending')
                    <a href="{{ route('support-pending') }}" class="btn btn-warning">Cancel</a>
                    @elseif($mode == 'referal')
                    <a href="{{ route('userlist') }}" class="btn btn-warning">Cancel</a>
                    @else
                    <a href="{{ route('paid-list') }}" class="btn btn-warning">Cancel</a>
                    @endif
                </form>

            </form>

        </div>
    </div>
</section>
@endsection
