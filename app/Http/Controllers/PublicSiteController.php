<?php

namespace App\Http\Controllers;

use App\Models\AcademyGalleryItem;
use App\Models\Registration;
use App\Models\TrainingGroup;
use App\Models\User;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function index(): View
    {
        $groups = TrainingGroup::query()
            ->where('active', true)
            ->orderBy('age_min')
            ->get();

        $coaches = User::query()
            ->where('role', User::ROLE_COACH)
            ->where('active', true)
            ->with(['coachedGroups' => fn ($query) => $query->where('active', true)->orderBy('age_min')])
            ->orderBy('name')
            ->get();

        $galleryItems = AcademyGalleryItem::query()
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        $categoryCount = $groups->pluck('category')->unique()->count();
        $playerCount = Registration::query()
            ->where('season', config('academy.season'))
            ->where('status', Registration::APPROVED)
            ->distinct()
            ->count('child_id');

        return view('vitrine.index', compact('groups', 'coaches', 'galleryItems', 'categoryCount', 'playerCount'));
    }
}
