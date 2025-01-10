<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'parent_id', 'sort_description', 'image', 'sort_order', 'status',
    ];

    protected $dates = ['deleted_at'];

    public function subCategories() {
        return $this->hasMany(Category::class, 'parent_id')->where('status', 1);
    }

    public function getparentCat($mode=0){  

        $records = DB::select('SELECT c1.id, 
                case WHEN c1.parent_id!=0 THEN c3.name ELSE "" END as main_id, 
                case WHEN c2.parent_id=0 THEN c2.name ELSE 
                case WHEN c1.parent_id!=0 THEN c2.name ELSE "" END END as parent_id,
                c1.name FROM categories c1  
                LEFT JOIN categories c2 ON (c1.parent_id = c2.id)
                LEFT JOIN categories c3 ON (c2.parent_id = c3.id)
                where c1.status=1  order by c3.name, c2.name, c1.name');

        // dd($records);
        $newRecord = [];    
        foreach ($records as $key =>  $row) 
        { 
            if($mode==0){
                if(empty($row->main_id)){
                    $catName = '';  
                    if($row->main_id){ $catName = $row->main_id.' >> '; }
                    if($row->parent_id){ $catName.= $row->parent_id.' >> '; }
                    if($row->name){ $catName.= $row->name;}
                    $newRecord[$key]['id'] = $row->id;
                    $newRecord[$key]['name'] = $catName; 
                }
            }else{
                $catName = '';  
                if($row->main_id){ $catName = $row->main_id.' >> '; }
                if($row->parent_id){ $catName.= $row->parent_id.' >> '; }
                if($row->name){ $catName.= $row->name;}
                $newRecord[$key]['id'] = $row->id;
                $newRecord[$key]['name'] = $catName; 
            } 
        }
        return $newRecord;
    }

}
