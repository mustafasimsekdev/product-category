{{-- layout --}}
@extends('admin.layouts.contentLayoutMaster')

{{-- title --}}
@section('title', __('locale.Category List'))

{{-- vendor-style --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
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
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>{{__('locale.Category List')}}</span></h5>
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">{{__('locale.Home Page')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{__('locale.Category List')}}
                        </li>
                    </ol>
                </div>
                <div class="col s2 m6 l6 show-btn">
                    <a class="btn waves-effect waves-light breadcrumbs-btn right"
                       href="{{route('admin.category.add')}}">
                        <span>{{__('locale.Category Add')}}</span></a>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- content --}}
@section('content')
    <!-- users list start -->
    <section class="users-list-wrapper section">
        <div class="users-list-filter">
            <div class="card-panel">
                <div class="row">
                    <form>
                        {{ csrf_field() }}
                        <div class="col s12 m6 l3">
                            <label for="users-list-status">Status</label>
                            <div class="input-field">
                                <select class="form-control" id="users-list-status">
                                    <option value="">{{__('locale.Any')}}</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->fullname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col s12 m6 l4 display-flex align-items-center show-btn">
                            <a class="btn btn-block indigo waves-effect waves-light"
                               style="cursor:pointer; margin-right: 18px; margin-top: 5px; width: 50%;"
                               onclick="$.filter_st()">
                                <i class="material-icons dp48">search</i><span>{{__('locale.Show')}}</span></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="users-list-table">
            <div class="card">
                <div class="card-content">
                    <!-- datatable start -->
                    <div class="responsive-table">
                        <table id="myTable" class="table" style="width: 100%">
                            <thead>
                            <tr>
                                <th>{{__('locale.ID')}}</th>
                                <th>{{__('locale.Category Name')}}</th>
                                <th>{{__('locale.Slug')}}</th>
                                <th>{{__('locale.Category Type')}}</th>
                                <th>{{__('locale.Created At')}}</th>
                                <th>{{__('locale.Edit')}}</th>
                                <th>{{__('locale.Delete')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- datatable ends -->
                </div>
            </div>
        </div>
    </section>
    <!-- users list ends -->
@endsection

{{-- vendor-script --}}
@section('vendor-script')
    <script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendors/data-tables/buttons/dataTables.buttons.min.js')}}"></script>
@endsection

{{-- page-script --}}
@section('page-script')
    <script src="{{asset('js/scripts/page-users.min.js')}}"></script>

    <script>

        function formatDate(date) {
            var d = new Date(date),
                minute = '' + d.getMinutes(),
                hours = '' + d.getHours(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [day, month, year,].join('-') + " " + [hours, minute].join(':');
        }

        function deleteCategory(id) {

            $.confirm({
                title: '{{__('locale.Delete Category')}}',
                content: '{{__("locale.This dialog will automatically trigger 'cancel' in 6 seconds if you don't respond")}}',
                autoClose: 'cancel|8000',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    deleteUser: {
                        btnClass: 'btn-red',
                        text: "{{__('locale.Delete Category')}}",
                        action: function () {
                            $.post("{{ route('admin.category.delete') }}", {
                                id: id,
                                _method: 'POST',
                                _token: '{{ csrf_token() }}'
                            })
                                .done(function (response) {
                                    if (response == 'ok') {
                                        $.alert("{{__('locale.Deleted the category')}}").delay(3000);
                                    } else {
                                        $.alert("{{__('locale.Operation failed')}}").delay(3000);
                                    }
                                });
                            setTimeout("window.location='categories-list'", 500);
                        }
                    },
                    cancel: {
                        text: "{{__('locale.Cancel')}}",
                    }
                }
            });
        }

        $(document).ready(function () {
            $('#myTable').DataTable({

                responsive: true,
                "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Tümü"]],
                "order": [[0, "desc"]],
                "processing": true,
                "serverSide": true,

                "ajax": {
                    url: "{{url('admin/datatable')}}?type=categories_list",
                    type: 'GET',
                    data: function (d) {
                        d.user = $('#users-list-status').val();
                    }
                },
                'columns': [
                    {
                        data: 'id', class: 'center',
                        "searchable": false,
                        "orderable": true,

                    },
                    {
                        data: 'name', name: 'name',
                        "searchable": true,
                        "orderable": true,
                    },
                    {
                        data: 'slug',
                        "searchable": false,
                        "orderable": false,
                    },
                    {
                        data: null,
                        render: function (data) {
                            console.log(data.parent_id);
                            if (data.parent_id == null) {
                                return '<span class="chip green lighten-5">' +
                                    '<span class="green-text">{{__('locale.Main Category')}}</span>' +
                                    '</span>';
                            } else {
                                return '<span class="chip red lighten-5">' +
                                    '<span class="red-text">{{__('locale.Sub Category')}}</span><br>' +
                                    '<span class="red-text">Parent ID: '+data.parent_id+'</span>' +
                                    '</span>';
                            }
                        },
                        "searchable": false,
                        "orderable": true,
                    },
                    {
                        data: null,
                        render: function (data) {
                            return formatDate(data.created_at);
                        },
                        "searchable": false,
                        "orderable": true,
                    },
                    {
                        data: null, class: 'center',
                        render: function (data) {
                            return '<a href="/admin/category-edit/' + data.id + '">' +
                                '<i class="material-icons">input</i></a>';
                        },
                        "searchable": false,
                        "orderable": false,
                    },
                    {
                        data: null, class: 'center',
                        render: function (data) {
                            return '<a href="javascript:void(0);" onclick="deleteCategory(' + data.id + ')">' +
                                '<i class="material-icons dp48">delete</i></a>';
                        },
                        "searchable": false,
                        "orderable": false,
                    },

                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/a5734b29083/i18n/Turkish.json"
                }
            });

            $.filter_st = function () {
                $('#myTable').DataTable().draw(true);
            }

        });
    </script>

@endsection
