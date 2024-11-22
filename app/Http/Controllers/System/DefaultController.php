<?php

namespace App\Http\Controllers\System;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends BaseController
{
    use AuthorizesRequests,
        ValidatesRequests;

    /**
     * Chose locale.
     */
    public function locale(string $locale = null)
    {
        !empty($locale) ? Session::put('locale', $locale) : Session::remove('locale');

        return Redirect::back();
    }

    /**
     * Returns translation messages file in JSON format.
     */
    public function translations(string $locale)
    {
        $cacheKey = 'translations_' . $locale;

        if (!is_dir(lang_path($locale))) {
            throw new NotFoundHttpException();
        }

        $json = Cache::remember($cacheKey, 10, function () use ($locale) {
            $json = collect(File::allFiles(lang_path($locale)))
                ->flatMap(function ($file) {
                    return [
                        $translation = $file->getBasename('.php') => trans($translation),
                    ];
                })
                ->toJson();

            return $json;
        });

        return response('window.i18n = ' . $json . ';')
            ->header('Content-Type', 'text/javascript');
    }
}
