<?php

namespace App\Http\Controllers;

use App\Models\CivilStatus;
use Illuminate\Http\Request;

class CivilStatusController extends Controller
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
	public function getCivilStatus(Request $request){
		$civilstatus = CivilStatus::select(['name','description'])->get();
		
		return response()->json($civilstatus);
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
     * @param  \App\CivilStatus  $civilStatus
     * @return \Illuminate\Http\Response
     */
    public function show(CivilStatus $civilStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CivilStatus  $civilStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(CivilStatus $civilStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CivilStatus  $civilStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CivilStatus $civilStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CivilStatus  $civilStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(CivilStatus $civilStatus)
    {
        //
    }
}
