<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ImageController extends Controller
{
    /*
        input: base_url/get/{image}
        example: localhost/imageupload/get/1492731897275.jpg
        output:
            file exists: 
                url: "public images folder"/image
                example: {"url":"http://localhost/imageupload/public/images/1492731897275.jpg"}
            file not exists:
                status: "not found"
    */
    public function get(Request $request) {
        if(file_exists( public_path() . '/images/' . $request->image)) 
        {
            return response()->json(['url' => url('/public/images/'.$request->image)]);
        }
        else 
        {
            return response()->json(['status' => 'not found']);
        }     
    }

    /*
        input: multipart/form-data request
        output:
            upload success: 
                file: file name
                example: {"file":"1492731897275.jpg"}
            upload not success:
                status: "fail"
    */
    public function add(Request $request) {
        if (Input::file('image')->isValid()) 
        {
            $extension = "";
            if(is_null($request->extension)) 
            {
                $extension = Input::file('image')->getClientOriginalExtension();
            } 
            else 
            {
                $extension = $request->extension;
            }
            $fileName = round(microtime(true) * 1000).'.'.$extension;
            Input::file('image')->move(base_path().'/public/images/',$fileName);
            return response()->json(['file' => $fileName]);;
        }
        return response()->json(['status' => 'fail']);
    }

}
