<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTimeInterface;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'customers';
    
    protected $fillable = [
        'kode',
        'nama1',   
        'nama2',
        'kota',
        'jalan1',
        'jalan2',
        'country_id',
        'group_id',        
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

    public function country()
    {
        return $this->belongsTo(MasterOption::class, 'country_id')->where('type','COUNTRY');
    }

    public function group()
    {
        return $this->belongsTo(MasterOption::class, 'group_id')->where('tipe','ACCGROUP');
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
