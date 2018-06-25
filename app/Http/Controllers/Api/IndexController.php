<?php

namespace App\Http\Controllers\Api;

class IndexController extends ApiController
{
    public function index(){

        return $this->success(['a' => 1, 'b' => 2]);
    }
}