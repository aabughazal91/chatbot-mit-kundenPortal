<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeCustomerMail;
use App\Models\ClickUpMapping;
use App\Models\Inquiry; // Benutzermodell hinzufügen
use App\Models\User;
use Illuminate\Http\Request; // Um ein zufälliges Passwort zu generieren
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    public function index()
    {
        // 'user' wurde für das Eager Loading hinzugefügt, um den Anforderer in der Tabelle anzuzeigen
        $inquiries = Inquiry::with(['clickUpMapping', 'user'])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['items.priceModule', 'clickUpMapping', 'user']);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Die Anfrage mit einem Benutzer verknüpfen (ob bereits vorhanden oder neu)
     */
    public function linkUser(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'identifier' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        $identifier = $request->identifier;
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        $user = User::where('email', $identifier)->orWhere('username', $identifier)->first();

        if ($user) {
            $msg = "Bestehender Kunde ({$user->name}) erfolgreich verknüpft!";
        } else {
            // User not found. We can only create a new user if the identifier is an email address.
            if (! $isEmail) {
                return back()->with('error', 'Benutzer wurde nicht gefunden. Um einen neuen Benutzer zu erstellen, geben Sie bitte eine gültige E-Mail-Adresse ein.');
            }

            // Zufälliges Passwort generieren
            $plainPassword = Str::random(10);

            $user = User::create([
                'name'         => $request->name ?? 'Kunde',
                'email'        => $identifier,
                'username'     => explode('@', $identifier)[0].rand(10, 99),
                'password'     => bcrypt($plainPassword),
                'role'         => 'kunde',
                'is_confirmed' => true,
            ]);

            // Willkommens-E-Mail an den Kunden senden
            try {
                Mail::to($user->email)->send(new WelcomeCustomerMail($user, $plainPassword));
                $msg = "Neuer Kunde erstellt und verknüpft! Eine Willkommens-E-Mail wurde an {$user->email} gesendet.";
            } catch (\Exception $e) {
                $msg = 'Neuer Kunde erstellt und verknüpft! Die Willkommens-E-Mail konnte jedoch nicht gesendet werden.';
            }
        }

        $inquiry->update(['user_id' => $user->id, 'status' => 'confirmed']);

        return back()->with('success', $msg);
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:offen,bestätigt,storniert',
        ]);

        // منطق إضافي: لا يمكن تأكيد طلب (Confirmed) بدون وجود مستخدم مرتبط
        if ($request->status === 'bestätigt' && ! $inquiry->user_id) {
            return back()->with('error', 'Bitte verknüpfen Sie zuerst einen Benutzer, bevor Sie den Status auf "Confirmed" setzen.');
        }

        $inquiry->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status erfolgreich aktualisiert.');
    }

    public function updateClickUp(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'clickup_task_id' => 'required|string',
        ]);

        // Verwenden updateOrCreate für die Sicherstellung, dass nur ein Datensatz pro Inquiry existiert [cite: 46]
        ClickUpMapping::updateOrCreate(
            ['anfrage_id' => $inquiry->id],
            [
                'clickup_aufgabe_id'        => $request->clickup_task_id,
                'zuletzt_synchronisiert_am' => null,
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
            'angebot_nummer' => $request->quote_number,
        ]);

        return back()->with('success', 'Projekt Name erfolgreich aktualisiert.');
    }

    public function updateItemPrice(Request $request, Inquiry $inquiry, \App\Models\InquiryItem $item)
    {
        // Sicherstellen, dass das Item zu dieser Anfrage gehört
        if ($item->anfrage_id !== $inquiry->id) {
            abort(404);
        }

        $request->validate([
            'preis_zum_zeitpunkt' => 'required|numeric|min:0',
        ]);

        $item->update([
            'preis_zum_zeitpunkt' => $request->preis_zum_zeitpunkt,
        ]);

        // Gesamtsumme neu berechnen
        $inquiry->update([
            'geschätzter_gesamtpreis' => $inquiry->items()->get()->sum(function ($i) {
                return $i->preis_zum_zeitpunkt * $i->menge;
            }),
        ]);

        return back()->with('success', 'Preis erfolgreich aktualisiert.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Anfrage erfolgreich gelöscht.');
    }
}
