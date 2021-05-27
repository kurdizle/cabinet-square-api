<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $data = DB::table('notes')
                    ->where('user_id', '=', $user->id)
                    ->orderby('updated_at', 'desc')
                    ->get();

        if ( !$user ) {
            return response()->json([
                'error' => 'Unathorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
          'notes' => $data,
          'user' => $user,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $user->id,
            'note_id' => Str::random(30)
        ]);

        return response()->json([
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $data = DB::table('notes')
                    ->where([
                        ['id', '=', $id],
                        ['user_id', '=', $user->id],
                    ])
                    ->get();

        if ( isset($data[0]) ) {
            return response()->json([
                'note' => $data,
                'user' => $user,
              ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => 'Not found'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Note::find($id);
        $data = $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Note::find($id);
        $data = $post->delete();

        return $data;
    }
}
