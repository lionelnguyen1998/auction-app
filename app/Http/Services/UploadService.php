<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Validator;

class UploadService implements UploadServiceInterface
{
    public function store($request)
    {
        try {
            $name = $request->getClientOriginalName();
            $pathFull = 'uploads/' . date("Y/m/d");

            $request->storeAs(
                'public/' . $pathFull, $name
            );

            $url = 'http://localhost:8080';
            
            return $url . '/storage/' . $pathFull . '/' . $name;
        } catch (\Exception $error) {
            return false;
        }
    }
}
