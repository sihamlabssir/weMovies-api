# WeMovies API


## Introduction

WeMovies API is a restfull API that interacts with a 3d party API The movie database (themoviedb), 
it exposes different APIs to be used to manage movies, list genres and rate a given movie

## Main features
- Fetch the current Top Rates movie
- Fetch list of genres
- Fetch movies by genre
- Search movies by keyword
- Rate Movies (this one only makes POST requests as TMDB is responding with a 401)

## Technical details
Technical stack
- PHP 8.2
- Symfony 7.1
- HttpClient
- Redis
- PhpUnit
- Composer

WeMovies API is built using Clean Architecture principles, which promotes a clear separation of concerns across different layers:
- Application Layer: This layer contains the business logic and orchestrates the application's use cases. It defines the operations that can be performed and coordinates the flow of data between the Domain and UI layers. 
- Domain Layer: The core of the application resides here. It encapsulates the business rules and domain entities.
- Infrastructure Layer: This layer handles the technical details and integrations with external systems (TMDB for instance), It contains the implementation of interfaces defined in the Domain layer, allowing for flexibility and easy replacement of external dependencies. 
- UI Layer (presentation): This layer manages the user interface and user experience. It interacts with the Application layer to display data to users ensuring a clean separation from the business logic.

## How to run the api

### `make build`
This will build both php and nginx images based on the Dockerfile located in the root of the project, runs composer install to download the list of vendors needed

### `make start`
Runs the app in the development mode.\
Open [http://localhost:8080](http://localhost:3000) to view it in your browser or test in API tools like postman.

### `make stop`
To stop the containers

### `make test`
This will run the unit tests using phpUnit

### `make phpstan`
This will perform the static codebase analysis (currently it's on level 5)

### `make phpcs`
This will perform a check of the coding styles

### `make phpcsfix`
This will fix any coding styles errors or warning if possible

### Nice to Have
- Build Functional tests
- Implement tests for controllers
- Build an OpenApi documentation using swagger
