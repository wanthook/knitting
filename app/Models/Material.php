<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTimeInterface;

class Material extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'materials';
    
    protected $fillable = [
        'kode',
        'deskripsi',   
        'mrp_id',
        'mtype_id',
        'mgroup_id',
        'bunit_id',
        'valcl_id',
        'deleted_at',
        'created_by', 
        'created_at',
        'updated_by', 
        'updated_at'
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function mrp()
    {
        return $this->belongsTo(MasterOption::class, 'mrp_id')->where('tipe','MRPGROUP');
    }

    public function mtype()
    {
        return $this->belongsTo(MasterOption::class, 'mtype_id')->where('tipe','MATTYPE');
    }

    public function mgroup()
    {
        return $this->belongsTo(MasterOption::class, 'mgroup_id')->where('tipe','MATGROUP');
    }

    public function bunit()
    {
        return $this->belongsTo(MasterOption::class, 'bunit_id')->where('tipe','BUNIT');
    }

    public function valcl()
    {
        return $this->belongsTo(MasterOption::class, 'valcl_id')->where('tipe','VALCL');
    }
    
    public function createdby()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    
    public function updatedby()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
