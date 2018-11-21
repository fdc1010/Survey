<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\Voter;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VoterRequest as StoreRequest;
use App\Http\Requests\VoterRequest as UpdateRequest;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;

/**
 * Class VoterCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VoterCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Voter');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/voter');
        $this->crud->setEntityNameStrings('voter', 'voters');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['precinct_id','profilepic','middle_name','address','age','contact','birth_date','birth_place','status_id']);
	
		$this->crud->addColumn([
            'name' => 'precinct_id',			
            'label' => 'Precinct',
            'type' => 'model_function',
			'function_name' => 'getPrecinct'
	    ])->makeFirstColumn();
		$this->crud->addColumn([
            'name' => 'status_id',			
            'label' => 'Status',
            'type' => 'model_function',
			'function_name' => 'getStatusName'
	    ]);
		$this->crud->addColumn([
            'label' => "Precint",
			'type' => 'select',
			'name' => 'precint_id', // the relationship name in your Model
			'entity' => 'precinct', // the relationship name in your Model
			'attribute' => 'precint_info', // attribute on Article that is shown to admin
			'model' => "App\Models\Precinct" // on create&update, do you need to add/delete pivot table entries?
		]);
		$this->crud->addColumn([   // CustomHTML
			'label' => "Profile Image",
			'name' => "profilepic",
			'type' => 'image',
			'width' => '50px',
			'height' => '50px',
		])->beforeColumn('first_name');
		$this->crud->addColumn([
            'name' => 'barangay',			
            'label' => 'Barangay',
            'type' => 'model_function',
			'function_name' => 'getVoterBarangay',
			'fake' => true
	    ]);
		$this->crud->addField([
			'label' => "Precinct",
			'type' => 'select2',
			'name' => 'precinct_id', // the relationship name in your Model
			'entity' => 'precinct', // the relationship name in your Model
			'attribute' => 'precinct_info', // attribute on Article that is shown to admin
			'model' => "App\Models\Precinct", // on create&update, do you need to add/delete pivot table entries?
			//'attribute2' => 'name', // attribute on Article that is shown to admin
			//'entity2' => "barangay"
		]);
		/*$this->crud->addField([
			'label' => "Firstname",
			'type' => 'text',
			'name' => 'first_name'
		]);
		$this->crud->addField([
			'label' => "Lastname",
			'type' => 'text',
			'name' => 'last_name'
		]);*/
		$this->crud->addField([
			'label' => "Gender",
			'type' => 'enum',
			'name' => 'gender'
		])->beforeField('profilepic');
		$this->crud->addField([
			'label' => "Status",
			'type' => 'select',
			'name' => 'status_id', // the relationship name in your Model
			'entity' => 'status', // the relationship name in your Model
			'attribute' => 'status', // attribute on Article that is shown to admin
			'attribute2' => 'status_name',
			'model' => "App\Models\VoterStatus" // on create&update, do you need to add/delete pivot table entries?
		]);
		/*$this->crud->addField([ // base64_image
			'label' => "Profile Image",
			'name' => "profilepic",
			'filename' => null, // set to null if not needed
			'type' => 'base64_image',
			'aspect_ratio' => 1, // set to 0 to allow any aspect ratio
			'crop' => true, // set to true to allow cropping, false to disable
			'src' => 'getImageSource', // null to read straight from DB, otherwise set to model accessor function
			'upload' => true
		]);*/
		$this->crud->addField([ // image
			'label' => "Profile Image",
			'name' => "profilepic",
			'type' => 'image',
			'upload' => true,
			'crop' => true, // set to true to allow cropping, false to disable
			'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
			// 'disk' => 's3_bucket', // in case you need to show images from a different disk
			//'prefix' => config('app.url').'/profilepic/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
		]);
        // add asterisk for fields that are required in VoterRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		
        return $redirect_location;
    }
	public function destroy($id)
	{
		$this->crud->hasAccessOrFail('delete');
		//Voter::where('id',$id)->delete();
		$voter=Voter::find($id);
		$path = public_path('profilepic/');
		$photo=$path.basename($voter->profilepic);		
		File::delete($photo);		
		return $this->crud->delete($id);
	}
}
