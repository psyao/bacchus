<?php namespace Bacchus\Http\Requests;

class RecipeRequest extends Request
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
            'name'             => 'required',
            'slug'             => 'sometimes|unique:recipes,slug',
            'preparation_time' => 'numeric|min:1',
            'cooking_time'     => 'numeric|min:0',
            'rest_time'        => 'numeric|min:0',
            'total_time'       => 'required|numeric|min:1',
            'guests'           => 'required|numeric|min:1',
            'url'              => 'url'
        ];
    }
}
