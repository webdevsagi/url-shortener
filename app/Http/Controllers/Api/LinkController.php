<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "target_url" => "required|url",
            "slug" => "nullable|string|unique:links,slug|regex:/^[a-zA-Z0-9]+$/",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Validation failed",
                "errors" => $validator->errors(),
            ], 422);
        }

        $slug = $request->slug ?? Link::generateUniqueSlug();

        $link = Link::create([
            "slug" => $slug,
            "target_url" => $request->target_url,
            "is_active" => true,
        ]);

        return response()->json([
            "slug" => $link->slug,
            "target_url" => $link->target_url,
            "short_url" => url("/r/{$link->slug}"),
            "is_active" => $link->is_active,
            "created_at" => $link->created_at,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
