<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadImage
{
    public function uploadImage(Request $request, $inputName, $folderName, $disk, $imageableId, $imageableType)
    {

        if (! $request->hasFile($inputName) || ! $request->file($inputName)->isValid()) {
            return null;
        }

        $photo = $request->file($inputName);
        $fileName = $photo->getClientOriginalName();
        $cleanName = Str::slug($fileName, '-').time();

        // Store the file first
        $path = $photo->storeAs($folderName, $cleanName, $disk);

        // Then create the record
        Image::create([
            'filename' => $cleanName,
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType,
        ]);

        return $path;

    }

    public function deleteImage($imageableId, $imageableType, $folderName, $disk)
    {
        $image = Image::where('imageable_id', $imageableId)->where('imageable_type', $imageableType)->first();
        if ($image) {
            Storage::disk($disk)->delete($folderName.'/'.$image->filename);
            $image->delete();
        }
    }
}
