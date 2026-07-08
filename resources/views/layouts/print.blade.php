<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk - Laundry ProMax</title>
    <!-- CSS via Vite -->
    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>
        body {
            background-color: #ffffff;
            color: #000000;
            font-family: 'Courier New', Courier, monospace;
            padding: 0;
            margin: 0;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
        }
    </style>
</head>
<body class="antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
