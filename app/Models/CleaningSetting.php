<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningSetting extends Model
{
    protected $fillable = ['enabled', 'time', 'use_max', 'height', 'active', 'last_run_date'];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'use_max' => 'boolean',
            'active' => 'boolean',
            'height' => 'integer',
            'last_run_date' => 'date',
        ];
    }

    /** There is only ever one row. */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    public function targetHeight(): int
    {
        return $this->use_max ? User::MAX_HEIGHT : $this->height;
    }
}
