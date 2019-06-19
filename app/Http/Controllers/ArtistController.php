<?php

namespace Battles\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Battles\Models\ArtistModel;
use Battles\Models\ArtistBattlesModel;
use Battles\Models\BattlesModel;

class ArtistController extends Controller
{
    public function getArtist($channel, $artist) {
        $response = [];

        $am = new ArtistModel;
        $abm = new ArtistBattlesModel;
        $bm = new BattlesModel;

        $battle_ids = $abm->getIDs($channel, $artist);

        $info = $am->getArtistInfo($channel, $artist);
        $battles = $bm->getBattlesFromIDs($battle_ids);

        if($info == ['error']) {
            $response['response'] = 'bad';
        } else {
            $response['response'] = 'good';
        }

        $battles = collect($battles)->sortBy('timestamp')->reverse()->toArray();

        $response['artist_info'] = $info;
        $response['artist_battles'] = $battles;

        echo json_encode($response);
    }

    public function getChannelArtists($channel, $page) {
        $response = [];

        $am = new ArtistModel;

        $response['response'] = 'good';
        $response['artists'] = $am->getChannelArtistsInfo($channel, $page);

        echo json_encode($response);
    }
}
