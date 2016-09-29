<?php

namespace Ecommerce\Http\Requests;

use Ecommerce\Http\Requests\Request;

class AdminProductRequest extends Request
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
        return [
            'name' => 'required',
        	'description' => 'required',
        	'price' => 'required',
        	'category_id' => 'required'
        ];
    }
}
