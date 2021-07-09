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
            <th>ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movies as $movie)
        <tr>
            <td>{{ $movie->id }}</td>
            <td>{{ $movie->title }}</td>
            <td>{{ $movie->genre }}</td>
            <td>{{ Helper::formatDate($movie->released_date) }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
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
    var movieGenres = {!! json_encode($movieGenres, JSON_HEX_TAG) !!};
    var movieTable

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
                { data: 'date' },
                { data: null, orderable: false, render: (data, type, row) => {
                    var actions = generateActionIcons(row)

                    return '<div class="text-center">' + actions.editIcon + actions.deleteIcon + '</div>'
                    }
                },
            ]
        });

        $("#js-add-movie").click(() => {
            openCreateModal()
        })
    });

    async function openCreateModal() {
        const {
            value: movie
        } = await Swal.fire({
            title: 'Add Movie',
            html: `
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
            showLoaderOnConfirm: true,
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
                    uiLibrary: 'bootstrap4',
                    format: 'd mmm yyyy'
                });
            },
            preConfirm: async () => {
                var movie = {
                    title: $('#swal-title').val(),
                    genre: $('#swal-genre').val(),
                    date: $('#swal-release-date').val(),
                }

                try {
                    var result = await $.ajax({
                        url: './movie',
                        method: 'POST',
                        data: {
                            movie
                        }
                    })

                    movie = result.added_movie

                    movieTable.row.add({
                        'id': movie.id,
                        'title': movie.title,
                        'genre': movie.genre,
                        'date': dayjs(movie.released_date).format('D MMM YYYY'),
                        render: (data, type, row) => {
                            var actions = generateActionIcons(row)

                            return '<div class="text-center">' + actions.editIcon + actions.deleteIcon + '</div>'
                        }
                    }).draw();
                } catch (error) {
                    console.log(error)
                    Swal.showValidationMessage('Something went wrong')
                }

                return movie
            }
        })

        if (movie) {
            Swal.fire({
                icon: 'success',
                title: 'Movie Added',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-lg mr-2',
                }
            })
        }
    }

    async function openEditModal(el) {
        const {
            value: movie
        } = await Swal.fire({
            title: 'Edit Movie',
            html: `
        <form class="text-left">
            <div class="form-group row">
                <label for="swal-title" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="swal-title" value="${$(el).attr('data-title')}">
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
            showLoaderOnConfirm: true,
            confirmButtonText: 'Update',
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
                $('#swal-genre').val($(el).attr('data-genre'))

                $('#swal-release-date').datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'd mmm yyyy'
                });

                $('#swal-release-date').val($(el).attr('data-date'))
            },
            preConfirm: async () => {
                var movie = {
                    id: $(el).data('id'),
                    title: $('#swal-title').val(),
                    genre: $('#swal-genre').val(),
                    date: $('#swal-release-date').val(),
                }

                try {
                    var result = await $.ajax({
                        url: './movie/' + movie.id,
                        method: 'PATCH',
                        data: {
                            movie
                        }
                    })

                    movie = result.updated_movie

                    // Update the Frontend data
                    var row = getTableRow(movieTable, movie.id)
                    var column = getRowColumns(row)
                    updateTableRowData(column, movie)
                } catch (error) {
                    console.log(error)
                    Swal.showValidationMessage('Something went wrong')
                }

                return movie
            }
        })

        if (movie) {
            Swal.fire({
                icon: 'success',
                title: 'Movie Info Updated',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary btn-lg mr-2',
                }
            })
        }
    }

    async function openDeleteModal(el) {
        var movieId = $(el).data('id')

        var swalResponse = await Swal.fire({
            title: 'Delete Movie',
            html:
            `
            <div class="alert alert-danger" role="alert">
                <p class="m-0">Are you sure you want to delete this movie?</p>
                <p class="m-0">This action is irreversible</p>
            </div>
            <div class="text-left">
                <p class="mb-1"><b>Title:</b> ${$(el).data('title')}</p>
                <p class="mb-1"><b>Genre:</b> ${$(el).data('genre')}</p>
                <p><b>Release Date:</b> ${$(el).data('date')}</p>
            </div>
            `,
            buttonsStyling: false,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: 'Delete',
            customClass: {
                confirmButton: 'btn btn-danger btn-lg mr-2',
                cancelButton: 'btn btn-secondary btn-lg',
            },
            focusCancel: true,
            preConfirm: async () => {
                try {
                    var response = await $.ajax({
                        url: './movie/' + movieId,
                        method: 'DELETE'
                    })

                    if (response.movie_deleted) {
                        var row = getTableRow(movieTable, movieId)
                        movieTable.row(row.node).remove().draw(false)
                    }
                } catch (error) {
                    console.log(error)
                    Swal.showValidationMessage('Something went wrong')
                }
            }
        })

        if (swalResponse.isConfirmed) {
            Swal.fire(
                'Deleted!',
                'Movie has been deleted.',
                'success'
            )
        }
    }

    function getTableRow(table, movieId) {
        var row = {}

        table.rows((index, data, node) => {
            if (parseInt(data.id) == movieId) {
                row.index = parseInt(data.id)
                row.data = data
                row.node = node
            }
        })

        return row
    }

    function getRowColumns(row) {
        var column = {}

        column.title = row.node.cells[0]
        column.genre = row.node.cells[1]
        column.date = row.node.cells[2]
        column.action = row.node.cells[3]
        column.action.editIcon = $(column.action).find('svg.js-action-edit')
        column.action.deleteIcon = $(column.action).find('svg.js-action-delete')

        // Fallback if browser does not support SVG for FontAwesome and used i tag instead
        if ($(column.action.editIcon).length == 0) {
            column.action.editIcon = $(column.action).find('i.js-action-edit')
        }

        if ($(column.action.deleteIcon).length == 0) {
            column.action.deleteIcon = $(column.action).find('i.js-action-delete')
        }

        return column
    }

    function updateTableRowData(column, movie) {
        // Free Method
        movieTable.cell(column.title).data(movie.title)
        movieTable.cell(column.genre).data(movie.genre)
        movieTable.cell(column.date).data(dayjs(movie.released_date).format('D MMM YYYY'))

        // NOTE: Alternative Method - Requires Payed Editor Package (UNTESTED)
        // movieTable.row(':eq(0)').edit({
        //     title: movie.title,
        //     genre: movie.genre,
        //     date: dayjs(movie.date).format('D MMM YYYY'),
        // })

        $(column.action.editIcon).attr('data-title', movie.title)
        $(column.action.editIcon).attr('data-genre', movie.genre)
        $(column.action.editIcon).attr('data-date', dayjs(movie.released_date).format('D MMM YYYY'))

        $(column.action.deleteIcon).attr('data-title', movie.title)
        $(column.action.deleteIcon).attr('data-genre', movie.genre)
        $(column.action.deleteIcon).attr('data-date', dayjs(movie.released_date).format('D MMM YYYY'))
    }

    function generateActionIcons(row) {
        var editIcon =
            `
            <i onclick="openEditModal(this)"
                data-id="${row.id}"
                data-title="${row.title}"
                data-genre="${row.genre}"
                data-date="${row.date}"
                class="js-action-edit far fa-edit fa-lg mr-2 icon icon__edit">
            </i>
            `
        var deleteIcon =
            `
            <i onclick="openDeleteModal(this)"
                data-id="${row.id}"
                data-title="${row.title}"
                data-genre="${row.genre}"
                data-date="${row.date}"
                class="js-action-delete far fa-trash-alt fa-lg icon icon__delete">
            </i>
            `

        return { editIcon, deleteIcon }
    }
</script>
@endsection
