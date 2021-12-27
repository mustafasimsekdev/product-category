{{-- layout --}}
@extends('admin.layouts.contentLayoutMaster')

{{-- title --}}
@section('title', __('locale.Product Edit'))

{{-- vendor-style --}}
@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/select2/select2.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('vendors/select2/select2-materialize.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/form-select2.min.css')}}">

@endsection

{{-- page-style --}}
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.min.css')}}">
    <style>
        .jconfirm-cell .container {
            width: 500px;
        }
    </style>
@endsection

{{-- breadcrumbs --}}
@section('breadcrumbs')
    <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s10 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{__('locale.Product Edit')}}</span></h5>
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">{{__('locale.Home Page')}}</a>
                        </li>
                        <li class="breadcrumb-item"><a
                                href="{{url('/admin/products-list')}}">{{__('locale.Product List')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{__('locale.Product Edit')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- content --}}
@section('content')
    <!-- users edit start -->
    <div class="section users-edit">

        <div class="card">
            <div class="card-content">
                <!-- <div class="card-body"> -->
                <ul class="tabs mb-2 row">
                    <li class="tab">
                        <a class="display-flex align-items-center" href="#account">
                            <i class="material-icons mr-1">person_outline</i><span>{{__('locale.General Information')}}</span>
                        </a>
                    </li>
                </ul>
                <div class="divider mb-3"></div>

                <form id="accountForm">
                @csrf
                <!-- users edit media object start -->
                    <div class="row">
                        <input id="product_id" name="product_id" type="hidden"
                               value="{{$product->id}}">
                        <div class="col s6 m6 l6 input-field">
                            <div class="row">
                                <div class="col s12 m12 l12 input-field">
                                    <label for="name">{{__('locale.Name')}}</label>
                                    <input id="name" name="name" type="text"
                                           data-error=".errorTxt2" value="{{$product->name}}" required>
                                    <small class="errorTxt2"></small>
                                </div>
                                <div class="col s12 m12 l12 input-field">
                                    <label for="description">{{__('locale.Description')}}</label>
                                    <input id="description" name="description" type="text"
                                           data-error=".errorTxt2" value="{{$product->description}}" required>
                                    <small class="errorTxt2"></small>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 l6">
                            <input type="file" id="user_image" name="user_image"
                                   class="dropify"
                                   data-max-file-size="5M"
                                   data-allowed-file-extensions="jpeg jpg png svg"
                                   data-default-file="{{isset($product->photo) ? asset('storage/images/product-image/'.$product->photo) : ''}}"
                                   required/>
                        </div>
                    </div>
                    <!-- users edit media object ends -->
                    <!-- users edit account form start -->
                    <div class="row">
                        <div class="col s6 input-field">
                            <label for="price">{{__('locale.Price')}}</label>
                            <input id="price" name="price" type="number"
                                   data-error=".errorTxt2" value="{{$product->price}}" required>
                            <small class="errorTxt2"></small>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <select class="select2 browser-default" name="category_select2[]" id="category_select2"
                                        multiple>
                                    <optgroup label="{{__('locale.Main Category')}}">

                                        @foreach($mainCategory as $category)
                                            @foreach($categoryProduct as $cProduct)
                                                @if($category->id == $cProduct->category_id)
                                                    {{$selected = 'selected'}}
                                                    @break
                                                @else
                                                    {{$selected = ''}}
                                                @endif
                                            @endforeach

                                            <option
                                                value="{{$category->id}}" {{$selected}}>{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{__('locale.Sub Category')}}">

                                        @foreach($subCategory as $category)
                                            @foreach($categoryProduct as $cProduct)
                                                @if($category->id == $cProduct->category_id)
                                                    {{$selected = 'selected'}}
                                                    @break
                                                @else
                                                    {{$selected = ''}}
                                                @endif
                                            @endforeach

                                            <option
                                                value="{{$category->id}}" {{$selected}}>{{$category->name}}</option>
                                        @endforeach
                                    </optgroup>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 input-field">
                        <div class="switch">
                            {{__('locale.Activity Status')}} :
                            <label>
                                {{__('locale.Active')}}
                                <input type="checkbox" id="is_active"
                                       name="is_active" {{$product->is_active == 1 ? "checked":''}}>
                                <span class="lever"></span>
                                {{__('locale.Passive')}}
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 display-flex justify-content-end mt-3">
                            <button type="submit"
                                    class="btn indigo waves-effect waves-light mr-2">
                                {{__('locale.Save Changes')}}
                            </button>
                        </div>
                    </div>
                    <!-- users edit account form ends -->
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <ul class="tabs mb-2 row">
                    <li class="tab">
                        <a class="display-flex align-items-center" href="#account">
                            <i class="material-icons mr-1">person_outline</i><span>{{__('locale.History')}}</span>
                        </a>
                    </li>
                </ul>
                <div class="divider mb-3"></div>
                <div class="responsive-table">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{__('locale.Record Date')}}</th>
                            <th>{{__('locale.Creator')}}</th>
                            <th>{{__('locale.Old Log')}}</th>
                            <th>{{__('locale.Log')}}</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>{{date('d-m-Y H:i:s', strtotime($history->created_at))}}
                                <td>{{isset($history->authorD) ? $history->authorD->fullname : __('locale.Not Known')}}
                                @if(isset($history->oldlogC))
                                    <td>{{$history->oldlogC->log}}</td>
                                @else
                                    <td>-</td>
                                @endif

                                <td>{{$history->logC->log}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
    <!-- users edit ends -->
@endsection

{{-- vendor-script --}}
@section('vendor-script')
    <script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page-script --}}
@section('page-script')
    <script src="{{asset('js/scripts/form-select2.min.js')}}"></script>

    <script src="{{asset('js/scripts/page-users.min.js')}}"></script>
    <script src="{{asset('vendors/dropify/js/dropify.min.js')}}"></script>
    <script src={{asset('js/scripts/form-file-uploads.min.js')}}></script>

    <script>
        $('#accountForm').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            var name = $('#name').val();
            var description = $('#description').val();
            var user_image = $('#user_image').val();
            var price = $('#price').val();


            if (name == '' || description == '' || user_image == '' || price == '') {
                $.alert("{{__('locale.Enter the information completely')}}", "{{__('locale.Warning')}}");
            } else {
                $.ajax({
                    url: "{{ route('admin.product.update') }}",
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status == 1) {
                            var status = 'green';
                        } else {
                            var status = 'red'
                        }
                        $.confirm({
                            title: "{{__('locale.Attention Admin')}}",
                            content: data.message,
                            type: status,
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: "{{__('locale.Ok')}}",
                                    btnClass: 'btn-red',
                                    action: function () {
                                        if (data.status == 1) {
                                            setTimeout("window.location='{{route('admin.products.list')}}'", 500);
                                        } else {
                                            location.reload();
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function (data) {
                        alert(data.message);
                    }
                });
            }
        });

        $(document).ready(function () {
            //dropy çalıştırmak için
            $('.dropify').dropify({
                tpl: {
                    wrap: '<div class="dropify-wrapper"></div>',
                    loader: '<div class="dropify-loader"></div>',
                    preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ __('locale.Preview') }}</p></div></div></div>',
                    filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
                    clearButton: '<button type="button" class="dropify-clear">{{ __('locale.Remove') }}</button>',
                    errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
                },
                messages: {
                    'default': '{{__('locale.Drag and drop a image here or click')}}',
                    'error': '{{__('locale.Ooops, something wrong happended')}}'
                },
                error: {
                    'fileSize': '{{__('locale.The file size is too big (2MB max)')}}',
                    'imageFormat': '{{__('locale.The image format is not allowed image only)')}}'
                }
            });
        });

        //Jquery validate mesaj
        jQuery.extend(jQuery.validator.messages, {
            required: "{{__('locale.This field is required')}}.",
            email: "{{__('locale.Please enter a valid email address')}}.",
            minlength: jQuery.validator.format("{{__('locale.Please enter at least {0} characters')}}."),
        });
    </script>
@endsection
