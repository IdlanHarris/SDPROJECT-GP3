## Requirements
- [Apache Server - Laragon (recommended)](https://laragon.org/)
- [Composer](https://getcomposer.org/Composer-Setup.exe)
- [PDO Pgsql enabled](https://www.php.net/manual/en/ref.pdo-pgsql.php)

## Installation

1. **Clone or Download the Project:**
    - If you have Git installed, clone the repository using:

        ```bash
        git clone https://your-github-repository-url.git
        ```

    - Alternatively, download the project ZIP file from your preferred source and extract it.

2. **Navigate to Project Directory:**
    Open your terminal or command prompt and navigate to the root directory of the downloaded project.

3. **Install Dependencies:**
    Run the following command to install project dependencies using Composer:

        ```bash
        composer install
        ```

4. **Configure Database Settings:**
    - Create a `.env` file in the project root directory.
    - This file should contain sensitive information like database credentials. Here's an example structure:

        ```
        DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
        DB_NAME=postgres
        DB_PORT=6543
        DB_USERNAME=postgres.vngbztccffaxwgmcyioh
        DB_PASSWORD=Br0ncoGymD@tabase
        ```

    **Important:**  Do not commit the `.env` file to your version control system (e.g., Git) for security reasons.

5. **Enable PDO Pgsql (IMPORTANT):**
    - Go to your php.ini file and uncomment this line `extension=pdo_pgsql`.
