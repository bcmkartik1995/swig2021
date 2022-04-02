@extends('layouts.admin')

@section('styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin-assets/js/plugin/summernote/summernote-lite.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/css/plugin/toaster/vendor/toastr.css') }}" media="screen">

@endsection

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header-left col-12 mb-2 mt-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h5 class="content-header-title float-left pr-1 mb-0">Services</h5>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb p-0 mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active"><a href="{{ route('services.index') }}">Services</a>
                            </li>
                            <li class="breadcrumb-item">Add Services
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Basic Vertical form layout section start -->
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Services</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                            <form id="form_subservice" method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Category</label>
                                            <select name="category_id" id="category_id" class="form-control select2">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (old('category_id') == $category->id ? 'selected':'') }}>{{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('category_id'))
                                                <label id="name-error" class="error" for="category_id">{{ $errors->first('category_id') }}</label>
                                            @endif
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                        <label class="form-label">Sub Category</label>
                                            <select name="sub_category_id" id="sub_category_id" class="form-control select2">
                                                <option value=""></option>
                                            </select>
                                            @if($errors->has('sub_category_id'))
                                                <label id="name-error" class="error" for="sub_category_id">{{ $errors->first('sub_category_id') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    {{--<!-- <div class="form-group">
                                        <div class="form-line">
                                        <label class="form-label">City</label>
                                            <select name="city_id[]" id="p_city_id" class="multi-select2 form-control" multiple="multiple">
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->id }}" {{!empty(old('city_id')) && in_array($city->id,old('city_id')) ? 'selected':''}}>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('city_id'))
                                                <label id="name-error" class="error" for="city_id">{{ $errors->first('city_id') }}</label>
                                            @endif
                                        </div>
                                    </div> -->--}}
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" name="title" value="{{old('title')}}">
                                            @if($errors->has('title'))
                                                <label id="name-error" class="error" for="title">{{ $errors->first('title') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Icon</label>
                                            <input type="file" class="form-control @if($errors->has('image')) is-invalid @endif" name="image" value="{{old('image')}}">
                                            @if($errors->has('image'))
                                                <label id="name-error" class="error" for="image">{{ $errors->first('image') }}</label>
                                            @endif
                                            <p><small class="text-muted">Only allowed png format. Image resolution must be 64*64. Max file size allowed : 2MB</small></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Banner</label>
                                            <input type="file" class="form-control @if($errors->has('banner')) is-invalid @endif" name="banner" value="{{old('banner')}}">
                                            @if($errors->has('banner'))
                                                <label id="name-error" class="error" for="banner">{{ $errors->first('banner') }}</label>
                                            @endif
                                            <p><small class="text-muted">Only allowed jpg, png, jpeg format. Image resolution must be 300*200. Max file size allowed : 2MB</small></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">multi media</label>
                                            <input type="file" class="form-control @if($errors->has('multi_media')) is-invalid @endif" name="multi_media[]" value="{{old('multi_media')}}" multiple>
                                            @if($errors->has('multi_media'))
                                                <label id="name-error" class="error" for="multi_media">{{ $errors->first('multi_media') }}</label>
                                            @endif
                                            @if ($errors->has('multi_media.*'))
                                                <div class="help-block">
                                                    <label id="name-error" class="error" for="banner">{{ $errors->first('multi_media.*') }}</label>
                                                </div>
                                            @endif
                                            <p><small class="text-muted">Only allowed jpg, png, jpeg, mp4 format. Image resolution must be 300*200</small></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Price</label>
                                            <input type="text" class="form-control @if($errors->has('price')) is-invalid @endif" name="price" value="{{old('price')}}">
                                            @if($errors->has('price'))
                                                <label id="name-error" class="error" for="price">{{ $errors->first('price') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                        $hours_arr = range(0,24);
                                        $minute_arr = range(0,59);
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label class="form-label">Hours</label>
                                                    <select name="hour" id="" class="form-control select2">
                                                        <option value="" selected>Select Hours</option>
                                                        @foreach($hours_arr as $hour)
                                                        <option value="{{$hour}}" {{ (old('hour') == "$hour" ? 'selected':'') }}>{{$hour}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('hour'))
                                                        <label id="name-error" class="error" for="hour">{{ $errors->first('hour') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <label class="form-label">Minute</label>
                                                    <select name="minute" id="" class="form-control select2">
                                                        <option value="" selected>Select Minute</option>
                                                        @foreach($minute_arr as $minute)
                                                        <option value="{{$minute}}" {{ (old('minute') == "$minute" ? 'selected':'') }}>{{$minute}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('minute'))
                                                        <label id="name-error" class="error" for="minute">{{ $errors->first('minute') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control @if($errors->has('description')) is-invalid @endif" name="description" id="description">{{old('description')}}</textarea>
                                            @if($errors->has('description'))
                                                <label id="description-error" class="error" for="description">{{ $errors->first('description') }}</label>
                                            @endif
                                            <p><small class="text-muted">Max file size allowed : 500Kb.</small></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <label class="form-label">Long Description</label>
                                            <textarea class="form-control @if($errors->has('long_description')) is-invalid @endif" name="long_description" id="long_description">{{old('long_description')}}</textarea>
                                            @if($errors->has('long_description'))
                                                <label id="long_description-error" class="error" for="long_description">{{ $errors->first('long_description') }}</label>
                                            @endif
                                            <p><small class="text-muted">Max file size allowed : 500Kb.</small></p>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic Vertical form layout section end -->
    </div>
</div>



@endsection

@section('scripts')

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('assets/admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

<script src="{{ asset('assets/admin-assets/js/plugin/summernote/summernote-lite.js') }}"></script>
<script src="{{ asset('assets/admin-assets/js/plugin/toaster/toastr.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<script>

    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Write Your Description Here',
            tabsize: 2,
            height: 200,
            maximumImageFileSize: 500*1024, // 500 KB
            callbacks:{
                onImageUploadError: function(msg){
                    //console.log(msg + ' (1 MB)');
                    toastr.error('File Size Less Then 500KB', 'Error', { "progressBar": true });
                }
            },
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                //['fontname', ['fontname']],
                // ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['codeview']],
                //['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ],
        });
        $('#form_subservice').submit(function(){
            content = $('#description').summernote('code');
            $('#description').val(content);

            content = $('#long_description').summernote('code');
            $('#long_description').val(content);
        });

        $('#long_description').summernote({
            placeholder: 'Write Your Description Here',
            tabsize: 2,
            height: 200,
            maximumImageFileSize: 500*1024, // 500 KB
            callbacks:{
                onImageUploadError: function(msg){
                    //console.log(msg + ' (1 MB)');
                    toastr.error('File Size Less Then 500KB', 'Error', { "progressBar": true });
                }
            },
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                //['fontname', ['fontname']],
                // ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['codeview']],
                //['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ],
        });
    });


    $('#category_id').on('change', function () {
    // console.log(e);
    var cat_id = $(this).val();
    var _token = $("#form_subservice input[name='_token']").val();
    //console.log(cat_id);

    $.ajax({
        url: "{{route('ajax-subcat')}}",
        type: 'POST',
        data: { cat_id: cat_id, _token: _token },
        success: function (data) {
            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value ="">Select Sub Category</option>');
            $.each(data, function (inedx, subcatObj) {
                //console.log(subcatObj.title);
                selected = '{{old('sub_category_id')}}'==subcatObj.id?'selected':'';
                $('#sub_category_id').append('<option value ="' + subcatObj.id + '" '+selected+'>' + subcatObj.title + '</option>');
            });
        }
    });

});

$(document).ready(function(){
    $('#category_id').trigger('change');
});

    $(".select2").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        dropdownAutoWidth: true,
        width: '100%'
    });

    $(".multi-select2").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        placeholder: "Select City",
        dropdownAutoWidth: true,
        width: '100%'
    });
    // $(".multi-select").select2({
    //     //maximumSelectionLength: 2
    // });
</script>
@endsection
