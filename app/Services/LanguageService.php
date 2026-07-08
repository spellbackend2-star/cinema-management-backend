<?php

namespace App\Services;

use App\Models\Language;

class LanguageService
{
    public function index()
    {
        return Language::latest()->get();
    }

    public function store(array $data): Language
    {
        return Language::create($data);
    }

    public function update(Language $language, array $data): Language
    {
        $language->update($data);

        return $language->fresh();
    }

    public function destroy(Language $language): void
    {
        $language->delete();
    }
}