<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RescheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RescheduleManagementController extends Controller
{
    public function approve(Request $request, int $id): RedirectResponse
    {
        try {
            $resRequest = RescheduleRequest::findOrFail($id);
            RescheduleRequest::autoApprove($resRequest, $request->user()->id);
            return back()->with('success', 'Permintaan reschedule berhasil disetujui.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui reschedule: ' . $e->getMessage());
        }
    }


    public function reject(Request $request, int $id): RedirectResponse
    {
        $resRequest = RescheduleRequest::findOrFail($id);
        
        if ($resRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses.');
        }

        $resRequest->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Permintaan reschedule telah ditolak.');
    }
}
