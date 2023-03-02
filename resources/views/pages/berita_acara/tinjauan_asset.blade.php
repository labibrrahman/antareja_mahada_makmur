<style type="text/css">
fieldset 
{
	border: 0px solid #ddd !important;
	margin: 0;
	xmin-width: 0;
	padding: 10px;       
	position: relative;
	border-radius:4px;
	background-color:#f5f5f5;
	padding-left:10px!important;
}	
tr, td {
    padding-left:5px;
    padding-right:5px;
}
td,tr, div{
	font-family:calibri;
} 
 
/* } */
</style>

<div class="card-body" >
    <div class="box-border">
        <fieldset style="background-color:#ffff">
            <div class="row">
                <table border="0" style="border-collapse: collapse" class="header" width="100%">
                    <tr>
                        <td>
                            <img alt="logo" src="{{ asset('assets/images/amm_header.png') }}" width="100%">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;font-size:8px">Head Office : Gedung Office 8, Lantai 8, SCBD Lot 28. Jl. Jend. Sudirman Kav 52-53 Senayan, Kebayoran Baru, Jakarta Selatan, 12190. Telp. +62 21 5790 3456 | www.amm.id</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;font-size:8px">Site Office : Jl. Gerbang Dayaku Poros Samarinda - Tenggarong RT. 16 No. 28 Desa Jembayan, Kecamatan Loa Kulu, Kab. Kutai Kartanegara</td>
                    </tr>
                    
                </table>
                <hr style="border: 1px solid">
                <table width="100%">
                    <tr>
                        <td style="text-align:center;font-size:12px">BERITA ACARA TINJAUAN ASSET AMM SITE ABP</td>
                    </tr>
                    <?php
                        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
                        $bln = $array_bln[date('n')];
                       ;
                    ?>
                    <tr>
                        <td style="text-align:center;font-size:10px"><u>Nomor : ABP/1/FA-PENINJAsAN ASSET/<?=$bln?>/<?php echo date("Y"); ?></u></td>
                    </tr>
                </table>
                <br><br>
                <table width="100%" style="font-size:10px;">
                    <tr >
                        <td width="50px">Site Code</td>
                        <td width="1px">:</td>
                        <td width="500px"> ..... </td>
                        <td width="150px"></td>
                        <td>Penilaian Kondisi :</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top">Departement</td>
                        <td style="vertical-align:top">:</td>
                        <td style="vertical-align:top"> <?= $dept ?> </td>
                        <td style="vertical-align:top"></td>
                        <td style="vertical-align:top">
                            SB = Sangat Baik <br>
                            B = Cukup Baik <br>
                            RD = Rusak, dapat diperbaiki <br>
                            RT = Rusak, tidak dapat diperbaiki <br>
                            H = Hilang
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">Bersama dengan berita acara ini, kami telah melakukan pengecekan Aset sebagai berikut ini :</td>
                    </tr>
                </table>
                <br>
                <table width="100%" style="border-collapse: collapse" border="1">
                    <thead>
                        <tr style="text-align:center;font-size:9px">
                            <th>Asset</th>
                            <th>Class</th>
                            <th>Capitalized on</th>
                            <th>QTY </th>
                            <th>OUN </th>
                            <th>Asset description</th>
                            <th>Acquis.val. </th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>PIC</th>
                            <th>FOTO 1</th>
                            <th>FOTO 2</th>
                            <th>FOTO 3</th>
                            <th>Penilaian Kondisi</th>
                            <th>Status Pengguna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if((isset($asset_data)) && ($asset_data != null)){ ?>
                            @foreach ($asset_data as $data)
                            <tr style="font-size:9px">
                                <td>{{$data->asset_number}}</td>
                                <td>{{$data->category_id}}</td>
                                <td>{{$data->asset_capitalized_on}}</td>
                                <td>{{$data->asset_quantity}}</td>
                                <td>{{$data->count}}</td>
                                <td>{{$data->asset_desc}}</td>
                                <td>{{$data->asset_price}}</td>
                                <td>{{$data->category}}</td>
                                <td>{{$data->asset_condition}}</td>
                                <td>{{$data->location}}</td>
                                <td></td>
                                @for ($i = 1; $i <= 3; $i++)
                                    <?php 
                                    if(isset($data->photo[$i-1])){ 
                                        $file = env('APP_URL_STORAGE').'/'.$data->photo[$i-1];
                                    }else{
                                        $file =  env('APP_URL_STORAGE');
                                    }
                                    if(isset($data->photo[$i-1])){ 
                                        $check_storage = Storage::disk('public')->exists($data->photo[$i-1]);
                                        if($check_storage == false) {?>
                                            <td></td>
                                        <?php }else{?>
                                                <td><img alt="img_asset" src="{{ asset('/storage').'/'.$data->photo[$i-1]}}" width="80px"></td>
                                        <?php } 
                                    }else{?>
                                        <td></td>
                                    <?php }?>
                                @endfor
                                <td>{{$data->asset_status}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        <?php }else{ ?>
                            <tr style="font-size:9px">
                                <td colspan="16" style="text-align:center">No data available in table</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </fieldset> 


    </div>
</div>
        
<script type="text/javascript">
    var css = '@page { size: landscape; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');
    
    style.type = 'text/css';
    style.media = 'print';
    
    if (style.styleSheet){
      style.styleSheet.cssText = css;
    } else {
      style.appendChild(document.createTextNode(css));
    }
    
    head.appendChild(style);
    
    window.print();
    </script>
        
</body>
</html>