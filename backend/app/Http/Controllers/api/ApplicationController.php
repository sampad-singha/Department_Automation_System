<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function submitApplication(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'application_template_id' => 'required|exists:application_templates,id',
                'placeholders' => 'required|array',
                'attachment' => 'nullable|file|max:2048', // max 2MB
            ]);

            $template = ApplicationTemplate::findOrFail($validated['application_template_id']);

            $body = str_replace(
                array_map(fn($k) => "%$k%", array_keys($validated['placeholders'])),
                array_values($validated['placeholders']),
                $template->body
            );

            $attachmentPath = null;

            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store(
                    'application_attachments', // folder
                    ['disk' => 'local']        // use the local disk
                );
            }


            $application = Application::create([
                'user_id' => auth()->id(),
                'application_template_id' => $template->id,
                'body' => $body,
                'attachment' => $attachmentPath,
                'status' => 'pending',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Application submitted successfully.',
                'data' => $application,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit application.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function authorizeApplication(Request $request, $id): JsonResponse
    {
        try {
            $application = Application::where('id', $id)
                ->where('authorized_by', auth()->id())
                ->where('status', 'pending')
                ->firstOrFail();

            $action = $request->input('action'); // 'approve' or 'reject'

            if ($action === 'reject') {
                $application->update([
                    'status' => 'rejected',
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Application rejected.',
                ]);
            }

            // Approve and generate PDF
            $mpdf = new Mpdf();
            $mpdf->WriteHTML('<h3>Authorized Application</h3>');
            $mpdf->WriteHTML('<p>' . nl2br(e($application->body)) . '</p>');
            $mpdf->WriteHTML('<br><p>Authorized By: ' . $application->authorizedBy->name . '</p>');

            $filename = 'authorized_applications/' . Str::uuid() . '.pdf';
            Storage::disk('local')->put($filename, $mpdf->Output('', 'S'));

            $application->update([
                'status' => 'approved',
                'authorized_copy' => $filename,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Application approved and PDF generated.',
                'data' => $application,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process application.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPendingApplications(): JsonResponse
    {
        try {
            $applications = Application::with('user', 'applicationTemplate')
                ->where('authorized_by', auth()->id())
                ->where('status', 'pending')
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $applications,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch pending applications.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadAuthorizedCopy($id)
    {
        try {
            $application = Application::findOrFail($id);

            $user = auth()->user();
            $isAuthorized = $user->id === $application->user_id || $user->id === $application->authorized_by;

            if (!$isAuthorized) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access.',
                ], 403);
            }

            if ($application->status !== 'approved' || !$application->authorized_copy) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Application is not yet approved or missing file.',
                ], 403);
            }

//            $filePath = storage_path('app/' . $application->authorized_copy);
            $filePath = Storage::disk('local')->path($application->authorized_copy);


//            dd($filePath);

            if (!file_exists($filePath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authorized copy not found on server.',
                ], 404);
            }

            return response()->download($filePath);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to download file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getMyApplications(): JsonResponse
    {
        try {
            $applications = Application::with(['applicationTemplate', 'authorizedBy'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $applications,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch applications.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
