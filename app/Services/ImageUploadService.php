<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait ImageUploadService
{
   
    public function Upload(Model $model, UploadedFile $file, string $disk = 'doctors') : Image {

        // $this->delete($model, $disk);

        $filename = $file->store('', $disk); # '' => No folder, just the filename

        return $model->image()->create([
            'imageable_id'   => $model->id,
            'imageable_type' => $model::class,
            'path'           => basename($filename),
        ]);
    }

    public function Delete(Model $model, string $disk = 'doctors') {
        if($model->image) {
            $model->image()->delete();  
            Storage::disk($disk)->delete($model->image->path);
        } 
    }

    public function getUrl(Model $model, string $disk = 'doctors') : array {
        return [
            'url' => Storage::disk($disk)->url($model->image->path),
            'file' => asset('storage/' . $model->image->path),
        ];
    }

}