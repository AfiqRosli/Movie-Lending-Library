@extends('layouts.app')

@section('title', 'Member')

@section('style')
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs4/dt-1.10.25/af-2.3.7/b-1.7.1/cr-1.5.4/date-1.1.0/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css" />
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-5">
        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lend.home') }}">Lend</a></li>
        <li class="breadcrumb-item active" aria-current="page">Record</li>
    </ol>
</nav>

@section('h1', 'Lending Movies - Records')

<table id="lend-table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Movie ID</th>
            <th>Member ID</th>
            <th>Lending Date</th>
            <th>Returned Date</th>
            <th>Lateness Charge</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lends as $lend)
        <tr>
            <td>{{ $lend->id }}</td>
            <td>{{ $lend->movie_id }}</td>
            <td>{{ $lend->member_id }}</td>
            <td>{{ Helper::formatDate($lend->lending_date) }}</td>
            <td>{{ $lend->returned_date <> null ? Helper::formatDate($lend->returned_date) : 'Lending' }}</td>
            <td>{{ Helper::latenessChargeDisplay($lend->lateness_charge) }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Movie ID</th>
            <th>Member ID</th>
            <th>Lending Date</th>
            <th>Returned Date</th>
            <th>Lateness Charge</th>
            <th>Actions</th>
        </tr>
    </tfoot>
</table>
@endsection

@section('script')
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs4/dt-1.10.25/af-2.3.7/b-1.7.1/cr-1.5.4/date-1.1.0/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js">
</script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

<script>
    var lendTable

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        lendTable = $('#lend-table').DataTable({
            responsive: true,
            language: {
                emptyTable: "No movie has been lended (～o￣3￣)～",
                zeroRecords: "No matching lend records found (´。＿。｀))"
            },
            columns: [
                { data: 'id', visible: false },
                { data: 'movie_id' },
                { data: 'member_id' },
                { data: 'lending_date' },
                { data: 'returned_date' },
                { data: 'lateness_charge' },
                { data: null, orderable: false, render: (data, type, row) => {
                        return '<div class="text-center">' + generateEditIcon(row) + '</div>'
                    }
                },
            ],
        });
    });


    function generateEditIcon(row) {
        var editIcon = ''

        if (row.lateness_charge == '-') {
            editIcon =
            `
            <i onclick="openEditModal(this)"
                data-id="${row.id}"
                data-movie_id="${row.movie_id}"
                data-member_id="${row.member_id}"
                data-lending_date="${row.lending_date}"
                data-returned_date="${row.returned_date}"
                data-lateness_charge="${row.lateness_charge}"
                class="js-action-edit fas fa-edit fa-lg icon icon__edit">
            </i>
            `
        }

        return editIcon
    }
</script>
@endsection
