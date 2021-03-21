<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    private bool $flag;

    public function __construct(bool $flag)
    {
        $this->flag = $flag;
    }

    public function render(Request $request)
    {
        $message = trans('custom_validations.customException', ['flag' => $this->flag ? 'true' : 'false']);

        if ($request->expectsJson()){
            return response()->json([
                'exception' => $message,
            ]);
        }

        return view('errors.custom', compact('message'));
    }
}
