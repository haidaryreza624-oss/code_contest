<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstitutionSetting;

class InstitutionSettingController extends Controller
{
    public function edit(){
        $settings = InstitutionSetting::first();
        return view('admin.settings.edit',compact('settings'));
    }
    public function update(Request $req){
        $validated = $req->validate([
            'institution_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
        ]);
        InstitutionSetting::updateOrCreate(
            ['id' => 1],
            $validated
        );
        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Institution settings updated successfully.');
    }
}
