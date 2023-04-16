<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\MutationsDet;
use App\Models\Upload;
use DB;

class MutationAsset implements FromCollection, WithHeadings, WithEvents
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
        $data = MutationsDet::leftJoin('assets','assets.id','=','detail_mutations.asset_id')
        ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
        ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
        ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
        ->leftjoin('mutations', 'mutations.id', '=', 'detail_mutations.mutasi_id')
        ->leftjoin('users', 'users.id', '=', 'mutations.user_id')
        ->where('detail_mutations.mutasi_id',$this->id)
        ->where('mutations.status','m')
        ->get([
            'asset_number',
            DB::raw("CONCAT(categories.id, ' - ' , categories.category)"),
            'asset_capitalized_on',
            'asset_desc',
            'asset_quantity',
            'counts.count',
            'asset_price',
            'location',
            DB::raw("(
                CASE 
                WHEN assets.asset_condition='sb' THEN 'Sangat Baik'
                WHEN assets.asset_condition='b' THEN 'Baik '
                WHEN assets.asset_condition='rd' THEN 'Rusak,diperbaiki '
                WHEN assets.asset_condition='rt' THEN 'Rusak, tidak dapat diperbaiki'
                WHEN assets.asset_condition='h' THEN 'Hilang'
                ELSE ''
                END) 
            as assets_cond"),
          ]);
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'No Asset',
            'Class',
            'Capitalized on',
            'Asset description',
            'QTY ',
            'OUN ',
            'Price Unit ',
            'Lokasi',
            'Penilaian Kondisi',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(70);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
     
            },
        ];
    }
}
