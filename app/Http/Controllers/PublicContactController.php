<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\View\View;

class PublicContactController extends Controller
{
    public function index(): View
    {
        $companyProfile = CompanyProfile::query()
            ->where('code', 'Main')
            ->first();

        return view('public.contact.index', [
            'companyProfile' => $companyProfile,
        ]);
    }
}
