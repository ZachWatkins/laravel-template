<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ImportModel;
use App\Models\Location;
use App\Models\User;

class ImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $file = $request->input('file', 'storage/app/public/import.csv');
        $user = auth()->user();
        if (!$user) {
            $example = User::factory()->example()->make();
            $user = User::where('name', $example->name)->first();
        }
        ImportModel::dispatch(
            'storage/app/public/import.csv',
            Location::class,
            ['submitter_id' => $user->id]
        );
    }
}
