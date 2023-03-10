<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\ExportModel;
use App\Jobs\ArchiveUserFiles;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

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

        $files = glob(storage_path("app/exports/**/{$user->id}/*"));
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

        $select = array_filter(explode(',', $request->input('keys', '')));

        // Export the model to a CSV file.
        $now = now();
        ExportModel::dispatchSync(
            $user->id,
            $now,
            $request->input('model', 'location') . '.csv',
            '\\App\\Models\\' . Str::studly($request->input('model', 'location')),
            $select,
            $this->whereModelUser($request, $user)
        );

        // Archive all files in the user's folder.
        ArchiveUserFiles::dispatchSync(
            $user->id,
            $now,
            $request->input('model', 'location') . '.zip'
        );

        // Create a signed route for authentication.
        $expires = $now->clone()->addDays(3);
        $url = URL::temporarySignedRoute(
            'download',
            $expires,
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
                return ['submitter_id' => $user->id];
            default:
                return [];
        }
    }
}
