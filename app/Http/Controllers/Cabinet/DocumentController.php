<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Services\DocumentService;
use App\Models\UserDocument;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JsonException;

final class DocumentController extends Controller
{
    public function list(): Application|Factory|View|App
    {
        $documents = Auth::user()->documents;

        return view('front.document_list', compact('documents'));
    }

    /**
     * @throws JsonException
     */
    public function signDocument(int $id, Request $request): Application|Factory|View|App|RedirectResponse
    {
        $document = UserDocument::find($id);
        if ($request->method() === 'GET') {
            DocumentService::setSignCodeOfDocument($document);

            return view('front.sign_document', compact('document'));
        }
        if ($request->method() === 'POST') {
            $data = $request->validate([
                'sms_code' => ['required'],
                '_token' => ['required'],
            ]);
            if ($document->sign_code === $data['sms_code'] && ! $document->sign_status) {
                $document->sign_status = 1;
                if (DocumentService::createSignDocument($document, (string) $data['sms_code']) && $document->save()) {
                    return redirect(route('documents'));
                }
            }
        }
        abort(404);
    }
}
