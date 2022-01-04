<?php

namespace App\Actions\Batch;

use App\Models\Batch\Batch;
use App\Traits\AsOrchidAction;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Orchid\Support\Facades\Toast;

class CreateBatch
{
    use AsAction;

    use AsOrchidAction;

    public function handle(Batch $batch, $batchData)
    {
        $batch
            ->fill($batchData)
            ->save();

        $batch
            ->farmers()
            ->sync($batchData['farmers']);
    }

    public function asOrchidAction(Batch $batch, Request $request)
    {
        $batchData = $request->get('batches');

        $this->handle($batch, $batchData);

        Toast::info(__('Batch was saved successfully.'));

        return redirect()->route('platform.batches');
    }

    public function rules(): array
    {
        return [
            'batches.assigned_farmschool_name' => [
                'required',
            ],
            'batches.number_seeds_distributed' => [
                'required',
            ],
            'batches.region_id' => [
                'required',
            ],
            'batches.province_id' => [
                'required',
            ],
            'batches.municity_id' => [
                'required',
            ],
            'batches.farmers' => [
                'required',
                'array',
            ],
        ];
    }
}