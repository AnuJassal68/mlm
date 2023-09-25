<!-- resources/views/notifications/index.blade.php -->
@extends('admin.layout')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <h5> Manage Email Notification </h5>
                    <form role="form" method="post" action="">
                        @csrf
                        <div class="col-12">
                            <div class="table-responsive">
                                 <table id="order-listing" class="table table-bordered table-hover table-striped" id="example">
            <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                        <tr>
                                            <!--<th width="3%" class="text-center"><input type="checkbox" name="chktgl" title="Select/Unselect All" class="seldel"></th>-->
                                            <th width="24%">Type</th>
                                            <th width="21%">Subject</th>
                                            <th width="35%">Message</th>
                                            <th width="9%" class="text-center">Status</th>
                                            <th width="8%" class="text-center">
                                                @php
                                                $ed = ($fullset=='Y' || in_array("edit", $perset[$page])) ? 'Edit' : 'View';
                                                echo $ed;
                                                @endphp
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach($rinfo as $r)
                                        <tr {!! ($r->bActive=='Y') ? '' : 'class="danger"' !!}>
                                            <!--<td class="text-center"><input type="checkbox" name="del[]" value="{{ $r->id }}" class="delids"></td>-->
                                            <td>{{ $r->title }}</td>
                                            <td>{{ $r->subject }}</td>
                                            <td>{{ substr(strip_tags($r->notification), 0, 50).'..' }}</td>
                                            <td class="text-center">{{ ($r->bActive=='Y') ? 'Active' : 'Deactive' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('notifications.edit', ['id' => $r->id]) }}" class="btn btn-warning btn-sm">{{ $ed }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @if(count($rinfo) === 0)
                                        <tr>
                                            <td colspan="5" class="text-center">-no records-</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if(count($rinfo) > 0)
                                @php
                                $str = "pg=notification".($q != '' ? '&q='.$q : '').($f != '' ? '&f='.$f : '');
                                @endphp
                                <paging-component :str="{{ json_encode($str) }}" :half="50"></paging-component>
                                <div class="form-group">
                                    @if($fullset=='Y' || in_array("delete", $perset[$page]))
                                    <input type="hidden" name="delsel" class="btn btn-danger btn-sm actbut" value="Delete Selected">
                                    @endif
                                    @if($fullset=='Y' || in_array("edit", $perset[$page]))
                                    <input type="hidden" name="actsel" class="btn btn-success btn-sm actbut" value="Activate Selected">
                                    <input type="hidden" name="deactsel" class="btn btn-warning btn-sm actbut" value="Deactivate Selected">
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

</section>
@endsection
