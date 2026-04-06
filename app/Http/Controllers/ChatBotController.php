<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\InquiryItem;
use App\Models\PriceModule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // تأكد من تثبيت dompdf

class ChatBotController extends Controller
{
    /**
     * عرض الصفحة الأولى وبدء الجلسة
     */
    public function show(Request $request)
    {
        // إعادة ضبط الجلسة عند البداية
        $request->session()->put('chatbot.step', 0);
        $request->session()->put('chatbot.answers', []);

        // جلب أول سؤال ديناميكياً من قاعدة البيانات
        $firstModule = PriceModule::where('is_active', true)->orderBy('id', 'asc')->first();

        if (! $firstModule) {
            return view('chatbot', [
                'welcome' => config('chatbot.fallback_welcome'),
                'first' => config('chatbot.fallback_first'),
                'firstStep' => [
                    'key' => 'error',
                    'question' => config('chatbot.no_modules_error'),
                    'type' => 'boolean',
                    'options' => config('chatbot.boolean_options'),
                ],
            ]);
        }

        return view('chatbot', [
            'welcome' => config('chatbot.welcome'),
            'first' => config('chatbot.first_message'),
            'firstStep' => $this->transformModuleToStep($firstModule),
        ]);
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:200'],
        ]);

        // جلب قائمة الموديلات النشطة من قاعدة البيانات
        $modules = PriceModule::where('is_active', true)->orderBy('id', 'asc')->get();
        $stepIndex = (int) $request->session()->get('chatbot.step', 0);
        $answers = (array) $request->session()->get('chatbot.answers', []);

        if ($modules->isEmpty()) {
            return response()->json([
                'bot' => config('chatbot.no_modules_error'),
                'done' => true,
            ]);
        }

        // التأكد من وجود خطوات متبقية
        if ($stepIndex >= $modules->count()) {
            return $this->finalizeInquiry($request);
        }

        $currentModule = $modules[$stepIndex];
        $userMsg = trim(mb_strtolower($request->input('message')));

        // تحويل الموديل إلى "خطوة" للفحص (الفحص هنا يعتمد على نعم/لا غالباً)
        $stepData = $this->transformModuleToStep($currentModule);
        [$ok, $value, $error] = $this->parseAnswer($stepData, $userMsg);

        if (! $ok) {
            return response()->json([
                'bot' => config('chatbot.parsing_error').' '.$currentModule->label_de,
                'type' => $stepData['type'],
                'options' => $stepData['options'] ?? null,
                'done' => false,
                'progress' => (int) round(($stepIndex / $modules->count()) * 100),
            ], 422);
        }

        // حفظ الإجابة
        $answers[$currentModule->key] = $value;
        $request->session()->put('chatbot.answers', $answers);

        $stepIndex++;
        $request->session()->put('chatbot.step', $stepIndex);

        // إذا وصلنا للنهاية -> حفظ البيانات وتوليد الـ PDF
        if ($stepIndex >= $modules->count()) {
            return $this->finalizeInquiry($request);
        }

        // إرسال السؤال التالي
        $nextModule = $modules[$stepIndex];
        $nextStep = $this->transformModuleToStep($nextModule);

        $transitions = [
            'Verstanden! ',
            'Alles klar! ',
            'Perfekt, weiter geht\'s! ',
            'Super, danke! ',
            'Gut, nächste Frage: ',
            'Toll! ',
            'Prima! Weiter: ',
            'Sehr gut! ',
            'Danke für Ihre Antwort! ',
            'Notiert! ',
        ];
        $botMsg = $transitions[array_rand($transitions)].$nextStep['question'];
        if (! empty($nextStep['description'])) {
            $botMsg .= "\n<span style=\"color: #666; font-size: 0.9em;\"><i>".$nextStep['description'].'</i></span>';
        }

        return response()->json([
            'bot' => $botMsg,
            'type' => $nextStep['type'],
            'options' => $nextStep['options'] ?? null,
            'done' => false,
            'progress' => (int) round(($stepIndex / $modules->count()) * 100),
        ]);
    }

    /**
     * تحويل بيانات الموديل من DB إلى تنسيق يفهمه الـ Frontend
     */
    private function transformModuleToStep($module)
    {
        if ($module->type === 'quantity') {
            return [
                'key' => $module->key,
                'question' => sprintf(config('chatbot.quantity_question'), $module->label_de),
                'description' => $module->description,
                'type' => 'number', // سيستخدم الـ Frontend حقل إدخال رقمي
                'min' => 1,
                'max' => 50,
            ];
        }

        if ($module->type === 'select') {
            $options = is_array($module->options) ? $module->options : json_decode($module->options, true) ?? [];
            if (! empty($options)) {
                return [
                    'key' => $module->key,
                    'question' => $module->label_de,
                    'description' => $module->description,
                    'type' => 'select',
                    'options' => array_column($options, 'label'),
                ];
            }

            // Fallback to hardcoded logic if no DB options are set
            if ($module->key === 'design_type') {
                return [
                    'key' => $module->key,
                    'question' => $module->label_de,
                    'description' => $module->description,
                    'type' => 'select',
                    'options' => config('chatbot.design_type_options'),
                ];
            }
            if ($module->key === 'anzahl_der_seiten') {
                return [
                    'key' => $module->key,
                    'question' => config('chatbot.pages_question'),
                    'description' => $module->description,
                    'type' => 'select',
                    'options' => config('chatbot.pages_options'),
                ];
            }
            if ($module->key === 'anzahl_der_sprachen') {
                return [
                    'key' => $module->key,
                    'question' => config('chatbot.languages_question'),
                    'description' => $module->description,
                    'type' => 'number',
                    'min' => 1,
                    'max' => 20,
                ];
            }
        }

        return [
            'key' => $module->key,
            'question' => sprintf(config('chatbot.boolean_question'), $module->label_de),
            'description' => $module->description,
            'type' => 'boolean',
            'options' => config('chatbot.boolean_options'),
        ];
    }

    private function parseAnswer(array $step, string $msg): array
    {
        switch ($step['type']) {
            case 'boolean':
                return $this->parseBoolean($msg);
            case 'number':
                return $this->parseNumber($step, $msg);
            case 'select':
                return $this->parseSelect($step, $msg);
            default:
                return [false, null, config('chatbot.unknown_type_error')];
        }
    }

    /**
     * إنهاء الطلب وحفظه في قاعدة البيانات
     */
    private function finalizeInquiry(Request $request)
    {
        $answers = (array) $request->session()->get('chatbot.answers', []);
        $totalEstimate = 0;
        $selectedModules = [];

        // المرحلة الأولى: الفلترة وحساب السعر الإجمالي
        foreach ($answers as $key => $value) {
            $module = PriceModule::where('key', $key)->first();
            if (! $module) {
                continue;
            }

            if ($module->type === 'quantity' && is_numeric($value) && (int) $value > 0) {
                $totalEstimate += ($module->price * (int) $value);
                $selectedModules[] = ['module' => $module, 'qty' => (int) $value, 'customer_choice' => (string) $value];
            } elseif ($module->type === 'boolean' && ($value === true || $value === 'Ja')) {
                $totalEstimate += $module->price;
                $selectedModules[] = ['module' => $module, 'qty' => 1, 'customer_choice' => 'Ja'];
            } elseif ($module->type === 'select') {
                $options = is_array($module->options) ? $module->options : json_decode($module->options, true) ?? [];

                if (! empty($options)) {
                    // DB-driven logic
                    $price = 0;
                    $found = false;
                    foreach ($options as $opt) {
                        if (mb_strtolower($opt['label']) === mb_strtolower($value)) {
                            $price = (float) $opt['price'];
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        $totalEstimate += $price;
                        $selectedModules[] = ['module' => $module, 'qty' => 1, 'override_price' => $price, 'customer_choice' => (string) $value];
                    }
                } else {
                    // Fallback to hardcoded logic
                    if ($module->key === 'design_type') {
                        $prices = config('chatbot.design_type_prices');
                        $price = $prices[$value] ?? 0;

                        if ($price > 0) {
                            $totalEstimate += $price;
                            $selectedModules[] = ['module' => $module, 'qty' => 1, 'override_price' => $price, 'customer_choice' => (string) $value];
                        }
                    } elseif ($module->key === 'anzahl_der_seiten') {

                        // Gesamtpreis berechnen
                        $basePrice = config('chatbot.pages_base_price');
                        $additionalPrices = config('chatbot.pages_additional_prices');
                        $price = $basePrice + ($additionalPrices[$value] ?? 0);

                        // Zum Gesamtpreis hinzufügen
                        if ($price > 0) {
                            $totalEstimate += $price;
                            $selectedModules[] = [
                                'module' => $module,
                                'qty' => 1,
                                'override_price' => $price,
                                'customer_choice' => (string) $value,
                            ];
                        }
                    } elseif ($module->key === 'anzahl_der_sprachen') {
                        $price = ((int) $value === 1) ? config('chatbot.languages_base_price') : ((int) $value - 1) * config('chatbot.languages_additional_price_per_language');

                        if ($price > 0) {
                            $totalEstimate += $price;
                            $selectedModules[] = ['module' => $module, 'qty' => 1, 'override_price' => $price, 'customer_choice' => (string) $value];
                        }
                    }
                }
            }
        }

        // المرحلة الثانية: إنشاء الاستفسار الرئيسي (Inquiry)
        $quoteNumber = 'BOT-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $inquiry = Inquiry::create([
            'quote_number' => $quoteNumber,
            'session_id' => $request->session()->getId(),
            'user_id' => $request->user()?->id,
            'total_estimated_price' => $totalEstimate,
            'status' => 'pending',
        ]);

        // المرحلة الثالثة: حفظ العناصر المختارة فقط في الجدول الوسيط
        foreach ($selectedModules as $item) {
            InquiryItem::create([
                'inquiry_id' => $inquiry->id,
                'price_module_id' => $item['module']->id,
                'price_at_time' => $item['override_price'] ?? $item['module']->price,
                'quantity' => $item['qty'],
                'customer_choice' => $item['customer_choice'] ?? null,
            ]);
        }

        return response()->json([
            'bot' => "Vielen Dank!\nIhre Schätzung: ** ".number_format($totalEstimate, 2, ',', '.')." €**\n"
                ."Angebot Nummer: **{$quoteNumber}**",
            'done' => true,
            'pdf_url' => route('chatbot.pdf', ['quote' => $quoteNumber]),
        ]);
    }

    private function resetAndRestart(Request $request, $firstModule)
    {
        $request->session()->put('chatbot.step', 0);
        $request->session()->put('chatbot.answers', []);
        $firstStep = $this->transformModuleToStep($firstModule);

        return response()->json([
            'bot' => $firstStep['question'],
            'type' => $firstStep['type'],
            'options' => $firstStep['options'],
            'done' => false,
            'progress' => 0,
        ]);
    }

    private function parseNumber(array $step, string $msg): array
    {
        if (! preg_match('/^\d+$/', $msg)) {
            return [false, null, 'Bitte eine Zahl eingeben.'];
        }
        $n = (int) $msg;
        if (isset($step['min']) && $n < $step['min']) {
            return [false, null, "Zahl zu klein (min {$step['min']})."];
        }
        if (isset($step['max']) && $n > $step['max']) {
            return [false, null, "Zahl zu groß (max {$step['max']})."];
        }

        return [true, $n, null];
    }

    private function parseBoolean(string $msg): array
    {
        $yes = ['ja', 'j', 'yes', 'y', 'true', '1', 'sicher', 'klar', 'jep', 'jo'];
        $no = ['nein', 'n', 'no', 'false', '0', 'nope', 'niemals'];
        if (in_array($msg, $yes, true)) {
            return [true, true, null];
        }
        if (in_array($msg, $no, true)) {
            return [true, false, null];
        }

        return [false, null, 'Bitte mit ja/nein antworten.'];
    }

    private function parseSelect(array $step, string $msg): array
    {
        $opts = array_map('mb_strtolower', $step['options'] ?? []);

        // Exact match
        if (in_array($msg, $opts, true)) {
            $idx = array_search($msg, $opts, true);

            return [true, $step['options'][$idx], null];
        }

        // Fuzzy match
        $bestMatch = null;
        $highestSim = 0;
        foreach ($step['options'] as $opt) {
            similar_text($msg, mb_strtolower($opt), $sim);
            if ($sim > $highestSim) {
                $highestSim = $sim;
                $bestMatch = $opt;
            }
        }

        if ($highestSim >= 70) {
            return [true, $bestMatch, null];
        }

        return [false, null, 'Bitte eine der Optionen wählen: <br>'.implode('<br>', $step['options']).'.'];
    }

    public function downloadPdf($quoteNumber)
    {
        $inquiry = Inquiry::with('items.priceModule')
            ->where('quote_number', $quoteNumber)
            ->firstOrFail();

        // Temporarily commented out for testing HTML/CSS
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.quote', compact('inquiry'));
        return $pdf->download("angebot-{$quoteNumber}.pdf");

        // for rapid CSS testing:
        //return view('pdf.quote', compact('inquiry'));
    }

    public function embeddedPdf($quoteNumber)
    {
        $inquiry = Inquiry::with('items.priceModule')
            ->where('quote_number', $quoteNumber)
            ->firstOrFail();

        return view('pdf.quote', compact('inquiry'));
    }
}
