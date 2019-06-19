<?php

namespace Battles\Http\Controllers;

use Illuminate\Http\Request;

use Battles\Models\StatsModel;

class StatsController extends Controller
{
    public function getStats() {
        $response = [];

        $sm = new StatsModel;

        $response['response'] = 'good';
        $response['stats'] = $sm->getStats();

        echo json_encode($response);
    }
}
