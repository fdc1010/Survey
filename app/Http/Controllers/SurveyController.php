<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Voter;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function edit(Survey $survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
		$voter = Voter::find($voter_id);
        $voter->fill($request->all());
		
		if($request->hasFile('profilepic')){
			
			$path = config('app.root') . '/public/profilepic/';
			$photo=$path.basename($voter->profilepic);
			
			File::delete($photo);
			
            $md5profName = md5_file($request->file('profilepic')->getRealPath());
            $guessExtensionprof = $request->file('profilepic')->guessExtension();

            $srvroot = $_SERVER['DOCUMENT_ROOT'];
            $pathimage =  $srvroot . '/profilepic/';
            $path = url('/profilepic/');
            if (!File::exists($path)) {
                File::makeDirectory($path,0777);
            }

            $width = 160;
            $height = 160;
            $image = Image::make($request->file('profilepic')->getRealPath());
            $image->width() > $image->height() ? $width=null : $height=null;
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $image->save($pathimage.$md5profName.'.'.$guessExtensionprof);

            $filename = $md5profName.'.'.$guessExtensionprof;
            $voter->profilepic =  config('app.url') . '/profilepic/' . $filename;

        }


        $voter->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey)
    {
        //
    }
}
