<?php

namespace Battles\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistModel extends Model
{
    protected $table = 'artists';

    public function getArtistInfo($channel, $artist) {
        $response = ArtistModel::select('*')
            ->where('channel', '=', $channel)
            ->where('name', '=', $artist)
            ->get();

        if(count($response) > 0) {
            return $response[0];
        } else {
            return ['error'];
        }
    }

    public function getChannelArtistsInfo($channel, $page) {
        return ArtistModel::select('*')
            ->where('channel', '=', $channel)
            ->orderBy('battle_views', 'DESC')
            ->skip($page * 50)
            ->limit(50)
            ->get();
    }
}
