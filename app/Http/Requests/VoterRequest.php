<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class VoterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|min:5|max:255'
			'precinct_id' => 'required',
			'first_name' => 'required',
			'last_name' => 'required', 
			'gender' => 'required',
			'status_id' => 'required',
			'employment_status_id' => 'required',
			'civil_status_id' => 'required',
			'occupancy_status_id' => 'required',
			'monthly_household' => 'required|numeric',
			'occupancy_length' => 'required|numeric',
			'work' => 'required_if:employment_status_id,1'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
			'work'=>'The work field is required when employment status is Employed.'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
