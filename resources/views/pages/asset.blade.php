@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

@section('content')
  <div class="card">
        <div class="card-header ">
          <div class="card-tools">
            <a href="#" class="edit btn btn-primary btn-sm">Add Asset + </a>
          </div>
        </div>
        <div class="card-body">
          <table id='empTable' width='100%' border="1" style='border-collapse: collapse;'>
            <thead>
              <tr>
                <th>no</th>
                <th>id</th>
                <th>Asset Number</th>
                <th>Asset</th>
                <th>Quantity</th>
                <th>Department</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Price</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('asset.store_price') }}" method="POST">
              @csrf
              <div class="modal-body">
                  <div class="form-group">
                    <input type="text" hidden class="form-control" id="id_asset" name="id_asset">
                  </div>
                  <div class="form-group">
                    <label for="asset_number" class="col-form-label">Asset Number :</label>
                    <input type="text" readonly class="form-control" id="asset_number" name="asset_number">
                  </div>
                  <div class="form-group">
                    <label for="asset_name" class="col-form-label">Asset:</label>
                    <input type="text" readonly class="form-control" id="asset_name" name="asset_name">
                  </div>
                  <div class="form-group">
                    <label for="asset_price" class="col-form-label">Price:</label>
                    <input type="text" class="form-control" id="asset_price" name="asset_price">
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
@endsection
    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatables JS CDN -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(function () {
    var table = $('#empTable').DataTable({
        iDisplayLength: 10,
        oLanguage: {
          sProcessing: "loading..."
        },
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: "{{ route('asset') }}",
        columns: [
            {data: null, render: function (data, type, row, meta) 
              {
                return meta.row + meta.settings._iDisplayStart + 1;
              },
            },
            {data: 'id'},
            {data: 'asset_number'},
            {data: 'asset_desc'},
            {data: 'asset_quantity'},
            {data: 'department'},
            {data: 'asset_price', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp' )},
            {data: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
            {
                targets: [ 1 ],
                orderable: false, //set not orderable
                visible: false
            },
        ],
    });
  });

  function getasset_ajax(id){
    $.ajax({
      url: "{{ route('asset.get_data_asset') }}",
      type: 'POST',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'POST',
        id : id,
        _token : '{{ csrf_token() }}'
      },
      success: function(data){    
        $('#id_asset').val(data.id)                
        $('#asset_number').val(data.asset_number)                
        $('#asset_name').val(data.asset_desc)                
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    })

  }
  </script>