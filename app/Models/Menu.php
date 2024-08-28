<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $primaryKey = 'menu_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['menu_id', 'depth', 'parent_data', 'name'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->menu_id = (string) Str::uuid();
        });
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_data', 'name');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_data', 'name');
    }
}


