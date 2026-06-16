<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait ImageUploadService
{

    public function Upload(Model $model, UploadedFile $file, string $disk = 'doctors'): Image
    {

        // $this->delete($model, $disk);

        $filename = $file->store('', $disk); // '' => No folder, just the filename

        return $model->image()->create([
            'imageable_id'   => $model->id,
            'imageable_type' => $model::class,
            'filename'       => basename($filename),
        ]);
    }

    public function Delete(Model $model, string $disk = 'doctors')
    {
        if ($model->image) {
            if ($model->image->filename) {
                Storage::disk($disk)->delete($model->image->filename);
            }
            $model->image()->delete();
        }
    }

    public function getUrl(Model $model, string $disk = 'doctors'): array
    {
        if (!$model->image) {
            return [
                'url' => null,
                'file' => null,
            ];
        }

        return [
            'url' => Storage::disk($disk)->url($model->image->filename),
            'file' => asset('storage/' . $model->image->filename),
        ];
    }
}
