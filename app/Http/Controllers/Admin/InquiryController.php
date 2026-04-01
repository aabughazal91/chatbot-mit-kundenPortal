<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\ClickUpMapping;
use App\Models\User; // إضافة موديل المستخدم
use Illuminate\Http\Request;
use Illuminate\Support\Str; // لتوليد كلمة سر عشوائية
use App\Mail\WelcomeCustomerMail;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function index()
    {
        // تم إضافة 'user' للـ eager loading لعرض صاحب الطلب في الجدول
        $inquiries = Inquiry::with(['clickUpMapping', 'user'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['items.priceModule', 'clickUpMapping', 'user']);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * ربط الطلب بمستخدم (سواء موجود مسبقاً أو جديد)
     */
    public function linkUser(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'identifier' => 'required|string',
            'name'       => 'nullable|string|max:255'
        ]);

        $identifier = $request->identifier;
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        $user = User::where('email', $identifier)->orWhere('username', $identifier)->first();

        if ($user) {
            $msg = "Bestehender Kunde ({$user->name}) erfolgreich verknüpft!";
        } else {
            // User not found. We can only create a new user if the identifier is an email address.
            if (!$isEmail) {
                return back()->with('error', 'Benutzer wurde nicht gefunden. Um einen neuen Benutzer zu erstellen, geben Sie bitte eine gültige E-Mail-Adresse ein.');
            }

            // توليد كلمة سر عشوائية
            $plainPassword = Str::random(10);

            $user = User::create([
                'name'         => $request->name ?? 'Kunde',
                'email'        => $identifier,
                'username'     => explode('@', $identifier)[0] . rand(10, 99), // اسم مستخدم افتراضي من الإيميل
                'password'     => bcrypt($plainPassword),
                'role'         => 'customer',
                'is_confirmed' => true
            ]);

            // إرسال الإيميل الترحيبي للعميل
            try {
                Mail::to($user->email)->send(new WelcomeCustomerMail($user, $plainPassword));
                $msg = "Neuer Kunde erstellt und verknüpft! Eine Willkommens-E-Mail wurde an {$user->email} gesendet.";
            } catch (\Exception $e) {
                $msg = "Neuer Kunde erstellt und verknüpft! Die Willkommens-E-Mail konnte jedoch nicht gesendet werden.";
            }
        }

        $inquiry->update(['user_id' => $user->id, 'status' => 'confirmed']);

        return back()->with('success', $msg);
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        // منطق إضافي: لا يمكن تأكيد طلب (Confirmed) بدون وجود مستخدم مرتبط
        if ($request->status === 'confirmed' && !$inquiry->user_id) {
            return back()->with('error', 'Bitte verknüpfen Sie zuerst einen Benutzer, bevor Sie den Status auf "Confirmed" setzen.');
        }

        $inquiry->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status erfolgreich aktualisiert.');
    }

    public function updateClickUp(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'clickup_task_id' => 'required|string'
        ]);

        // استخدام updateOrCreate لضمان وجود سجل واحد فقط لكل Inquiry [cite: 46]
        ClickUpMapping::updateOrCreate(
            ['inquiry_id' => $inquiry->id],
            [
                'clickup_task_id' => $request->clickup_task_id,
                'last_synced_at' => null // تصفير وقت المزامنة ليقوم الـ Scheduler بجلب البيانات فوراً
            ]
        );

        return back()->with('success', 'ClickUp Task ID verknüpft.');
    }

    public function updateProjectName(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'quote_number' => 'required|string|max:255',
        ]);

        $inquiry->update([
            'quote_number' => $request->quote_number,
        ]);

        return back()->with('success', 'Projekt Name erfolgreich aktualisiert.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Anfrage erfolgreich gelöscht.');
    }
}
