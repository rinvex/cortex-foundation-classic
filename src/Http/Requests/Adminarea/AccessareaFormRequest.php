<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Requests\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;

class AccessareaFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $accessarea = $this->route('accessarea') ?? app('cortex.foundation.accessarea');
        $accessarea->updateRulesUniques();

        return $accessarea->getRules();
    }
}
