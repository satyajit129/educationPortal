<?php

namespace App\Http\Controllers;

use App\Services\YearService;
use Illuminate\Http\Request;

class YearController extends Controller
{
    protected $yearService;

    public function __construct(YearService $yearService)
    {
        $this->yearService = $yearService;
    }
    public function yearList()
    {
        return $this->yearService->renderYearList();
    }
    public function yearForm($id = null)
    {
        return $this->yearService->renderYearForm($id);
    }
    public function yearSave(Request $request)
    {
        return $this->yearService->handleYearsave($request);
    }
    public function yearDelete($id)
    {
        return $this->yearService->handleYearDelete($id);
    }
}
