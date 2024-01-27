<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingHour extends Model
{
    use HasAdvancedFilter;
    use HasFactory;
    use SoftDeletes;

    public const ATTRIBUTES = [
        'id',
        'hour',
        'updated_at',
        'created_at',
    ];

    public $orderable = self::ATTRIBUTES;
    public $filterable = self::ATTRIBUTES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'hour',
        'is_am',
    ];

    public function getLabelIsAmAttribute(){
        return $this->is_am ? 'AM' : 'PM';
    }

    public function getFullLabelAttribute(){
        return $this->hour.' '.$this->label_is_am;
    }

}
