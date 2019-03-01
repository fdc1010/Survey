<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;
use Excel;
use File;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('importexcel');
    }
    public function importVotersExcel2()
    {
        return view('importexcel2');
    }
	/*public function getMedia($userId, $collection)
    {
        $user = Voter::findOrFail($userId);
        $media = $user->getMedia($collection)->last();
        $filePath = isset($media) ? $media->getPath() : abort(404, 'Media not found.');
        return Image::make($media->getPath())->response();
    }*/
	public function extramiddlename(){
		$voters = Voter::get();

		foreach($voters as $voter){
			$explodefm = explode(" ",$voter->first_name);
			echo $voter->first_name . " : ";
			if(count($explodefm)>1){
				$curvoter = Voter::find($voter->id);
				$curvoter->middle_name = $explodefm[count($explodefm)-1];
				$curvoter->save();
			}
		}
	}
	public function importvoters(Request $request){
        //validate the xls file
        // $this->validate($request, array(
        //     'filevoters'      => 'required'
        // ));
        $index=$request->index;
        if($request->hasFile('filevoters')){
            $extension = File::extension($request->file('filevoters')->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file('filevoters')->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
                    foreach ($data as $key => $value) {
                    //for($i = $index; $i <= $data->count(); $i++){
                    //    if($index<=1000){
                            $insert[] = [
            						            'precinct_id' => $value->precinct,
                                    'seq_num' => $value->seqnum,
                                    // 'status_id' => $value->status,
                              			// 'sitio_id' => $value->sitio,
                                    'last_name' => $value->lastname,
                        						'first_name' => $value->firstname,
                              			// 'middle_name' => $value->middlename,
                              			// 'address' => $value->address
                                    ];

                            $index++;
                      //  }
                    }

                    if(!empty($insert)){

                        $insertData = DB::table('voters')->insert($insert);
                        if ($insertData) {
                        //if($data->count()>0){
                            //Session::flash('success', 'Your Data has successfully imported');
                            $messages = array('messages' => array("Your Data has successfully imported"));
                            return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
                        }else {
                            //Session::flash('error', 'Error inserting the data..');
                            //return back();
                            $messages = array('messages' => array("Error inserting the data.."));
                            return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],401);
                        }
                    }
                }
                //return back();

            }else {
                // Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                // return back();
                $messages = array('messages' => array("File is a ".$extension." file.!! Please upload a valid xls/csv file..!!"));
                return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
            }
        }
        $messages = array('messages' => array("Error uploading the data..","Has File: ".$request->hasFile('filevoters')));
        return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],401);
    }
    public function importvoters2(Request $request){
          //validate the xls file
          // $this->validate($request, array(
          //     'filevoters'      => 'required'
          // ));
          $i = 0;
          $index=$request->index;
          if($request->hasFile('filevoters')){
              $extension = File::extension($request->file('filevoters')->getClientOriginalName());
              if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                  $path = $request->file('filevoters')->getRealPath();
                  $data = [];
                  Excel::filter('chunk')->load($path)->chunk(1000, function ($results) use (&$data) {
                      foreach ($results as $row) {
                          $data[] = $row;
                          //$data = Excel::load($path, function($reader) { })->get();
                          if(!empty($data) && count($data)>0){
                              foreach ($data as $value) {
                                      info($value);
                                      $insert[] = [
                      						            'precinct_id' => $value['precinct'],
                                              'seq_num' => $value['seqnum'],
                                              // 'status_id' => $value['status'],
                                        			// 'sitio_id' => $value['sitio'],
                                              'last_name' => $value['lastname'],
                                  						'first_name' => $value['firstname'],
                                        			// 'middle_name' => $value['middlename'],
                                        			// 'address' => $value['address']
                                              ];
                              }
                              $index=$i++;
                              if(!empty($insert)){

                                  //$insertData = DB::table('voters')->insert($insert);
                                  //if ($insertData) {
                                  if(count($data)>0){
                                      //Session::flash('success', 'Your Data has successfully imported');
                                      $messages = array('messages' => array("Your Data has successfully imported"));
                                      return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
                                  }else {
                                      //Session::flash('error', 'Error inserting the data..');
                                      //return back();
                                      $messages = array('messages' => array("Error inserting the data.."));
                                      return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],401);
                                  }
                              }
                          }
                        }

                      }, $shouldQueue = false);

                }else {
                    // Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                    // return back();
                    $messages = array('messages' => array("File is a ".$extension." file.!! Please upload a valid xls/csv file..!!"));
                    return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
                }
          }
          $messages = array('messages' => array("Error uploading the data..","Has File: ".$request->hasFile('filevoters')));
          return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],401);
      }
	public function importprecinct(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));

        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){

                    foreach ($data as $key => $value) {
                        $insert[] = [
                        'precinct_number' => $value->prec,
                        'barangay_id' => $value->brgy
                        ];
			echo $value->prec . "<br>" . $value->brgy;			
                    }
                    if(!empty($insert)){
 			try{
                        $insertData = DB::table('precincts')->insert($insert);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
			}catch(\Exception $e){			    
			    echo $e;
			    info($e);	
			}
                    }
                }

                return back();

            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
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
     * @param  \App\Voter  $voter
     * @return \Illuminate\Http\Response
     */
    public function show(Voter $voter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Voter  $voter
     * @return \Illuminate\Http\Response
     */
    public function edit(Voter $voter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voter  $voter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voter $voter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Voter  $voter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voter $voter)
    {
        //
    }
}
