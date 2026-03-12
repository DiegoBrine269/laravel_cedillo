<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotización</title>

    <style> 

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        html {
            width:100%;
            margin: 40px 60px;
            font-size: 12px;
        }

        img{
            max-width: 100%;
        }

        .d-inline-block {
            display: inline-block;
        }
        
        
        .bold {
            font-weight: bold;
        }
    
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
        } 

        table, tr, td {
            padding-left: 0;
        }




        /* .logo-container {
            width: 25%;
        } */

        .header-text {

            text-align: center;
            font-size: 15px;
            padding-top: 0; 
        }

        .nombre {
            font-size: 23pt;
        }

        .titulo {
            width: 25%;
            color: rgb(126, 126, 126) ;
            font-size: 30px;
            text-align: right
        }

        .datos {
            width: 100%;
        }

        .fecha {
            width: 100%;
        }

        .fiscales, p {
            margin-top: 0px;
            margin-bottom: 2px;
        }

        .cotizacion {
            padding: 0;
            vertical-align:top;
        }

        .lista td, .lista th {
            border: 1px solid rgb(104, 103, 103);
            border-collapse: collapse;
            padding: 4px 6px;
            height: 15px;
        }

        /* Alternar color */
        .lista tr:nth-child(even) {
            background-color: #b0cfe09c;
        }

        .encabezado-azul {
            background-color: #16365C;
            color: white;
            font-weight: bold;
        }

        .encabezado-rojo {
            background-color: #FF0000;
            color: white;
            font-weight: bold;
        }

        .border {
            border: 1px solid black;
        }

        .no-border {
            border: none!important;
        }

        .no-bg {
            background-color: transparent;
        }

    </style>
</head>
<body>
    <header>
        <table>
            <tr>
                <td>
                    <div class="header-text">
                        <p class="nombre bold ">{{ $businessProfile->business_name }}</p>
                    </div>

                </td>
                <td style="width: 32%; padding:20px">
                    <img class="logo" src="{{ public_path('images/logo.jpeg') }}" alt="Logotipo de empresa">
                </td>

            </tr>
            <tr>
                <td>
                    <h2 class="">Cotización</h2>
                </td>
                <td>
                    Fecha: {{$date}}
                </td>
            </tr>
            <tr>
                <td>
                    <p><span class="bold" style="font-size: 10pt">Número de proveedor: 4413887</span></p>
                    <p style="width: 25em">{{ $businessProfile->address }}</p>
                    <p>Tel. {{ $businessProfile->phone }}</p>
                    <p>{{ $businessProfile->email }}</p>
                </td>
                <td>
                    <table class="lista" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th class="no-bg" colspan="2" class="encabezado-rojo">CLIENTE</th>
                            </tr>
                            <tr>
                                <td class="no-bg">Contacto</td>
                                <td class="no-bg">{{ $responsible->name }}</td>
                            </tr>
                            <tr>
                                <td class="no-bg">Empresa</td>
                                <td class="no-bg">BIMBO, SA de CV</td>   
                            </tr>
                            <tr>
                                <td class="no-bg">Centro</td>
                                <td class="no-bg">{{ $invoice->centre->name }}</td>    
                            </tr>
                        </thead>
                    </table>
                </td>
            </tr>
        </table>
    </header>



            

    <main>

        <br>
    
        <table class="lista" cellspacing="0" cellpadding="0">
            <tr>
                <th class="encabezado-azul">Concepto</th>
                <th class="encabezado-azul">Cantidad</th>
                <th class="encabezado-azul">P.U</th>
                <th class="encabezado-azul">Importe</th>
            </tr>


            @php
                $grandTotal = 0;
                $totalRows = 0;
            @endphp


            @if($invoice->rows->count() > 0)
                @foreach ($invoice->rows as $row)
                    <tr>
                        <td >
                            {{ $row->concept }} 
                        </td>
                        <td class="text-center" style="min-width: 70px">{{ $row->quantity }}</td>
                        <td class="text-right" style="min-width: 70px"><span class="text-left">$</span> {{ number_format($row->price, 2) }}</td>
                        <td class="text-right" style="min-width: 70px"><span class="text-left">$</span> {{ number_format($row->total, 2) }}</td>
                    </tr>

                    @php
                        $grandTotal += $row->total; 
                    @endphp
                @endforeach
        
            {{-- Si no es custom, muestra los vehículos agrupados por proyecto y tipo --}}
            @else
                @foreach ($projects as $project)                
                    @foreach ($project->vehicles_grouped_by_price as $price => $grouped_by_price)

                        @foreach ($grouped_by_price as $data)
                            @php
                                $grouped_vehicles_by_type = $data['group'];
                                $type = $data['type'];
                                // $grouped_vehicles = $data['group']; // Obtén el grupo de vehículos
                                $totalForGroup = $grouped_vehicles_by_type->sum('price'); // Calcula el total del grupo
                                $grandTotal += $totalForGroup; 

                                $totalRows++;
                            @endphp
                            <tr>
                                <td >
                                    {{ $project->service . " (" . $type ."):" }} 
                                    {{ implode(', ', $grouped_vehicles_by_type->pluck('eco')->toArray()) }}
                                </td>
                                <td class="text-center" style="min-width: 70px">{{ count( $grouped_vehicles_by_type) }}</td>
                                <td class="text-right" style="min-width: 70px"><span class="text-left">$</span> {{ number_format($price,2) }}</td>
                                <td class="text-right" style="min-width: 70px"><span class="text-left">$</span> {{ number_format($totalForGroup, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            @endif



                {{-- Acompletar para que sean mínimo 10 filas --}}
                @for($i = $totalRows; $i < 10; $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor

                <tr>
                    <td class="no-border no-bg"></td>
                    <td class="no-border no-bg"></td>
                    <td class="encabezado-azul">SUBTOTAL</td>                    
                    <td class="text-right encabezado-azul"><span class="text-left">$</span> {{ number_format($grandTotal, 2)}} </td>
                </tr>
                <tr>
                    <td class="no-border no-bg"></td>
                    <td class="no-border no-bg"></td>
                    <td class="encabezado-azul">IVA</td>                    
                    <td class="text-right encabezado-azul"><span class="text-left">$</span> {{ number_format($grandTotal*0.16, 2)}} </td>
                </tr>
                <tr>
                    <td class="no-border no-bg"></td>
                    <td class="no-border no-bg"></td>
                    <td class="encabezado-azul">TOTAL</td>                    
                    <td class="text-right encabezado-azul"><span class="text-left">$</span> {{ number_format($grandTotal*1.16, 2)}} </td>
                </tr>
        </table>
    </main>

    <br>

    <footer>
        <p class="bold">Términos</p>
        <ol>
            <li>Este documento es informativo y no tiene ningún valor fiscal</li>
            <li>Precios en Moneda Nacional.</li>
            <li>Esta cotización tiene vigencia de 15 días hábiles a partir de la fecha de expedición</li>
            <li>Sus datos personales están seguros y se manejan de acuerdo al aviso de privacidad.</li>
        </ol>
    </footer>

</body>
</html>