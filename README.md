# ZTL ARTCC Website
This is the main website for the vZTL ARTCC, part of the VATSIM Network.

### Running Locally
#### Pre-requisites
* [git](https://git-scm.com/downloads/)
* [Docker](https://www.docker.com/get-started/) - Must be installed and running
* [PHP v8.0.13](https://www.php.net/)
* [Node v8.2.0](https://nodejs.org/en/)
* _Recommended on UNIX based platforms for managing PHP and Node versions_ [asdf](https://asdf-vm.com/)

> 💡 If you are not on a UNIX based platform (like MacOS or Linux), it is recommended to use a terminal that is compatible with UNIX style commands ([git bash](https://gitforwindows.org/), [WSL](https://learn.microsoft.com/en-us/windows/wsl/install), etc.)

#### Setting up
1. Clone the repository
    ```bash
    git clone https://github.com/ZTL-ARTCC/Current_Website.git
    ```

1.  Change directory into the repository
    ```bash
    cd Current_Website
    ```

1. Run the setup script
    ```bash
    ./scripts/setup.sh
    ```

    > 💡 The docker MySQL database container is configured to always start when the docker daemon starts. If it does not start automatically, you can manually start it with the following script
    > ```bash
    > ./scripts/start-db.sh

1. Finish configuring environment variables in `.env` (Note that the database configuration is set properly for the MySQL database created in Docker)

1.  To give yourself admin privileges in the local environment, follow these instructions
    1. In `routes/web.php`, uncomment the following lines (at the bottom of the file) and replace the default CID with your CID
        ```php
        Route::get('/laratrust', function () {
            $user = App\User::find(1315134);
            $user->attachRole('wm');
        });
        ```
    1. Run the command `php artisan serve`
    1. Navigate to `http://localhost:8000/laratrust`
    1. Stop the server and re-comment out those lines

1. Start the local environment with `php artisan serve` and the website will be running locally on `http://localhost:8000`!

### Helpful Commands
| Command                                          | Action                   |
| ------------------------------------------------ | ------------------------ |
| `composer format`                                | Formats all PHP files    |
| `npm run format`                                 | Formats all JS files     |
| `php artisan serve`                              | Start the local server   |
| `php artisan migrate`                            | Run all new migrations   |
| `php artisan make:migration [migration name]`    | Create a new migration   |
| `php artisan make:controller [controller name]`  | Create a new controller  |
