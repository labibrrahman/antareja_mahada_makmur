@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

<style>
  .note, .image_stat{
    color:red;
  }
</style>

@section('content')
  <div class="card">
    <div class="card-header ">
      <div class="card-tools">
        <a href="#" data-toggle="modal" data-target="#inputBAModal" class="edit btn btn-primary btn-sm">Add Berita Acara + </a>
      </div>
    </div>
    <div class="card-body">
      <table class="tinjauanAssetTable" width='100%' border="1" style='border-collapse: collapse;'>
        <thead>
          <tr>
            <th>no</th>
            <th>ID</th>
            <th>BA Number</th>
            <th>Tgl Awal</th>
            <th>Tgl Akhir</th>
            <th>Department</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="card-footer">

    </div>
  </div>
      
  <div class="modal fade" id="inputBAModal" tabindex="-1" role="dialog" aria-labelledby="inputBAModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="inputBAModalLabel">Add Berita Acara Tinjauan Asset</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('tinjauan_asset.store') }}" method="POST" id="insertBAForm">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="ba_number" class="col-form-label">BA Number :</label>
              <input type="text" readonly ondblclick="remove_readonly()" class="form-control" id="ba_number" name="ba_number">
            </div>
            <div class="form-group">
              <label for="departement_id" class="col-form-label">Show By :</label>
              <select class="form-control" name="departement_id" id="departement_id">
                <option value="0" selected name="departement_id" id="departement_id">-- Show All --</option>
                @foreach ($departement as $data)
                  <option value="<?= $data->id ?>" name="departement_id" id="departement_id" ><?= $data->department ?></option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="tgl_awal" class="col-form-label">Capitalized on from :</label>
              <input type="date" class="form-control" id="tgl_awal" name="tgl_awal">
            </div>
            <div class="form-group">
              <label for="tgl_akhir" class="col-form-label">Capitalized on to :</label>
              <input type="date" class="form-control" id="tgl_akhir_to" name="tgl_akhir">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deletedModal" tabindex="-1" role="dialog" aria-labelledby="deletedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletedModalLabel">Deleted Berita Acara Tinjauan Asset</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('tinjauan_asset.deleted') }}" method="POST" id="deletedBAForm">
          @csrf
          <input type="text" hidden class="form-control" id="id" name="id">

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
<script type="text/javascript">
  $(function () {
    var table = $('.tinjauanAssetTable').DataTable({
      initComplete: function () {
        $('.btnPrints').printPage();
      },
      iDisplayLength: 10,
      oLanguage: {
        sProcessing: "loading..."
      },
      responsive: 'true',
      processing: true,
      serverSide: true,
      ajax: "{{ route('tinjauan_asset') }}",
      columns: [
        {data: null, render: function (data, type, row, meta) 
          {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
        },
        {data: 'id'},
        {data: 'ba_number'},
        {data: 'tgl_awal_desc'},
        {data: 'tgl_akhir_desc'},
        {data: 'dept_desc'},
        {data: 'action', orderable: false, searchable: false},
      ],
      order: [[ 1, 'desc' ]],
      columnDefs: [
        {
            targets: [ 1 ],
            orderable: false, //set not orderable
            visible: false
        },
      ],
    });

    $('#showBy').change(function () {
        table.columns(5).search(this.value).draw();
    });

    $('#deletedBAForm').on('submit', (function (e) {
      e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
              $('#deletedModal').modal('hide');
              if (data.status == "fail") {
                  var message = "";
                  $.each(data.error, function (index, value) {
                      message += value;
                  });
                  //errorMsg(message);
                  Swal.fire({
                    title: '',
                    html: '<div class="xred">'+message+'</div>',
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
                $('.tinjauanAssetTable').DataTable().destroy();
                $('.tinjauanAssetTable').DataTable({
                  initComplete: function () {
                    $('.btnPrints').printPage();
                  },
                  iDisplayLength: 10,
                  oLanguage: {
                    sProcessing: "loading..."
                  },
                  responsive: 'true',
                  processing: true,
                  serverSide: true,
                  ajax: "{{ route('tinjauan_asset') }}",
                  columns: [
                    {data: null, render: function (data, type, row, meta) 
                      {
                        return meta.row + meta.settings._iDisplayStart + 1;
                      },
                    },
                    {data: 'id'},
                    {data: 'ba_number'},
                    {data: 'tgl_awal_desc'},
                    {data: 'tgl_akhir_desc'},
                    {data: 'dept_desc'},
                    {data: 'action', orderable: false, searchable: false},
                  ],
                  order: [[ 1, 'desc' ]],
                  columnDefs: [
                    {
                        targets: [ 1 ],
                        orderable: false, //set not orderable
                        visible: false
                    },
                  ],
                });
              }
            },
            error: function () {
            }
        });
    }));
  });

  $(document).ready(function () {
    $('#insertBAForm').on('submit', (function (e) {
      e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
              $('#inputBAModal').modal('hide');
              if (data.status == "fail") {
                  var message = "";
                  $.each(data.error, function (index, value) {
                      message += value;
                  });
                  //errorMsg(message);
                  Swal.fire({
                    title: '',
                    html: '<div class="xred">'+message+'</div>',
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
                location.reload();
                // $('.tinjauanAssetTable').DataTable().ajax.reload(null, false);
              }
            },
            error: function () {
            }
        });
    }));

    $('#inputBAModal').on('show.bs.modal', function () {
      set_ba_number();
    })

    $('#inputBAModal').on('hide.bs.modal', function () {
      $("#ba_number").attr("readonly", true);
    })
    
  });

  function set_ba_number(){
    $.ajax({
      url: "{{ route('tinjauan_asset.get_ba_number') }}",
      type: 'POST',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'GET',
      },
      success: function(data){
        $('#ba_number').val(data)                
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    })
  }

  function remove_readonly(){
    $("#ba_number").attr("readonly", false);
  }

  function confirmDelete(id, ba_number) {
      $('#id').val(id)
      document.getElementById("title_alert").innerHTML = "Are you sure you want to delete BA number : "+ba_number;  
  }
</script>