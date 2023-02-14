@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

@section('content')
  <div class="card">
        <div class="card-header">
          <h3 class="card-title">Contact Us</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id='empTable' width='100%' border="1" style='border-collapse: collapse;'>
            <thead>
              <tr>
                <td>no</td>
                <td>Username</td>
                <td>Name</td>
                <td>Email</td>
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
@endsection
    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatables JS CDN -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    // DataTable
    $('#empTable').DataTable({
       processing: true,
       serverSide: true,
       ajax: "{{route('asset.data')}}",
       columns: [
          { data: 'id' },
          { data: 'username' },
          { data: 'name' },
          { data: 'email' },
       ]
    });

  });
  </script>