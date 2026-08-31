<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; text-align: center; padding: 60px; }
        .cadre { border: 8px solid #1D4ED8; padding: 50px; }
        h1 { color: #1D4ED8; font-size: 32px; }
        .nom { font-size: 28px; font-weight: bold; margin: 20px 0; }
        .cours { font-size: 20px; color: #374151; }
        .code { margin-top: 40px; font-family: monospace; font-size: 14px; color: #6B7280; }
    </style>
</head>
<body>
    <div class="cadre">
        <h1>Certificat CodeLearn</h1>
        <p>Ce certificat est décerné à</p>
        <p class="nom">{{ $apprenant?->nomComplet() ?? 'Apprenant' }}</p>
        <p>pour avoir validé avec succès le cours</p>
        <p class="cours">{{ $cour->titre }}</p>
        <p>Score final : {{ $certificat->score_final }}%</p>
        <p class="code">Code de vérification : {{ $certificat->code_verification }}</p>
    </div>
</body>
</html>
