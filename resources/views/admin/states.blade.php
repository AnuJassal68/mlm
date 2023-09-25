@extends('admin.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center">Manage States</h4>

                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Add New
                    </button>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{ route('statestatus') }}" method="get" id="form_bulk">
                            @csrf
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="order-listing" class="table table-bordered table-hover table-striped" id="example">
                                        <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                            <tr>
                                                <th><input type="checkbox" id="select_all" class="chk-users" name="checkbox[]" />
                                                <th width="32%">States</th>
                                                <th width="26%" class="text-center">Manage cities</th>
                                                <th width="22%" class="text-center">Status</th>
                                                <th width="15%" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">

                                            @foreach($rinfo as $rinfo)

                                            <tr>
                                                <td><input type="checkbox" class="chk-user" name="checkbox[]" value="{{ $rinfo['id'] }}" /></td>
                                                <td>{{$rinfo['title']}}</td>
                                                <td class="text-center"><a href="{{route('cities',$rinfo['id'])}}">cities</a>
                                                </td>
                                                <td class="text-center">{{ $rinfo['bActive'] }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stateupdate', ['id' => $rinfo['id']]) }}" class="btn btn-warning update_state" data-bs-toggle="modal" data-bs-target="#staticBackdrop1" data-city-id="{{ $rinfo['id'] }}" data-city-title="{{ $rinfo['title'] }}" data-updateUrl="{{ url('stateupdate',$rinfo['id']) }}" data-city-bactive="{{ $rinfo['bActive'] }}">Edit</a>

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
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('stateadd',$pid)}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>State Name</label>
                            <input type="text" name="title" " class=" form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Active </label>
                            <input type="checkbox" name="bactive">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="submit" name="submitclient" value="Add" class="btn btn-success">
                            <a href="{{ route('states', ['id' => $pid]) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" method="post" action="" id="update-form">
                    @csrf
                    <input type="hidden" name="userid" class="update-userid" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Update State</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>State Name</label>
                            <input type="text" name="title" class="update-title form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Active </label>
                            <input type="checkbox" class="update-bactive" name="bactive">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="submit" name="submitclient" value="Update" class="btn btn-success">
                            <a href="{{ route('states', ['id' => $pid]) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
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
    //filter by status code


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
    $(document).ready(function() {
        $(document).on('click', '.update_state', function() {
            var updateUrl = $(this).attr('data-updateUrl');
            var id = $(this).data('city-id');
            var title = $(this).data('city-title');
            var bactive = $(this).data('city-bactive');

            // Set values for the modal input fields based on the clicked button's data
            $('.update-userid').val(id);
            $('.update-title').val(title);
            $('.update-bactive').prop('checked', bactive === 'Y');
            $('#update-form').attr('action', updateUrl);
            console.log('updateUrl');
        });
    });

</script>
@endsection
