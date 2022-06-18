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

            // $result = $request->storeOnCloudinary();
            // $url = $result->getPath();
            // return $url;
        } catch (\Exception $error) {
            return false;
        }
    }

    public function storeImage($request)
    {
        if ($request->hasFile('file')) {
            try {
                // $name = $request->file('file')->getClientOriginalName();
                // $pathFull = 'uploads/' . date("Y/m/d");
                // $request->file('file')->storeAs(
                //     'public/' . $pathFull, $name
                // );

                // $url = 'http://localhost:8080';
                
                // return $url . '/storage/' . $pathFull . '/' . $name;

                $result = $request->file('file')->storeOnCloudinary();
                $url = $result->getPath();
                return $url;
            } catch (\Exception $error) {
                return false;
            }
        }
    }
}
