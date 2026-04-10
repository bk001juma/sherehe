<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeleteImageController extends Controller
{
    function getAllWhatsappImages()
    {
        $imageFolder = public_path('whatsapp_images');

        if (!File::exists($imageFolder)) {
            return []; // Hakuna folder wala picha
        }

        $files = File::files($imageFolder);

        $images = [];

        foreach ($files as $file) {
            // Hakikisha ni picha pekee (.jpg, .jpeg, .png, .gif, etc)
            if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $images[] = [
                    'name' => $file->getFilename(),
                    'path' => asset('whatsapp_images/' . $file->getFilename()),
                    'size_kb' => round($file->getSize() / 1024, 2),
                    'created_at' => date('Y-m-d H:i:s', $file->getCTime()),
                ];
            }
        }

        return $images;
    }

    function deleteAllWhatsappImages()
    {
        $imageFolder = public_path('whatsapp_images');

        if (!File::exists($imageFolder)) {
            return "Folder halipo.";
        }

        $files = File::files($imageFolder);

        $deletedCount = 0;

        foreach ($files as $file) {
            // Futa tu picha zinazomalizika na aina hizi
            if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                File::delete($file->getPathname());
                $deletedCount++;
            }
        }

        return "Jumla ya picha zilizofutwa: {$deletedCount}";
    }



    function getAllWelcomeNotes()
    {
        $folderPath = public_path('welcome_notes');

        if (!File::exists($folderPath)) {
            return [];
        }

        $files = File::files($folderPath);

        $images = [];

        foreach ($files as $file) {
            if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $images[] = [
                    'name' => $file->getFilename(),
                    'path' => asset('welcome_notes/' . $file->getFilename()),
                    'size_kb' => round($file->getSize() / 1024, 2),
                    'created_at' => date('Y-m-d H:i:s', $file->getCTime()),
                ];
            }
        }

        return $images;
    }

    function deleteWelcomeNotesExceptOne()
    {
        $excludedFilename = 'welcome_note_6861334be9c6c.jpeg';
        $folderPath = public_path('welcome_notes');

        if (!File::exists($folderPath)) {
            return "Folder halipo.";
        }

        $files = File::files($folderPath);
        $deletedCount = 0;

        foreach ($files as $file) {
            if (in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                if ($file->getFilename() !== basename($excludedFilename)) {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }
            }
        }

        return "Jumla ya picha zilizofutwa (isipokuwa {$excludedFilename}): {$deletedCount}";
    }
}
