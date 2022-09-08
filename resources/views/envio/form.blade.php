@extends('layouts.principal')
@section('content')

<div class="container py-5">
    <div class="card">
        <div class="card-header">
           <h3 class="text-center">Formulario de registro de Envios</h3> 
        </div>
        <div class="card-body">
            @if (Session::has('mensaje'))
    <div class="alert alert-info my-5">
        {{ Session::get('mensaje')}}
    </div>
         
    @endif
        </div>
    </div>


    <form action="{{ route('envio.store')}}" method="post">
        @csrf

        <div class="col-md-4">
            <label for="action" class="form-label">Eliga la opcion de la accion  que desea realizar</label>
            <select class="form-select" name="action" aria-label="Default select example">
                <option value="1">Cootizar Envio</option>
                <option value="2">Guardar Envio</option>
              </select>
        </div>
    <div class="row">
        
        <div class="col-md-3">
            <label for="producto" class="form-label">Producto</label>
            <select class="form-select" name="id_producto" aria-label="Default select example">
                @foreach($productos as $producto)
                <option value=" {{$producto->id}} "> {{$producto->nombre_p}} </option>
                @endforeach
              </select>
        </div>
        <div class="col-md-3">
            <label for="ciudad_origen" class="form-label">Ciudad Origen</label>
            <select class="form-select" name="ciudad_origen" aria-label="Default select example">
                @foreach($ciudades as $ciudad)
                <option value="{{$ciudad->code}}"> {{$ciudad->city}} </option>
                @endforeach
              </select>
        </div>
        <div class="col-md-3">
            <label for="ciudad_destino" class="form-label">Ciudad destino</label>
            <select class="form-select" name="ciudad_destino" aria-label="Default select example">
                @foreach($ciudades as $ciudad)
                <option value="{{$ciudad->code}}"> {{$ciudad->city}} </option>
                @endforeach
              </select>
        </div>
        <div class="col-md-3">
            <label for="peso" class="form-label">Peso en Kg</label>
            <input type="number" name="peso" class="form-control" value="{{ old('peso') ?? @$producto->peso }}">
            @error('peso')
            <p class="form-text text-danger"> {{ $message }} </p>
            @enderror
        </div>
        
    </div>

    <div class="row">
        
        <div class="col-md-3">
            <label for="tipo_envio" class="form-label">Tipo de Envio</label>
            <select class="form-select" name="tipo_envio" aria-label="Default select example">
                <option value="paquete">Paquete</option>
                <option value="mercancia">Mercancia</option>
              </select>
        </div>
        <div class="col-md-6">
            <label for="alto" class="form-label">Rellene estos campos en Centimetros solo si el tipo de envio es de mercancia</label>
            <div class="row">
                <div class="col-md-4">
                    <input type="number" placeholder="Alto" name="alto" class="form-control" value="{{ old('alto') ?? @$producto->alto }}">
                    @error('alto')
                    <p class="form-text text-danger"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="col-md-4">
                    <input type="number" placeholder="Ancho" name="ancho" class="form-control" value="{{ old('ancho') ?? @$producto->ancho }}">
                    @error('ancho')
                    <p class="form-text text-danger"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="col-md-4">
                    <input type="number" placeholder="Largo" name="largo" class="form-control" value="{{ old('largo') ?? @$producto->largo }}">
                    @error('largo')
                    <p class="form-text text-danger"> {{ $message }} </p>
                    @enderror
                </div>
            </div>
            

        </div>
        
        
    </div>

    <br>
    <div>

    <button class="btn btn-primary form-control" type="submit">Realizar Envio</button>
    </div>
</form>
</div>
@endsection