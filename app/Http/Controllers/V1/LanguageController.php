<?php
namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreLanguageRequest;
use App\Http\Requests\Language\UpdateLanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Services\LanguageService;
use App\Traits\AuthorizesWithPermission;
use App\Traits\ResponseTrait;

class LanguageController extends Controller
{
    use ResponseTrait;
    use AuthorizesWithPermission;
    public function __construct(
        protected LanguageService $languageService
    ) {}

    public function index()
    {
         $this->authorizePermission('language.view');
        $languages = $this->languageService->index();

        return $this->successResponse(
            LanguageResource::collection($languages),
            'Languages retrieved successfully.'
        );
    }

    public function store(StoreLanguageRequest $request)
    {
        $this->authorizePermission('language.create');
        $language = $this->languageService->store($request->validated());

        return $this->successResponse(
            new LanguageResource($language),
            'Language created successfully.',
            201
        );
    }

    public function show(Language $language)
    {
        $this->authorizePermission('language.view');
        return $this->successResponse(
            new LanguageResource($language),
            'Language retrieved successfully.'
        );
    }

    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $this->authorizePermission('language.update');
        $language = $this->languageService->update($language, $request->validated());

        return $this->successResponse(
            new LanguageResource($language),
            'Language updated successfully.'
        );
    }

    public function destroy(Language $language)
    {
        $this->authorizePermission('language.delete');
        $this->languageService->destroy($language);

        return $this->successResponse(
            null,
            'Language deleted successfully.'
        );
    }
}