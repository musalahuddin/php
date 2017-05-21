@extends('layouts.default')


{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			{{{ $title }}}
		</h3>
	</div>
	<table id="dealers" class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="col-md-2">ID#</th>
				<th class="col-md-2">Account</th>
				<th class="col-md-2">No. Of Users</th>
				<th class="col-md-2">Notes</th>
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
				oTable = $('#dealers').dataTable( {
				"sScrollY": "360px",
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('client/'.$client_id.'/dealers/data'.$uri) }}",
		        "fnDrawCallback": function ( oSettings ) {
	           		//$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
	     		}
			});
		});
	</script>
@stop
