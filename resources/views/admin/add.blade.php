@extends('admin.layout')

@section('content')

<section class="content">

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Manage Countries</h4>
                    <div class="box box-warning">
                        <div class="box-body">
                            @if(session('message'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('message') }}
                            </div>
                            @endif
                            <form role="form" method="post" action="{{ url('/countries/addedit/' . (isset($country->id) ? $country->id : '')) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ old('title', $country->title ?? '') }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Country Code</label>
                                    <input type="text" name="code" value="{{ old('code', $country->code ?? '') }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    <input type="checkbox" name="bactive" {{ isset($country) && $country->bActive == 'Y' ? 'checked' : '' }}>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submitclient" value="{{ isset($country) ? 'Update' : 'Add' }}" class="btn btn-success">
                                    <input type="button" name="cancel" id="cancel" value="Cancel" onclick="javascript:window.location.href='{{ url('/countries') }}'" class="btn btn-danger">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
              </div>
            </div>
</section>
@endsection
