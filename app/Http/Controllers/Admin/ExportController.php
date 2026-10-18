<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Export des inscriptions en CSV (s'ouvre dans Excel, accents et arabe compris).
 * Contient des données personnelles de mineurs : réservé à l'administrateur.
 */
class ExportController extends Controller
{
    public function registrations(): StreamedResponse
    {
        $season = config('academy.season');
        $filename = 'inscriptions-'.str_replace('/', '-', $season).'.csv';

        return response()->streamDownload(function () use ($season) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour qu'Excel lise bien les accents et l'arabe

            fputcsv($out, ['Enfant', 'Naissance', 'Parent', 'Téléphone', 'E-mail', 'Groupe', 'Statut', 'Montant', 'Paiement'], ';', '"', '');

            Registration::with(['child.parent', 'trainingGroup', 'payments'])
                ->where('season', $season)
                ->orderBy('id')
                ->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $r) {
                        $paid = $r->payments->contains('status', Payment::PAID) ? 'payé' : 'en attente';

                        fputcsv($out, array_map(fn ($v) => self::safe($v), [
                            $r->child->full_name,
                            $r->child->birth_date->format('d/m/Y'),
                            $r->child->parent->name,
                            $r->child->parent->phone,
                            $r->child->parent->email,
                            $r->trainingGroup->name,
                            $r->status,
                            $r->amount,
                            $paid,
                        ]), ';', '"', '');
                    }
                });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Empêche l'injection de formules Excel (=, +, -, @) tout en gardant les numéros "+216 ..." intacts. */
    private static function safe(mixed $value): string
    {
        $s = (string) $value;

        if (preg_match('/^\+[\d\s]+$/', $s)) {
            return $s;
        }

        return preg_match('/^[=+\-@\t\r]/', $s) ? "'".$s : $s;
    }
}
