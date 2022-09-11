<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;
use Illuminate\Http\Request;

class EmploymentStatusController extends Controller
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
	public function getEmploymentStatus(Request $request){
		$empstatus = EmploymentStatus::select(['name','description'])->get();
		
		return response()->json($empstatus);
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
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function show(EmploymentStatus $employmentStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(EmploymentStatus $employmentStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmploymentStatus $employmentStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmploymentStatus $employmentStatus)
    {
        //
    }
}
