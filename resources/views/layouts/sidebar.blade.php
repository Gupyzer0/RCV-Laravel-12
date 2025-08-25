<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-text mx-3 ">
            <img src="{{asset('images/logo.jpg')}}" style="width:100%">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    {{-- Usuario normal --}}
    @hasrole('usuario')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index.policies') }}">
                <i class="fas fa-sticky-note"></i>
                <span>Pólizas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index-vencidas.policies') }}">
                <i class="fas fa-sticky-note"></i>
                <span>Pólizas Vencidas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index.prices') }}">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Precios</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index.show.notpaid') }}">
                <i class="fas fa-check-square"></i>
                <span>Polizas por Pagar</span>
            </a>
        </li>
    @endhasrole
    {{-- /Usuario normal --}}

    {{-- Moderador --}}
    @hasrole('moderador')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('index.policies.mod') }}">
                <i class="fas fa-sticky-note"></i>
                <span>Pólizas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('index.prices.mod') }}">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Precios</span>
            </a>
        </li>
        
        {{-- TODO: permisos para evitar esto @if(in_array(auth()->user()->type, [2, 6, 9, 0]) || auth()->user()->id == 177) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('index.offices.mod') }}">
                <i class="fas fa-building"></i>
                <span>Oficinas</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('index.users.mod') }}">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('index.notpaid.mod')}}">
                <i class="fas fa-money-check-alt"></i>
                <span>Consultas de pago</span>
            </a>
        </li>
        
          
        <li class="nav-item">
            <a class="nav-link" href="{{ route('mod.general.payments')}}">
                <i class="fas fa-money-check-alt"></i>
                <span>Reporte General</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('index.inventory.mod') }}">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Inventario</span>
            </a>
        </li>
    @endhasrole
    {{-- /Moderador --}}

    {{-- Administrador --}}
    @hasrole('administrador')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGeneral"
                aria-expanded="true" aria-controls="collapseGeneral">
                <i class="fas fa-fw fa-cog"></i>
                <span>Gestión General</span>
            </a>
            <div id="collapseGeneral" class="collapse" aria-labelledby="headingGeneral" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Opciones de Gestión:</h6>
                    <a class="collapse-item" href="{{ route('index.policies') }}">Pólizas</a>
                    <a class="collapse-item" href="{{ route('facturacion.index') }}">Facturación</a>
                    <a class="collapse-item" href="{{ route('index.vehicle.classes') }}">Clase de Vehiculos</a>
                    <a class="collapse-item" href="{{ route('index.offices') }}">Oficinas</a>
                    <a class="collapse-item" href="{{ route('index.prices') }}">Precios</a>
                    <a class="collapse-item" href="{{ route('index.inventory') }}">Inventario</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinanceReports"
                aria-expanded="true" aria-controls="collapseFinanceReports">
                <i class="fas fa-fw fa-dollar-sign"></i>
                <span>Finanzas y Reportes</span>
            </a>
            <div id="collapseFinanceReports" class="collapse" aria-labelledby="headingFinanceReports"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Reportes Financieros:</h6>
                    <a class="collapse-item" href="{{ route('index.notpaid')}}">Consultas de pago</a>
                    @if(auth()->user()->id <> 999536 )
                    <a class="collapse-item" href="{{ route('index.finance')}}">Finanzas</a>
                    <a class="collapse-item" href="{{ route('index.finance2')}}">Finanzas New</a>
                    <a class="collapse-item" href="{{ route('general.payments')}}">Reporte General</a>
                    <a class="collapse-item" href="{{ route('index.static') }}">Estadisticas</a>
                    @endif
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#siniestroReports"
                aria-expanded="true" aria-controls="siniestroReports">
                <i class="fas fa-car-crash"></i>
                <span>Siniestros</span>
            </a>
            <div id="siniestroReports" class="collapse" aria-labelledby="headingFinanceReports"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Reportes de Siniestros:</h6>
                    <a class="collapse-item" href="{{ route('index.siniestros')}}">Pagados</a>
                    <a class="collapse-item" href="{{ route('index.siniestros')}}">Por Pagar</a>
                    <a class="collapse-item" href="{{ route('index.siniestros')}}">Rechazados<
                    
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin"
                aria-expanded="true" aria-controls="collapseAdmin">
                <i class="fas fa-fw fa-user-shield"></i>
                <span>Administración</span>
            </a>
            <div id="collapseAdmin" class="collapse" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Gestión de Usuarios:</h6>
                    <a class="collapse-item" href="{{ route('index.users') }}">Usuarios</a>
                    <a class="collapse-item" href="{{ route('admin.activity.log.all') }}">Registro de Actividad</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('map') }}">
                <i class="fas fa-map-marked-alt"></i>
                <span>Mapa</span>
            </a>
        </li>
    @endhasrole
    {{-- /Administrador --}}

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>