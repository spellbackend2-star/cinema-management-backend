<?php
namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreLanguageRequest;
use App\Http\Requests\Language\UpdateLanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Services\LanguageService;
use App\Traits\ResponseTrait;

class LanguageController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected LanguageService $languageService
    ) {}

    public function index()
    {
        $languages = $this->languageService->index();

        return $this->successResponse(
            LanguageResource::collection($languages),
            'Languages retrieved successfully.'
        );
    }

    public function store(StoreLanguageRequest $request)
    {
        $language = $this->languageService->store($request->validated());

        return $this->successResponse(
            new LanguageResource($language),
            'Language created successfully.',
            201
        );
    }

    public function show(Language $language)
    {
        return $this->successResponse(
            new LanguageResource($language),
            'Language retrieved successfully.'
        );
    }

    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $language = $this->languageService->update($language, $request->validated());

        return $this->successResponse(
            new LanguageResource($language),
            'Language updated successfully.'
        );
    }

    public function destroy(Language $language)
    {
        $this->languageService->destroy($language);

        return $this->successResponse(
            null,
            'Language deleted successfully.'
        );
    }
}