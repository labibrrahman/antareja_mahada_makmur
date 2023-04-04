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
                        <td style="text-align:center;font-size:12px">Berita Acara Mutasi Asset</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;font-size:10px"><u>Nomor : ABP/{{$no_ba}}/FA-MUTASI ASSET/IX/2022</u></td>
                    </tr>
                </table>
                <br><br>
                <table width="100%" style="font-size:10px;">
                    <tr >
                        <td width="80px">Site Code</td>
                        <td width="1px">:</td>
                        <td width="500px"> AM01 </td>
                        <td width="150px"></td>
                        <td width="500px"><i>Coret Apabila Tidak Perlu</i></td>
                    </tr>
                    <tr >
                        <td width="80px">Jenis Pengajuan</td>
                        <td width="1px">:</td>
                        <td width="500px"> Mutasi Asset </td>
                        <td style="vertical-align:top"></td>
                        <td style="vertical-align:top"><u>Lampiran Berupa :</u></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top">Diajuan Oleh</td>
                        <td style="vertical-align:top">:</td>
                        <td style="vertical-align:top"> {{$user_req ?? "........"}} </td>
                        <td style="vertical-align:top"></td>
                        <td style="vertical-align:top">1. Berita Acara Mutasi Asset Ditandatangani 2 Pihak (wajib Ada)</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top">Lampiran</td>
                        <td style="vertical-align:top">:</td>
                        <td style="vertical-align:top"> 3 Halaman </td>
                        <td style="vertical-align:top"></td>
                        <td style="vertical-align:top">2. Surat jalan (wajib ada)</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="vertical-align:top">3. Packing List</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="vertical-align:top">4. Foto Asset (wajib ada)</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>
                        <td colspan="3">Kepada Yth.</td>
                        <td></td>
                        <td style="vertical-align:top">5. BAPP (plant Khusus Unit yang memiliki Equipment No /No. Lambung Wajib Ada)</td>
                    </tr>
                    <tr>
                        <td colspan="3"> .......... </td>
                        <td></td>
                        <td style="vertical-align:top">6. Lainnya,</td>
                    </tr>
                    <tr>
                        <td colspan="3">Ditempat,</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4">Bersama dengan Berita Acara ini kami informasi Perpindahan Asset berupa:</td>
                    </tr>
                </table>
                <br>
                <table width="100%" style="border-collapse: collapse" border="1">
                    <tr style="text-align:center;font-size:9px">
                        <th>No</th>
                        <th>No Asset</th>
                        <th>Class</th>
                        <th>Capitalized on</th>
                        <th>Asset description</th>
                        <th>QTY </th>
                        <th>OUN </th>
                        <th>Price Unit </th>
                        <th>Lokasi</th>
                        <th>Penilaian Kondisi</th>
                    </tr>
                    <?php $i = 1;?>
                    <?php if((isset($mutation_data)) && ($mutation_data != null)){ ?>
                        @foreach ($mutation_data as $data)
                            <tr style="font-size:9px">
                                <td>{{$i++}}</td>
                                <td>{{$data->asset_number}}</td>
                                <td>{{$data->category_id ." - ". $data->category}}</td>
                                <td>{{$data->asset_capitalized_on}}</td>
                                <td>{{$data->asset_desc}}</td>
                                <td>{{$data->asset_quantity}}</td>
                                <td>{{$data->count}}</td>
                                <td>{{$data->asset_price}}</td>
                                <td>{{$data->location}}</td>
                                <td>{{$data->assets_cond}}</td>
                            </tr>
                        @endforeach
                    <?php } else{ ?>
                        <tr style="font-size:9px">
                            <td colspan="17" style="text-align:center">No data available in table</td>
                        </tr>
                    <?php } ?>
                </table>
                <br>
                <br>
                <table width="100%" style="font-size:10px;">
                    <tr>
                        <td width="15%">Hari/Tanggal pengajuan</td>
                        <td width="1px">:</td>
                        <td> .... </td>
                    </tr>
                </table>
                <br>
                <br>
                {{-- <table width="100%" style="font-size:10px;">
                    <tr style="text-align:center;font-size:9px">
                        <td width="15%">Diserahkan Oleh AMM Site ABP</td>
                        <td width="15%">Diterima Oleh AMM Site MHU</td>
                    </tr>
                    <tr>
                        <td style="text-align:center">
                            <table width="100%">
                                <tr style="text-align:center;font-size:9px">
                                    <td width="50%">Diajukan Oleh :</td>
                                    <td width="50%">Diperiksa Oleh :</td>
                                </tr>
                                <tr>
                                    <td height="100px"></td>
                                    <td></td>
                                </tr>
                                <tr style="text-align:center;font-size:9px">
                                    <td><u>Mochamad Iqbal Wijya</u><br>Group Leader FA<br><u>NIK : 22001025</u></td>
                                    <td><u>Budi Utomo Burhanudin</u><br>Sect. Head FA-LOG<br><u>NIK : 14060655</u></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table width="100%">
                                <tr style="text-align:center;font-size:9px">
                                    <td width="50%">Diajukan Oleh :</td>
                                    <td width="50%">Diperiksa Oleh :</td>
                                </tr>
                                    <tr>
                                <tr style="text-align:center;font-size:9px">
                                    <td height="100px"></td>
                                    <td></td>
                                </tr>
                                <tr style="text-align:center;font-size:9px">
                                    <td><u>Mochamad Iqbal Wijya</u><br>Group Leader FA<br><u>NIK : 22001025</u></td>
                                    <td><u>Budi Utomo Burhanudin</u><br>Sect. Head FA-LOG<br><u>NIK : 14060655</u></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="text-align:center;font-size:9px">
                        <td width="15%">Disetujui oleh,</td>
                        <td width="15%">Disetujui oleh,</td>
                    </tr>
                    <tr>
                        <td height="100px"></td>
                        <td></td>
                    </tr>
                    <tr style="text-align:center;font-size:9px">
                        <td><u>Muhammad Anwar Hasan Salasa</u><br>Site Manager<br><u>NIK : 22004256</u></td>
                        <td><u>Budi Utomo Burhanudin</u><br>Site Manager<br><u>NIK : 5090025</u></td>
                    </tr>
                </table> --}}
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