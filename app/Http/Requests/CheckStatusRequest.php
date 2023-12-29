<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckStatusRequest extends FormRequest
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

    protected $CheckStatus = [
        'receiptNumber' => [
            'required',
            'numeric',
        ],
    ];


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $action = $this->route()->getAction();
        switch ($action['as']){
            case "CheckStatus":return $this->CheckStatus;
            default: return [];
        }
    }
}
