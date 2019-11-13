@extends('admin.layout.app')
@section('title','Blog Listing')
@section('css')
<link href="{{URL::asset('/public/admn/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('/public/admn/css/plugincss/dataTables.bootstrap.css')}}" rel="stylesheet">
<link href="{{URL::asset('/public/admn/css/pages/tables.css')}}" rel="stylesheet">
@stop
@section('content')
<div id="content" class="bg-container">
    <header class="head">
        <div class="main-bar">
            <div class="row no-gutters">
                <div class="col-6">
                    <h4 class="m-t-5">
                        <i class="fa fa-bold"></i>
                        Blog Listing
                    </h4>
                </div>
            </div>
        </div>
    </header>
    @if(session()->has('success'))
    <div class="col-md-12">
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    </div>
    @endif
    @if(session()->has('error'))
    <div class="col-md-12">
        <div class="alert alert-success">
            {{ session()->get('error') }}
        </div>
    </div>
    @endif
    <div class="outer">
        <div class="inner bg-container">
            <!--top section widgets-->
            <div class="card">
                <div class="card-header bg-white">
                    Blog Grid
                </div>
                <div class="btn-group blog-btn">
                    <a href="{{url('admin/blog')}}" id="editable_table_new" class=" btn btn-default">
                        Add Blog  <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="card-block m-t-35" id="user_body">
                    <div class="table-toolbar">
                        <div class="btn-group float-right users_grid_tools">
                            <div class="tools"></div>
                        </div>
                    </div>
                    <div>
                        <div>
                            <table class="table  table-striped table-bordered table-hover dataTable no-footer" id="editable_table" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc wid-20" tabindex="0" rowspan="1" colspan="1">Sl</th>
                                        <th class="sorting_asc wid-20" tabindex="0" rowspan="1" colspan="1">Feature Image</th>
                                        <th class="sorting_asc wid-20" tabindex="0" rowspan="1" colspan="1">Meta Title</th>
                                        <th class="sorting wid-10" tabindex="0" rowspan="1" colspan="1">Blog title</th>
                                        <th class="sorting wid-15" tabindex="0" rowspan="1" colspan="1">Created By</th>
                                        <th class="sorting wid-15" tabindex="0" rowspan="1" colspan="1">Created At</th>
                                        <th class="sorting wid-10 noExport" tabindex="0" rowspan="1" colspan="1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div> 
    </div>


    @endsection
    @section('pagescript')
    <script>
        var base_url = "{{asset('/')}}";
        $(document).ready(function () {
        var table = $('#editable_table');
                table.DataTable({
                        "dom": "<'text-right'B><f>lr<'table-responsive't><'row'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
                        "sServerMethod": "get",
                        "bProcessing": true,
                        "bServerSide": true,
                        "sAjaxSource": base_url + 'admin/fetchblogs',
                        "columnDefs": [
                        { "className": "dt-center", "targets": [0, 3, 4] }
                        ],
                        "rowCallback": function (nRow, aData, iDisplayIndex) {
                            var oSettings = this.fnSettings ();
                            $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                            return nRow;
                        },
                        "aoColumns": [
                        { 'bSortable' : true},
                        { 'bSortable' : false },
                        { 'bSortable' : true },
                        { 'bSortable' : true },
                        { 'bSortable' : true },
                        { 'bSortable' : true },
                        { 'bSortable' : false, "width": "10%" }
                        ],
                        "order": [[ 4, "desc" ]]
                });
                var tableWrapper = $("#editable_table_wrapper");
                tableWrapper.find(".dataTables_length select").select2({
        showSearchInput: false //hide search box with special css class
        }); // initialize select2 dropdown
            $("#editable_table_wrapper .dt-buttons .btn").addClass('btn-secondary').removeClass('btn-default');
            $(".dataTables_wrapper").removeClass("form-inline");
        });
	</script>
    <script src="{{URL::asset('/public/admn/js/select2.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/pluginjs/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/pluginjs/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/public/admn/js/pluginjs/dataTables.responsive.min.js')}}"></script>
    @stop