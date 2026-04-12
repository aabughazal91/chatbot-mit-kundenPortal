<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\PriceModule;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_inquiries'     => Inquiry::count(),
            'pending_inquiries'   => Inquiry::where('status', 'offen')->count(),
            'confirmed_inquiries' => Inquiry::where('status', 'bestätigt')->count(),
            'total_customers'     => User::where('role', 'kunde')->count(),
            'total_revenue'       => Inquiry::where('status', 'bestätigt')->sum('geschätzter_gesamtpreis'),
            'active_modules'      => PriceModule::where('ist_aktiv', true)->count(),
        ];

        // Aktuelle Anfragen abrufen
        $recentInquiries = Inquiry::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get monthly revenue data for the last 6 months
        $monthlyRevenue = Inquiry::where('status', 'bestätigt')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(geschätzter_gesamtpreis) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Die beliebtesten Module nach Nutzung anzeigen
        $topModules = DB::table('anfrage_positionen')
            ->join('preis_modules', 'anfrage_positionen.preis_module_id', '=', 'preis_modules.id')
            ->select('preis_modules.bezeichnung_de', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('preis_modules.id', 'preis_modules.bezeichnung_de')
            ->orderBy('usage_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentInquiries', 'monthlyRevenue', 'topModules'));
    }
}
