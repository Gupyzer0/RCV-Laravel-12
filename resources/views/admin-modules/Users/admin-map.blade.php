@extends('layouts.app')

@section('module')
{{-- <div class="registro">
    <h1>Registrar enlace de Google Maps</h1>
    <form action="{{ route('extract-coordinates') }}" method="post">
        @csrf
        <label for="google_maps_link">Enlace de Google Maps:</label>
        <input class="form-control my-1" type="text" id="google_maps_link" name="google_maps_link" required>
        <button class="btn" type="submit">Enviar</button>
    </form>
</div> --}}
<div class="showmap">
    <h1>Mapa con Rango de 500 Metros</h1>
    <div id="map"></div>
</div>
<link rel="stylesheet" href="{{ asset('node_modules/leaflet/dist/leaflet.css') }}">
<style>
    #map {
        height: 500px;
        width: 100%;
    }
</style>

<script src="{{ asset('node_modules/leaflet/dist/leaflet.js') }}"></script>
<script>
    var map = L.map('map'); // No establecer el centro y el zoom inicialmente

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    @foreach($locations as $location)
    var circleColor;
        @if($location->type == 1) //Eduardo
            circleColor = 'orange';
        @elseif($location->type == 2) //Oriana
            circleColor = 'red';
        @elseif($location->type == 3) //Liliana
            circleColor = 'blue';
        @elseif($location->type == 4) //Anais
            circleColor = 'purple';
        @else
            circleColor = 'black';
        @endif
    var iconUrl;
    @if($location->type == 1) //Eduardo
        iconUrl = '/images/pinnaranja.png';
    @elseif($location->type == 2) //Oriana
        iconUrl = '/images/pinrojo.png';
    @elseif($location->type == 3) //Liliana
        iconUrl = '/images/pinx.png';
    @elseif($location->type == 4) //Anais
        iconUrl = '/images/pinmorado.png';
    @endif

    var customIcon = L.icon({
        iconUrl: iconUrl,
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        tooltipAnchor: [16, -28],
        shadowSize: [41, 41],
        shadowAnchor: [12, 41]
    });

    L.marker([{{ $location->latitude }}, {{ $location->longitude }}], {icon: customIcon}).addTo(map);

    // Agregar un círculo con un radio de 500 metros alrededor de cada marcador
    L.circle([{{ $location->latitude }}, {{ $location->longitude }}], {
            color: circleColor, // Color del borde del círculo
            fillColor: circleColor, // Color de relleno del círculo
            fillOpacity: 0.5, // Opacidad del relleno del círculo
            radius: 500 // Radio del círculo en metros
        }).addTo(map);
@endforeach



    // Ajustar el centro y el zoom del mapa según las coordenadas de la primera ubicación
    var firstLocation = {!! $locations->first() !!};
    if (firstLocation) {
        map.setView([firstLocation.latitude, firstLocation.longitude], 13);
    }
</script>
@endsection
