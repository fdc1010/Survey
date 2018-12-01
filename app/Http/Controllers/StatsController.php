<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use PDF;

class StatsController extends Controller
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
			->setOption('orientation', 'Landscape')
			->setOption('javascript-delay', 1500);	
			//->setOption('zoom', 1.33)
			//->setOption('enable-smart-shrinking',true);
			//->setOption('enable-smart-width',true)
			//->setOption('page-width', '21.59cm')
			//->setOption('page-height', '33.02cm')
            //->setOption('header-html', route('pdf.headerin'))
            //->setOption('footer-html', route('pdf.footerin'));
		return $pdf->inline();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
