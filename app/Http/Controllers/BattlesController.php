<?php

namespace Battles\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Battles\Models\BattlesModel;

class BattlesController extends Controller
{
    public function getBattles($channel, $order, $page) {
        $validator = Validator::make([$channel, $order, $page], array(
            [$channel => 'required', 'string'],
            [$order => 'required', 'in:timestamp,views', 'string'],
            [$page => 'required', 'numeric']));

        if($validator->fails()) {
            return json_encode(['response' => 'bad']);
        }

        $response = [];
        $battle_ids = [];

        $bm = new BattlesModel;

        foreach($bm->getChannelBattles($channel, $order, $page) as $b) {
            $battle_ids[] = ['battle_id' => $b->battle_id, 'team' => $b->team];
        }

        $response['response'] = 'good';
        $response['battles'] = $bm->getBattlesFromIDs($battle_ids);

        echo json_encode($response);
    }

    public function getSingleBattle($id) {
        $bm = new BattlesModel;

        return $this->getInfo($bm, $id);
    }

    public function getRandomBattle() {
        $bm = new BattlesModel;

        return $this->getInfo($bm, $bm->randomBattle());
    }

    private function getInfo($bm, $id) {
        $response = [];

        $battle = $bm->getSingleBattleInfo($id);

        if($battle == ['error']) {
            $response['response'] = 'bad';
        } else {
            $battle_ids = array([  'battle_id' => $battle->battle_id,
                            'team' => $battle->team]);

            $response['response'] = 'good';
            $response['battle'] = $bm->getBattlesFromIDs($battle_ids);
        }

        echo json_encode($response);
    }
}
