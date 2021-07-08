@extends('layouts.app')

@section('title', 'Movie')

@section('style')
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/bs4/dt-1.10.25/af-2.3.7/b-1.7.1/cr-1.5.4/date-1.1.0/kt-2.6.2/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.4/sb-1.1.0/sp-1.3.0/sl-1.3.3/datatables.min.css" />
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-5">
        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Movie</li>
    </ol>
</nav>

@section('h1', 'Movies')

<div class="d-flex flex-row-reverse mb-2">
    <button id="js-add-movie" type="button" class="btn btn-primary">Add Movie</button>
</div>

<table id="movie-table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Avengers: End Game</td>
            <td>Action</td>
            <td>2018</td>
        </tr>
        <tr>
            <td>Captain Phillips</td>
            <td>Historical</td>
            <td>2013</td>
        </tr>
        <tr>
            <td>Pokémon Detective Pikachu</td>
            <td>Adventure</td>
            <td>2019</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
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
    $(document).ready(function () {
        var movieGenres = {!! json_encode($movieGenres, JSON_HEX_TAG) !!};

        $('#movie-table').DataTable({
            responsive: true
        });

        $("#js-add-movie").click(async () => {
            const {
                value: formValues
            } = await Swal.fire({
                title: 'Add Movie',
                html:
                `
                <form class="text-left">
                    <div class="form-group row">
                        <label for="swal-title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="swal-title" placeholder="Avengers">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="swal-genre" class="col-sm-2 col-form-label">Genre</label>
                        <div class="col-sm-10">
                            <select id="swal-genre" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="swal-release-date" class="col-sm-4 col-form-label">Release Date</label>
                        <div class="col-sm-8">
                            <input id="swal-release-date" width="276">
                        </div>
                    </div>
                </form>
                `,
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Add',
                customClass: {
                    confirmButton: 'btn btn-primary btn-lg mr-2',
                    cancelButton: 'btn btn-secondary btn-lg',
                },
                focusConfirm: false,
                didOpen: () => {
                    for (let index = 0; index < movieGenres.length; index++) {
                        const genre = movieGenres[index];

                        $('#swal-genre').append(new Option(genre, genre))
                    }

                    $('#swal-release-date').datepicker({
                        uiLibrary: 'bootstrap4'
                    });
                },
                preConfirm: () => {
                    return [
                        document.getElementById('swal-title').value,
                        document.getElementById('swal-genre').value,
                        document.getElementById('swal-release-date').value
                    ]
                }
            })

            if (formValues) {
                Swal.fire(JSON.stringify(formValues))
            }
        })
    });
</script>
@endsection
