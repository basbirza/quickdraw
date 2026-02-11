<?php

namespace App\Filament\Resources\HeroImageCampaignResource\Pages;

use App\Filament\Resources\HeroImageCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeroImageCampaigns extends ListRecords
{
    protected static string $resource = HeroImageCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
