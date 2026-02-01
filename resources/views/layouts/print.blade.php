<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <style>
        @page {
            margin: 0;
            size: auto; /* auto is the initial value */
        }
        body {
            font-family: 'Courier New', monospace; /* Monospace font looks like thermal print */
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 58mm; /* Standard thermal paper width */
            background-color: white;
            color: black;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .border-b { border-bottom: 1px dashed black; }
        .border-t { border-top: 1px dashed black; }
        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .my-2 { margin-top: 8px; margin-bottom: 8px; }
        .flex { display: flex; justify-content: space-between; }
        .w-full { width: 100%; }
        
        /* Hide everything else when printing */
        @media print {
            body { width: auto; margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    {{ $slot }}
</body>
</html>
