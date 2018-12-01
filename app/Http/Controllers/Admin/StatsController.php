<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use PDF;

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
		
		$rdata = $request->except(['q','_token']);
        return view('backpack::dashboard', [$this->data,'rdata'=>$rdata]);
    }
	public function printsurvey(Request $request){
		$this->data['title'] = trans('backpack::base.dashboard'); // set the page title
		$rdata = $request->except(['q','_token']);
		//dd($request);
		$pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('backpack::printSurvey', [$this->data,'rdata'=>$rdata])
            ->setPaper('Legal')
            ->setOption('margin-top', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 15)
			//->setOption('orientation', 'Landscape')
			//->setOption('javascript-delay', 1500);	
			//->setOption('zoom', 1.33)
			//->setOption('enable-smart-shrinking',true);
			//->setOption('enable-smart-width',true)
			//->setOption('page-width', '21.59cm')
			//->setOption('page-height', '33.02cm')
            //->setOption('header-html', route('pdf.headerin'))
            //->setOption('footer-html', route('pdf.footerin'));
		return $pdf->inline();
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
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
	public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }
}
