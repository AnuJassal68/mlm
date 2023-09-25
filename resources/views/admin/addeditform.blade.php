@extends('layouts.app') <!-- Adjust the layout as per your application structure -->

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{ route('submit.client') }}" enctype="multipart/form-data">
                <div class="panel">
                    <div class="panel-heading"> <i class="fa fa-user fa-fw"></i> PROFILE </div>
                    <div class="panel-body">
                        @include('partials.alert_box') <!-- Assuming you have a partial for alert_box -->

                        <div class="row">
                            <div class="col-md-4" style="clear:both">
                                <div class="form-group">
                                    <label>Registration Date</label>
                                    <br>{{ $regisdate }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referral User</label>
                                    @if ($binfo[0]->referalid > 0)
                                        @php
                                            $rinfo = getquery("tbl_user", " AND id = '".$binfo[0]->referalid."' ", "firstname,lastname,loginid");
                                        @endphp
                                        <br>{{ $rinfo[0]->firstname }} {{ $rinfo[0]->lastname }} [{{ $rinfo[0]->loginid }}]
                                    @else
                                        <br>.: TOP NODE :.
                                    @endif
                                </div>
                            </div>
                            <!-- ... Other form fields ... -->
                        </div>
                    </div>
                </div>
                <!-- ... Other panels ... -->

                <div class="panel">
                    <div class="panel-body" style="padding-bottom:5px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="HTTP_REFERER" value="{{ $HTTP_REFERER }}" />
                                    @if ($_GET['id'] > 0)
                                        @if ($fullset == 'Y' || in_array("edit", $perset[$page]))
                                            <input type="submit" name="submitclient" value="Update" class="btn btn-success">
                                        @endif
                                        @if ($endel == "Y")
                                            @if ($fullset == 'Y' || in_array("delete", $perset[$page]))
                                                <input type="submit" name="delsel" class="btn btn-warning" value="Delete User">
                                            @endif
                                        @endif
                                    @else
                                        @if ($fullset == 'Y' || in_array("add", $perset[$page]))
                                            <input type="submit" name="submitclient" value="Add" class="btn btn-success">
                                        @endif
                                    @endif
                                    <a href="{{ $HTTP_REFERER }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
