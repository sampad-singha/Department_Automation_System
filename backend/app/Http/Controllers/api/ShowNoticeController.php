<?php

namespace App\Http\Controllers\Api;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ShowNoticeController extends Controller
{

    public function show()
    {
        Log::info('show the notice');
        $approvedNotices = DB::table('notice_user')
            ->select('notice_id')
            ->groupBy('notice_id')
            ->havingRaw('COUNT(user_id) = SUM(is_approved)')
            ->pluck('notice_id');

        
        Log::info('Approved Notices:', $approvedNotices->toArray());
        $notices =Notice::whereIn('id', $approvedNotices)->get();

        return response()->json([
            'message' => 'Approved notices retrieved successfully.',
            'notices' => $notices,
        ], 200);

    }
}
