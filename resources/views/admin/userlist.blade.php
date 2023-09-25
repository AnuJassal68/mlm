@extends('admin.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="card-title text-center" style="color: #0b3547;">Manage Userlist View</h3>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('userlist') }}" method="get">
                    @csrf
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Search filter</label>
                                <select class="form-control" name="f">
                                    <option value="0">All</option>
                                    <option value="ac_active" {{ $f == 'ac_active' ? 'selected' : '' }}>Active</option>
                                    <option value="ac_inactive" {{ $f == 'ac_inactive' ? 'selected' : '' }}>InActive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Search for</label>
                                <select class="form-control" name="fo">
                                    <option value="0">All</option>
                                    <option value="country" {{ $fo == 'country' ? 'selected' : '' }}>Country</option>
                                    <option value="city" {{ $fo == 'city' ? 'selected' : '' }}>City</option>
                                    <option value="name" {{ $fo == 'name' ? 'selected' : '' }}>Name</option>
                                    <option value="property" {{ $fo == 'property' ? 'selected' : '' }}>Property</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Search Keyword</label>
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Search..." />
                                    <span class="input-group-btn">
                                        <button type="submit" name="searchqry" id="search-btn" class="btn btn-flat btn-info"><i class="fa fa-search"></i></button>
                                        @if(request()->has('q') || request()->has('fo') || request()->has('f') || request()->has('df') || request()->has('dt'))
                                        <a href="{{route('userlist')}}" type="submit" name="reset" id="reset-btn" class="btn btn-flat btn-danger" title="Reset Search Filters"><i class="fa fa-refresh"></i> </a>
                                        @endif

                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <a href="{{route('generateExcel')}}"> <button type="submit" name="excel" id="excel-btn" class="btn btn-flat btn-success mb-3" title="Excel Download">excel</button></a>
                <form action="{{ route('agent') }}" method="get" id="form_bulk">
                    @csrf
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="order-listing" class="table table-responsive text-center table-bordered table-hover table-striped">
                                <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                    <tr>
                                        <th><input type="checkbox" id="select_all" class="chk-users" name="checkbox[]" />
                                        </th>
                                        <th width="20%">User Info</th>
                                        <th width="22%">Referral Info</th>
                                        <th width="18%">Registration</th>
                                        <th width="22%">$ Investment</th>
                                        <th width="16%">Login Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @php
                                    print_r($qinfo['referalid']);
                                @endphp --}}
                                    @foreach ($qinfo as $result)
                                    <tr>
                                        <td><input type="checkbox" class="chk-user" name="checkbox[]" value="{{ $result['id'] }}" id="{{ $result['id'] }}" /></td>
                                        <td>
                                            <a href="{{ route('profile.edit', ['id' => $result['id']]) }}?mode=userlist">
                                                {!! strtoupper($result['firstname'] . " " . $result['middlename'] . " " . $result['lastname'] . " (" . $result['loginid'] . ")") !!}
                                            </a>
                                        </td>
                                        <td>
                                            @if (isset($refinfo[$result['referalid']]))
                                            <a href="{{ route('profile.edit', $result['referalid']) }}?mode=referal">
                                                {{ $refinfo[$result['referalid']] }}
                                            </a>
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>{{ date("d/m/Y, G:i:s", $result['createdate']) }}</td>
                                        @php
                                        $uinv = json_decode($result['aboutme']);
                                        @endphp
                                        <td>
                                            @if (isset($uinv))
                                            Inv: ${{ $uinv->toti * 1 }},
                                            Per: ${{ $uinv->inc * 1 }}<br>
                                            Wid: ${{ $uinv->wid * 1 }},
                                            Bal: ${{ $uinv->binc * 1 }}
                                            @else
                                            No data available
                                            @endif
                                        </td>
                                        <td>
                                            Account: {{ $result['bActive'] == 'Y' ? 'Enabled' : 'Disabled' }}<br>
                                            Email-Id: {{ $result['bemail'] == '1' ? 'Verified' : 'Not Verified' }}
                                        </td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                            <div class="d-flex container mt-2">
                                <div class="col-6">
                                    <input class="btn text-white" style="background: #0b3547;" name="bulk" type="submit" value="Active" onclick="confirmAction(event, 'Active')">
                                    <input class="btn btn-secondary" name="bulk" type="submit" value="Inactive" onclick="confirmAction(event, 'Inactive')">
                                    <input class="btn btn-danger" name="bulk" type="submit" value="Delete" onclick="confirmAction(event, 'Delete')">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js" integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.chk-users').change(function() {
        if ($(this).prop('checked')) {
            $('tbody tr td input[type="checkbox"]').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $('tbody tr td input[type="checkbox"]').each(function() {
                $(this).prop('checked', false);
            });
        }
    });
//sweet alert function
    function confirmAction(event, action) {
        event.preventDefault();

        const bulkArray = Array.from(document.querySelectorAll('input[name="checkbox[]"]:checked')).map(input => input.value);

        if (bulkArray.length === 0) {
            Swal.fire({
                title: 'Error'
                , text: 'No items selected.'
                , icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Confirm'
            , text: 'Are you sure you want to ' + action + '?'
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#3085d6'
            , cancelButtonColor: '#d33'
            , confirmButtonText: 'Yes'
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('form_bulk');

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'bulk';
                actionInput.value = action;
                form.appendChild(actionInput);
                form.submit();
            }
        });
    }

</script>
@endsection
