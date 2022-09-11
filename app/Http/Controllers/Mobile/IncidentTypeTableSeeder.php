<?php

use Illuminate\Database\Seeder;

class IncidentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      App\IncidentType::CREATE([
          'name'=>'Fire',
          'description'=>'Fire description',
          'logo' => config('app.url') . '/incidenttype/firecolor.png'
      ]);
      App\IncidentType::CREATE([
          'name'=>'Crime',
          'description'=>'Police description',
          'logo' => config('app.url') . '/incidenttype/crime.png'
      ]);
      App\IncidentType::CREATE([
          'name'=>'Medical',
          'description'=>'Medical Description',
          'logo' => config('app.url') . '/incidenttype/medicalcolor.png',
      ]);
      App\IncidentType::CREATE([
          'name'=>'General',
          'description'=>'General Description',
          'logo' => config('app.url') . '/incidenttype/natural.png'
      ]);
    }
}
