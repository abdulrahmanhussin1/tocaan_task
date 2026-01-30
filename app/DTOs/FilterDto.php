<?php

namespace App\DTOs;

readonly class FilterDto
{
    public ?string $search;
    public int $perPage;
    public string $sortBy;
    public string $sortDir;

    public function __construct(
        ?string $search = null,
        int $perPage = 15,
        string $sortBy = 'id',
        string $sortDir = 'desc'
    ) {
        $this->search  = $search;
        $this->perPage = $perPage;
        $this->sortBy  = $sortBy;
        $this->sortDir = $sortDir;
    }

    public static function fromRequest($request): static
    {
        return new static(
            search: $request->input('search'),
            perPage: (int) $request->input('per_page', 15),
            sortBy: $request->input('sort_by', 'id'),
            sortDir: $request->input('sort_dir', 'desc'),
        );
    }

    public function sort(): array
    {
        return [$this->sortBy => $this->sortDir];
    }

    public function toArray(): array
    {
        return [
            'search'  => $this->search,
            'perPage' => $this->perPage,
            'sortBy'  => $this->sortBy,
            'sortDir' => $this->sortDir,
        ];
    }
}
