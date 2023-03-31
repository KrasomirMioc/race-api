> **Task for PHP Senior Developer**
>
> Your task will be to create a simple api for importing and showing
> race results. You should use with and cover the solution with tests.
>
> Bonus points will go to the solutions that are optimized and written
> with clean code using design patterns.
>
> ***Intro***

> What's expected of you:
> 
> - Importing a CSV list of results for a race
> - Calculate the average finish time & placement of runners
> - Showing the imported results
> - Ability to edit a result

> ***Import results***
>
> Create an endpoint for importing results from a CSV file. The endpoint
> should also receive the race title and date. Here is an example of CSV
> file content:
>
> fullName,distance,time,ageCategory\
> Matthias Floyd,medium,5:15:24,M18-25\
> Toby Phillips,long,4:07:45,M26-34\
> Paloma Mclean,long,4:04:31,F18-25\
> Willow Brock,medium,3:04:30,M18-25\
> Alissa Harris,long,5:04:24,F18-25\
> Dania Travis,long,6:04:12,F26-34\
> Lorena Villegas,medium,2:09:31,F26-34\
> Marc Rivera,long,6:23:14,M26-34\
> Ryan Roberts,long,6:15:45,M26-34\
> Sergio Spears,medium,2:13:45,M35-43
>
> All the fields are required and distance should be medium or long. Age
> category doesn't have predefined values, so it should accept any
> string.
> 
> Process the file and save the race and results to the database along with the
calculated placements. Shorter time gets the better placement. For each long distance
result save the following placements:
> 
> - **overall placement** for all results together
> - **age category placement** for each age category separately


> Medium distance results don't have placements.

> Make sure to optimize the import for a larger number of results
> (10-20k).
>
> The expected placements for the example CSV data:

| **Full name** | **Finish time** | **Overall placement** | **Age category placement** | **Age category** |
| Paloma Mclean | 4:04:31 | 1 | 1 | F18-25 |
| Toby Phillips | 4:07:45 | 2 | 1 | M26-34 |
| Alissa Harris | 5:04:24 | 3 | 2 | F18-25 |
| Dania Travis | 6:04:12 | 4 | 1 | F26-34 |
| Ryan Roberts | 6:15:14 | 5 | 2 | M26-34 |
| Marc Rivera | 6:23:14 | 6 | 3 | M26-34 |


> ***Get races collection***
> 
> Create an endpoint which returns results by a given race. Response should contain the
following **fields**:
> 
> - Race title
> - Race date
> - Average finish time for medium distance
> - Average finish time for long distance
> 
> Create following **filters** for this endpoint:
> 
> - Race title
> - Order filter for all returned fields
> - 

> ***Get results by race***
> 
> Create an endpoint which returns results by a given race. Response should contain the
following **fields**:
> 
> - Racer full name
> - Finish time
> - Distance
> - Age category
> - Overall place
> - Age category place


> Create following **filters** for this endpoint:
> 
> - Racer full name
> - Order filter for all returned fields
> - Distance
> - Age category


> ***Edit single result***
> 
> Create an endpoint for editing a single result. All fields except the
> placement fields can be edited.
>
> 
> 
> How to install:
> 
> ```php
> clone this repository
> start symfony server
> run the migration
> 
> go to /api route
> ```


