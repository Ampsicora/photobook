<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$photos = Photo::latest()->where('user_id', $request->user()->id)->paginate(5);
        $photos = $request->user()->photos()->latest()->paginate(5);

//        $urls = Storage::disk('azure')->url($data->name);

        return view('index', compact('photos'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         =>  'required|image|max:2048',
            'description'    =>  'required'
        ]);

        $image = $request->file('name');

        $new_name = md5($image) . '.' . $image->getClientOriginalExtension();

        Storage::disk('azure')->putFileAs('', $image, $new_name);

        $form_data = array(
            'name'            =>   $new_name,
            'description'       =>   $request->description,
            'user_id' => $request->user()->id,
        );

        $photo = Photo::create($form_data);

        $tags = $this->detectTags($new_name)->get('Labels');

        $tags = array_map(fn ($tag) => ['name' => $tag['Name'], 'confidence' => $tag['Confidence']], $tags);

        $photo->tags()->createMany($tags);
        
        return redirect('photo')->with('success', 'Data Added successfully.');
    }

    public function detectTags ($image_name)
    {
        $client = RekognitionClient::factory([
            'version'   => 'latest',
            'region'    => 'us-east-1',
            'credentials' => [
                'key' => 'AKIAIHWZRZGFABQON45Q',
                'secret' => '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'
            ]
        ]);

        return $client->detectLabels([
            'Image' => [
                'Bytes' => Storage::disk('azure')->get($image_name),
            ],
            'MaxLabels' => 10,
            'MaxConfidence' => 20,
        ]);
    }

    public function search (Request $request)
    {
        $request->validate([
            'search'         =>  'required'
        ]);

        $tagToSearch = $request->get('search');

        $photos = Photo::whereHas('tags', function (Builder $query) use($tagToSearch)
        {
            $query->where('name', 'like', '%' . $tagToSearch . '%');
        })->paginate(5);

        return view('index', compact('photos'));
    }


    public function createToken (Request $request)
    {
        $request->validate([
            'date'         =>  'required|after:now',
        ]);

        $photo = Photo::findOrFail($request->id);

        $photo->token = md5(rand(0, 1000000000));

        $photo->token_death_date = $request->date;

        $photo->save();

        return redirect('photo')->with('success', 'Token successfully created');
    }

    public function updateToken($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $photo = Photo::findOrFail($id);

        return view('view', compact('photo'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Photo::findOrFail($id);

        return view('edit', compact('data'));
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
        $image_name = $request->hidden_image;

        $image = $request->file('name');

        if ($image != '') 
        {
            $request->validate([
                'name'         =>  'required|image|max:2048',
                'description'    =>  'required'
            ]);

            $image_name = md5($image) . '.' . $image->getClientOriginalExtension();

            Storage::disk('azure')->delete(Photo::findOrFail($id)->name);

            Storage::disk('azure')->putFileAs('', $image, $image_name);

            Tag::where('photo_id', $id)->delete();

            $new_tags = $this->detectTags($image_name)->get('Labels');

            $tags = array_map(fn ($tag) => ['name' => $tag['Name'], 'confidence' => $tag['Confidence']], $new_tags);

            Photo::findOrFail($id)->tags()->createMany($tags);
        } 

        else 
        {
            $request->validate([
                'description'    =>  'required'
            ]);
        }

        $form_data = array(
            'name'            =>   $image_name,
            'description'       =>   $request->description
        );

        Photo::whereId($id)->update($form_data);

        return redirect('photo')->with('success', 'Data is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        $photo->tags()->delete();

        $photo->delete();

        return redirect('photo')->with('success', 'Data is successfully deleted');
    }
}
