<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment PDF</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; }
  </style>
</head>
<body>
  <h1>Payment #{{ $document->id }}</h1>
  <p>Patient: {{ $document->patient?->name ?? 'N/A' }}</p>
  <p>Amount: {{ $document->amount }}</p>
  <p>Description: {{ $document->description }}</p>
  <p>Printed at: {{ $print_date }}</p>
</body>
</html>
