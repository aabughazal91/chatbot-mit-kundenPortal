<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kalkulation - {{ $inquiry->quote_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0b3d91;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0b3d91;
            margin: 0;
            font-size: 24px;
        }
        .quote-info {
            margin-bottom: 30px;
        }
        .quote-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            color: #0b3d91;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
        }
        .total-row td {
            border-top: 2px solid #0b3d91;
            border-bottom: none;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Unverbindliche Kostenschätzung</h1>
    </div>

    <div class="quote-info">
        <p><strong>Angebotsnummer:</strong> {{ $inquiry->quote_number }}</p>
        <p><strong>Datum:</strong> {{ $inquiry->created_at->format('d.m.Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Leistung / Modul</th>
                <th>Menge </th>
                <th style="text-align: right;">Preis (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquiry->items as $item)
            <tr>
                <td>
                    @if($item->quantity > 1)
                        {{ $item->quantity }}x 
                    @endif
                    {{ $item->priceModule ? $item->priceModule->label_de : 'Unbekanntes Modul' }}
                </td>
                <td style="text-align: right;">{{ number_format($item->price_at_time * $item->quantity, 2, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td style="text-align: right;">
                    {{ $item->quantity }}
                </td>
            </tr>
            <tr class="total-row">
                <td style="text-align: right;">Gesamtsumme (Netto):</td>
                <td style="text-align: right;">{{ number_format($inquiry->total_estimated_price, 2, ',', '.') }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Hinweis: Dies ist eine automatisch generierte, unverbindliche Kostenschätzung und stellt kein bindendes Angebot dar.</p>
    </div>

</body>
</html>
