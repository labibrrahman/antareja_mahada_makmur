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
      </div>
    </div>
    <div class="card-body">
      <table class="empTable" width='100%' border="1" style='border-collapse: collapse;'>
        <thead>
          <tr>
            <th>no</th>
            <th>id</th>
            <th>User</th>
            <th>Created Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="card-footer">

    </div>
  </div>
      
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail Mutation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="detailMutation" width='100%' border="1" style='border-collapse: collapse;'>
            <thead>
              <tr>
                <th>no</th>
                <th>id</th>
                <th>User</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
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
    var table = $('.empTable').DataTable({
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
      ajax: "{{ route('mutation_asset') }}",
      columns: [
        {data: null, render: function (data, type, row, meta) 
          {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
        },
        {data: 'id'},
        {data: 'name'},
        {data: 'created_ats'},
        {data: 'mutations_stat'},
        {data: 'action', orderable: false, searchable: false},
      ],
      order: [[ 5, 'desc' ]],
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
  });

  function getDetailMutation($id = 0){
    $('.detailMutation').DataTable().destroy();
    var url = '{{ route("mutation_asset.get_detail_mutation",["dataurl"]) }}';
    url1 = url.replace("dataurl", $id);
    var table = $('.detailMutation').DataTable({
        iDisplayLength: 10,
        oLanguage: {
          sProcessing: "loading..."
        },
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: url1,
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

  function getasset_ajax(id){
    $.ajax({
      url: "{{ route('mutation_asset.get_data_asset') }}",
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
      url: "{{ route('mutation_asset.get_data_asset') }}",
      type: 'POST',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'POST',
        id : id,
        _token : '{{ csrf_token() }}'
      },
      success: function(data){   
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
        $.each(data.photo, function( index, value ) {
          var image_url = "{{ asset('/storage')}}/"+value.image;
          $("#id_upload"+index).val(value.id_image);
          $.get(image_url)
            .done(function() { 
              $("#image"+index).attr("src",image_url);
              $("#image_stat"+index).hide();
            }).fail(function() { 
              $("#image_stat"+index).hide(false);
          })
        });
       
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    })
  }

  function getupdate_image(id){
    $.ajax({
      url: "{{ route('mutation_asset.get_data_asset') }}",
      type: 'POST',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'POST',
        id : id,
        _token : '{{ csrf_token() }}'
      },
      success: function(data){    
        $('#id_asset_edit2').val(data.id)    
        $(".deletePhoto0").hide();
        $(".deletePhoto1").hide();
        $(".deletePhoto2").hide();

        $.each(data.photo, function( index, value ) {
          var image_url = "{{ asset('/storage')}}/"+value.image;
          $("#id_upload"+index).val(value.id_image);
          $.get(image_url)
            .done(function() { 
              $("#image"+index).show();
              $("#image"+index).attr("src",image_url);
              $("#image_stat"+index).hide();
              $(".deletePhoto"+index).show();
              var url = '{{ route("download_img",["url" => "dataurl"]) }}';
              url1 = url.replace("dataurl", value.image);
              $("#downloadImage"+index).attr("href", url1);
            }).fail(function() { 
              $("#image_stat"+index).show();
              $(".deletePhoto"+index).hide();
              $("#image"+index).hide();
          })
        });
       
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    })
  }

  function confirmDelete(id) {
    $.ajax({
      url: "{{ route('mutation_asset.get_data_asset') }}",
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
      $('#assetimportbtn').on('click', function() {
          $('#importmodal').modal('show');
      });
  });

  function readURL1(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#image0").show();
        $('#image0').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  function readURL2(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#image1").show();
        $('#image1').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  function readURL3(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#image2").show();
        $('#image2').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

	$(document).ready(function () {
    $('#exampleModal').on('hidden.bs.modal', function () {
      $('#addPriceForm').trigger("reset");
    })
    $('#insertAsset').on('hidden.bs.modal', function () {
      $('#insertAssetForm').trigger("reset");
    })
    $('#updateAsset').on('hidden.bs.modal', function () {
      $('#updateAssetForm').trigger("reset");
    })
    $('#deletedModal').on('hidden.bs.modal', function () {
      $('#deletedAssetForm').trigger("reset");
    })
    $('#importmodal').on('hidden.bs.modal', function () {
      $('#importAssetForm').trigger("reset");
    })
    $('#updateImage').on('hidden.bs.modal', function () {
      $('#updateImageForm').trigger("reset");
      document.getElementById('image0').src = "#";
      document.getElementById('image1').src = "#";
      document.getElementById('image2').src = "#";
      $('#id_upload0').val('');
      $('#id_upload1').val('');
      $('#id_upload2').val('');
      $('#image0').hide();
      $('#image1').hide();
      $('#image2').hide();
      $('#id_asset_edit').val('');
      $("#image_stat0").show();
      $("#image_stat1").show();
      $("#image_stat2").show();
      $(".deletePhoto0").hide();
      $(".deletePhoto1").hide();
      $(".deletePhoto2").hide();

    })

    $('#deletedPhotoModal').on('hidden.bs.modal', function () {
      var asset_id = $('#id_asset_deleted').val();
      getupdate_image(asset_id);
      $('#updateImage').modal('show');
    })

    // $('#updateImage').on('show.bs.modal', function () {
    //   var check_empty0 = $('#image_stat0').is(":visible");
    //   var check_empty1 = $('#image_stat1').is(":visible");
    //   var check_empty2 = $('#image_stat2').is(":visible");
    //   console.log(check_empty2);
    //   if(check_empty0 ==  false){
    //     $(".deletePhoto0").show();
    //   }else{
    //     $(".deletePhoto0").hide();
    //   }
    //   if(check_empty1 ==  false){
    //     $(".deletePhoto1").show();
    //   }else{
    //     $(".deletePhoto1").hide();
    //   }
    //   if(check_empty2 ==  false){
    //     $(".deletePhoto2").show();
    //   }else{
    //     $(".deletePhoto2").hide();
    //   }
    // })

    
    
    $('#deletePhoto0').on('click', function(e){
      var id_upload = $('#id_upload0').val();
      var id_asset = $('#id_asset_edit2').val();
      
      $('#id_asset_deleted').val(id_asset);
      $('#id_upload').val(id_upload);
    });

    $('#deletePhoto1').on('click', function(e){
      var id_upload = $('#id_upload1').val();
      var id_asset = $('#id_asset_edit2').val();
      
      $('#id_asset_deleted').val(id_asset);
      $('#id_upload').val(id_upload);
    });

    $('#deletePhoto2').on('click', function(e){
      var id_upload = $('#id_upload2').val();
      var id_asset = $('#id_asset_edit2').val();
      
      $('#id_asset_deleted').val(id_asset);
      $('#id_upload').val(id_upload);
    });

  })  
  </script>