<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Counts;
use App\Models\Categories;
use App\Models\Departement;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportAsset implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $category = Categories::select('id')->where('category', $row['category'])->get();
        $check_category = count(json_decode($category));
        if($check_category != 0){
            $category_id = json_decode($category)[0]->id;
            $row['category'] = $category_id;
        }

        $count = Counts::select('id')->where('count', $row['oun'])->get();
        $check_count = count(json_decode($count));
        if($check_count != 0){
            $count_id = json_decode($count)[0]->id;
            $row['oun'] = $count_id;
        }

        $dept = Departement::select('id')->where('department', $row['deptarea'])->get();
        $check_dept = count(json_decode($dept));
        if($check_dept != 0){
            $dept_id = json_decode($dept)[0]->id;
            $row['deptarea'] = $dept_id;
        }

        if($row['serial_number'] == null){
            $row['serial_number'] = '-';
        }
        if($row['qty'] == null){
            $row['qty'] = '0';
        }
        
        return new Asset([
            'asset_number' => $row['asset_number'],
            'category_id' => $row['category'],
            'asset_capitalized_on' => Date::excelToDateTimeObject($row['capitalized_on']),
            'asset_desc' => $row['asset_description'],
            'asset_serial_number' => $row['serial_number'],
            'departement_id' => $row['deptarea'],
            'asset_manager' => $row['pic'],
            'asset_quantity' => $row['qty'],
            'count_id' => $row['oun'],
            'asset_po' => $row['po'],
            'asset_price' => $row['harga_beli']
        ]);
    }
}
