<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kalkulation - {{ $inquiry->quote_number }}</title>
    <style>
        @page {
            margin: 40px 50px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.5;
            font-size: 13px;
        }

        /* Top Layout Table */
        .header-layout {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .header-layout td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .company-info h1 {
            color: #0b3d91;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .company-info p {
            margin: 2px 10px;
            color: #555555;
            font-size: 12px;
        }
        .logo-container {
            text-align: right;
        }
        .logo-img {
            max-width: 180px;
            height: auto;
            margin: 30px 10px;
            
        }

        /* Document Title Bar */
        .title-bar {
            background-color: #0b3d91;
            color: #ffffff;
            padding: 12px 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .title-bar h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Info Block */
        .info-block {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-block td {
            padding: 4px 0;
            font-size: 13px;
        }
        .info-label {
            font-weight: bold;
            color: #0b3d91;
            width: 150px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f4f6fa;
            color: #0b3d91;
            font-weight: bold;
            text-align: left;
            padding: 12px 10px;
            font-size: 13px;
            border-bottom: 2px solid #0b3d91;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eeeeee;
        }
        .align-right {
            text-align: right;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        /* Totals Section */
        .total-wrapper {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .total-wrapper td {
            padding: 12px 10px;
            font-size: 16px;
        }
        .total-label {
            font-weight: bold;
            color: #333333;
            text-transform: uppercase;
        }
        .total-value {
            font-weight: bold;
            color: #0b3d91;
            font-size: 18px;
        }
        .total-row-highlight {
            background-color: #f4f6fa;
            border-top: 2px solid #0b3d91;
            border-bottom: 2px solid #0b3d91;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 11px;
            color: #888888;
            border-top: 1px solid #eeeeee;
            padding-top: 15px;
        }
 

    </style>
</head>
<body>

    <!-- Header Grid -->
    <table class="header-layout">
        <tr>
            <td class="company-info">
                <img src="{{ asset('logo.png') }}" class="logo-img" alt="agentur-77 Logo">
                <p><strong>Webdesign & Digitalisierung</strong></p>
                <p>Bloherfelder Str. 71</p>
                <p>26129 Oldenburg</p>
                <p>Telefon: 0441 18018566</p>
                <p>E-Mail: info@agentur-77.de</p>
            </td>
            <td class="logo-container">
                
            </td>
        </tr>
    </table>
   
    <!-- Title Bar -->
    <div class="title-bar">
        <h2 style="text-align: center;">Unverbindliche Kostenschätzung</h2>
    </div>

    <!-- Inquiry Info -->
    <table class="info-block">
        <tr>
            <td class="info-label">Angebotsnummer:</td>
            <td><strong>{{ $inquiry->quote_number }}</strong></td>
            <td class="info-label" style="text-align: right; width: 100px;">Datum:</td>
            <td style="text-align: right;">{{ $inquiry->created_at->format('d.m.Y') }}</td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Leistung / Modul</th>
                <th style="width: 30%;">Ihre Auswahl</th>
                <th class="align-right" style="width: 25%;">Preis (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquiry->items as $item)
            <tr>
                <td>{{ $item->priceModule ? $item->priceModule->label_de : 'Unbekanntes Modul' }}</td>
                <td>{{ $item->customer_choice ?? ($item->quantity > 1 ? $item->quantity . ' Stück' : 'Ja') }}</td>
                <td class="align-right">{{ number_format($item->price_at_time * $item->quantity, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="total-wrapper">
        <tr class="total-row-highlight">
            <td class="total-label" style="width: 75%; text-align: left;">Gesamtsumme (Brutto):</td>
            <td class="total-value align-right" style="width: 25%;">{{ number_format($inquiry->total_estimated_price, 2, ',', '.') }} €</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dies ist eine automatisch generierte, unverbindliche Kostenschätzung und stellt kein bindendes Angebot dar.</p>
        <a href="https://www.agentur-77.de/designvorschlag/#kontakt" style="color: #060607ff; text-decoration: none;">Für dieses Projekt einen kostenfreien Design­vorschlag oder ein Angebot anfordern</a>
    </div>

</body>
</html>
