@extends('app')


@section('content')
<div class="container">
	<h1>Checkout</h1>
	
	    @include('errors._check')

		{!! Form::open(['route'=>'customer.order.store','class'=>'form']) !!}
		<div class="container">
		<div class="form-group">
            <label>Total: </label>
            <p id="total"></p>
            <a href="#" id="btnNewItem" class="btn btn-default">Novo Item</a>
        </div>
		
		<table>
		    <thead>
		        <tr>
		           <th>Produto</th>
		           <th>Quantidade</th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr>
		           <td>
		               <select class="form-control" name="items[0][product_id]">
		                    @foreach($products as $p)
		                        <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} --- {{ $p->price }}</option>
		                    @endforeach
		               </select>
		           </td>
		           <td>
		               {!! Form::text('items[0][amount]',1,['class'=>'form-control']) !!}
		           </td>
		        </tr>
		    </tbody>
		</table>
		</div>
		<div class="form-group">
		{!! Form::submit('Criar Pedido', ['class'=>'btn btn-primary']) !!}
		</div>

		{!! Form::close() !!}

</div>
@endsection

@section('post-script')
   <script type="text/javascript">
      $("#btnNewItem").click(function(){

    	  var row = $('table tbody > tr:last'),
    	           newRow = row.clone(),
    	           length = $("table tbody tr").length;

          newRow.find('td').each(function() {
              var td = $(this),
              input = td.find('input,select'),
              name = input.attr('name');

          input.attr('name', name.replace((length - 1)+""));


          });

          newRow.find('input').val(1);
          newRow.insertAfter(row);
          
      });

      $(document.body).on('click','select',function(){
          calculateTotal();
      });

      $('input[name*=amount]').blur(function(){
          calculateTotal();
      });

      function calculateTotal(){
          var total = 0,
              trLen = $('table tbody tr').length,
              tr = null, prive, qtd;

          for (var i = 0; i < trLen; i++) {
			tr = $('table tbody tr').eq(i);
			price = tr.find(':selected').data('price');
			qtd = tr.find('input').val();
			total+= price * qtd;
		}
  		$('#total').html(total);
      }
   
   </script>
@endsection