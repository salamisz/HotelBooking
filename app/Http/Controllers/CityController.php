<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin'))
            {
                $city_data = City::with("country")->orderBy("id")->get();
                return view('cities.index', ['cities' => $city_data]);
            }
            else
            {
                return abort(404);
            }
        }
        else
        {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        if (Auth::check())
        {
            if (Auth::user()->hasRole('admin'))
            {
                $country_data = Country::orderBy("country_name")->get();
                return view('cities.create', ["countries" => $country_data]);
            }
            else
            {
                return abort(404);
            }
        }
        else
        {
            return redirect('/login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CityRequest $request)
    {
        $city = City::firstOrCreate
        ([
            'country_id' => $request->input('countries'),
            'city_name' => strtolower($request->input('city_name')),
            'postal_code' => $request->input('postal_code'),
        ]);
        return redirect(route('cities.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(City $city)
    {
        $city->delete();
        return redirect(route('dashboard'));
    }
}
