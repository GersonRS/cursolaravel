@extends('app')


@section('content')
<div class="container">
	<h1>Cupons</h1>

	<a href="{{ route('admin.cupoms.create') }}" class="btn btn-default">Novo cupom</a>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>Código</th>
				<th>Valor</th>
				<th>Ação</th>
			</tr>
		</thead>
		<tbody>
			@foreach($cupoms as $cupom)
			<tr>
				<td>{{ $cupom->id }}</td>
				<td>{{ $cupom->code }}</td>
				<td>{{ $cupom->value }}</td>
				<td>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	{!! $cupoms->render() !!}

</div>
@endsection