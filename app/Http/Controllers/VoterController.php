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
  		Voter::chunk(400, function ($results){
          foreach ($results as $voter) {
      			$explodefm = explode(" ",$voter->first_name);
      			echo $voter->first_name . " : ";
      			if(count($explodefm)>1){
      				$curvoter = Voter::find($voter->id);
      				$curvoter->middle_name = $explodefm[count($explodefm)-1];
      				$curvoter->save();
      			}
      		}
        });
	}
  public function removeMNfromLN(){
    Voter::chunk(400, function ($results){
        foreach ($results as $voter) {
            $firstname = str_replace($voter->middle_name, '', $voter->first_name);
            if(!empty($firstname)){
                echo "ID# " . $voter->id . " " . $firstname ." <br>";
                $updateData = Voter::where('id',$voter->id)
                                  ->update(['first_name' => $firstname]);
            }else {
                echo "ID# " . $voter->id . " BLANK first name";
            }
            //$curvoter->save();
        }
      });
  }
  public function updatedfnvoters(Request $request){
        //validate the xls file
        // $this->validate($request, array(
        //     'filevoters'      => 'required'
        // ));
        try{
        $index=$request->index;
        if($request->hasFile('fileupdatevoters')){
            $extension = File::extension($request->file('fileupdatevoters')->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                $path = $request->file('fileupdatevoters')->getRealPath();
                $data = [];
                $messages['messages'] = [];
                $ok = true;
                Excel::filter('chunk')->load($path)->chunk(400, function ($results) use (&$data,&$index,&$messages,&$ok) {
                    foreach ($results as $key => $value) {
                              //$update = [
                                        //'precinct_id' => $value->prec_id,
                                        //'seq_num' => $value->seq_num,
                                        // 'status_id' => $value->status,
                                        //'barangay_id' => $value->brgy_id,
                                //        'last_name' => $value->lastname,
                                //        'first_name' => $value->firstname,
                                        // 'middle_name' => $value->middlename,
                                //        'address' => $value->address
                                //        ];

                              $updateData = Voter::where('seq_num',$value->seq_num)
                                                ->where('precinct_id',$value->prec_id)
                                                ->where('barangay_id',$value->brgy_id)
                                                ->update(['first_name' => $value->firstname]);
                              if (!$updateData) {
                                  $ok = false;
                              }
                              $index++;
                        }

                    }, $shouldQueue = false);
                    if($ok){
                      $messages = array('messages' => array("Your Data has successfully uploaded"));
                      return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
                    }else{
                      $messages = array('messages' => array("Error inserting the data.."));
                      return response()->json(['success'=>false,'messages'=>$messages,'index'=>$index],401);
                    }
              }else {
                  // Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                  // return back();
                  $messages = array('messages' => array("File is a ".$extension." file.!! Please upload a valid xls/csv file..!!"));
                  return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
              }
        }
      }catch(\Exception $e){
        info($e);
      }
        $messages = array('messages' => array("Error uploading the data..","Has File: ".$request->hasFile('filevoters')));
        return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],401);
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
                $data = [];
                $messages['messages'] = [];
                $ok = true;
                Excel::filter('chunk')->load($path)->chunk(400, function ($results) use (&$data,&$index,&$messages,&$ok) {
                    foreach ($results as $key => $value) {
                              $insert = [
                                        'precinct_id' => $value->prec_id,
                                        'seq_num' => $value->seq_num,
                                        // 'status_id' => $value->status,
                                        'barangay_id' => $value->brgy_id,
                                        'last_name' => $value->lastname,
                                        'first_name' => $value->firstname,
                                        // 'middle_name' => $value->middlename,
                                        'address' => $value->address
                                        ];
                              info($value->lastname . ", " . $value->firstname)
                              $insertData = DB::table('voters')->insert($insert);
                              if (!$insertData) {
                                  $ok = false;
                              }
                              $index++;
                        }

                    }, $shouldQueue = false);
                    if($ok){
                      $messages = array('messages' => array("Your Data has successfully uploaded"));
                      return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
                    }else{
                      $messages = array('messages' => array("Error inserting the data.."));
                      return response()->json(['success'=>false,'messages'=>$messages,'index'=>$index],401);
                    }
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
      $index=$request->index;
      if($request->hasFile('filevoters')){
          $extension = File::extension($request->file('filevoters')->getClientOriginalName());
          if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
              $path = $request->file('filevoters')->getRealPath();
              $data = [];
              $messages['messages'] = [];
              $ok = true;
              Excel::filter('chunk')->load($path)->chunk(400, function ($results) use (&$data,&$index,&$messages,&$ok) {
                  foreach ($results as $key => $value) {
                            $insert = [
                                      'precinct_id' => $value->prec_id,
                                      'seq_num' => $value->seq_num,
                                      // 'status_id' => $value->status,
                                      'barangay_id' => $value->brgy_id,
                                      'last_name' => $value->lastname,
                                      'first_name' => $value->firstname,
                                      // 'middle_name' => $value->middlename,
                                      'address' => $value->address
                                      ];

                            $insertData = DB::table('voters')->insert($insert);
                            if (!$insertData) {
                                $ok = false;
                            }
                            $index++;
                      }

                  }, $shouldQueue = false);
                  if($ok){
                    $messages = array('messages' => array("Your Data has successfully uploaded"));
                    return response()->json(['success'=>true,'messages'=>$messages,'index'=>$index],200);
                  }else{
                    $messages = array('messages' => array("Error inserting the data.."));
                    return response()->json(['success'=>false,'messages'=>$messages,'index'=>$index],401);
                  }
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
                                'id' => $value->precinct,
                                'precinct_number' => $value->prec_code,
                                'barangay_id' => $value->brgy_id
                            ];
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
