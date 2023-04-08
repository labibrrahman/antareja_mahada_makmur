@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user',
])

<style>
    .note,
    .image_stat {
        color: red;
    }
</style>
@section('content')
    <div class="card">
        <div class="card-header ">
            <div class="card-tools">
                <a href="#" data-toggle="modal" data-target="#insertUser" class="edit btn btn-primary btn-sm">Add User +
                </a>
            </div>
        </div>
        {{-- <div class="card-body" style="overflow-y: scroll; height:750px;"> --}}
        <div class="card-body">
            <div class="table-responsive">
                <table id='userTable' width='100%' border="1" style='border-collapse: collapse;'>
                    <thead>
                        <tr>
                            <th>no</th>
                            <th>username</th>
                            <th>Full Name</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
    </div>
    <div class="modal fade" id="insertUser" tabindex="-1" role="dialog" aria-labelledby="insertUser" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertUser">Add Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.store') }}" method="POST" id="insertUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username" class="col-form-label"><i class="note">*</i>Username :</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="full_name" class="col-form-label"><i class="note">*</i>Full Name :</label>
                            <input type="text" class="form-control" id="full_name" name="full_name">
                        </div>
                        <div class="form-group">
                            <label for="departement_id" class="col-form-label"><i class="note">*</i>Departement :</label>
                            <select required id="departement_id" name="departement_id" class="select2 form-control"
                                style="width:100%">
                                <option value="">- Set Departement -</option>
                                @foreach ($departement as $data)
                                    <option value="{{ $data->id }}">{{ $data->department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-form-label"><i class="note">*</i>Password :</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label"><i
                                    class="note">*</i>password_confirmation :</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateUser" tabindex="-1" role="dialog" aria-labelledby="updateUser" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateUser">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data"
                    id="updateUserForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id">
                        <div class="form-group">
                            <label for="username_edit" class="col-form-label"><i class="note">*</i>Username :</label>
                            <input type="text" class="form-control" id="username_edit" name="username">
                        </div>
                        <div class="form-group">
                            <label for="full_name_edit" class="col-form-label"><i class="note">*</i>Full Name :</label>
                            <input type="text" class="form-control" id="full_name_edit" name="full_name">
                        </div>
                        <div class="form-group">
                            <label for="departement_id_edit" class="col-form-label"><i class="note">*</i>Departement
                                :</label>
                            <select required id="departement_id_edit" name="departement_id" class="select2 form-control"
                                style="width:100%">
                                <option value="">- Set Departement -</option>
                                @foreach ($departement as $data)
                                    <option value="{{ $data->id }}">{{ $data->department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-form-label"><i class="note">*</i>Password :</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label"><i
                                    class="note">*</i>password_confirmation :</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletedModal" tabindex="-1" role="dialog" aria-labelledby="deletedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletedModalLabel">Deleted User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.destroy') }}" method="POST" id="deletedUserForm">
                    @csrf
                    <input type="text" hidden class="form-control" id="id_user_deleted" name="id_user">

                    <div class="modal-body">
                        <div class="form-group">
                            <label id="title_alert">Are you sure you want to delete</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Yes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Datatables JS CDN -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

{{-- Swal --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
        console.log(id);
        $('#id_user_deleted').val(id)
    }

    function getDetail(id) {
        $.ajax({
            url: "{{ route('user.detail') }}",
            type: 'POST',
            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {
                _method: 'POST',
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
                $('#user_id').val(response.data.id)
                $("#username_edit").val(response.data.username)
                $("#full_name_edit").val(response.data.full_name)
                $("#departement_id_edit").val(response.data.departement_id)
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        })
    }
    $(document).ready(function() {
        var table = $('#userTable').DataTable({
            iDisplayLength: 10,
            oLanguage: {
                sProcessing: "loading..."
            },
            responsive: 'true',
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.index') }}",
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'username'
                },
                {
                    data: 'full_name'
                },
                {
                    data: 'department'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });

        $('#insertUserForm').on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#insertUser').modal('hide');
                    if (data.status == false) {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        //errorMsg(message);
                        Swal.fire({
                            title: '',
                            html: '<div class="xred">' + message + '</div>',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: true,
                            //timer: 1500
                        });
                        $('#userTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: error,
                        showConfirmButton: true,
                        //timer: 1500
                    });
                }
            });
        }));

        $('#updateUserForm').on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#updateUser').modal('hide');
                    if (data.status == false) {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        //errorMsg(message);
                        Swal.fire({
                            title: '',
                            html: '<div class="xred">' + message + '</div>',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: true,
                            //timer: 1500
                        });
                        $('#userTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(response) {
                    var message = '';
                    var errors = response.responseJSON;
                    console.log(errors);
                    if (errors.errors != null) {
                        $.each(errors.errors, function(key, value) {
                            message += value + '<br>';
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: message,
                        showConfirmButton: true,
                        //timer: 1500
                    });
                }
            });
        }));

        $('#deletedUserForm').on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#deletedModal').modal('hide');
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        //errorMsg(message);
                        Swal.fire({
                            title: '',
                            html: '<div class="xred">' + message + '</div>',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: true,
                            //timer: 1500
                        });
                        $('#userTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function() {}
            });
        }));

        $('#insertUser').on('hidden.bs.modal', function() {
            $('#insertUserForm').trigger("reset");
        })
        $('#updateUser').on('hidden.bs.modal', function() {
            $('#updateUserForm').trigger("reset");
        })
        $('#deletedModal').on('hidden.bs.modal', function() {
            $('#deletedUserForm').trigger("reset");
        })
    });
</script>
