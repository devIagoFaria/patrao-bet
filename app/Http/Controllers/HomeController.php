<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\CategoryButton;

class HomeController extends Controller
{
    /**
     * Games used to feed the "Maiores Ganhos" widget, matched against
     * our own registered games so the thumbnails always exist.
     */
    private const WIN_FEED_POOL = [
        ['title' => 'AVIATOR', 'class' => 'aviator', 'match' => 'Aviator'],
        ['title' => 'FORTUNE TIGER', 'class' => 'tiger', 'match' => 'Fortune Tiger'],
        ['title' => 'MINES', 'class' => 'mines', 'match' => 'Mines'],
        ['title' => 'FORTUNE RABBIT', 'class' => 'slots', 'match' => 'Fortune Rabbit'],
        ['title' => 'FORTUNE OX', 'class' => 'tiger', 'match' => 'Fortune Ox'],
        ['title' => 'BAC BO', 'class' => 'mines', 'match' => 'Bac Bo'],
        ['title' => 'GATES OF OLYMPUS', 'class' => 'slots', 'match' => 'Gates Of Olympus'],
        ['title' => 'BIG BASS BONANZA', 'class' => 'slots', 'match' => 'Big Bass Bonanza'],
    ];

    /**
     * Show the main page of the betting site.
     */
    public function index()
    {
        $recentBets = Bet::with('user')
            ->where('status', 'win')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $carouselBanners = \App\Models\Banner::active()
            ->where('type', 'home_carousel')
            ->orderBy('order', 'asc')
            ->get();

        $mobileBanners = \App\Models\Banner::active()
            ->where('type', 'home_mobile')
            ->orderBy('order', 'asc')
            ->get();

        $categories = \App\Models\GameCategory::where('is_active', true)
            ->whereNotIn('layout_type', ['sidebar', 'sports'])
            ->orderBy('order', 'asc')
            ->get();

        $winFeedGames = $this->getWinFeedGames();

        return view('home', compact('recentBets', 'carouselBanners', 'mobileBanners', 'categories', 'winFeedGames'));
    }

    /**
     * Resolve the "Maiores Ganhos" game pool to real images from our
     * registered games, dropping any entry we can't find so the widget
     * never renders a broken thumbnail.
     */
    private function getWinFeedGames()
    {
        return collect(self::WIN_FEED_POOL)->map(function ($item) {
            $game = CategoryButton::where('title', 'like', '%' . $item['match'] . '%')
                ->where('is_active', true)
                ->whereNotNull('image_path')
                ->where('image_path', '!=', '')
                ->first();

            if (!$game) {
                return null;
            }

            return [
                'title' => $item['title'],
                'class' => $item['class'],
                'image' => asset($game->image_path),
            ];
        })->filter()->values();
    }

    /**
     * Show the Tournaments page.
     */
    public function tournaments()
    {
        return view('tournaments');
    }

    /**
     * Show the Missions page.
     */
    public function missions()
    {
        return view('missions');
    }
}

