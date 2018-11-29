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

<li><a href='{{ backpack_url('question') }}'><i class='fa fa-list'></i> <span>Questions</span></a></li>
<li><a href='{{ backpack_url('precinct') }}'><i class='fa fa-list'></i> <span>Precincts</span></a></li>
<li class="treeview">
  <a href="#"><i class="fa fa-group"></i> <span>Diplomacy</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
	<li><a href='{{ backpack_url('party') }}'><i class='fa fa-list'></i> <span>Parties</span></a></li>
	<li><a href='{{ backpack_url('positioncandidate') }}'><i class='fa fa-list'></i> <span>Positions</span></a></li>
	<li><a href='{{ backpack_url('voter') }}'><i class='fa fa-users'></i> <span>Voters</span></a></li>
	<li><a href='{{ backpack_url('voterstatus') }}'><i class='fa fa-users'></i> <span>Voter Status</span></a></li>
	<li><a href='{{ backpack_url('candidate') }}'><i class='fa fa-users'></i> <span>Candidate</span></a></li>
  </ul>
</li>
<li><a href='{{ backpack_url('barangay') }}'><i class='fa fa-users'></i> <span>Barangays</span></a></li>
<li class="treeview">
  <a href="#"><i class="fa fa-group"></i> <span>Poll/Surveys</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
  	<li><a href='{{ backpack_url('survey') }}'><i class='fa fa-users'></i> <span>Surveys</span></a></li>
	<li><a href='{{ backpack_url('surveyorassignment') }}'><i class='fa fa-users'></i> <span>Surveyor Assignment</span></a></li>
	<li><a href='{{ backpack_url('surveydetail') }}'><i class='fa fa-tag'></i> <span>Survey Details</span></a></li>
  </ul>
</li>