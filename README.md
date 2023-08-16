# Laravel Filter Query String
#### Filter your queries based on url query string parameters like a breeze.

*Compatible with Laravel **5.x** **6.x** **7.x** **8.x** **9.x** **10.x***.

## Table of Content
- [Describing the Problem](#Describing-the-Problem)
- [Usage](#Usage)
    - [Installation](#Usage)

## Describing the Problem

You have probably faced the situation where you needed to filter your query based on given parameters in url query-string and after developing the logics, You've had such a code:

```php
$users = User::latest();

if(request('username')) {
    $users->where('username', request('username'));
}

if(request('age')) {
    $users->where('age', '>', request('age'));
}

if(request('email')) {
    $users->where('email', request('email'));
}

return $users->get();

```

This works, But it's not a good practice.

When the number of parameters starts to grow, The number of these kind of `if` statements also grows and your code gets huge and hard to maintain.
 
Also it's against the Open/Closed principal of SOLID principles, Because when you have a new parameter, You need to get into your existing code and add a new logic (which may breaks the existing implementations).

So we have to design a way to make our filters logics separated from each other and apply them into the final query, which is the whole idea behind this package.

## Usage
1. First you need to install the package:

`$ composer require ayman-els/laravel-filter-package`

2. Then you should `use` the `Filter` trait in your model, And define `$filterCols, $filterDates, $filterSearchCols, $filterColsChilds` property.

```php
use AymanEls\LaravelFilterPackage\Traits\Filter;

class Product extends Model
{
    use Filter;

    public static $filterCols = ['category_id', 'department_id', 'user_id'];
    public static $filterDates = ['from' => 'created_at', 'to' => 'created_at'];
    public static $filterSearchCols = ['name_en', 'name_ar', 'description'];
    public static $filterColsChilds = ['currency' => 'department'];

    ...
}
```
3. You need to use `filter()` method in your eloquent query. For example:

```php
Product::filter()->get();
```
assume our query is something like this:

```php
?search=field&from=2023-01-01&to=2023-01-02&category_id=1&department_id=1&user_id=1&currency=1
```

