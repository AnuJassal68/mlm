@extends('admin.layout')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                <h5>Site Settings</h5>
                    <div class="box box-warning">
                        <div class="box-body">
                            @if($emsg)
                            <div class="alert alert-{{ $etype }}">
                                {{ $emsg }}
                            </div>
                            @endif
                            <form role="form" method="post" action="{{ route('settings.update') }}">
                                @csrf
                                @foreach($setinfo as $setting)
                                <div class="form-group">
                                    <label>{{ $setting->setlabel }}</label>
                                    <input type="hidden" name="vold_{{ $setting->setname }}" value="{{ $setting->setvalue }}">
                                    <input type="text" name="{{ $setting->setname }}" value="{{ $setting->setvalue }}" class="form-control">
                                </div>
                                @endforeach

                                <div class="form-group">
                                    @if($fullset=='Y'||@in_array('edit', $perset[$page]))
                                    <input type="submit" name="submitclient" value="Update" class="btn btn-success">
                                    @endif
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
