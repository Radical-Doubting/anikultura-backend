<?php

declare(strict_types=1);

use App\Orchid\Screens\Site\Municity\MunicityEditScreen;
use App\Orchid\Screens\Site\Municity\MunicityListScreen;
use App\Orchid\Screens\Site\Province\ProvinceEditScreen;
use App\Orchid\Screens\Site\Province\ProvinceListScreen;
use App\Orchid\Screens\Site\Region\RegionEditScreen;
use App\Orchid\Screens\Site\Region\RegionListScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Sites > Regions
Route::screen('sites/regions', RegionListScreen::class)
    ->name('platform.sites.regions')
    ->middleware(['access:platform.sites.read'])
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Regions'), route('platform.sites.regions'));
    });

// Sites > Regions > Edit Region
Route::screen('sites/regions/{region}/edit', RegionEditScreen::class)
    ->name('platform.sites.regions.edit')
    ->middleware(['access:platform.sites.edit'])
    ->breadcrumbs(function (Trail $trail, $region) {
        return $trail
            ->parent('platform.sites.regions')
            ->push(__('Edit Region'), route('platform.sites.regions.edit', $region));
    });

// Sites > Regions > Create Region
Route::screen('sites/regions/create', RegionEditScreen::class)
    ->name('platform.sites.regions.create')
    ->middleware(['access:platform.sites.edit'])
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.sites.regions')
            ->push(__('Create Region'), route('platform.sites.regions.create'));
    });

// Sites > Provinces
Route::screen('sites/provinces', ProvinceListScreen::class)
    ->name('platform.sites.provinces')
    ->middleware(['access:platform.sites.read'])
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Provinces'), route('platform.sites.provinces'));
    });

// Sites > Provinces > Edit Province
Route::screen('sites/provinces/{province}/edit', ProvinceEditScreen::class)
    ->name('platform.sites.provinces.edit')
    ->middleware(['access:platform.sites.edit'])
    ->breadcrumbs(function (Trail $trail, $province) {
        return $trail
            ->parent('platform.sites.provinces')
            ->push(__('Edit Province'), route('platform.sites.provinces.edit', $province));
    });

// Sites > Provinces > Create Province
Route::screen('sites/provinces/create', ProvinceEditScreen::class)
    ->name('platform.sites.provinces.create')
    ->middleware(['access:platform.sites.edit'])
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.sites.provinces')
            ->push(__('Create Province'), route('platform.sites.provinces.create'));
    });

// Sites > Municipalities or cities
Route::screen('sites/municities', MunicityListScreen::class)
    ->name('platform.sites.municities')
    ->middleware(['access:platform.sites.read'])
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Municipalities and Cities'), route('platform.sites.municities'));
    });

// Sites > Municities > Edit Municity
Route::screen('sites/municities/{municity}/edit', MunicityEditScreen::class)
    ->name('platform.sites.municities.edit')
    ->middleware(['access:platform.sites.edit'])
    ->breadcrumbs(function (Trail $trail, $municity) {
        return $trail
            ->parent('platform.sites.municities')
            ->push(__('Edit Municipality or City'), route('platform.sites.municities.edit', $municity));
    });

// Sites > Municities > Create Municity
Route::screen('sites/municities/create', MunicityEditScreen::class)
    ->name('platform.sites.municities.create')
    ->middleware(['access:platform.sites.edit'])
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.sites.municities')
            ->push(__('Create Municipality or City'), route('platform.sites.municities.create'));
    });
