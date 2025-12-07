<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ArchiveFileService
{
    /**
     * Substitui um arquivo antigo por um novo (se houver),
     * gera um nome padronizado e retorna o caminho final.
     */
    public function replaceFile($file, $year, $oldFilePath = null)
    {
        if (!$file) {
            return $oldFilePath; // nada a fazer
        }

        // Apaga o arquivo anterior, se existir
        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }

        // Monta o nome padronizado
        $name = str_replace(' ', '_', $file->getClientOriginalName());

        $fileName = $year . '_' . pathinfo($name, PATHINFO_FILENAME)
            . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Armazena
        return $file->storeAs('archives', $fileName, 'public');
    }
}
