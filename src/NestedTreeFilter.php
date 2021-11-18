<?php

namespace Gekich\NestedTreeFilter;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Filters\Filter;

class NestedTreeFilter extends Filter
{
    public function __construct()
    {
        $this->withMeta([
            'idKey'         => $this->idKey,
            'labelKey'      => $this->labelKey,
            'childrenKey'   => $this->childrenKey,
            'multiple'      => $this->multiple,
            'placeholder'   => $this->placeholder,
        ]);
    }

    /**
     * Multiple options select.
     *
     * @var boolean
     */

    public $multiple = true;

    /**
     * Node id key name.
     *
     * @var string
     */

    public $idKey = 'id';

    /**
     * Node label key name.
     *
     * @var string
     */

    public $labelKey = 'name';

    /**
     * Select placeholder.
     *
     * @var string
     */

    public $placeholder = 'Select...';

    /**
     * Node children key name.
     *
     * @var string
     */

    public $childrenKey = 'children';

    /**
     * Model, filter corresponds to.
     *
     * @var string
     */

    public $filterModel = \App\Models\Category::class;

    /**
     * The filter's component.
     *
     * @var string
     */

    public $filterRelation = 'categories';

    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Categories';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'nested-tree-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $filterSelected = json_decode($value, true);
        $filteredModels = $this->filterModel::find($filterSelected);

        $filteredAndDescendants = collect();
        foreach ($filteredModels as $model) {
            $filteredAndDescendants = $filteredAndDescendants->merge($model->descendants()->pluck($this->idKey));
            $filteredAndDescendants = $filteredAndDescendants->merge($model->getKey());
        }

        if ($filteredAndDescendants->isNotEmpty()) {
            return $query->whereHas($this->filterRelation, function ($q) use ($filteredAndDescendants) {
                $q->whereIn($this->idKey, $filteredAndDescendants);
            });
        }

        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return $this->filterModel::get()->toTree()->toJson();
    }
}
