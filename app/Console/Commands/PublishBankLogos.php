<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PublishBankLogos extends Command
{
    protected $signature = 'publish:bank-logos';
    protected $description = 'Publish bank logos to storage';

    public function handle()
    {
        $this->info('Publishing bank logos...');

        // Create banks directory if it doesn't exist
        if (!Storage::disk('public')->exists('images/banks')) {
            Storage::disk('public')->makeDirectory('images/banks');
        }

        // Bank logos data
        $logos = [
            'bca' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg',
            'mandiri' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg',
            'bni' => 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg'
        ];

        foreach ($logos as $bank => $url) {
            $contents = file_get_contents($url);
            if ($contents) {
                Storage::disk('public')->put("images/banks/{$bank}.png", $contents);
                $this->info("Published {$bank} logo");
            } else {
                $this->error("Failed to download {$bank} logo");
            }
        }

        $this->info('Bank logos published successfully!');
    }
} 