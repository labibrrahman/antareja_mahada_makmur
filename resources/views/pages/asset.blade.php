@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

<style>
  .note{
    color:red;
  }
</style>

@section('content')
  <div class="card">
    <div class="card-header ">
      <div class="card-tools">
        <a href="#" data-toggle="modal" data-target="#insertAsset" class="edit btn btn-primary btn-sm">Add Asset + </a>
      </div>
    </div>
    <div class="card-body" style="overflow-y: scroll; height:750px;">
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

      <div class="modal fade" id="insertAsset" tabindex="-1" role="dialog" aria-labelledby="insertAsset" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="insertAsset">Add Data Asset</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('asset.store_asset') }}" method="POST">
              @csrf
              <div class="modal-body">
                  <div class="form-group">
                    <label for="asset_number" class="col-form-label">Asset Number :</label>
                    <input type="number" class="form-control" id="asset_number" name="asset_number">
                  </div>
                  <div class="form-group">
                    <label for="asset_serial_number" class="col-form-label">Serial Number:</label>
                    <input type="text" class="form-control" id="asset_serial_number" name="asset_serial_number">
                  </div>
                  <div class="form-group">
                    <label for="asset_capitalized_on" class="col-form-label">Capitalized On:</label>
                    <input type="date" class="form-control" id="asset_capitalized_on" name="asset_capitalized_on">
                  </div>
                  <div class="form-group">
                    <label for="asset_desc" class="col-form-label">Asset Desc :</label>
                    <input type="text" class="form-control" id="asset_desc" name="asset_desc">
                  </div>
                  <div class="form-group">
                    <label for="asset_quantity" class="col-form-label">Quantity :</label>
                    <input type="number" class="form-control" id="asset_quantity" name="asset_quantity">
                  </div>
                  <div class="form-group">
                    <label for="asset_po" class="col-form-label">PO :</label>
                    <input type="text" class="form-control" id="asset_po" name="asset_po">
                  </div>
                  <div class="form-group">
                    <label for="departement_id" class="col-form-label">Departement :</label>
                    <select id="departement_id" name="departement_id" class="select2 form-control" style="width:100%">
                      <option value="">- Set Departement -</option>
                      @foreach ($departement as $data)
                        <option value="{{$data->id}}">{{$data->department}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="count_id" class="col-form-label">Count :</label>
                    <select id="count_id" name="count_id" class="select2 form-control" style="width:100%">
                      <option value="">- Set Count -</option>
                      @foreach ($count as $data)
                        <option value="{{$data->id}}">{{$data->count}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="category_id" class="col-form-label">Category :</label>
                    <select id="category_id" name="category_id" class="select2 form-control" style="width:100%">
                      <option value="">- Set Category -</option>
                      @foreach ($categories as $data)
                        <option value="{{$data->id}}">{{$data->category}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="location" class="col-form-label">Location :</label>
                    <input type="text" class="form-control" id="location" name="location">
                  </div>
                  <div class="form-group">
                    <label for="asset_condition" class="col-form-label">Condition :</label>
                    <select id="asset_condition" name="asset_condition" class="select2 form-control" style="width:100%">
                      <option value="">- Set Condition -</option>
                        <option value="sb">Sangat Baik</option>
                        <option value="b">Baik</option>
                        <option value="rd">Rusak, diperbaiki</option>
                        <option value="rt">Rusak, tidak dapat diperbaiki</option>
                        <option value="h">Hilang</option>
                    </select>
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

      <div class="modal fade" id="updateAsset" tabindex="-1" role="dialog" aria-labelledby="updateAsset" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="updateAsset">Add Data Asset</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('asset.update_asset') }}" method="POST">
              @csrf
              <div class="modal-body">
                  <input type="hidden" id="id_asset_edit" name="id_asset">
                  <div class="form-group">
                    <label for="asset_number_edit" class="col-form-label">Asset Number :</label>
                    <input required type="number" class="form-control" id="asset_number_edit" name="asset_number">
                  </div>
                  <div class="form-group">
                    <label for="asset_serial_number_edit" class="col-form-label">Serial Number:</label>
                    <input type="text" class="form-control" id="asset_serial_number_edit" name="asset_serial_number">
                  </div>
                  <div class="form-group">
                    <label for="asset_capitalized_on_edit" class="col-form-label">Capitalized On:</label>
                    <input required type="date" class="form-control" id="asset_capitalized_on_edit" name="asset_capitalized_on">
                  </div>
                  <div class="form-group">
                    <label for="asset_desc_edit" class="col-form-label">Asset Desc :</label>
                    <input required type="text" class="form-control" id="asset_desc_edit" name="asset_desc">
                  </div>
                  <div class="form-group">
                    <label for="asset_quantity_edit" class="col-form-label">Quantity :</label>
                    <input required type="number" class="form-control" id="asset_quantity_edit" name="asset_quantity">
                  </div>
                  <div class="form-group">
                    <label for="asset_po_edit" class="col-form-label">PO :</label>
                    <input type="text" class="form-control" id="asset_po_edit" name="asset_po">
                  </div>
                  <div class="form-group">
                    <label for="departement_id_edit" class="col-form-label">Departement :</label>
                    <select id="departement_id_edit" name="departement_id" class="select2 form-control" style="width:100%">
                      <option value="">- Set Departement -</option>
                      @foreach ($departement as $data)
                        <option value="{{$data->id}}">{{$data->department}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="count_id_edit" class="col-form-label">Count :</label>
                    <select id="count_id_edit" name="count_id" class="select2 form-control" style="width:100%">
                      <option value="">- Set Count -</option>
                      @foreach ($count as $data)
                        <option value="{{$data->id}}">{{$data->count}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="category_id_edit" class="col-form-label">Category :</label>
                    <select id="category_id_edit" name="category_id" class="select2 form-control" style="width:100%">
                      <option value="">- Set Category -</option>
                      @foreach ($categories as $data)
                        <option value="{{$data->id}}">{{$data->category}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="location_edit" class="col-form-label">Location :</label>
                    <input type="text" class="form-control" id="location_edit" name="location">
                  </div>
                  <div class="form-group">
                    <label for="asset_condition_edit" class="col-form-label">Condition :</label>
                    <select id="asset_condition_edit" name="asset_condition" class="select2 form-control" style="width:100%">
                      <option value="sb">Sangat Baik</option>
                      <option value="b">Baik</option>
                      <option value="rd">Rusak, diperbaiki</option>
                      <option value="rt">Rusak, tidak dapat diperbaiki</option>
                      <option value="h">Hilang</option>
                    </select>
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

      <div class="modal fade" id="deletedModal" tabindex="-1" role="dialog" aria-labelledby="deletedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deletedModalLabel">Deleted Asset</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('asset.deleted_asset') }}" method="POST">
              @csrf
              <input type="text" hidden class="form-control" id="id_asset_deleted" name="id_asset">

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

    function getupdate_ajax(id){
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
        console.log(data);
        $('#id_asset_edit').val(data.id)                
        $("#asset_number_edit").val(data.asset_number)
        $("#asset_serial_number_edit").val(data.asset_serial_number)
        $("#asset_capitalized_on_edit").val(data.asset_capitalized_on)
        $("#asset_desc_edit").val(data.asset_desc)
        $("#asset_quantity_edit").val(data.asset_quantity)
        $("#asset_po_edit").val(data.asset_po)
        $("#departement_id_edit").val(data.departement_id)
        $("#count_id_edit").val(data.count_id)
        $("#category_id_edit").val(data.category_id)
        $("#location_edit").val(data.location)
        $("#asset_condition_edit").val(data.asset_condition)
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    })
  }

  function confirmDelete(id) {
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
        $('#id_asset_deleted').val(data.id)    
        document.getElementById("title_alert").innerHTML = "Are you sure you want to delete asset number : "+data.asset_number;  
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    })
  }

  $(function(){
      $(".btnPrint").printPage({
          url: "{{ route('berita_acara')}}", 
          attr: "href",
          message:"Your document is being created"
      });
  });

  </script>