@extends('admin.layout')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="text-center">Manage Countries</h4>

                    @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                    @endif
                    <a href="{{ url('/countries/addedit') }}" class="btn btn-info m-2 mb-3">Add New</a>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{ route('teams') }}" method="get" id="form_bulk">
                            @csrf
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="order-listing" class="table table-bordered table-hover table-striped" id="example">
                                        <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                            <tr>
                                                <th><input type="checkbox" id="select_all" class="chk-users" name="checkbox[]" />
                                                <th width="32%">Country</th>
                                                <th width="26%" class="text-center">Manage States</th>
                                                <th width="22%" class="text-center">Status</th>
                                                <th width="15%" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($countries as $country)
                                            <tr {{ $country['bActive'] == 'Y' ? '' : 'class=danger' }}>

                                                <td><input type="checkbox" class="chk-user" name="checkbox[]" value="{{ $country['id'] }}" /></td>
                                                <td>{{ $country['title'] }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('states' , $country['id']) }}">States</a>
                                                </td>
                                                <td class="text-center">{{ $country['bActive'] == 'Y' ? 'Active' : 'Deactive' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('/countries/addedit/' . $country['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if(count($countries) == 0)
                                            <tr>
                                                <td colspan="5" class="text-center">-no records-</td>
                                            </tr>
                                            @endif
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
    $("document").ready(function() {
        var table = $("#order-listing").DataTable();
        table.destroy();
        $("#order-listing_filter.dataTables_filter").append($("#categoryFilter"));

        var statusIndex = -1;
        $("#order-listing thead th").each(function(i) {
            if ($(this).text().trim() === "bActive") {
                statusIndex = i;
                return false;
            }
        });

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var selectedStatus = $('#categoryFilter').val();
            var status = data[statusIndex];

            if (selectedStatus === "" || status === selectedStatus) {
                return true;
            }

            return false;
        });

        $("#categoryFilter").change(function(e) {
            table.draw();
        });

        table.draw();
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

</script>
@endsection
