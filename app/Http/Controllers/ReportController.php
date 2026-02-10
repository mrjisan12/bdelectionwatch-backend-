<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    // Create Report
    public function createReport(Request $request)
    {
        // Honeypot
        if ($request->filled('website')) {
            return response()->json(['message' => 'Bot detected'], 403);
        }
    
        // Rate-limit per IP
        $recentCount = Report::where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();
    
        if ($recentCount >= 5) {
            return response()->json(['message' => 'Too many submissions'], 429);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'constituency' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'time' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'video' => 'nullable|url',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
            // return $this->response(false, 'Something went wrong', $validator->errors(), 422);
        }
    
        $data = $validator->validated();
        $data['status'] = 'pending';
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reports/images', 'public');
        }
    
        $report = Report::create($data);
    
        return $this->response(true, 'Report submitted successfully', $report, 201);
    }

    
    
    
    
    
    // public function createReport(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'nullable|string|max:255',
    //         'category' => 'required|string|max:255',
    //         'location' => 'required|string|max:255',
    //         'constituency' => 'required|string|max:255',
    //         'url' => 'nullable|string|max:255',
    //         'time' => 'required|string|max:255',
    //         'contact' => 'nullable|string|max:255',
    //         'description' => 'required|string',
    //         'evidence' => 'nullable|string',
    //         'status' => 'in:pending,verified,rejected',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB
    //         'video' => 'nullable|url',
    //     ]);
        
    //     if ($request->hasFile('image')) {
    //         if (!$request->file('image')->isValid()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid image file'
    //             ], 422);
    //         }
    //     }


    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $data = $validator->validated();

    //     // Handle image upload
    //     if ($request->hasFile('image')) {
    //         $data['image'] = $request->file('image')->store('reports/images', 'public');
    //     }

    //     // Create report
    //     $report = Report::create($data);

    //     return $this->response(true, 'Report created successfully', $report, 201);
    // }

    // Get Reports with pagination
    // public function getReports(Request $request)
    // {
    //     $page = $request->query('page', 1);   // default page 1
    //     $size = $request->query('size', 10);  // default 10 per page

    //     $reports = Report::where('status', 'verified')
    //     ->orderBy('created_at', 'desc')
    //         ->paginate($size, ['*'], 'page', $page);


    //     $data = [
    //         'items' => $reports->items(),
    //         'current_page' => $reports->currentPage(),
    //         'last_page' => $reports->lastPage(),
    //         'per_page' => $reports->perPage(),
    //         'total' => $reports->total(),
    //     ];

    //     return $this->response(true, 'Reports fetched successfully', $data, 200);
    // }
    
    public function getReports(Request $request)
    {
        $date = $request->query('date');

        $reports = Report::query()
            ->where('status', 'verified')
            ->when($date, function ($q) use ($date) {
                $q->whereDate('event_date', $date);
            })
            ->latest('event_date')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Reports fetched successfully',
            'data' => [
                'items' => $reports->items(),
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
            'code' => 200,
        ]);
    }

}
