<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserPlanRequest extends FormRequest
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
    public function rules()
    {
        $unless = "change_status";
        switch ($this->method()) {
            case 'POST':
                return [
                    'user_id' => 'required_unless:action,'.$unless.'|exists:users,id',
                    'plan_id' => 'required_unless:action,'.$unless.'|exists:plans,id',
                    'project_id' => 'required_unless:action,'.$unless.'|exists:projects,id',
                    'device_id' => 'required_unless:action,'.$unless.'|string',
                    'device_type' => 'required_unless:action,'.$unless.'|string'
                ];
            case 'PUT':
                return [
                    'device_id' => 'required_unless:action,'.$unless.'|string',
                    'device_type' => 'required_unless:action,'.$unless.'|string'
                ];
            default:

                break;
        }
    }
}
