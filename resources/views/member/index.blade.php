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
        <li class="breadcrumb-item active" aria-current="page">Member</li>
    </ol>
</nav>

@section('h1', 'Member')

<div class="d-flex flex-row-reverse mb-2">
    <button id="js-add-member" type="button" class="btn btn-primary">Add Member</button>
</div>

<table id="member-table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Address</th>
            <th>Telephone</th>
            <th>Identity No.</th>
            <th>Date Joined</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
        <tr>
            <td>{{ $member->id }}</td>
            <td>{{ $member->name }}</td>
            <td>{{ $member->age }}</td>
            <td>{{ $member->address }}</td>
            <td>{{ $member->telephone }}</td>
            <td>{{ $member->identity_number }}</td>
            <td>{{ Helper::formatDate($member->date_of_joined) }}</td>
            <td>{{ $member->is_active }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Address</th>
            <th>Telephone</th>
            <th>Identity No.</th>
            <th>Date Joined</th>
            <th>Active</th>
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
    // Data passed from MovieController@index
    var memberTable

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        memberTable = $('#member-table').DataTable({
            responsive: true,
            language: {
                emptyTable: "No members here ( ´･･)ﾉ(._.`)",
            },
            columns: [
                { data: 'id', visible: false },
                { data: 'name' },
                { data: 'age' },
                { data: 'address' },
                { data: 'telephone' },
                { data: 'identity_number' },
                { data: 'date_of_joined' },
                { data: 'is_active' },
                { data: null, orderable: false, render: (data, type, row) => {
                    var actions = generateActionIcons(row)

                    return '<div class="text-center">' + actions.editIcon + actions.deleteIcon + '</div>'
                    }
                },
            ]
        });

        $("#js-add-member").click(() => {
            openCreateModal()
        })
    });

    async function openCreateModal() {
        const {
            value: member
        } = await Swal.fire({
            title: 'Add Member',
            html: `
            <form class="text-left">
                <div class="form-group row">
                    <label for="swal-name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="swal-name" placeholder="Afiq Rosli">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="swal-age" class="col-sm-2 col-form-label">Age</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="swal-age" placeholder="23">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="swal-address" class="col-sm-3 col-form-label">Address</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="swal-address" placeholder="iCenter, Simpang 32-37, Kampung Anggerek Desa, Jalan Berakas Bandar Seri Begawan, BB3713">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="swal-telephone" class="col-sm-3 col-form-label">Telephone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="swal-telephone" placeholder="+673 7117 694">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="swal-identity_number" class="col-sm-3 col-form-label text-nowrap">Identity No.</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="swal-identity_number" placeholder="01-123456">
                    </div>
                </div>

                <small>
                    Date Joined & Is-Active are added automatically after submission.
                    By default, the Date Joined is the time adding the member and Is-Active is set to true.
                </small>
            </form>
            `,
            buttonsStyling: false,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: 'Add',
            customClass: {
                confirmButton: 'btn btn-primary btn-lg mr-2',
                cancelButton: 'btn btn-secondary btn-lg',
            },
            focusConfirm: false,
            preConfirm: async () => {
                var member = {
                    name: $('#swal-name').val(),
                    age: $('#swal-age').val(),
                    address: $('#swal-address').val(),
                    telephone: $('#swal-telephone').val(),
                    identity_number: $('#swal-identity_number').val(),
                    date_of_joined: dayjs().format('D MMM YYYY'),
                    is_active: true
                }

                try {
                    var result = await $.ajax({
                        url: './member',
                        method: 'POST',
                        data: {
                            member
                        }
                    })

                    member = result.added_member

                    memberTable.row.add({
                        'id': member.id,
                        'name': member.name,
                        'age': member.age,
                        'address': member.address,
                        'telephone': member.telephone,
                        'identity_number': member.identity_number,
                        'date_of_joined': dayjs(member.date_of_joined).format('D MMM YYYY'),
                        'is_active': 'Active', // By default, first time adding equates to active
                        render: (data, type, row) => {
                            var actions = generateActionIcons(row)

                            return '<div class="text-center">' + actions.editIcon + actions.deleteIcon + '</div>'
                        }
                    }).draw();
                } catch (error) {
                    console.log(error)
                    Swal.showValidationMessage('Something went wrong')
                }

                return member
            }
        })

        if (member) {
            Swal.fire({
                icon: 'success',
                title: 'Member Added',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-lg mr-2',
                }
            })
        }
    }

    function generateActionIcons(row) {
        var editIcon =
            `
            <i onclick="openEditModal(this)"
                data-id="${row.id}"
                data-name="${row.name}"
                data-age="${row.age}"
                data-address="${row.address}"
                data-telephone="${row.telephone}"
                data-identity_number="${row.identity_number}"
                data-date_of_joined="${row.date_of_joined}"
                data-is_active="${row.is_active}"
                class="js-action-edit far fa-edit fa-lg mr-2 icon icon__edit">
            </i>
            `
        var deleteIcon =
            `
            <i onclick="openDeleteModal(this)"
                data-id="${row.id}"
                data-name="${row.name}"
                data-age="${row.age}"
                data-address="${row.address}"
                data-telephone="${row.telephone}"
                data-identity_number="${row.identity_number}"
                data-date_of_joined="${row.date_of_joined}"
                data-is_active="${row.is_active}"
                class="js-action-delete far fa-trash-alt fa-lg icon icon__delete">
            </i>
            `

        return { editIcon, deleteIcon }
    }
</script>
@endsection
