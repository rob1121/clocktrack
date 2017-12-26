<?php

namespace App\Clocktrack\Download\Interfaces;

use Illuminate\Http\Request;


interface Downloadable
{
    public function __construct(Request $request);
    public function download();
}