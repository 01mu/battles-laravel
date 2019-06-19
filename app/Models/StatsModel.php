<?php

namespace Battles\Models;

use Illuminate\Database\Eloquent\Model;

class StatsModel extends Model
{
    protected $table = 'stats';

    public function getStats() {
        return StatsModel::select('*')->get();
    }
}
