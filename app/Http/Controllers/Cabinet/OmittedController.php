<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use App\Models\Omitted;
use App\Models\OmittedDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OmittedController extends Controller
{
    public function omitted_view(int $id)
    {
        $omitted = Omitted::find($id);
        return view('front.omitted_view', compact('omitted'));
    }

    public function all()
    {
        $omitteds = [];
        $data = Omitted::all();
        $funds = Auth::user()->funds;
        foreach ($data as $item) {
            foreach ($funds as $uFund) {
                if ($item->fund->id === $uFund->fund_id && UserService::getOmittebAccess($uFund, $item->fund)) {
                    $omitteds[] = $item;
                }
            }
        }
        return view('front.omitted_all', ['omitteds' => $omitteds]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function upload(int $id): RedirectResponse
    {
        $document = OmittedDocument::find($id);
        $config = config('company_details');
        $path = str_replace([$config['root_catalog'], '/storage/app/',], '', $document->link);
        if ($document && Storage::drive('local')->exists($path)) {
            if ($this->downloadFile(Storage::drive('local')->path($path))) {
                return redirect()->back();
            }
        }
        abort(404);
    }
}
