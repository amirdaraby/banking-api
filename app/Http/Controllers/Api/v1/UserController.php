<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;

class UserController extends Controller
{
    public function index()
    {
        return ResponseJson::success(null, "api working",);
    }

    public function store()
    {

    }

    public function show(int $id)
    {

    }

    public function update(int $id)
    {

    }

    public function destroy(int $id)
    {

    }
}
