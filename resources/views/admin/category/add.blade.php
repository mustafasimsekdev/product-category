{{-- layout --}}
@extends('admin.layouts.contentLayoutMaster')

{{-- title --}}
@section('title', __('locale.Category Add'))

{{-- vendor-style --}}
@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/select2/select2.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('vendors/select2/select2-materialize.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{__('locale.Category Add')}}</span></h5>
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">{{__('locale.Home Page')}}</a>
                        </li>
                        <li class="breadcrumb-item">{{__('locale.Category Add')}}
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
                <!-- users edit account form start -->
                    <div class="row">
                        <div class="col s6 input-field">
                            <label for="name">{{__('locale.Category Name')}}</label>
                            <input id="name" name="name" type="text"
                                   data-error=".errorTxt2" required>
                            <small class="errorTxt2"></small>
                        </div>
                        <div class="col s6 input-field">
                            <select class="select2 browser-default" name="category_select2" id="category_select2">
                                <option value="" selected>{{__('locale.Main Category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
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
    <script src="{{asset('js/scripts/page-users.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#category_select2').select2();
        });
    </script>

    <script>
        $('#accountForm').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            var category = $('#name').val();

            if (category == '') {
                $.alert("{{__('locale.Enter the information completely')}}", "{{__('locale.Warning')}}");
            } else {
                $.ajax({
                    url: "{{ route('admin.category.store') }}",
                    type: 'POST',
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
                                            setTimeout("window.location='{{route('admin.categories.list')}}'", 500);
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


        //Jquery validate mesaj
        jQuery.extend(jQuery.validator.messages, {
            required: "{{__('locale.This field is required')}}.",
            email: "{{__('locale.Please enter a valid email address')}}.",
            minlength: jQuery.validator.format("{{__('locale.Please enter at least {0} characters')}}."),
        });
    </script>
@endsection
