<?php

namespace App\Http\Requests\Admin\Image;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateImage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.image.edit', $this->image);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'salsah_id' => ['nullable', 'integer'],
            'oldnr' => ['nullable', 'string'],
            'signature' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'original_title' => ['nullable', 'string'],
            'file_name' => ['nullable', 'string'],
            'original_file_name' => ['nullable', 'string'],
            'salsah_date' => ['nullable', 'string'],
            'sequence_number' => ['nullable', 'string'],
            'location_id' => ['nullable', 'integer'],
            'collection' => ['nullable', 'integer'],
            'verso' => ['nullable', 'integer'],
            'objecttype' => ['nullable', 'integer'],
            'model' => ['nullable', 'integer'],
            'format' => ['nullable', 'integer'],
            
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();


        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
