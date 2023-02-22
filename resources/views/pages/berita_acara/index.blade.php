
@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

@section('content')
<div class="card">
    {{-- <div class="card-header ">
      <div class="card-tools">
      </div>
    </div> --}}
    <div class="card-body" style="height:750px;">
        <a href="#" id="btn" class="btn btn-sm btn-warning btnPrint"><i class="fa fa-print"></i> Print</a>
    </div>
</div>

@endsection
  <!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatables JS CDN -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function(){
      $(".btnPrint").printPage({
          url: "{{ route('berita_acara.tinjauan_asset')}}", 
          attr: "href",
          message:"Your document is being created"
      });
    });

</script>