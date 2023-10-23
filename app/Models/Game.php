<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';

    protected $fillable = [
        'player_id',
        'dice1',
        'dice2',
        'result',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
