<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Models\BeritaAcaraTinjauanAsset;
use App\Models\Upload;
use DB;

class TinjauanAsset implements FromCollection, WithHeadings, WithDrawings, WithEvents
{
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()    
    {
        $asset = $this->getAsset();
        foreach ($asset as $data) {
            $data->photo1 = '';
            $data->photo2 = '';
            $data->photo3 = '';
        }
        return collect($asset);
    }

    public function getAsset(){
        $get_data_ba_tinjauan_asset = json_decode(BeritaAcaraTinjauanAsset::find($this->id));
        $date_from = $get_data_ba_tinjauan_asset->tgl_awal;
        $date_to = $get_data_ba_tinjauan_asset->tgl_akhir;
        $departement_id = $get_data_ba_tinjauan_asset->departement_id;
        $query_asset = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                      ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                      ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                      ->when($departement_id != 0, function ($query_) use ($departement_id) {
                          return $query_->where('assets.departement_id', $departement_id);
                      })
                      ->when($date_from != "-", function ($query_) use ($date_from, $date_to) {
                          return $query_->whereBetween('assets.asset_capitalized_on', [$date_from, $date_to]);
                      })
                      ->whereIn('assets.id',Upload::select('asset_id'))
                      ->orderBy('assets.asset_capitalized_on')
                      ->get([
                        'assets.id',
                        'assets.asset_number',
                        'categories.id as category_id',
                        'assets.asset_capitalized_on',
                        'assets.asset_quantity',
                        'counts.count',
                        'assets.asset_desc',
                        'assets.asset_price',
                        'categories.category',
                        'assets.location',
                        DB::raw("(
                            CASE 
                            WHEN assets.asset_condition = 'sb' THEN 'Sangat Baik'
                            WHEN assets.asset_condition = 'b' THEN 'Baik'
                            WHEN assets.asset_condition = 'rd' THEN 'Rusak, dapat diperbaiki'
                            WHEN assets.asset_condition = 'rt' THEN 'Rusak, tidak dapat diperbaiki'
                            WHEN assets.asset_condition = 'h' THEN 'Hilang'
                            ELSE ''
                            END) as asset_condition"),
                        'assets.status_pengguna'
                        ]);

        $asset = json_decode($query_asset);
        foreach($asset as $data_asset){
            $query_upload = Upload::where('asset_id', $data_asset->id)->get();
            $get_photo = json_decode($query_upload);
            $maxPhoto = 3;
            for ($i=1; $i <= $maxPhoto; $i++) { 
                $photo = "photo".$i;
                if(isset($get_photo[$i-1])){
                    $data_asset->$photo = $get_photo[$i-1]->upload_image;
                }else{
                    $data_asset->$photo = '';
                }
            }
            $data_asset_condition = $data_asset->asset_condition;
            $data_status_pengguna = $data_asset->status_pengguna;
            unset($data_asset->asset_condition);
            unset($data_asset->status_pengguna);
            unset($data_asset->id);
            $data_asset->asset_condition = $data_asset_condition;
            $data_asset->status_pengguna = $data_status_pengguna;
        }

        return $asset;
    }
    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('This is my logo');
        
    //     $drawing->setPath(public_path('storage/asset/105000228.jpeg'));
    //     // $drawing->setPath(asset('/storage/asset/105000228.jpeg'));
    //     $drawing->setHeight(90);
    //     $drawing->setCoordinates('B3');

    //     return $drawing;
    // }

    public function drawings():array
    {
        $drawings = [];
        
        foreach ($this->getAsset() as $key => $row) {
            if($row->photo1 != ''){
                $keyrow1 = $key + 1;
                $keys = (string)$keyrow1+1;
                $drawing = new Drawing();
                $drawing->setPath(public_path('storage').'/'.$row->photo1);
                // $drawing->setPath(asset('/storage').'/'.$row->photo1);
                $drawing->setCoordinates('J'.$keys);
                $drawing->setHeight(90);
                $drawings[] = $drawing;
                $row->photo1 = '';
            }
            if($row->photo2 != ''){
                $keyrow2 = $key + 1;
                $keys = (string)$keyrow2+1;
                $drawing2 = new Drawing();
                $drawing2->setPath(public_path('storage').'/'.$row->photo2);
                // $drawing2->setPath(asset('/storage').'/'.$row->photo2);
                $drawing2->setCoordinates('K'.$keys);
                $drawing2->setHeight(90);
                $drawings[] = $drawing2;
                $row->photo2 = '';
            }
            if($row->photo3 != ''){
                $keyrow3 = $key + 1;
                $keys = (string)$keyrow3+1;
                $drawing3 = new Drawing();
                $drawing3->setPath(public_path('storage').'/'.$row->photo3);
                // $drawing3->setPath(asset('/storage').'/'.$row->photo3);
                $drawing3->setCoordinates('K'.$keys);
                $drawing3->setHeight(90);
                $drawings[] = $drawing3;
                $row->photo3 = '';
            }
        }

        return $drawings;
    }

    public function headings(): array
    {
        return [
            'Asset',
            'Class',
            'Capitalized on',
            'QTY ',
            'OUN ',
            'Asset description',
            'Acquis.val. ',
            'Jenis',
            'Lokasi',
            'Photo 1',
            'Photo 2',
            'Photo 3',
            'Penilaian Kondisi',
            'Status Pengguna'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                foreach ($this->getAsset() as $key => $row) {
                    $key = $key + 1;
                    $event->sheet->getDelegate()->getRowDimension($key+1)->setRowHeight(90);
                }
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(70);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
            },
        ];
    }
}
