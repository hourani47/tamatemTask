<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OrganizeFilesByLanguage extends Command
{
    protected $signature = 'files:organize';

    protected $description = 'Organize files into sub-folders based on language names';

    public function handle()
    {
        $sourceFolder = storage_path('app/one-k-files/files');
        $files = File::files($sourceFolder);

        $languages = [];
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            if (preg_match('/^([a-z]+)-\d+\.txt$/i', $fileName, $matches)) {
                $language = strtolower($matches[1]);
                $languages[$language][] = $fileName;
            }
        }
        foreach ($languages as $language => $files) {
            $languageFolder = storage_path('app/languageFile/' . $language);
            File::makeDirectory($languageFolder, 0755, true);

            foreach ($files as $file) {
                File::move($sourceFolder . '/' . $file, $languageFolder . '/' . $file);
            }
        }

        $this->info('Files have been organized successfully.');
    }
}
