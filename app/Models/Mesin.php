<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTimeInterface;

class Mesin extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'mesins';
    
    protected $fillable = [
        'kode',
        'nama',
        'merek',   
        'proses',
        'spesifikasi',
        'deskripsi',
        'k_min',
        'k_max',
        'tipe',
        'wc_id',
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

    public function wc()
    {
        return $this->belongsTo(MasterOption::class, 'wc_id')->where('tipe','WC');
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
