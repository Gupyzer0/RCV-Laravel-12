<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Policy;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class FinanceController extends Controller
{

    public function sumTotalPremiumByType(Request $request)
    {
        $userId = auth()->user()->id;
        $type = auth()->user()->type;

        // Lista de IDs permitidos que pueden ver todos los tipos (excepto tipo 0)
        $allowedIds = [999502, 999530];

        $query = Policy::select(
            'policies.type', // Especificar la tabla para 'type'
            \DB::raw('SUM(policies.total_premium) as total'), // Especificar la tabla para 'total_premium'
            \DB::raw('SUM(policies.total_premium * policies.`foreign`) as total_foreign'), // Especificar la tabla para 'foreign'
            \DB::raw('COUNT(policies.id) as count') // Especificar la tabla para contar pólizas
        )
            ->join('users', 'policies.user_id', '=', 'users.id') // Unir con la tabla users
            ->groupBy('policies.type'); // Agrupar por tipo de póliza

        // Aplicar filtro por usuario o mostrar todos (excepto tipo 0)
        // Este filtro se aplica al tipo de póliza.
        if (!in_array($userId, $allowedIds)) {
            $query->where('policies.type', $type); // Especificar tabla para type
        } else {
            $query->where('policies.type', '!=', 0); // Especificar tabla para type
        }

        // Aplicar filtro por rango de fechas si se proporcionan (en created_at de la póliza)
        // Este filtro se mantiene en policies.created_at según la lógica original para el rango de fechas de la solicitud
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('policies.created_at', [$startDate, $endDate]); // Filtrar por created_at de la póliza
        } else {
            // Si no hay rango de fechas, filtrar por los últimos 7 días por defecto (en created_at de la póliza)
            $query->where('policies.created_at', '>=', now()->subDays(7)); // Filtrar por created_at de la póliza
        }

        // *** Nueva condición: Filtrar por created_at menor a '2025-05-16 00:00:00' (en created_at del USUARIO) ***
        $query->where('users.created_at', '<', '2025-05-16 00:00:00'); // Filtrar por created_at del usuario


        $sumAndCountByType = $query->get()->map(function ($item) {
            // La lógica de mapeo se mantiene igual ya que opera sobre los resultados agregados.
            switch ($item->type) {
                case 1:
                    $item->type_name = 'Eduardo Ortega';
                    break;
                case 2:
                    $item->type_name = 'Oriana Silva';
                    break;
                case 3:
                    $item->type_name = 'Jesus Silva';
                    break;
                case 4:
                    $item->type_name = 'Petter Diaz';
                    break;
                case 5:
                    $item->type_name = 'Lexaida';
                    break;
                case 6:
                    $item->type_name = 'Juan Carlos';
                    break;
                case 7:
                    $item->type_name = 'Dorys Ramirez';
                    break;
                case 8:
                    $item->type_name = 'Richard Gomez';
                    break;
                case 9:
                    $item->type_name = 'Jesus Anuel';
                    break;
                default:
                    $item->type_name = 'Otro tipo';
                    break;
            }
            return $item;
        });

        $grandTotalPremium = $sumAndCountByType->sum('total');
        $totalPolicies = $sumAndCountByType->sum('count');

        return view('admin-modules.Finance.index-finance', [
            'sumAndCountByType' => $sumAndCountByType,
            'grandTotalPremium' => $grandTotalPremium,
            'totalPolicies' => $totalPolicies
        ]);
    }

    public function exportSummaryPdf(Request $request)
    {
        $query = Policy::select(
            'type',
            \DB::raw('SUM(total_premium) as total'),
            \DB::raw('SUM(total_premium * `foreign`) as total_foreign'),
            \DB::raw('COUNT(*) as count')
        )
            ->groupBy('type');

        if (auth()->user()->id !== 999502) {
            $query->where('type', auth()->user()->type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } else {
            $query->where('created_at', '>=', now()->subDays(7));
        }

        $sumAndCountByType = $query->get()->map(function ($item) {
            switch ($item->type) {
                case 1:
                    $item->type_name = 'Eduardo';
                    break;
                case 2:
                    $item->type_name = 'Oriana';
                    break;
                case 3:
                    $item->type_name = 'Liliana';
                    break;
                case 4:
                    $item->type_name = 'Anais';
                    break;
                case 5:
                    $item->type_name = 'Lexaida';
                    break;
                case 6:
                    $item->type_name = 'David';
                    break;
                case 7:
                    $item->type_name = 'Juan Carlos';
                    break;
                case 8:
                    $item->type_name = 'Alex';
                    break;
                default:
                    $item->type_name = 'Otro tipo';
                    break;
            }
            return $item;
        });

        $grandTotalPremium = $sumAndCountByType->sum('total');
        $totalPolicies = $sumAndCountByType->sum('count');

        // Datos de fecha
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Generar el PDF
        $pdf = PDF::loadView('admin-modules.Finance.export-policies', [
            'sumAndCountByType' => $sumAndCountByType,
            'grandTotalPremium' => $grandTotalPremium,
            'totalPolicies' => $totalPolicies,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('resumen_polizas.pdf');
    }


    
    public function exportPoliciesPdfByAdmin(Request $request, $type)
    {
        // Obtener los datos del rango de fecha y filtrar las pólizas
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->format('Y-m-d') : null;
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->format('Y-m-d') : null;


        $policies = Policy::where('type', $type)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('user')
            ->get();

        $totalPremiumSum = $policies->sum('total_premium');

        // Datos adicionales para el PDF
        $adminName = $this->getAdminNameByType($type); // Ej. una función que obtiene el nombre por tipo

        $pdf = \PDF::loadView('admin-modules.Finance.export-policies', [
            'policies' => $policies,
            'adminName' => $adminName,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPremiumSum' => $totalPremiumSum, // Pasar el total a la vista
        ]);

        return $pdf->stream('Reporte de Polizas_' . now()->format('Y-m-d') . '.pdf');
    }

    // Ejemplo de función para obtener el nombre del administrador por el tipo
    protected function getAdminNameByType($type)
    {
        $names = [
            1 => 'Eduardo',
            2 => 'Oriana',
            3 => 'Liliana',
            4 => 'Anais',
            5 => 'Lexaida',
            6 => 'David',
            7 => 'Juan Carlos',
            8 => 'Alex',
        ];

        return $names[$type] ?? 'Otro';
    }
}
