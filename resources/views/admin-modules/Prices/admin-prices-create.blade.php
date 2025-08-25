@extends('layouts.admin-modules')

@section('module')
<div class="container">
    <div class="card shadow mb-4 text-dark">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Nuevo Plan</h6>
            <a href="{{route('index.prices')}}" class="float-right btn btn-danger text-white">X</a>

        </div>
        <div class="card-body">
            <form action="{{ route('register.price.submit') }}" method="POST" id="price_form">
                @csrf
                <div class="form-row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Descripcion:</label>
                            <input class="form-control @error('description') is-invalid @enderror is-invalid" type="text" name="description" id="description" placeholder="Descricion del precio" value="{{old('description')}}" required autofocus>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="vehicle_class">Clase de vehículo:</label>

                            <select name="vehicle_class" id="vehicle_class" class="form-control @error('vehicle_class') is-invalid @enderror custom-select is-invalid" required>
                                <option value="">- Seleccionar -</option>
                                @foreach($vehicle_classes as $class)
                                <option value="{{$class->id}}">{{$class->class}}</option>
                                @endforeach
                            </select>
                            @error('vehicle_class')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-12">
                        <div class="form-group">

                        <label for="office_id" class="col-form-label text-md-right">Oficina</label>
                                    <select id="office_id" name="office_id" class="form-control custom-select is-invalid">
                                        <option value="">- Seleccionar Oficina -</option>
                                        @foreach($offices as $office)
                                        <option value="{{$office->id}}">{{$office->office_address}}</option>
                                        @endforeach
                                    </select>
                             @error('office_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="form-group row">
                    <div class="col-md-6">
                        <a class="text-success" href="#" data-toggle="modal" data-target="#classModal">Registar Clase de vehículo</a>
                   </div>

               </div>
{{-- INICIO DEL FOMULARIO --}}

                <h3 class="mt-2 text-center">Introduzca el plan</h5>
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="damage_things">Descripción</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="campo" id="campo" placeholder="Despcripcion" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="campoc1">Cobertura</label>
                            <div class="input-group">
                                <input class="form-control number @error('campoc') is-invalid @enderror" type="numeric" name="campoc" id="campoc" placeholder="Insertar Porcentaje" value="{{old('campoc1')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="campop1">Prima</label>
                            <div class="input-group">
                                <input class="form-control number" type="numeric" name="campop" id="campop" placeholder="Insertar Porcentaje" required>
                            </div>
                        </div>
                    </div>
                </div>
{{-- INICIO DE DIV HIDDEN --}}

{{-- primer hidden --}}
                <div class="form-row" id="1" >
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " type="text" name="campo1" id="campo1" placeholder="Despcripcion">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control number @error('campoc1') is-invalid @enderror" type="numeric" name="campoc1" id="campoc1" placeholder="Insertar Porcentaje" value="{{old('campoc1')}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control number" type="numeric" name="campop1" id="campop1" placeholder="Insertar Porcentaje">
                            </div>
                        </div>
                    </div>
                </div>
{{-- fin --}}

{{-- INICIO DIV.OCULTO --}}
                <div class="form-row" id="2" >
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " type="text" name="campo2" id="campo2" placeholder="Despcripcion">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control number" type="numeric" name="campoc2" id="campoc2" placeholder="Insertar Porcentaje">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control number" type="numeric" name="campop2" id="campop2" placeholder="Insertar Porcentaje">
                            </div>
                        </div>
                    </div>
                </div>
{{-- FIN DIV.OCULTO --}}

{{-- INICIO DIV.OCULTO --}}
            <div class="form-row" id="3" >
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control " type="text" name="campo3" id="campo3" placeholder="Despcripcion">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control number" type="numeric" name="campoc3" id="campoc3" placeholder="Insertar Porcentaje">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control number" type="numeric" name="campop3" id="campop3" placeholder="Insertar Porcentaje">
                        </div>
                    </div>
                </div>
            </div>
{{-- FIN DIV.OCULTO --}}

{{-- INICIO DIV.OCULTO --}}
<div class="form-row" id="4" >
    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control " type="text" name="campo4" id="campo4" placeholder="Despcripcion">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control number" type="numeric" name="campoc4" id="campoc4" placeholder="Insertar Porcentaje">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control number" type="numeric" name="campop4" id="campop4" placeholder="Insertar Porcentaje">
            </div>
        </div>
    </div>
</div>
{{-- FIN DIV.OCULTO --}}

{{-- INICIO DIV.OCULTO --}}
<div class="form-row" id="5" >
    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control " type="text" name="campo5" id="campo5" placeholder="Despcripcion">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control number" type="numeric" name="campoc5" id="campoc5" placeholder="Insertar Porcentaje">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control number" type="numeric" name="campop5" id="campop5" placeholder="Insertar Porcentaje">
            </div>
        </div>
    </div>
</div>
{{-- FIN DIV.OCULTO --}}

{{-- INICIO DIV.OCULTO --}}
<div class="form-row" id="6" >
    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control " type="text" name="campo6" id="campo6" placeholder="Despcripcion">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control number" type="numeric" name="campoc6" id="campoc6" placeholder="Insertar Porcentaje">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <input class="form-control number" type="numeric" name="campop6" id="campop6" placeholder="Insertar Porcentaje">
            </div>
        </div>
    </div>
</div>

<div class="form-row" id="7" >
   <div class="col-md-4">
       <div class="form-group">
           <div class="input-group">
               <input class="form-control " type="text" name="campo7" id="campo7" placeholder="Despcripcion">
           </div>
       </div>
   </div>

   <div class="col-md-4">
       <div class="form-group">
           <div class="input-group">
               <input class="form-control number" type="numeric" name="campoc7" id="campoc7" placeholder="Despcripcion">
           </div>
       </div>
   </div>

   <div class="col-md-4">
       <div class="form-group">
           <div class="input-group">
               <input class="form-control number" type="numeric" name="campop7" id="campop7" placeholder="Despcripcion">
           </div>
       </div>
   </div>
</div>
{{-- FIN DIV.OCULTO --}}

{{-- FIN DE TODOS LOS DIV OCULTOS --}}


{{-- Salto de linea --}}

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                             Registrar
                         </button>
                     </div>
                 </div>
             </form>
         </div>
     </div>
 </div>
 @include('partials.register-class-modal')



 <script src="{{asset('js/simple-mask-money.js')}}"></script>
<script type="text/javascript">

</script>
@endsection
