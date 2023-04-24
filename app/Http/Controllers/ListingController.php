<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //show all listings
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(10)
        ]);
    }

    //show a single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //show the form to create a new listing
    public function create()
    {
        return view('listings.create');
    }

    public function store()
    {
        $listing = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => 'required',
            'tags' => 'required'
        ]);
        if (request('logo')) {
            $listing['logo'] = request('logo')->store('logos', 'public');
        }

        $listing['user_id'] = auth()->id();

        Listing::create($listing);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    //show the form to edit a listing
    public function edit(Listing $listing)
    {
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    public function update(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to edit this listing!');
        }
        $formFields = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => 'required',
            'tags' => 'required'
        ]);
        if (request('logo')) {
            $formFields['logo'] = request('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('message', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to delete this listing!');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    public function manage()
    {
        return view('listings.manage', [
            'listings' => auth()->user()->listings()->get()
        ]);
    }
}
