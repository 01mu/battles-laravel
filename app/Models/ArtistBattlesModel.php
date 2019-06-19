<?php

namespace Battles\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistBattlesModel extends Model
{
    protected $table = 'artist_battles';

    public function getIDs($channel, $artist) {
        if($channel === 'total') {
            $battle_ids = ArtistBattlesModel::select('battle_id', 'team')
                ->where('name', '=', $artist)
                ->get();
        } else {
            $battle_ids = ArtistBattlesModel::select('battle_id', 'team')
                ->where('channel', '=', $channel)
                ->where('name', '=', $artist)
                ->get();
        }

        return $battle_ids;
    }

    public function getOpponents($battle_id) {
        return ArtistBattlesModel::select('name')
            ->where('battle_id', '=', $battle_id)
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getTeamMembers($battle_id) {
        $participants = [];

        foreach($this->getTeams($battle_id) as $team) {
            $participants[] = $team;
        }

        return $participants;
    }

    private function getTeams($battle_id) {
        $participants = [];

        $teamCount = ArtistBattlesModel::select('team')
            ->distinct()
            ->where('battle_id', '=', $battle_id)
            ->get();

        foreach($teamCount as $t) {
            $group = [];

            $opposingTeam = ArtistBattlesModel::select('name')
                ->where('battle_id', '=', $battle_id)
                ->where('team', '=', $t->team)
                ->get();

            foreach($opposingTeam as $team) {
                $group[] = $team->name;
            }

            $participants[] = $group;
        }

        return $participants;
    }
}
