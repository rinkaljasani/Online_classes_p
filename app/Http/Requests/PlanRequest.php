<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $unless = "change_status";
        $id = (!empty(Route::current()->parameters()['user']->id) ? Route::current()->parameters()['user']->id : NULL);
        return [
            'name'                  => 'required_unless:action,'.$unless.'|min:3',
            'description'           => 'required_unless:action,'.$unless.'|min:3',
            'months'                => 'required_unless:action,'.$unless.'|numeric',
            'special_offer_months'  => 'required_unless:action,'.$unless.'|numeric',
            'is_active'             => 'required_unless:action,'.$unless,
            'project_id'            => 'required_unless:action,'.$unless,
            'prorities'            => 'required_unless:action,'.$unless.'|numeric',
        ];
    }
}
