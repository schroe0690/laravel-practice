<?php

namespace App\Http\Controllers;

class StampController extends Controller
{
    public function show(){
        $greet1 = 'Good Morning !!';
        $greet2 = 'Good Afternoon !!';
        return view('stamp', [
            'greet1' => $greet1,
            'greet2' => $greet2,
        ]);
    }
}
