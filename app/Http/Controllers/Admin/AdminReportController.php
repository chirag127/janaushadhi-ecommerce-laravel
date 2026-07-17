<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
    ) {}

    public function index(Request $request): View
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $salesSummary = $this->orders->salesSummary($from, $to);

        return view('admin.reports.index', [
            'salesSummary' => $salesSummary,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
