<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialisation du mot de passe</title>
</head>
<body style="margin:0;background:#f4f6f8;color:#17243b;font-family:Arial,Helvetica,sans-serif;line-height:1.6">
    <main style="max-width:600px;margin:32px auto;padding:32px;background:#fff;border-radius:12px">
        <h1 style="font-size:22px;margin-top:0">Réinitialisation de votre mot de passe</h1>
        <p>Bonjour,</p>
        <p>Nous avons reçu une demande de réinitialisation du mot de passe associé à votre compte.</p>
        <p style="margin:28px 0">
            <a href="{{ $url }}" style="display:inline-block;padding:12px 20px;background:#142d5b;color:#fff;text-decoration:none;border-radius:6px;font-weight:bold">
                Choisir un nouveau mot de passe
            </a>
        </p>
        <p>Ce lien expirera dans {{ $expire }} minutes.</p>
        <p>Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer cet e-mail. Votre mot de passe restera inchangé.</p>
        <p style="margin-bottom:0">L’équipe de l’Académie Future Football Djerba</p>
    </main>
</body>
</html>
