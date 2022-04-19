<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTimeInterface;

class SpkRajutDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'spk_rajut_details';
    
    protected $fillable = [
        'mesin_id',
        'material_id',
        'material_raw_id',
        'warna',
        'greige',
        'finish',
        'size_finish',   
        'qty',     
        'spk_rajut_id',   
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

    public function mesin()
    {
        return $this->belongsTo(Mesin::class, 'mesin_id')->orderBy('deskripsi', 'asc');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function rawmaterial()
    {
        return $this->belongsTo(Material::class, 'material_raw_id');
    }

    public function spk()
    {
        return $this->belongsTo(SpkRajut::class, 'spk_rajut_id');
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
