<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lider de Seguros</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.min.css')}}">
    <link href="{{asset('fontawesome/css/nunitouser.css')}}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{asset('images/icon.png')}}" />
    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('Datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    <style>
        label {
            color: black;
        }
    </style>

</head>

<body id="page-top">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        <!-- /Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- navbar -->
                @include('layouts.navbar')
                <!-- /navbar -->

                <!-- Contenido -->
                <div class="container-fluid">
                    @include('partials.messages')
                    @yield('module')
                </div>
                <!-- /Contenido -->
            </div>
            <!-- /Main Content -->
        </div>
        <!-- /Content Wrapper -->
    </div>
    <!-- /Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Seguro que desea salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione "Cerrar Sesión" si desea continuar</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar
                        Sesión</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('js/jquery/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>


    <!-- Page level plugins -->
    <script src="{{asset('js/chart.js/Chart.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                cache: false
            });
            $('#brand').change(function () {
                var brand = $('#brand option:selected').text();
                console.log(brand);
                $.ajax({
                    url: "{{ route('ajax.policy.search.vehicle') }}",
                    type: "GET",
                    data: { brandId: brand },
                    dataType: "Text",
                    success: function (brand) {
                        $('#model').html(brand);
                    }
                })
            });

            $('#estado').change(function () {
                var estado = $(this).val();
                $.ajax({
                    url: "{{ route('ajax.office.search.municipio')  }}",
                    type: "GET",
                    data: { estadoId: estado },
                    dataType: "Text",
                    success: function (estado) {
                        $('#municipio').html(estado);
                    }
                })
            });

            $('#municipio').change(function () {
                var municipio = $(this).val();
                $.ajax({
                    url: "{{ route('ajax.office.search.parroquia')  }}",
                    type: "GET",
                    data: { municipioId: municipio },
                    dataType: "Text",
                    success: function (municipio) {
                        $('#parroquia').html(municipio);
                    }
                })
            });

            $('#price').change(function () {
                var data = $(this).val();
                console.log(data);
                $.ajax({
                    url: "{{ route('ajax.policy.price.view')  }}",
                    type: "GET",
                    data: { priceId: data },
                    dataType: "Text",
                    success: function (data) {
                        $('#quick_view').html(data);
                    }
                })
            });

            $('#vehicle_class').change(function () {
                var vehicle_class = $(this).val();
                $.ajax({
                    url: "{{route('ajax.policy.price.select')}}",
                    type: "GET",
                    data: { priceData: vehicle_class },
                    dataType: "Text",
                    success: function (vehicle_class) {
                        $('#price').html(vehicle_class);
                    }
                })
            });

            $('#model').change(function () {
                let vehiclebrand = $('#brand option:selected').text();
                let vehiclemodel = $('#model option:selected').text();

                $.ajax({
                    url: "{{ route('ajax.policy.search.vehicle.type') }}",
                    type: "GET",
                    data: { brandName: vehiclebrand, modelName: vehiclemodel },
                    dataType: "Text",
                    success: function (vehiclebrand, vehiclemodel) {
                        $('#type').html(vehiclebrand, vehiclemodel);
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('js/functions.js') }}"></script>
    <script type="text/javascript" charset="utf8" src="{{asset('Datatables/datatables.min.js')}}" defer></script>
    <script type="text/javascript" charset="utf8" src="{{asset('Datatables/dataTables.bootstrap4.js')}}" defer></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "aaSorting": [],
                "language": {
                    "url": "{{asset('Datatables/spanish.json')}}"
                }
            });
        });
    </script>

    @yield('scripts')
    @stack('scripts')
    @include('partials.change-pass-modal-user')
</body>

</html>