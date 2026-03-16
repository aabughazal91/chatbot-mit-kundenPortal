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
            'total_inquiries' => Inquiry::count(),
            'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
            'confirmed_inquiries' => Inquiry::where('status', 'confirmed')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_revenue' => Inquiry::where('status', 'confirmed')->sum('total_estimated_price'),
            'active_modules' => PriceModule::where('is_active', true)->count(),
        ];

        // Get recent inquiries
        $recentInquiries = Inquiry::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get monthly revenue data for the last 6 months
        $monthlyRevenue = Inquiry::where('status', 'confirmed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_estimated_price) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get top modules by usage
        $topModules = DB::table('inquiry_items')
            ->join('price_modules', 'inquiry_items.price_module_id', '=', 'price_modules.id')
            ->select('price_modules.label_de', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('price_modules.id', 'price_modules.label_de')
            ->orderBy('usage_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentInquiries', 'monthlyRevenue', 'topModules'));
    }
}
