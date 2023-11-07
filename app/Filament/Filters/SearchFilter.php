<?php

namespace App\Filament\Filters;

use Filament\Forms;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Lang;
use Str;

class SearchFilter extends BaseFilter
{
    protected bool $isRtlInput = true;
    protected bool $isEncodeUtf8 = false;
    protected bool $isSanitizeInput = false;
    protected bool $isConcatColumns = false;
    protected bool $isLabelWithSearchPrefix = true;
    protected ?string $indicatorLabel = null;
    protected ?string $relation = null;
    protected array $searchColumns = [];

    protected function setUp(): void
    {
        parent::setUp();

        $filterName = strtolower($this->getName());
        $this->searchColumns = [$filterName];

        if (Str::contains($filterName, '.')) {
            $this->name(Str::of($filterName)->replace('.', '_')->toString());
            $this->relation = Str::of($filterName)->beforeLast('.')->toString();
            $this->searchColumns[0] = Str::of($filterName)->afterLast('.')->toString();
        }

        $this->getSetUpForm();
        $this->getSetUpQuery();
        $this->getSetUpIndicate();
    }

    protected function getSetUpForm(): void
    {
        $inputDirection = $this->isRtlInput ? 'rtl' : 'ltr';

        $this->form([
            Forms\Components\TextInput::make('value')
                ->label($this->getLabel())
                ->extraInputAttributes(['dir' => $inputDirection])
                ->lazy(),
        ]);
    }

    protected function getSetUpQuery(): void
    {
        $this->query(fn(Builder $query, array $data) =>
            $query->when(
                $data['value'],
                fn(Builder $query, string $input) => (! is_null($this->relation))
                    ? $this->getRelationQuery($query, $input)
                    : $this->getSearchQuery($query, $input)
            )
        );
    }

    protected function getSetUpIndicate(): void
    {
        $this->indicateUsing(
            fn($state) => (filled($state['value'] ?? null))
                ? [sprintf('%s: %s', $this->indicatorLabel, $state['value'])]
                : []
        );
    }

    public function rtlInput(bool $condition = true): static
    {
        $this->isRtlInput = $condition;

        $this->getSetUpForm();

        return $this;
    }

    public function encodeToUtf8SearchInput(bool $condition = true): static
    {
        $this->isEncodeUtf8 = $condition;

        return $this;
    }

    public function sanitizeSpaceInSearchInput(bool $condition = true): static
    {
        $this->isSanitizeInput = $condition;

        return $this;
    }

    public function concatColumnsInQuery(bool $condition = true): static
    {
        $this->isConcatColumns = $condition;

        return $this;
    }

    public function labelWithSearchPrefix(bool $condition = true): static
    {
        $this->isLabelWithSearchPrefix = $condition;

        return $this;
    }

    public function searchFullName(array $columns = ['first_name', 'last_name']): static
    {
        $this->searchColumns = $columns;

        $this->isEncodeUtf8 = true;
        $this->isSanitizeInput = true;
        $this->isConcatColumns = true;

        return $this;
    }

    public function relation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function queryColumns(array $columns): static
    {
        $this->searchColumns = $columns;

        return $this;
    }

    public function label(string | \Closure | null $label): static
    {
        parent::label($label);

        $this->getSetUpForm();
        $this->getSetUpIndicate();

        return $this;
    }

    public function getLabel(): string
    {
        $label = $this->evaluate($this->label);

        if (blank($label)) {
            $filterName = $this->getName();
            $label = ucwords(Str::of($filterName)->camel()->snake(' '));

            if (Lang::has("app.fields.{$filterName}")) {
                $label = Lang::get("app.fields.{$filterName}");
            }

            if (Lang::has("app.commons.fields.{$filterName}")) {
                $label = Lang::get("app.commons.fields.{$filterName}");
            }
        }

        if ($this->shouldTranslateLabel) {
            $label = __($label);
        }

        $this->indicatorLabel = $label;

        if ($this->isLabelWithSearchPrefix) {
            $label = Lang::get('app.commons.labels.search_for') . ' ' . $label;
        }

        return $label;
    }

    private function getRelationQuery(Builder $query, string $input): Builder
    {
        return $query->whereHas($this->relation, fn(Builder $query) => $this->getSearchQuery($query, $input));
    }

    private function getSearchQuery(Builder $query, string $input): Builder
    {
        if ($this->isSanitizeInput) {
            $input = str_replace(' ', '%', $input);
        }

        if ($this->isEncodeUtf8) {
            $input = utf8_encode($input);
        }

        if (count($this->searchColumns) > 1) {
            $query->where(function (Builder $query) use ($input) {
                if ($this->isConcatColumns) {
                    $columns = implode(", ' ', ", $this->searchColumns);
                    return $query->whereRaw("CONCAT({$columns}) LIKE ?", ["%{$input}%"]);
                }

                foreach ($this->searchColumns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$input}%");
                }

                return $query;
            });
        } else {
            $query->where($this->searchColumns[0], 'LIKE', "%{$input}%");
        }

        return $query;
    }
}
