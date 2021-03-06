<?php

namespace App\Orchid\Layouts\Batch;

use App\Models\Site\Municity;
use App\Models\Site\Province;
use App\Models\Site\Region;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class BatchEditSiteLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Group::make([
                Relation::make('batch.region_id')
                    ->fromModel(Region::class, 'name')
                    ->required()
                    ->title('Region')
                    ->placeholder(__('Region')),

                Relation::make('batch.province_id')
                    ->fromModel(Province::class, 'name')
                    ->required()
                    ->title('Province')
                    ->placeholder(__('Province')),
            ]),
            Group::make([
                Relation::make('batch.municity_id')
                    ->fromModel(Municity::class, 'name')
                    ->required()
                    ->title('Municity')
                    ->placeholder(__('Municity')),

                Input::make('batch.barangay')
                    ->type('text')
                    ->max(255)
                    ->title(__('Barangay'))
                    ->placeholder(__('Barangay')),
            ]),
        ];
    }
}
