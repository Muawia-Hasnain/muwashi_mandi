<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        SitemapGenerator::create(config('app.url'))
            ->hasCrawled(function (Url $url) {
                // Ignore unwanted paths
                if (str_contains($url->path(), '/admin') || 
                    str_contains($url->path(), '/login') || 
                    str_contains($url->path(), '/register') ||
                    str_contains($url->path(), '/dashboard') ||
                    str_contains($url->path(), '/chats') ||
                    str_contains($url->path(), '/payments')) {
                    return;
                }

                // Set priorities
                if ($url->path() === '/') {
                    $url->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY);
                } elseif (str_contains($url->path(), '/ads/')) {
                    $url->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY);
                }

                return $url;
            })
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
