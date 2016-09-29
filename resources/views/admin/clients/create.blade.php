@extends('app')


@section('content')
<div class="container">
	<h1>Novo Cliente</h1>
	
	@include('errors._check')
	
	{!! Form::open(['route'=>'admin.clients.store']) !!}

	@include('admin.clients._form')

	<div class="form-group">
		{!! Form::submit('Criar Cliente', ['class'=>'btn btn-primary']) !!}
	</div>	

	{!! Form::close() !!}


</div>
@endsection