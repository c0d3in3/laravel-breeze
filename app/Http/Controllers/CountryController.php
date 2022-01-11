<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function getCountries(Request $request){
        return ['results' => Country::select(['id', 'name as text'])
        ->where('name', 'LIKE', '%'.$request->input('term').'%')
        ->get()];
    }

    public function getVisitedCountries(Request $request) {
        return $request->user()->visitedCountries()->get();
    }

    public function getCountriesToVisit(Request $request) {
        return $request->user()->countriesToVisit()->get();
    }

    public function addVisitedCountry(Request $request) {
        $country = Country::findOrFail($request->input('countryID'));
        $request->user()
            ->visitedCountries()
            ->attach($request->input('countryID'));
        $request->user()->save();
        return ['status' => 'success'];
    }

    public function addCountryToVisit(Request $request) {
        $country = Country::findOrFail($request->input('countryID'));
        $request->user()
            ->countriesToVisit()
            ->attach($request->input('countryID'));
        $request->user()->save();
        return ['status' => 'success'];
    }

    public function deleteVisitedCountry(Request $request) {
        $country = Country::findOrFail($request->countryID);
        $request->user()
        ->visitedCountries()
        ->detach($request->countryID);
        $request->user()->save();
        return ['status' => 'success'];
    }

    public function deleteCountryToVisit(Request $request) {
        $country = Country::findOrFail($request->countryID);
        $request->user()
        ->countriesToVisit()
        ->detach($request->countryID);
        $request->user()->save();
        return ['status' => 'success'];
    }
}
