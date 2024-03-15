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

2.  Change directory into the repository
    ```bash
    cd Current_Website
    ```
    
3. Copy `.env.example` to `.env`

4. Generate the application key
    ```bash
   php artisan key:generate
    ```
   
5. Update the `APP_STORAGE` variable in .env to be correct.
6. Initialize the database:
    ```bash
   php artisan migrate
   php artisan db:seed
    ```
7. Start the website:
    ```bash
   php artisan serve
    ```
8. Finally, you can log in with the username and password `10000002` (it's the username and the password). You'll automatically have all permissions assigned to you.

### Helpful Commands
| Command                                         | Action                  |
|-------------------------------------------------|-------------------------|
| `composer format`                               | Formats all PHP files   |
| `npm run format`                                | Formats all JS files    |
| `php artisan serve`                             | Start the local server  |
| `php artisan migrate`                           | Run all new migrations  |
| `php artisan make:migration [migration name]`   | Create a new migration  |
| `php artisan make:controller [controller name]` | Create a new controller |
