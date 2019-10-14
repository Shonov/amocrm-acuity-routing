<?php

namespace App\Http\Controllers;

use App\Http\Services\AcuityService;
use Illuminate\Http\Request;

class AcuityController extends Controller
{
    private $acuityService;

    public function __construct()
    {
        $this->acuityService = new AcuityService();
    }

    public function created(Request $request): array
    {
        $id = $request->get('id');
        return [
            'status' => $this->acuityService->created($id)
        ];
    }


    public function updated(Request $request): array
    {
        $id = $request->get('id');

        return [
            'status' => $this->acuityService->updated($id)
        ];
    }

    public function closed(Request $request): array
    {
        $id = $request->get('id');

        return [
            'status' => $this->acuityService->closed($id)
        ];
    }

    public function runCron(): array
    {
        return [
            'status' => $this->acuityService->runCron()
        ];
    }
}
