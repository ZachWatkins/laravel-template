<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\ExportModel;
use App\Jobs\ArchiveUserFiles;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class ExportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            $example = User::factory()->example()->make();
            $user = User::where('name', $example->name)->first();
        }

        // Define job parameters.
        $model = '\\App\\Models\\' . Str::studly($request->input('model'));
        $select = explode(',', $request->input('keys', ''));
        $where = $this->whereUser($request, $user);

        // Export the model to a CSV file.
        ExportModel::dispatchSync(
            $user->id,
            $request->input('model') . '.csv',
            $model,
            $select,
            $where
        );

        // Archive all files in the user's folder.
        ArchiveUserFiles::dispatchSync($user->id, $request->input('model') . '.zip');

        $url = Url::temporarySignedRoute(
            'download',
            now()->addDays(3),
            [
                'user' => $user->id,
                'file' => $request->input('model') . '.zip',
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
    protected function whereUser(Request $request, User $user): array
    {
        if ($request->input('model') === 'location') {
            return ['submitter_id' => $user->id];
        }
        return [];
    }
}
