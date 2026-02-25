<?php

namespace app\Http\Requests\Purchase;

use app\Enums\PurchaseStatus;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $invoiceProducts
 */
class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



}
