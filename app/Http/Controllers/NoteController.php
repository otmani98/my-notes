<?php

namespace App\Http\Controllers;

use \Mpdf\Mpdf;
use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\NoteResource;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\GeneralJsonException;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $notes = Note::whereHas('user', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->select(['id','title','brief', 'color','created_at', 'updated_at'])->orderBy('created_at', 'DESC')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $notes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'brief' => 'required|string|max:88',
            'content' => 'required|string',
            'color' => 'required|string',
        ]);

        $created = Note::create([
            'title' => $request->title,
            'brief' => $request->brief,
            'content' => $request->content,
            'color' => $request->color,
            'user_id' => auth()->user()->id,
        ]);

        return new NoteResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $note = Note::find($id);

        if (!$note || auth()->user()->id !== $note->user_id) {
            throw new GeneralJsonException('note not found', 404);
        }

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = Note::find($id);

        if (!$note || auth()->user()->id !== $note->user_id) {
            throw new GeneralJsonException('note not found', 404);
        }

        $note->update($request->all());

        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = Note::find($id);

        if (!$note || auth()->user()->id !== $note->user_id) {
            throw new GeneralJsonException('note not found', 404);
        }

        $note->delete();

        return response()->json(null,204);
    }

    public function print(string $id)

    {
        $note = Note::find($id);

        if (!$note || auth()->user()->id !== $note->user_id) {
            throw new GeneralJsonException('note not found', 404);
        }

        $randomName = Str::random(85) . '.pdf';

        $mpdf = new \Mpdf\Mpdf();

        $mpdf->WriteHTML(\View::make('print', [
            'title' => $note->title,
            'content' => $note->content,
            'created' => $note->created_at,
            'updated' => $note->updated_at,
        ]));

        Storage::disk('public')->put('486532pdf/' . $randomName, $mpdf->Output($randomName, "S"));

        $access_link = request()->getSchemeAndHttpHost() . '/pdfs/' . $randomName;

        return response()->json([
            'status' => 'success',
            'access_link' => $access_link
        ]);

    }

    public function getPdf(string $name)
    {

        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$name.'"'
        ];

        return Storage::disk('public')->download('486532pdf/'.$name, 'Request', $header);
    }

}
