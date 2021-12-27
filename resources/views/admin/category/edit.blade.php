{{-- layout --}}
@extends('admin.layouts.contentLayoutMaster')

{{-- title --}}
@section('title', __('locale.Category Edit'))

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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{__('locale.Category Edit')}}</span></h5>
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">{{__('locale.Home Page')}}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/categories-list')}}">{{__('locale.Category List')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{__('locale.Category Edit')}}
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
                        <input id="category_id" name="category_id" type="hidden"
                               value="{{$category->id}}">
                        <div class="col s6 input-field">
                            <label for="name">{{__('locale.Category Name')}}</label>
                            <input id="name" name="name" type="text"
                                   value="{{$category->name}}"
                                   data-error=".errorTxt2" required>
                            <small class="errorTxt2"></small>
                        </div>

                        <div class="col s6 input-field">
                            <select class="select2 browser-default" name="category_select2" id="category_select2">
                                <option value="" selected>{{__('locale.Main Category')}}</option>
                                @foreach($mainCategories as $mainCategory)
                                    <option
                                        value="{{$mainCategory->id}}" {{$mainCategory->id == $category->parent_id ? 'selected' : ''}}>{{$mainCategory->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="breadcrumbs-dark" id="breadcrumbs-wrapper">
                        <!-- Search for small screen-->
                        <div class="col s12 m6 l6">
                            <label for="name">{{__('locale.All Parent Slug')}}</label>
                            <ol class="breadcrumbs mb-0">
                                @if($category->parent_id)
                                    @foreach($mainParentCategories as $mainParentCategory)
                                        <li class="breadcrumb-item"><a
                                                href="{{url('/admin/category-edit/'.$mainParentCategory->id)}}"
                                                style="color: gray!important;">{{$mainParentCategory->name}}</a>
                                        </li>
                                    @endforeach
                                @endif
                                <li class="breadcrumb-item active"><a
                                        href="{{url('/admin/category-edit/'.$category->id)}}"
                                        style="color: gray!important;">{{$category->name}}</a>
                                </li>
                            </ol>
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
                    url: "{{ route('admin.category.update') }}",
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
