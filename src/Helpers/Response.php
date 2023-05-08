
<?php

use Illuminate\Validation\Validator;

if (!function_exists('validatorResponse')) {
    /**
     *
     * @param Validator $validator
     *
     * @return http_response or json
     */
    function validatorResponse(Validator $validator){
        if ($validator->fails()) {
            if(request()->ajax()){
                return response()->json(['status' => false, 'errors' => $validator->errors()]);

            }else if(request()->wantsJson()){
                return json_encode(['status' => false, 'errors' => $validator->errors()]);
            }else{
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        return true;
    }
}
