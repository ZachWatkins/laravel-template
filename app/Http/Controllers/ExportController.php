<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Jobs\ExportUserModels;
use App\Jobs\ZipUserFiles;

class ExportController extends Controller
{
    /**
     * List available downloads.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            $example = User::factory()->example()->make();
            $user = User::where('name', $example->name)->first();
        }

        $files = glob(storage_path("app/exports/{$user->id}/*"));
        foreach ($files as $key => $file) {
            // Sign routes.
            $files[$key] = Url::temporarySignedRoute(
                'download',
                now()->addMinutes(3),
                [
                    'uid' => $user->id,
                    'file' => basename($file),
                ]
            );
        }
        return $files;
    }

    /**
     * Handle the incoming request.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            $example = User::factory()->example()->make();
            $user = User::where('name', $example->name)->first();
        }

        $now = now();
        $date = $now->format('Y-m-d');
        $model = '\\App\\Models\\' . Str::studly($request->input('model', 'location'));
        $select = array_filter(explode(',', $request->input('keys', '')));
        $where = $this->whereModelUser($request, $user);
        $csv_dest = $date . '/' . $request->input('model', 'location') . '.csv';
        $zip_dest = $request->input('model', 'location') . '.zip';

        // Export the model to a CSV file.
        ExportUserModels::dispatchSync( $user->id, $csv_dest, $model, $select, $where );

        // Archive all files in the user's folder.
        ZipUserFiles::dispatchSync( $user->id, $zip_dest, $csv_dest );

        // Create a signed route for authentication.
        $url = URL::temporarySignedRoute(
            'download',
            $now->clone()->addDays(3),
            [
                'uid' => $user->id,
                'file' => $request->input('model', 'location') . '.zip',
            ]
        );

        return response()->json(['link' => $url]);
    }

    /**
     * Return a where clause scoped to the given user.
     *
     * @param Request $request Current Request object.
     * @param User    $user    User scope.
     *
     * @return array
     */
    protected function whereModelUser(Request $request, User $user): array
    {
        switch ($request->input('model')) {
            case 'location':
                return ['user_id' => $user->id];
            default:
                return [];
        }
    }
}
