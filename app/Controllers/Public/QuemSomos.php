<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class QuemSomos extends BaseController
{
    public function index()
    {
        return view('Public/QuemSomos/index');
    }
}
