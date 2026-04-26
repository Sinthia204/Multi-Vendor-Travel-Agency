<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function destinations()
    {
        return view('destinations');
    }

    public function packages(Request $request)
    {
        $destination = $request->string('destination')->trim()->toString();

        $packages = Package::with('agency')
            ->when($destination, function ($query) use ($destination) {
                $query->where(function ($sub) use ($destination) {
                    $sub->where('name', 'like', "%{$destination}%")
                        ->orWhere('location', 'like', "%{$destination}%")
                        ->orWhere('category', 'like', "%{$destination}%");
                });
            })
            ->where('status', '!=', 'draft')
            ->orderByDesc('created_at')
            ->get();

        return view('packages', [
            'packages' => $packages,
            'destination' => $destination,
            'dates' => $request->string('dates')->trim()->toString(),
            'travelers' => $request->string('travelers')->trim()->toString(),
        ]);
    }

    public function experiences()
    {
        return view('experiences');
    }

    public function stories()
    {
        return view('stories');
    }

    public function contact()
    {
        return view('contact');
    }
}
