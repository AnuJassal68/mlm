@extends('admin.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center">Manage Cities</h4>
                    <div class="box">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Add New
                        </button>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form role="form" method="post" action="{{route('citiesstatus')}}" id="form_bulk">
                                @csrf
                                <table id="order-listing" class="table table-bordered table-hover table-striped" id="example">
                                    <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                        <tr>
                                            <th><input type="checkbox" id="select_all" class="chk-users" name="checkbox[]" />
                                            <th width="32%">Cities</th>
                                            <th width="26%" class="text-center">Status</th>
                                            <th width="15%" class="text-center">Action

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach($rinfo as $rinfo)
                                        <tr class="">
                                            <td><input type="checkbox" class="chk-user" name="checkbox[]" value="{{ $rinfo->id }}" /></td>
                                            <td>{{$rinfo->title}}</td>
                                            <td class="text-center">{{$rinfo->bActive}}</td>
                                            <td class="text-center">
                                                <a href="{{ $rinfo->id }}" class="btn btn-warning edit-city" data-bs-toggle="modal" data-bs-target="#staticBackdrop1" data-city-id="{{ $rinfo->id }}" data-city-title="{{ $rinfo->title }}" data-city-code="{{ $rinfo->code }}" data-updateUrl="{{ url('citiesupdate',$rinfo->id) }}" data-city-pincodes="{{ $rinfo->pincodes }}" data-city-bactive="{{ $rinfo->bActive }}">Edit</a>

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
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" method="post" action="{{route('addcities', $pid)}}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>City Name</label>
                            <input type="text" name="title" value="{{ $binfo->title ?? '' }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>City Telephone Code</label>
                            <input type="text" name="code" value="{{ $binfo->code ?? '' }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Pincodes </label>
                            <span class="pull-right">
                                <em>Separated by Comma "," &nbsp;e.g., 110001,110004,110003</em>
                            </span>
                            <textarea name="pincodes" class="form-control" rows="10">{{ $binfo->pincodes ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Active </label>
                            <input type="checkbox" name="bactive" {{ isset($binfo) && $binfo->bActive == 'Y' ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">                    
                            <input type="submit" name="submitclient" value="Add" class="btn btn-success">                           
                            <input type="button" name="cancel" id="cancel" value="Cancel" class="btn btn-danger">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- update modale --}}
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="" id="update-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit City</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>City Name</label>
                            <input type="text" name="title" id="edit-city-title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>City Telephone Code</label>
                            <input type="text" name="code" id="edit-city-code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Pincodes</label>
                            <textarea name="pincodes" id="edit-city-pincodes" class="form-control" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Active</label>
                            <input type="checkbox" name="bactive" id="edit-city-bactive">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="submit" name="submitclient" value="Update" class="btn btn-warning">
                            <button type="button" name="cancel" id="cancel" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
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

    //edit modal

    $(document).ready(function() {
        // Update modal data when an "Edit" button is clicked
        $('.edit-city').on('click', function() {
            var button = $(this);
            var modal = $('#staticBackdrop1');
            var updateUrl = $(this).attr('data-updateUrl');
            var cityId = button.data('city-id');
            var cityTitle = button.data('city-title');
            var cityCode = button.data('city-code');
            var cityPincodes = button.data('city-pincodes');
            var cityBActive = button.data('city-bactive');

            modal.find('#edit-city-title').val(cityTitle);
            modal.find('#edit-city-code').val(cityCode);
            modal.find('#edit-city-pincodes').val(cityPincodes);
            modal.find('#edit-city-bactive').prop('checked', cityBActive === 'Y');
            $('#update-form').attr('action', updateUrl);
        });
    });

</script>
@endsection
