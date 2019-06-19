<?php

namespace Battles\Models;

use Illuminate\Database\Eloquent\Model;

use Battles\Models\ArtistBattlesModel;

class BattlesModel extends Model
{
    protected $table = 'battles';

    public function getChannelBattles($channel, $order, $page) {
        $battles = BattlesModel::select('battle_id', 'team')
            ->where('channel', '=', $channel)
            ->orderBy($order, 'DESC')
            ->skip($page * 50)
            ->limit(50)
            ->get();

        return $battles;
    }

    public function getSingleBattleInfo($id) {
        $response = BattlesModel::select('battle_id', 'team')
            ->where('battle_id', '=', $id)
            ->get();

        if(count($response) > 0) {
            return $response[0];
        } else {
            return ['error'];
        }
    }

    public function randomBattle() {
        return BattlesModel::select('battle_id')
            ->inRandomOrder()
            ->get()[0]->battle_id;
    }

    public function getBattlesFromIDs($battle_ids) {
        $abm = new ArtistBattlesModel;

        $battles = [];

        foreach($battle_ids as $battle) {
            $battlers = [];

            $team = $battle['team'];

            $battle_id = $battle['battle_id'];

            if($team == 0) {
                foreach($abm->getOpponents($battle_id) as $battler) {
                    $battlers[] = $battler->name;
                }
            } else {
                $teams = $abm->getTeamMembers($battle_id);
                $battlers = $teams;
            }

            $battleInfo =  BattlesModel::select('*')
                ->where('battle_id', '=', $battle_id)
                ->get()[0];

            $battleInfo['battlers'] = $battlers;
            $battles[] = $battleInfo;
        }

        return $battles;
    }
}
