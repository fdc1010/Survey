<?php

namespace App\Http\Controllers\Admin;

use App\cr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{
	protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }
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
	
	public function stats(Request $request)
    {
		
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
		
		/*$selposition = $request->selposition;
		$selcandidate = $request->selcandidate;
		$selagebracket = $request->selagebracket;
		$selgender = $request->selgender;
		$selcivil = $request->selcivil;
		$selemp = $request->selemp;
		$brgyto = $request->to;
		$chkpositions = $request->position;
		$chkagebracket= $request->agebracket;
		$chkcandidate = $request->candidate;
		$chkgender=$request->gender;
		$chkcivilstatus=$request->civilstatus;
		$chkempstatus=$request->empstatus;*/
		$rdata = $request->except(['q','_token']);
        return view('backpack::dashboard', [$this->data,$rdata]);
    }
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy(cr $cr)
    {
        //
    }
	public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }
}
