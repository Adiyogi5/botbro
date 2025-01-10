<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminPermission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'module_id',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete',
        'allow_all',
    ];

    public function toggle($type = '')
    {
        $this->update([$type => DB::raw('NOT ' . $type)]);
    }

    public function permission_name()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
