# laravel-test-e2e-integration-assignment-3

## Laravel Dusk End-to-End Browser Testing for Authentication Process Flow Verification

This project focuses on performing end-to-end browser testing for the authentication process in a Laravel application using **Laravel Dusk** with **MySQL** database. It leverages **Laravel Breeze** for the authentication scaffolding, which uses Blade templates and Alpine.js for a robust and user-friendly experience.

---

### Breeze Authentication (Blade with Alpine)

Laravel Breeze provides a simple and effective way to scaffold authentication features.

#### Installation Steps:

To get started with the Breeze authentication in your project, follow these steps:

-   **Install Laravel Breeze:**

    ```bash
    composer require laravel/breeze --dev
    ```

-   **Scaffold Breeze Components:**

    ```bash
    php artisan breeze:install
    ```

-   **Run Database Migrations:**

    ```bash
    php artisan migrate
    ```

-   **Generate NPM Build:**

    ```bash
    npm run build
    ```

---

### Laravel Dusk Setup

Laravel Dusk is a powerful browser automation testing tool. Here's how to set it up for your project:

-   **Install Laravel Dusk:**
    ```bash
    composer require --dev laravel/dusk
    ```
-   **Scaffold Dusk Components:**
    ```bash
    php artisan dusk:install
    ```
-   **Create `.env.dusk.local`:**

    ```bash
    cp .env .env.dusk.local
    ```

    This command creates a separate environment file specifically for Dusk tests, preventing conflicts with your main application environment.

-   **Edit MySQL `.env.dusk.local`:**
    Open the newly created `.env.dusk.local` file and configure it as follows:

    ```ini
    APP_NAME=DuskIntegrationE2ETesting
    APP_ENV=dusk.local
    APP_KEY=base64:fUUR4u1gv1UOnUWmc1KvBbrdc/Y5Uv6IDDDIAZRixNQ=
    APP_DEBUG=true
    APP_URL=http://localhost:8000

    # MySQL Test Database Configuration
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1 # or your MySQL host
    DB_PORT=3306 # default MySQL port
    DB_DATABASE=test_database_dusk # dedicated test database
    DB_USERNAME=root # or your MySQL username
    DB_PASSWORD=123456 # your MySQL password

    SESSION_DRIVER=array
    CACHE_DRIVER=array
    QUEUE_CONNECTION=sync
    MAIL_MAILER=array

    SESSION_DRIVER=array
    CACHE_DRIVER=array
    QUEUE_CONNECTION=sync
    MAIL_MAILER=array

    # ChromeDriver Specific Configurations
    DUSK_DRIVER_URL=http://localhost:9515
    CHROME_PATH=/usr/bin/google-chrome # Replace with your Google Chrome browser path
    DUSK_HEADLESS=false # Set to `false` for real-time browser interaction during tests; `true` for hidden background testing.
    ```

    **Note:**
    -   **MySQL Configuration**: If you prefer to use MySQL for your Dusk tests, uncomment the MySQL section and provide your database credentials. Ensure the `test_database_dusk` database exists.
    -   **`CHROME_PATH`**: Verify and update this path to the correct installation location of Google Chrome on your system.
    -   **`DUSK_HEADLESS`**: Set this to `false` if you want to see the browser opening and interacting during the tests, which is useful for debugging. For continuous integration or hidden testing, set it to `true`.

-   **Laravel Optimize Clear Once:**

    ```bash
    php artisan optimize:clear
    ```

    This command clears various cached files to ensure your application runs with the latest configurations.

---

### Running Your Laravel Application and Dusk Tests

To run your application and execute Dusk tests, you'll need two separate terminal instances:

-   **Run Laravel Server (Terminal 1):**

    ```bash
    php artisan serve
    ```

    This will start your Laravel development server, typically on `http://localhost:8000`.

-   **Run Laravel Dusk (Terminal 2):**
    ```bash
    php artisan dusk
    ```
    This command will execute your Dusk browser tests against the running Laravel application.

---

### Laravel Dusk Authentication Test Flow Steps

This project utilizes Laravel Breeze for authentication, providing a robust and easy-to-use scaffolding with Blade templates and Alpine.js.

-   **Make RegisterTest:** `tests/Browser/RegisterTest.php`

    ```bash
    php artisan dusk:make RegisterTest
    ```

    This command will create a new Dusk test file at `tests/Browser/RegisterTest.php`, where you can write scenarios to verify the user registration process.

-   **Make LoginTest:** `tests/Browser/LoginTest.php`
    ```bash
    php artisan dusk:make LoginTest
    ```
    This command will create a new Dusk test file at `tests/Browser/LoginTest.php`, where you can write scenarios to verify the user login process, including successful authentication and redirection.

---

### Browser Compatibility for Dusk

Laravel Dusk primarily uses ChromeDriver to automate Google Chrome. However, it can be configured to work with other browsers via custom drivers.

| Browser            | Supported via Custom Driver                                        | Notes                                                                                          |
| :----------------- | :----------------------------------------------------------------- | :--------------------------------------------------------------------------------------------- |
| **Google Chrome**  | ✅ Yes                                                             | Default and most thoroughly supported.                                                         |
| **Chromium**       | ✅ Yes                                                             | Same setup as Chrome; just change the binary path in `CHROME_PATH`.                            |
| **Firefox**        | ✅ Yes (via [GeckoDriver](https://github.com/mozilla/geckodriver)) | Works well using Selenium-compatible RemoteWebDriver setup.                                    |
| **Microsoft Edge** | ✅ Yes (via EdgeDriver)                                            | Must be manually configured similar to ChromeDriver. Download the correct EdgeDriver version.  |
| **Safari**         | ⚠️ Limited                                                         | Works only on macOS. Requires enabling Safari's Remote Automation in the Developer menu.       |
| **Opera**          | ⚠️ Possible                                                        | Requires configuration via Chromium/OperaDriver; not officially recommended or well-supported. |
| **Brave**          | ✅ Chrome-compatible                                               | Can work by pointing `CHROME_PATH` to Brave's executable binary, as it's Chromium-based.       |

---

### ChromeDriver Verification and Management (Linux Ubuntu Example)

Ensuring ChromeDriver is correctly installed and running is crucial for Dusk tests.

-   **Verify Google Chrome Installation:**

    ```bash
    google-chrome --version   # Should show your installed Chrome version (e.g., Google Chrome 138.0.x.x)
    ```

-   **Verify ChromeDriver Installation:**

    ```bash
    chromedriver --version  # Should show a version compatible with your Chrome (e.g., ChromeDriver 138.0.x.x)
    ```

-   **Locate Chrome Executable Path:**

    ```bash
    which google-chrome    # Should show the path to your Chrome executable (e.g., /usr/bin/google-chrome)
    ```

-   **Locate ChromeDriver Executable Path:**

    ```bash
    which chromedriver   # Should show the path to your chromedriver executable (e.g., /usr/local/bin/chromedriver)
    ```

-   **Ensure ChromeDriver is Running:**
    Laravel Dusk uses ChromeDriver under the hood. You can manage it with:
    ```bash
    php artisan dusk:chrome-driver # This command downloads and manages the ChromeDriver for you.
    ```
    Alternatively, you can run it directly:
    ```bash
    ./vendor/laravel/dusk/bin/chromedriver-linux # (Or chromedriver-mac, chromedriver-win.exe depending on your OS)
    ```
    When running `php artisan dusk`, Dusk automatically starts and stops the ChromeDriver process unless you have a custom setup.

---
