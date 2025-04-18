<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ApplicationTemplate;
use Illuminate\Http\JsonResponse;

class ApplicationTemplateController extends Controller
{
    public function getTemplates(): JsonResponse
    {
        try {
            $templates = ApplicationTemplate::all(['id', 'type', 'title']);

            return response()->json([
                'status' => 'success',
                'data' => $templates,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve templates.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function showTemplate($id): JsonResponse
    {
        try {
            $template = ApplicationTemplate::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $template,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Template not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve template.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



}
