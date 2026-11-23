<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desk extends Model
{
    protected $fillable = [
        'building', 'floor', 'room', 'desk_number',
        'height', 'mode', 'previous_height', 'previous_mode',
    ];

    protected function casts(): array
    {
        return [
            'height' => 'integer',
            'previous_height' => 'integer',
        ];
    }

    /** Human readable id, e.g. B1-F1-R101-D1 */
    public function getCodeAttribute(): string
    {
        return "B{$this->building}-F{$this->floor}-R{$this->room}-D{$this->desk_number}";
    }
}
