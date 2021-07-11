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
            <th>Movie Title</th>
            <th>Member Name</th>
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
            <td>{{ $lend->getMovieTitle() }}</td>
            <td>{{ $lend->getMemberName() }}</td>
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
            <th>Movie Title</th>
            <th>Member Name</th>
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
                { data: 'movie_id', visible: false },
                { data: 'member_id', visible: false },
                { data: 'movie_title' },
                { data: 'member_name' },
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

    async function openEditModal(el) {
        var lateness_charges = calculateLatenessCharges($(el).attr('data-lending_date'))

        const swalResponse = await Swal.fire({
            title: 'Return Movie',
            html:
            `
            <div class="text-left">
                <h3>Lending Info</h3>
                <p><b>Movie Title:</b> ${$(el).attr('data-movie_title')}</p>
                <p><b>Member Name:</b> ${$(el).attr('data-member_name')}</p>
                <p><b>Lateness Charges:</b> ${lateness_charges.text}</p>

                <div class="custom-control custom-switch">
                    <span onclick="toggleSwalConfirmBtn()" class="no-select">
                        <input type="checkbox" class="custom-control-input" id="swal-has_paid">
                        <label class="custom-control-label" for="swal-has_paid">
                            ${$(el).attr('data-member_name')} has payed the lateness charges
                        </label>
                    </span>
                </div>
            </div>
            `,
            buttonsStyling: false,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: 'Confirm',
            customClass: {
                confirmButton: 'btn btn-primary btn-lg mr-2',
                cancelButton: 'btn btn-secondary btn-lg',
            },
            focusConfirm: false,
            didOpen: () => {
                $(Swal.getConfirmButton()).prop('disabled', true)
            },
            preConfirm: async () => {
                var lend = {
                    id: $(el).data('id'),
                    lateness_charge: lateness_charges.cents
                }

                await patchLend(lend)
            }
        })

        if (swalResponse.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Movie Returned',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-lg mr-2',
                }
            })
        }
    }

    async function patchLend(lend) {
        try {
            var result = await $.ajax({
                url: '/lend/' + lend.id,
                method: 'PATCH',
                data: {
                    lend
                }
            })

            lend = result.updated_lend

            // Update the Frontend data
            var row = getTableRow(lendTable, lend.id)
            var column = getRowColumns(row)
            updateTableRowData(column, lend)
        } catch (error) {
            console.log(error)
            Swal.showValidationMessage('Something went wrong')
        }

        return lend
    }

    function calculateLatenessCharges(lending_date) {
        var days_diff = dayjs().diff(lending_date, 'day'),
            lateness_charges = {}

        if (days_diff > 30) {
            var days_overdue = days_diff - 30,
                charges_per_day = 50 // 50 cents

            lateness_charges.cents = days_overdue * charges_per_day
            lateness_charges.text = `$${((days_overdue * charges_per_day) / 100).toFixed(2)} BND`
        } else {
            lateness_charges.cents = 0
            lateness_charges.text = `$0.00 BND`
        }

        return lateness_charges
    }

    function toggleSwalConfirmBtn() {
        var btn = $(Swal.getConfirmButton()),
            input = $('#swal-has_paid')

        if (input.is(':checked')) {
            btn.prop('disabled', false)
        } else {
            btn.prop('disabled', true)
        }
    }

    function getTableRow(table, lendId) {
        var row = {}

        table.rows((index, data, node) => {
            if (parseInt(data.id) == lendId) {
                row.index = parseInt(data.id)
                row.data = data
                row.node = node
            }
        })

        return row
    }

    function getRowColumns(row) {
        var column = {}

        column.returned_date = row.node.cells[3]
        column.lateness_charge = row.node.cells[4]
        column.actions = row.node.cells[5]

        return column
    }

    function updateTableRowData(column, lend) {
        lendTable.cell(column.returned_date).data(dayjs(lend.returned_date).format('D MMM YYYY'))
        lendTable.cell(column.lateness_charge).data(`$${((lend.lateness_charge) / 100).toFixed(2)} BND`)
        lendTable.cell(column.actions).data('-')
    }

    function generateEditIcon(row) {
        var editIcon = '-'

        if (row.lateness_charge == '-') {
            editIcon =
            `
            <i onclick="openEditModal(this)"
                data-id="${row.id}"
                data-movie_id="${row.movie_id}"
                data-member_id="${row.member_id}"
                data-movie_title="${row.movie_title}"
                data-member_name="${row.member_name}"
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
