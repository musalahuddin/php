@extends('layouts.default')

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			{{{ $title }}}
		</h3>
	</div>
	<table id="accounts" class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="col-md-2">Client/Account</th>
				<th class="col-md-2"></th>
				<th class="col-md-2"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($accounts as $account)
				<tr>
				    <td class="col-md-2">{{{ $account->ClientName.'/'.$account->DealerName }}}</td>
				    <td class="col-md-2"><a href=''>Launch Planner</a></td>
				    
				    @if($account->UserHierarchyId == '3' || $account->UserHierarchyId == '1'){{-- client and AV act as clients --}}
				    	<td class="col-md-2">
				    		<ul class="list-unstyled">
					    		<li class="dropdown"> 
					    			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					    				Client <span class="caret"></span>
					    			</a>
					    			<ul class="dropdown-menu">
	    								<li><a href="{{{ URL::to('client/'.$account->ClientId) }}}">Admin</a></li>
	    								<li class="divider"></li>
	    								<li><a href="#">Report</a></li>
	    							</ul>
					    		</li>
				    		</ul>
				    	</td>
				    @elseif($account->UserHierarchyId == '4')
				    	@if($account->UserTypeId == '1' || $account->UserTypeId == '2')
				    		<td class="col-md-2">
				    			<ul class="list-unstyled">
						    		<li class="dropdown"> 
						    			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						    				Account <span class="caret"></span>
						    			</a>
						    			<ul class="dropdown-menu">
		    								<li><a href="{{{ URL::to('client/'.$account->ClientId.'/dealer/'.$account->DealerId) }}}">Admin</a></li>
		    								<li class="divider"></li>
		    								<li><a href="#">Report</a></li>
		    							</ul>
						    		</li>
					    		</ul>
				    		</td>
				    	@else
				    		<td class="col-md-2">
				    			<ul class="list-unstyled">
						    		<li class="dropdown"> 
						    			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						    				User <span class="caret"></span>
						    			</a>
						    			<ul class="dropdown-menu">
		    								<li><a href="{{{ URL::to('client/'.$account->ClientId.'/dealer/'.$account->DealerId.'/user/'.$account->UserId) }}}">Admin</a></li>
		    							</ul>
						    		</li>
					    		</ul>
				    		</td>
				    	@endif
				    @else
				    	<td class="col-md-2"></td>
				    @endif
				</tr>
			@endforeach
		</tbody>
	</table>
@stop