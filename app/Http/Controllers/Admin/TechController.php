<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class TechController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): mixed
    {
        return view('admin.tech.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $config = config('company_details');
        $dir = $config['root_catalog'].'/private/files';
        $files = $_FILES;
        if (isset($files['mvk']) && ! $files['mvk']['error']) {
            $file = $dir.'/mvk.xml';
            $this->uploadFile($file, $files['mvk']['tmp_name']);
        }
        if (isset($files['p639']) && ! $files['p639']['error']) {
            $file = $dir.'/p639.xml';
            $this->uploadFile($file, $files['p639']['tmp_name']);
        }
        if (isset($files['fedsfm']) && ! $files['fedsfm']['error']) {
            $file = $dir.'/fedsfm.xml';
            $this->uploadFile($file, $files['fedsfm']['tmp_name']);
        }
        if (isset($files['pod_ft']) && ! $files['pod_ft']['error']) {
            $file = $dir.'/pod_ft.xml';
            $this->uploadFile($file, $files['pod_ft']['tmp_name']);
        }
        if (isset($files['fromu']) && ! $files['fromu']['error']) {
            $file = $dir.'/fromu.xml';
            $this->uploadFile($file, $files['fromu']['tmp_name']);
        }

        return redirect()->back();
    }
}
