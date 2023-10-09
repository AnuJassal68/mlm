<!-- resources/views/income-setting/index.blade.php -->
@extends('admin.layout')


@section('content')
<section class="content">
    <div class="row">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5>Income Settings</h5>
                        <!-- general form elements disabled -->
                        <div class="box box-warning">
                            <div class="box-body">
                               
                                <form role="form" method="post" action="{{ url('/income-setting') }}">
                                    @csrf
                                    <!-- text input -->
                                    <div class="row">
                                        @foreach ($settings as $setting)
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $setting->setlabel }}</label>
                                                <input type="hidden" name="vold_{{ $setting->setname }}" value="{{ $setting->setvalue }}">
                                                <input type="text" name="{{ $setting->setname }}" value="{{ $setting->setvalue }}" class="form-control">
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                @if ($fullset == 'Y' || @in_array("edit", $perset[$page]))
                                                <input type="submit" name="submitclient" value="Update" class="btn btn-success">
                                                @endif
                                            </div>
                                        </div>
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
