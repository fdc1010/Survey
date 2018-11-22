<?php

namespace App\Http\Controllers;

use App\Models\OccupancyStatus;
use Illuminate\Http\Request;

class OccupancyStatusController extends Controller
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
	public function getOccupancyStatus(Request $request){
		$occstatus = OccupancyStatus::all();
		
		return response()->json($occstatus);
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
     * @param  \App\OccupancyStatus  $occupancyStatus
     * @return \Illuminate\Http\Response
     */
    public function show(OccupancyStatus $occupancyStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OccupancyStatus  $occupancyStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(OccupancyStatus $occupancyStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OccupancyStatus  $occupancyStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OccupancyStatus $occupancyStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OccupancyStatus  $occupancyStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(OccupancyStatus $occupancyStatus)
    {
        //
    }
}
