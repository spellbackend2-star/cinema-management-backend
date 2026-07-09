<?php

namespace App\services;

use App\Models\Person;

class PeopleService
{
    public function index()
    {
        return Person::latest()->get();
    }

    public function store(array $data): Person
    {
        return Person::create($data);
    }

    public function update(Person $language, array $data): Person
    {
        $language->update($data);

        return $language->fresh();
    }

    public function destroy(Person $language): void
    {
        $language->delete();
    }
}
