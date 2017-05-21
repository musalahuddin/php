@extends('layouts.default')


{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			{{{ $title }}}
		</h3>
	</div>
	<table id="users" class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="col-md-2">ID#</th>
				<th class="col-md-2">First Name</th>
				<th class="col-md-2">Last Name</th>
				<th class="col-md-2">Email</th>
				<th class="col-md-2">No. Of Sessions</th>
				<th class="col-md-2">User Type</th>
			</tr>
		</thead>
		<tbody>
	</table>
@stop

{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
				oTable = $('#users').dataTable( {
				"sScrollY": "360px",
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('client/'.$client_id.'/dealer/'.$dealer_id.'/users/data'.$uri) }}",
		        "fnDrawCallback": function ( oSettings ) {
	           		//$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
	     		}
			});
		});
	</script>
@stop
