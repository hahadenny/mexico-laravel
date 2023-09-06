<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Bookmark::class, 'bookmark');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return JsonResource::collection($user->bookmarks()->orderBy('sort_order')->orderBy('id', 'DESC')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $bdata['user_id'] = $user->id;
        $bdata['name'] = $request->name;
        $bdata['sort_order'] = 0;
        foreach ($request->data as $k => $v) {
            $ddata[$k] = $v;
        }
        $data = json_encode($ddata);
        $bdata['data'] = $data;
        $bookmark = new Bookmark($bdata);
        $bookmark->save();
        return new JsonResource($bookmark);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Bookmark $bookmark)
    {
        return new JsonResource($bookmark::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookmark $bookmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request, Bookmark $bookmark)
    {
        $bookmark::where('id', $id)->update($request->all());
        return new JsonResource($bookmark::find($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Bookmark $bookmark)
    {
        $bookmark::where('id', $id)->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    public function batchDestroy(Request $request)
    {
        $params = $request->all();
        DB::transaction(function () use ($params) {
            $bookmarks = Bookmark::query()->findMany($params['ids']);

            foreach ($bookmarks as $bookmark) {
                $bookmark->delete();
            }
        });
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
    public function batchUpdateOrders(Request $request, Bookmark $bookmark)
    {
        $rowOrders = $request->rowOrders;
        foreach ($rowOrders as $rowOrder) {
            $bookmark::where('id', $rowOrder['id'])->update(['sort_order' => $rowOrder['order']]);
        }        
        return new JsonResource($rowOrders);
    }
}