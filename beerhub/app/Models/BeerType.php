<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeerType extends Model
{
    //
    public $timestamps = false;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'beer_type_id' => ['required', 'integer', 'between:0,5'],
        ];
    }
}
