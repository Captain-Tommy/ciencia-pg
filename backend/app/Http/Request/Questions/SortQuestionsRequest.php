<?php

namespace Ciencia\Http\Request\Questions;

use Ciencia\Http\Request\BaseRequest;

class SortQuestionsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            '*.id' => 'integer|required',
            '*.order' => 'integer|required',
        ];
    }
}
