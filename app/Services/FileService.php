<?php 

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;


class FileService
{
    public function updateImage($model, $request)
    {
        $image = Image::read($request->file('image'));

        if (!empty($model->avatar)) {
            $currentImage = public_path() . $model->avatar;

            if (file_exists($currentImage) && $currentImage != public_path() . '/user-placeholder.png') {
                unlink($currentImage);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $image->crop(
            $request->width,
            $request->height,
            $request->left,
            $request->top
        );

        $name = time() . '.' . $extension;
        $image->save(public_path() . '/files/' . $name);
        $model->avatar = '/files/' . $name;

        return $model;
    }

    public function addVideo($model, $request)
    {
        $video = $request->file('video');
        $extension = $video->getClientOriginalExtension();
        $name = time() . '.' . $extension;
        $video->move(public_path() . '/files/', $name);
        $model->video = '/files/' . $name;

        return $model;
    }
}