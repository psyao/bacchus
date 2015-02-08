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
            'preparation_time' => 'numeric|between:1,600',
            'cooking_time'     => 'numeric:min:between:1,600',
            'total_time'       => 'required|numeric:min:between:1,600',
            'guests'           => 'required|numeric:min:between:1,20',
            'url'              => 'url'
        ];
    }
}
