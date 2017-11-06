<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BreakTime;

class BreaktimeController extends Controller
{   
    public function __construc() {
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $breaktime = new BreakTime();
        $breaktime->biometric_id = $request->biometric_id;
        $breaktime->break_in = $request->break_in;
        $breaktime->break_out = '';
        
        $breaktime->save();

        return [
            'message' => 'success',
            'success' => true
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $breaktime)
    {
        $breaktime = BreakTime::where('biometric_id', $request->biometric_id)->get()->last();
        $breaktime->biometric_id = $request->biometric_id;
        $breaktime->break_out = $request->break_out;

        $breaktime->save();
        return [
            'message' => 'success',
            'success' => true
        ];
    }
}
