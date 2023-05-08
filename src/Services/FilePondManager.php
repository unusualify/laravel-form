<?php
/*
    16.12.2019
    RolesService.php
*/

namespace OoBook\LaravelForm\Services;

use OoBook\LaravelForm\Models\TemporaryFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Image;

class FilePondManager
{

    protected $tmp_file_path = 'public/fileponds/tmp/';

    protected $file_path = 'public/fileponds/';

    protected $session_prefix = "filepond";

    public function createTemporaryFile(Request $request, $test = false)
    {
        // dd($request->all());
        // $image = $request->file('logo');
        // $file_name = $image->getClientOriginalName();

        // return uniqid() . $file_name;
        // dd(
        //     $request->keys(),
        //     $request->input(),
        //     Arr::dot($request->allFiles())
        // );

        $model = $request->model;
        foreach (Arr::dot($request->allFiles()) as $name => $file) {
            # code...
            $image = $file;
            $file_name = $image->getClientOriginalName();
            $folder = uniqid('', true);
            // dd($name, $folder);

            if( !$test ){
                $this->addFilePondToSession($model . "." . $name , $folder);
                $image->storeAs( $this->tmp_file_path . $folder, $file_name);

                TemporaryFile::create([
                    'folder' => $folder,
                    'file' => $file_name,
                    'input_name' => $name,
                ]);
            }

            return $folder;
        }

        return '';
    }

    public function deleteTemporaryFile(Request $request)
    {
        $tmp_file = TemporaryFile::where('folder', trim(request()->getContent()) )->first();

        $table = $request->input('model');

        if($tmp_file){

            Storage::deleteDirectory( $this->tmp_file_path . $tmp_file->folder);

            $this->deleteFilePondFromSession($table . '.' . $tmp_file->input_name);

            $tmp_file->delete();

            return response('');
        }

        return '';
    }

    public function previewFile($folder)
    {
        if(Storage::exists($this->file_path . '/' . $folder)){
            $path = Storage::files($this->file_path . '/' . $folder)[0] ;
        }else{
            $tmp_file = TemporaryFile::where('folder', $folder)->first();
            $path= $this->tmp_file_path . $tmp_file->folder . '/' .$tmp_file->file;
        }

        // dd($path);

        $storagePath = Storage::path($path);

        ob_end_clean(); // if I remove this, it does not work

        return Image::make($storagePath)
            // ->resize(300, 200)
            ->response('jpg', 70);
    }

    public function createFile(TemporaryFile $tmp_file, $table){


        $path= $this->tmp_file_path . $tmp_file->folder . '/' .$tmp_file->file;

        $new_folder = $tmp_file->folder;

        if( Storage::exists($path) ){

            $new_path = $this->file_path . $tmp_file->folder . '/'. $tmp_file->file;

            if( Storage::exists($new_path)){
                Storage::delete($new_path);
            }

            Storage::move($path, $new_path );

            Storage::deleteDirectory( $path );

            rmdir( Storage::path( $this->tmp_file_path . $tmp_file->folder ) );

            $tmp_file->delete();

            $this->deleteFilePondFromSession( $table . '.' . $tmp_file->input_name);

            return $new_folder;
        }

        return '';

    }

    public function saveFile($folder, $table){

        $tmp_file = TemporaryFile::where('folder', $folder)->first();

        if(!!$tmp_file){
            return $this->createFile($tmp_file, $table);
        }else{
            if(Storage::exists($this->file_path . '/' . $folder)){
                return $folder;
            }else{
                return '';
            }
        }
    }

    public function deleteFile($folder){
        try {
            if( count( Storage::files($this->file_path . '/' . $folder)) > 0 ){
                Storage::deleteDirectory($this->file_path . '/' . $folder);
            }
        } catch (\Throwable $th) {
            dd( $folder, $this->file_path, $th, debug_backtrace() );
        }

    }

    public function getCachedFolders($table, $notations)
    {
        $folders = [];

        foreach ($notations as $notation) {
            $folders = array_merge($folders, $this->getFilePondsFromSession($table, $notation));
        }

        return Arr::undot($folders);
    }

    /**
     * addToSession
     *
     * @param  string $key model->getTable() . $field_name
     * @param  string $folder uniqid for filepond
     *
     * @return void
     */
    public function addFilePondToSession(string $key, string $folder)
    {
        // $folders = Session::get('folders', []);

        // array_push($folders, $folder);

        // Session::put('folders', $folders);
        // Session::push('folders', $folder);

        // Session::push('folders', 'beli');
        Session::put("{$this->session_prefix}." . auth()->user()->id . "." . $key  , $folder);
    }
    /**
     * addToSession
     *
     * @param  string $key model->getTable() . $field_name
     * @param  string $folder uniqid for filepond
     *
     * @return void
     */
    public function deleteFilePondFromSession(string $key)
    {
        Session::forget("{$this->session_prefix}." . auth()->user()->id . "." . $key  );
    }

    /**
     * addToSession
     *
     * @param  string $key model->getTable() . $field_name
     *
     * @return string $folder uniqid for filepond
     */
    public function getFilePondsFromSession($table, $notation)
    {
        // $notations = Arr::dot(Session::get("{$this->session_prefix}"));

        $cache = data_get(Session::get("{$this->session_prefix}." . (auth()->user()->id ?? 0) . ".{$table}"), $notation);

        $mapped = [];

        if(is_array($cache)){
            array_walk( $cache, function($value, $i) use(&$mapped,$notation){
                $mapped[str_replace('*', $i, $notation)] = $value;
            });

        }else if(!!$cache){
            $mapped[$notation] = $cache;
        }

        return $mapped;

        // $mapped = collect( data_get(Session::get("{$this->session_prefix}.{$table}"), $notation) )
        //     ->mapWithKeys(function($item,$i) use($notation){
        //         return [ str_replace('*', $i, $notation) => $item];
        //     })->toArray();
    }

    public function getEncodedFile($folder){

        try {
            $path = Storage::files($this->file_path . '/' . $folder)[0];
            // dd(Storage::path($path));

            return encodeImagePath( Storage::path($path) );
        } catch (\Throwable $th) {

            return '';
        }


    }


}
