@extends('layouts.employeecontent')
@section('employeecontent')

@if (session()->has('status'))
    @if (session('status') === 'success')
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            {{ session('message') }}
        </div>
    @endif
@endif

<div class="service-fee-pages">
    <p class="title-common">Merchnat Service Fee</p>
    <div class="tab-seaction">
        <div class="tab-content">
            <div class="table-data">
                <table id="listofcommistionfee" class="table dataTable">
                    <thead>
                        <tr>
                            <td>S.no</td>
                            <td>Company Name</td>
                            <td>Merchnat ID</td>
                            <td>Merchant Name</td>
                            <td>No of Settings</td>
                            <td>View SlaB</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var listofcommistionfee = $('#listofcommistionfee').DataTable({
            sDom: '<"top"f>rt<"bottom"lp><"clear">',
            pageLength: 10,
            language: {
                search: '',
                searchPlaceholder: 'Search',
                sLengthMenu: "Page _MENU_",
                paginate: {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Prev"
                },
            },
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: '/merchant/service/list',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(d) {
                    d.merchant_id = $('#businesstypeselect').val();
                    d.status = $('#categorynameselect').val();
                    d.datetimes = $('input[name="datetimes"]').val();
                }
            },
            columns: [{
                    data: 'id',
                    title: 'S.No',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'company_name',
                    name: 'Compay Name'
                },
                {
                    data: 'user_label_id',
                    name: 'Merchnat ID'
                },
                {
                    data: 'first_name',
                    name: 'Merchant Name'
                },
                {
                    data: 'slabCount',
                    name: 'No of Settings',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'Action',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
    });
</script>
@endsection