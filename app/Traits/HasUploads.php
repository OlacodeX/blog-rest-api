<?php
namespace App\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
trait HasUploads
{

    public function store($file, $storage_path = '', $local_path = '', $file_name = '')
    {
         try {
            $token = Str::uuid();  
            $localfile = $file->store('tutorials');
            if ($localfile) {  
                app('firebase.storage')->getBucket()->upload(Storage::get($localfile), [
                    'name' =>  $storage_path.$file_name,
                    'metadata' => [
                        'metadata' => [
                            'firebaseStorageDownloadTokens' => $token,
                        ]
                        ]
                    ,
                ])->acl('PUBLICREAD');  
                //will remove from local laravel folder  
                Storage::delete($localfile);
            }   
            $encodedPath = urlencode($storage_path.$file_name);
            $downloadUrl = "https://firebasestorage.googleapis.com/v0/b/".config('services.firebase.project_id').".appspot.com/o/$encodedPath?alt=media&token=$token"; 
         } catch (Exception $e) {
            return $e->getMessage();
         }
        return $downloadUrl;
    }

}
