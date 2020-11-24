---
layout: post
title: Better Laravel lists
---

A wonderful pattern that I discovered this year for solving a growing eloquent problem

Problem:
Whenever you wanted to use eloquent to return data you ended up reusing the same queries over and over.

Solution:
By creating a simple rule set and filter/query services you can reuse the same query service for any controller?

### So the problem is code duplication?

Yes but there is more. The more you use the same query in a different controller the more disjointed it can be to make changes
when business rules change.

```php
    // this
    User::query()->whereHas('movies')->get();

    // turns into
    User::query()
        ->whereHas('movies')
        ->orWhereHas('theaters')
    ->get();

    // ends up looking like
    User::query()
    ->whereHas('movies', function($query) {
        $query->where('agency_id', request()->input('agency_id')
    })
    ->orWhereHas('movies', function($query) {
        $query->where('id', request()->input('movie_id');
    })
    ->orWhereHas('theaters', function($query) {
        $query->where('id', request()->input('theater_id');
    })
    ->get();
```

Growing business needs generally means changing query logic inside of controllers. However as your controllers grow it leads
to more areas that need to be maintained. I really ran into this problem when the business structure meant we had to have
multiple copies of the same controller logic but from different types of users.

* www.example.com/agencies/1/users
* www.example.com/movies/456/users
* www.example.com/theaters/789/users


As you can see we are asking the question. "return me a list of users" but from different points of view.

* A movie agency is looking for any user that has watched one of their movies.
* A movie is looking for any user that has watched this movie.
* A movie theater is looking for any user that has viewed a movie at that theater.


### Growing problem

The standard approach would be to simply rewrite the query over and over for each controller. While this is a self contained
approach it tends to mean you need to write duplicate tests around filtering users.

```php
    public function getUsersSearchByFirstNameTest();
    public function getUsersSearchByLastNameTest();
    public function getUsersSearchByEmailTest();
    public function getUsersSearchByIDTest()
```

This by itself wasn't all too bad however when we started to filter by relationships it started to show its code smell.

```php
    public function getUsersSearchByMovieTest()
    public function getUsersSearchByActorNameTest()
    public function getUsersSearchByGenreTypeTest()
```

### Changing the approach

This lead to a new approach that I coining the "Filter Query Service Pattern" (I am sure someone way smarter than me has already
found this and it has a different name. Please let me know if so). This approach changes the logic around a bit. The controller uses a service
to build the query up and it can then use it for whatever means it needs.

Here is how it looks before from an Agency controller

```php
    public function index(Agency $agency_id)
    {
       $users = User::query()
        ->whereHas('movies', function($query) {
            $query->where('agency_id', request()->input('agency_id')
        })
        ->orWhereHas('movies', function($query) {
            $query->whereIn('id', request()->input('movie_id_array');
        })
        ->get();

    return UserResource::collection($users);
    }
```

After

```php
    public function index(Agency $agency_id, UsersFilter $filter, UserQueryService $service)
    {
        $filter
            ->setMovieIds(request()->input('movie_id_array)
            ->setAgencyIds([$agency_id]);

        return UserResource::collection($service->getUsers($filter)->get());
    }
```


