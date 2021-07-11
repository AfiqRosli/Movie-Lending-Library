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
</script>
@endsection
