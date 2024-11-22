<?php

namespace App\Models\Repositories;


use App\Classes\Helpers\DbHelper;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CityRepository
{
    public function list(): Collection
    {
        return City::orderBy(DB::raw(DbHelper::orderByField('name', ['Москва', 'Санкт-Петербург'])))->get();
    }
}
