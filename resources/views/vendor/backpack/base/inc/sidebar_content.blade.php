<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

<!--<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>-->
<li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/backup') }}'><i class='fa fa-hdd-o'></i> <span>Backups</span></a></li>
<li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}'><i class='fa fa-terminal'></i> <span>Logs</span></a></li>
<!--<li><a href='{{ url(config('backpack.base.route_prefix', 'admin') . '/setting') }}'><i class='fa fa-cog'></i> <span>Settings</span></a></li>
<li><a href="{{ backpack_url('page') }}"><i class="fa fa-file-o"></i> <span>Pages</span></a></li>-->
<!-- Users, Roles Permissions -->
<li class="treeview">
  <a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
    <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
    <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
  </ul>
</li>
<!--<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/menu-item') }}"><i class="fa fa-list"></i> <span>Menu</span></a></li>
<li><a href='{{ backpack_url('tag') }}'><i class='fa fa-tag'></i> <span>Tags</span></a></li>-->
<li class="treeview">
  <a href="#"><i class="fa fa-map-marker"></i> <span>Location</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
  	<li><a href='{{ backpack_url('precinct') }}'><i class='fa fa-list'></i> <span>Precincts</span></a></li>
	<li><a href='{{ backpack_url('barangay') }}'><i class='fa fa-building'></i> <span>Barangays</span></a></li>
    <li><a href='{{ backpack_url('sitio') }}'><i class='fa fa-crosshairs'></i> <span>Area/Sitio</span></a></li>
  </ul>
</li>
<li class="treeview">
  <a href="#"><i class="fa fa-certificate"></i> <span>Voters, Candidates</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
	<li><a href='{{ backpack_url('party') }}'><i class='fa fa-list'></i> <span>Parties</span></a></li>
	<li><a href='{{ backpack_url('positioncandidate') }}'><i class='fa fa-list'></i> <span>Positions</span></a></li>
	<li><a href='{{ backpack_url('voter') }}'><i class='fa fa-users'></i> <span>Voters</span></a></li>
	<li><a href='{{ backpack_url('candidate') }}'><i class='fa fa-users'></i> <span>Candidate</span></a></li>
  </ul>
</li>
<li class="treeview">
  <a href="#"><i class="fa fa-bar-chart"></i> <span>Poll</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
  	<li><a href='{{ backpack_url('survey') }}'><i class='fa fa-users'></i> <span>Surveys</span></a></li>
	<li><a href='{{ backpack_url('surveyorassignment') }}'><i class='fa fa-users'></i> <span>Surveyor Assignment</span></a></li>
	<li><a href='{{ backpack_url('surveydetail') }}'><i class='fa fa-tag'></i> <span>Survey Details</span></a></li>
    <li><a href='{{ backpack_url('tallyvote') }}'><i class='fa fa-bar-chart'></i> <span>Tally</span></a></li>
    <li><a href='{{ backpack_url('tallyothervote') }}'><i class='fa fa-bar-chart'></i> <span>Other Tally</span></a></li>
  	<li class="treeview">
      	<a href="#"><i class="fa fa-comments"></i> <span>Questionnaire</span> <i class="fa fa-angle-left pull-right"></i></a>
      	<ul class="treeview-menu">
    		<li><a href='{{ backpack_url('question') }}'><i class='fa fa-question-circle'></i> <span>Questions</span></a></li>
    		<li><a href='{{ backpack_url('questionoption') }}'><i class='fa fa-info-circle'></i> <span>Question Options</span></a></li>
  		</ul>
    </li>
    <li class="treeview">
      	<a href="#"><i class="fa fa-tag"></i> <span>Tagged Option Tally</span> <i class="fa fa-angle-left pull-right"></i></a>
      	<ul class="treeview-menu">
    		<li><a href='{{ backpack_url('optionproblem') }}'><i class='fa fa-check-square-o'></i> <span>Option Tally Problem</span></a></li>
            <li><a href='{{ backpack_url('optioncandidate') }}'><i class='fa fa-thumbs-o-up'></i> <span>Option Tally Candidate</span></a></li>
  		</ul>
    </li>
  </ul>
</li>
<li class="treeview">
  <a href="#"><i class="fa fa-cog"></i> <span>Demographics</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
	<li><a href='{{ backpack_url('voterstatus') }}'><i class='fa fa-users'></i> <span>Voter Status</span></a></li>
    <li><a href='{{ backpack_url('agebracket') }}'><i class='fa fa-users'></i> <span>Age Bracket</span></a></li>
    <li><a href='{{ backpack_url('employmentstatus') }}'><i class='fa fa-users'></i> <span>Employment Status</span></a></li>
    <li><a href='{{ backpack_url('civilstatus') }}'><i class='fa fa-users'></i> <span>Civil Status</span></a></li>
    <li><a href='{{ backpack_url('occupancystatus') }}'><i class='fa fa-users'></i> <span>Occupancy Status</span></a></li>
    <li><a href='{{ backpack_url('statusdetail') }}'><i class='fa fa-users'></i> <span>Status Details</span></a></li>
	</ul>
</li>