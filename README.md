# Laravel Nova Nested Tree Filter
custom nested tree filter for laravel nova.

![Example usage](https://i.imgur.com/3R0cfqU.png)

> This filter uses tree provided by kalnoy/nestedset package

> This filter uses riophae/vue-treeselect under the hood 

## Install

```
composer require gekich/nested-tree-filter
```
Create new filter using, nova:filter, for example:
```
php artisan nova:filter CategoryFilter
```
Make your newly created class extend ```Gekich\NestedTreeFilter\NestedTreeFilter```: 
```php
use Gekich\NestedTreeFilter\NestedTreeFilter;
...
class CategoryFilter extends NestedTreeFilter 
{
//
}
```
Remove all code from newly created class CategoryFilter, and setup: 
```php

class CategoryFilter extends NestedTreeFilter
{
    public $filterModel = \App\Category::class; // - nested tree model 
    public $filterRelation = 'categories'; // - relation that filter uses
    public $name = 'Categories filter'; // - filter name
    public $idKey = 'id'; // - id column
    public $labelKey = 'name'; // - label column name
}

```

Also there are placeholder option
```php 

    public $placeholder = 'Select...'; 
```

And multiple select option
```php 

    public $multiple = true;
```

## Usage 


Filter is ready to use. You can apply this filter to filter to resource relation that set in ```$filterRelation```


## Contributing

Feel free to suggest changes, ask for new features or fix bugs yourself. 

Hope this package will be usefull for you.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
