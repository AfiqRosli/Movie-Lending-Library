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
        <li class="breadcrumb-item active" aria-current="page">Lending Movie</li>
    </ol>
</nav>

@section('h1', 'Lending Movies - Selection')

<h2 class="mb-0">Select Movie</h2>
<small>Only available movies for lend are shown</small>

<table id="movie-table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movies as $movie)
        <tr>
            <td>{{ $movie->id }}</td>
            <td>{{ $movie->title }}</td>
            <td>{{ $movie->genre }}</td>
            <td>{{ Helper::formatDate($movie->released_date) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
        </tr>
    </tfoot>
</table>

<h2 class="mt-5 mb-0">Select Member</h2>
<small>All members listed are active</small>

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
            <td>{{ $member->is_active == App\Enums\MemberState::Active ? 'Active' : 'Inactive' }}</td>
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
        </tr>
    </tfoot>
</table>

<div class="card my-5">
    <h2 class="card-header">Confirm Selections</h2>
    <div class="card-body">
        <h5 class="card-title">Movie Info</h5>
        <p id="js-movie-title" class="card-text"><b>Title:</b></p>
        <p id="js-movie-genre" class="card-text"><b>Genre:</b></p>
        <p id="js-movie-released_date" class="card-text"><b>Released Date:</b></p>

        <h5 class="card-title mt-4">Member Info</h5>
        <p id="js-member-name" class="card-text"><b>Name:</b></p>
        <p id="js-member-telephone" class="card-text"><b>Telephone:</b></p>
        <p id="js-member-identity_number" class="card-text"><b>Identity No.:</b></p>

        <div class="d-flex flex-row-reverse">
            <button id="js-lend-movie" type="button" class="btn btn-primary" disabled>Lend Movie</button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs4/dt-1.10.25/af-2.3.7/b-1.7.1/cr-1.5.4/date-1.1.0/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.js">
</script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

<script>
    var movieTable, memberTable

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        movieTable = $('#movie-table').DataTable({
            responsive: true,
            language: {
                emptyTable: "No movies here?!?! (╯°□°）╯︵ ┻━┻",
                zeroRecords: "No matching movies found （；´д｀）ゞ"
            },
            columns: [
                { data: 'id', visible: false },
                { data: 'title' },
                { data: 'genre' },
                { data: 'released_date' },
            ],
            select: {
                style: 'single'
            },
            buttons: [{
                text: 'Reset sorting',
                action: () => {
                    table.order.neutral().draw(false)
                }
            }]
        });

        memberTable = $('#member-table').DataTable({
            responsive: true,
            language: {
                emptyTable: "No members here ( ´･･)ﾉ(._.`)",
                zeroRecords: "No matching members found ( *^-^)ρ(*╯^╰)"
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
            ],
            select: {
                style: 'single'
            }
        });

        $("#js-lend-movie").click(() => {
            movieTable.rows({
                selected: true
            }).data()
        })
    });
</script>
@endsection
