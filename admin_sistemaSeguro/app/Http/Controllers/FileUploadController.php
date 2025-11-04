<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $user = auth()->user();
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        // Validaciones de negocio
        $errors = [];

        // 3.1: Verificar cuota
        $globalQuota = StorageConfig::getGlobalQuota();
        $userQuota = $user->groups()->max('quota_limit') ?: $globalQuota; // grupo > global
        $currentUsage = $user->usedStorage;
        $newSize = $file->getSize();

        if ($currentUsage + $newSize > $userQuota) {
            $errors[] = "Error: Cuota de almacenamiento (" . number_format($userQuota / 1024 / 1024, 2) . " MB) excedida.";
        }

        // 3.2: Verificar extensiones prohibidas
        $forbiddenExtensions = StorageConfig::getForbiddenExtensions();
        if (in_array(strtolower($extension), $forbiddenExtensions)) {
            $errors[] = "Error: El tipo de archivo '$extension' no está permitido.";
        }

        // 3.3: Analizar archivos .zip
        if ($extension === 'zip') {
            $zip = new \ZipArchive();
            if ($zip->open(storage_path("app/public/$filePath")) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $innerExt = pathinfo($filename, PATHINFO_EXTENSION);
                    if (in_array(strtolower($innerExt), $forbiddenExtensions)) {
                        $errors[] = "Error: El archivo '$filename' dentro del .zip no está permitido.";
                        break;
                    }
                }
                $zip->close();
            } else {
                $errors[] = "Error: No se pudo abrir el archivo .zip.";
            }
        }

        if (!empty($errors)) {
            return response()->json(['error' => implode(' ', $errors)], 400);
        }

        // Guardar en BD
        FileUpload::create([
            'user_id' => $user->id,
            'filename' => $fileName,
            'path' => $filePath,
            'size' => $newSize,
            'extension' => $extension,
        ]);

        return response()->json(['success' => 'Archivo subido exitosamente.']);
    }
}
