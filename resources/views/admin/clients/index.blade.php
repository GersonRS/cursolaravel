@extends('app')


@section('content')
<div class="container">
	<h1>Clientes</h1>

	<a href="{{ route('admin.clients.create') }}" class="btn btn-default">Novo Cliente</a>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th>Ação</th>
				<th>Email</th>
				<th>Telefone</th>
				<th>Estado</th>
				<th>Cidade</th>
				<th>CEP</th>
			</tr>
		</thead>
		<tbody>
			@foreach($clients as $client)
			<tr>
				<td>{{ $client->id }}</td>
				<td>{{ $client->user->name }}</td>
				<td>{{ $client->user->email }}</td>
				<td>{{ $client->phone }}</td>
				<td>{{ $client->state }}</td>
				<td>{{ $client->city }}</td>
				<td>{{ $client->zipcode }}</td>
				<td>
				<a class="btn btn-default btn-sm" href="{{ route('admin.clients.edit',['id'=>$client->id]) }}">
				Editar
				</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	{!! $clients->render() !!}

</div>
@endsection