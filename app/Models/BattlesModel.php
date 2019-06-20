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

    private function makeTeamVSString($teams) {
        $vsStr = '';

        $team_count = count($teams);

        for($j = 0; $j < $team_count; $j++) {
            $battler_count = count($teams[$j]);

            for($i = 0; $i < $battler_count; $i++) {
                $vsStr .= $teams[$j][$i];

                if($i != $battler_count - 1) {
                    $vsStr .= ' AND ';
                }
            }

            if($j != $team_count - 1) {
                $vsStr .= ' VS. ';
            }
        }

        return $vsStr;
    }

    public function getBattlesFromIDs($battle_ids, $artist = 0) {
        $abm = new ArtistBattlesModel;

        $battles = [];

        foreach($battle_ids as $battle) {
            $battlers = [];

            $team = $battle['team'];

            $battle_id = $battle['battle_id'];

            if($team == 0) {
                if(!$artist) {
                    foreach($abm->getOpponents($battle_id) as $battler) {
                        $battlers[] = $battler->name;
                    }
                } else {
                    $battlers[] = $artist;
                    $battlers[] = $abm->getSingleOpponent($battle_id, $artist);
                }

                $vs_str = $battlers[0] . ' VS. ' . $battlers[1];
            } else {
                $teams = $abm->getTeamMembers($battle_id);
                $battlers = $teams;
                $vs_str = $this->makeTeamVSString($teams);
            }

            $battleInfo =  BattlesModel::select('*')
                ->where('battle_id', '=', $battle_id)
                ->get()[0];

            $battleInfo['battlers'] = $battlers;
            $battleInfo['vs_str'] = $vs_str;

            $battles[] = $battleInfo;
        }

        usort($battles, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return $battles;
    }
}
