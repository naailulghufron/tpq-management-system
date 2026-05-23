<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Program;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'programs' => $this->activePrograms(3),
            'posts' => $this->publishedPosts(3),
            'announcements' => $this->activeAnnouncements(3),
        ]);
    }

    public function profile(): View
    {
        return view('public.profile');
    }

    public function programs(): View
    {
        return view('public.programs', [
            'programs' => $this->activePrograms(),
        ]);
    }

    public function blog(): View
    {
        return view('public.blog', [
            'posts' => $this->publishedPosts(),
        ]);
    }

    public function gallery(): View
    {
        return view('public.gallery', [
            'galleries' => $this->hasTable('galleries')
                ? Gallery::query()->where('status', 'active')->orderBy('sort_order')->latest()->get()
                : collect(),
        ]);
    }

    public function announcements(): View
    {
        return view('public.announcements', [
            'announcements' => $this->activeAnnouncements(),
        ]);
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function registration(): View
    {
        return view('public.registration', [
            'programs' => $this->activePrograms(),
        ]);
    }

    private function activePrograms(?int $limit = null)
    {
        if (! $this->hasTable('programs')) {
            return collect();
        }

        $query = Program::query()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->latest();

        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    private function publishedPosts(?int $limit = null)
    {
        if (! $this->hasTable('posts')) {
            return collect();
        }

        $query = Post::query()
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at');

        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    private function activeAnnouncements(?int $limit = null)
    {
        if (! $this->hasTable('announcements')) {
            return collect();
        }

        $query = Announcement::query()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', today());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', today());
            })
            ->orderByDesc('is_pinned')
            ->latest();

        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    private function hasTable(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (Throwable) {
            return false;
        }
    }
}
