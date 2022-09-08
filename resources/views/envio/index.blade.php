@extends('layouts.principal')
@section('content')

<div class="container py-5 ">
    @if (Session::has('mensaje'))
    <div class="alert alert-info my-5">
        {{ Session::get('mensaje')}}
    </div>
         
    @endif
    <div class="card">
        <div class="card-header">
           <h3 class="text-center">Envios Realizados</h3> 
        </div>

    </div>

    <div class="" id="search">
        <form method="POST" action="{{ route('search')}}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <br>
                    <a  href="{{ route('envio.create')}}" class="btn btn-primary">Agregar nuevo envio</a>
                </div>
                <div class="col-md-8"  >
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Selecciona el campo a realizar la busqueda</label>
                            <select  class="form-select" name="campo" id="">
                                <option value="id">Codigo</option>
                                <option value="ciudad_origen">Codigo Ciudad Origen</option>
                                <option value="ciudad_destino">Codigo Ciudad Destino</option>
                                <option value="tipo_envio">Tipo de envio</option>
                                <option value="transportadora">Transportadora</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Digita lo que deseas buscar </label>
                            <div class="input-group">
                                <input type="text" name="date" class="form-control" placeholder="¿Que Buscas?">
                                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    
  

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="bg-primary text-white">
            <th>Codigo</th>
            <th>Producto</th>
            <th>Ciudad Origen</th>
            <th>Ciudad Destino</th>
            <th>Peso</th>
            <th>Tipo</th>
            <th>Precio</th>
            <th>Transportadora</th>
        </thead>
        <tbody>
            @foreach ($envios as $envio)
            <tr>
                <td>{{$envio->id}}</td>
                <td>{{$envio->nombre_p}}</td>
                <td>{{$envio->ciudad_origen}}</td>
                <td>{{$envio->ciudad_destino}}</td>
                <td>{{$envio->peso}}Kg</td>
                <td>{{$envio->tipo_envio}}</td>
                <td>${{$envio->precio}}</td>
                <td>{{$envio->transportadora}}</td>
            </tr> 

            @endforeach
           
        </tbody>
    </table>
 
</div>
</div>

    
@endsection