<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'active')
            ->where('visibility', 'public')
            ->with('category')
            ->withCount([
                'registrations as confirmed_sales_count' => fn ($query) => $query->where('status', 'confirmed'),
            ])
            ->orderByDesc('confirmed_sales_count')
            ->orderBy('date', 'asc')
            ->take(8)
            ->get();

        $categories = Category::all();

        return view('welcome', compact(
            'events',
            'categories'
        ));
    }
}
