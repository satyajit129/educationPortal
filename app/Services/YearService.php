<?php

namespace App\Services;

use App\Models\Year;

class YearService
{
    public function renderYearList()
    {
        $years = Year::all();
        return view('teacher.pages.year_list', compact('years'));
    }
    public function renderYearForm($id = null)
    {
        $year = $id ? Year::findOrFail($id) : null;
        return view('teacher.pages.year_form', compact('year'));
    }
    public function handleYearsave($request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);
            Year::updateOrCreate(
                ['id' => $request->id],
                ['title' => $request->title]
            );
            return redirect()->route('yearList')->with('success', 'বছর সফলভাবে সংরক্ষণ করা হয়েছে।');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'বছর সংরক্ষণ করতে সমস্যা হয়েছে: ' . $th->getMessage())->withInput();
        }
    }
    public function handleYearDelete($id)
    {
            $year = Year::findOrFail($id);
            $year->delete();
            return redirect()->route('yearList')->with('success', 'বছর সফলভাবে মুছে ফেলা হয়েছে।');
    }
}