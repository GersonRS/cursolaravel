@extends('app')


@section('content')
<div class="container">
	<h1>Nova categoria</h1>
	
	@include('errors._check')
	
	{!! Form::open(['route'=>'admin.categories.store']) !!}

	@include('admin.categories._form')

	<div class="form-group">
		{!! Form::submit('Criar categoria', ['class'=>'btn btn-primary']) !!}
	</div>	

	{!! Form::close() !!}


</div>
@endsection