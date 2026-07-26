<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class OperatorPrintController extends Controller
{
    public function index(Request $request)
    {
        $operator = $request->attributes->get('operator');
        $team = Team::where('operator_id', $operator->id)->with(['ageCategory', 'players.verification', 'players.documents'])->first();
        $event = Event::active();
        $documentType = $request->input('type', 'buku-tim');

        return view('operator.cetak-dokumen', compact('operator', 'team', 'event', 'documentType'));
    }
}
