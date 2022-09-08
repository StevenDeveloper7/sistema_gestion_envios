<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;



class EnvioController extends Controller
{


    public function search(Request $request)
     {
        $date= $request->date;
        $campo = $request->campo;
        if ($date != null) {
            $envios = Envio::where('envios.'.$campo.'', 'like', '%'.$date.'%')
                    ->join('productos', 'productos.id', '=', 'envios.id_producto')
                    ->select("envios.*","productos.nombre_p")
                    ->paginate(10);
        }else {
            $envios = Envio::join('productos', 'productos.id', '=', 'envios.id_producto')
                    ->select("envios.*","productos.nombre_p")
                    ->paginate(10);
        }

        return view('envio.index')->with('envios', $envios);


        

     }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $envios = Envio::join('productos', 'productos.id', '=', 'envios.id_producto')
                    ->select("envios.*","productos.nombre_p")
                    ->paginate(10);
        return view('envio.index')->with('envios', $envios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $client = new Client();
        $url = "https://sandbox.entregalo.co/api/cities";

        $params = [
            //If you have any Params Pass here
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'token' => '1CSqDSlfLoCkV5cztbP1tegRwfSU2UiHkFQIiilIzjtPkWrUaVaR02fRdURtkbvYXuo0O1rGrxTbtIDg'
        ];

        $response = $client->request('POST', $url, [
            // 'json' => $params,
            'headers' => $headers,
            'verify'  => false,
        ]);

        $ciudades = json_decode($response->getBody());
        $ciudades = $ciudades->data->Ciudades;

        $productos = Producto::all();
        return view('envio.form', compact('ciudades'))->with('productos', $productos);
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|max:6',
            'id_producto' => 'required',
            'ciudad_origen' => 'required',
            'ciudad_destino' => 'required',
            'peso' => 'required',
            'tipo_envio' => 'required'
        ]);

        $action = $request->action;

       
        $id_producto = $request->id_producto;
        $producto = Producto::where('id', '=', $id_producto)->first();
        $valor_producto = $producto['valor'];


        $cityOrigin = $request->ciudad_origen;
        $cityDestination = $request->ciudad_destino;

        $cityO = strval($cityOrigin);
        $cityD = strval($cityDestination);
        
        $declaredValue = $valor_producto;
        $amountToReceive = 0;
        $weight = $request->peso;
        $shippingType = $request->tipo_envio;
        $high = $request->alto;
        $width = $request->largo;
        $long = $request->ancho;

      

        //PETICION HTTP con curl

        if ($shippingType == "paquete") {
            $data = array(
            'cityOrigin' => $cityO,
            'cityDestination' => $cityD,
            'declaredValue' => $declaredValue,
            'amountToReceive' => $amountToReceive,
            'weight' => $weight,
            'shippingType' => $shippingType
    
            );
        }else {
            $data = array(
            'cityOrigin' => $cityO,
            'cityDestination' =>$cityD,
            'declaredValue' => $declaredValue,
            'amountToReceive' => $amountToReceive,
            'weight' => $weight,
            'shippingType' => $shippingType,
            'high' => $high,
            'width' => $width,
            'long' => $long
                
            );
        }

        
            
          $post_data = json_encode($data);
            
          // Prepare new cURL resource
          $crl = curl_init('https://sandbox.entregalo.co/api/shippings/quotation');
          curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($crl, CURLINFO_HEADER_OUT, true);
          curl_setopt($crl, CURLOPT_POST, true);
          curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
            
          // Set HTTP Header for POST request 
          curl_setopt($crl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'token: 1CSqDSlfLoCkV5cztbP1tegRwfSU2UiHkFQIiilIzjtPkWrUaVaR02fRdURtkbvYXuo0O1rGrxTbtIDg')
        );
            
          // Submit the POST request
          $result = curl_exec($crl);
           
          curl_close($crl);

          $envio = json_decode($result,true);

          if ($envio == null) {
            Session::flash('mensaje', 'Por favor elija otras ciudades, en las que eligio no es posible realizar el envio');
            return redirect()->route('envio.create');
          }

          $data = $envio['data'];

          $data = $data[0];
          $precio = $data['price'];
          $transportadora = $data['logistic'];

          if ($action == 1) {

            Session::flash('mensaje', 'Cotizacion de envio: El precio total del envio es $'.$precio.' y Sera transportado por la Transportadora '.$transportadora.'');
            return redirect()->route('envio.create');

        }else {
            $envio = new Envio;
            $envio->id_producto = $id_producto;
            $envio->ciudad_origen = $cityOrigin;
            $envio->ciudad_destino = $cityDestination;
            $envio->peso = $weight;
            $envio->tipo_envio = $shippingType;
            $envio->alto = $request->alto;
            $envio->largo = $request->largo;
            $envio->ancho = $request->ancho;
            $envio->precio = $precio;
            $envio->transportadora = $transportadora;
            $envio->save();

            Session::flash('mensaje', 'Registro de envio realizado con Exito');
            return redirect()->route('envio.index');
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\Response
     */
    public function show(Envio $envio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\Response
     */
    public function edit(Envio $envio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Envio $envio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Envio  $envio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Envio $envio)
    {
        //
    }
}
