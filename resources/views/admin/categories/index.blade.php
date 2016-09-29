@extends('app')


@section('content')
<div class="container">
	<h1>Categorias</h1>

	<a href="{{ route('admin.categories.create') }}" class="btn btn-default">Nova categoria</a>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th>Ação</th>
			</tr>
		</thead>
		<tbody>
			@foreach($categories as $category)
			<tr>
				<td>{{ $category->id }}</td>
				<td>{{ $category->name }}</td>
				<td>
				<a class="btn btn-default btn-sm" href="{{ route('admin.categories.edit',['id'=>$category->id]) }}">
				Editar
				</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	{!! $categories->render() !!}

</div>
@endsection