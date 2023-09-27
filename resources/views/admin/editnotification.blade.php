@extends('admin.layout')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h3 class="m-3 text-info text-center"> Email Notification</h3>
                <div class="card-body">
                    <div class="box box-warning">
                        <div class="box-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <form role="form" method="post" action="{{ isset($binfo) ? url('/notification/update/'.$binfo->id) : url('/notification/update') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title" value="{{ old('title', optional($binfo)->title) }}" class="form-control" readonly>
                                            <!-- Add a hidden input field to store the original data -->
                                            <input type="hidden" name="original_title" value="{{ optional($binfo)->title }}">
                                        </div>


                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" name="subject" value="{{ old('subject', optional($binfo)->subject) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label><strong>Mask:</strong><br /> [FULLNAME], [FIRSTNAME], [MIDDLENAME], [LASTNAME], [LOGINID], [PASSWORD], [REFERAL_INFO], [IP_ADDRESS], [DATE_TIME]</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Message Body</label>
                                            <textarea type="text" name="content" id="editor" class="form-control" required>{{ old('content', optional($binfo)->notification) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Active </label>
                                            <br />
                                            <input type="checkbox" name="bactive" {{ optional($binfo)->bActive == 'Y' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submitclient" value="{{ isset($binfo) ? 'Update' : 'Add' }}" class="btn btn-success">
                                    <a href="{{ url('notifications') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');

</script>

@endsection
