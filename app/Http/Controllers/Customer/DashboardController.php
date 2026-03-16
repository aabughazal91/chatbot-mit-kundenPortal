<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's inquiries with relationships
        $inquiries = Inquiry::with(['items.priceModule', 'clickUpMapping'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user statistics
        $stats = [
            'total_inquiries' => $inquiries->count(),
            'pending_inquiries' => $inquiries->where('status', 'pending')->count(),
            'confirmed_inquiries' => $inquiries->where('status', 'confirmed')->count(),
            'total_spent' => $inquiries->where('status', 'confirmed')->sum('total_estimated_price'),
        ];

        // Get active project (first inquiry with ClickUp mapping)
        $activeInquiry = $inquiries->whereNotNull('clickUpMapping')->first();

        return view('customer.dashboard', compact('inquiries', 'stats', 'activeInquiry'));
    }
}
