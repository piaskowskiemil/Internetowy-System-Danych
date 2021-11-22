# Internetowy System Danych

The aim of the project was to develop a system in the form of a web application to manage user's private files. The web service engine was designed in PHP language, while the whole project works under control of Apache server. The web application also cooperates with the SMTP server contributing to automatic sending of e-mails. The graphic layout of the project was created using cascading style sheets.
This is my first PHP Project and I have plenty of new feature ideas to implement soon.

## Installation (Windows)

1. Download source code from this repo and place it in public_html folder or something else (make sure your PHP server is up and running)
2. Create isd_users table within empty MySQL database (or other SQL related db)
3. Build following columns: 

- id (primary key with auto increment) - type: int(11)
- nickname - type: varchar(50)
- password_db - type: varchar(255)
- email - type: varchar(100)
- mail_auth - type: varchar(50)
- storage_id - type: varchar(50)

4. SMTP server configuration is done using fake sendmail for Windows (for this project Yahoo account was used and tested)
5. Edit following parameters in sendmail.ini configuration file:
- smtp_server = smtp.mail.yahoo.com
- smtp_port = 465
- smtp_ssl = ssl
- auth_username = yourusername@yahoo.com
- auth_password = your_yahoo_password

6. PHP.INI changes

- sendmail_from = yourusername@yahoo.com
- sendmail_path = "x:\path\to\sendmail.exe -t"

7. Additional changes
- Some settings should be updated in code as for example your website URL address in activate.php and register.php files.
- SQL Database connection settings should be placed respectively in $DATABASE_HOST/USER/PASS/NAME directives for desired PHP files.
- Web application uses "user_uploads" folder which for security purposes is stored two directories above the main project folder and need to be created manually (easier installation will be implemented later..) 
- Also files user_uploads.php and delete_files.php need to be stored in "user_uploads" folder


## Usage

Simply create new user through register.php and check your mail account for activation link  and then feel free to log-in.

## License
Attribution-NonCommercial-ShareAlike:
[CC BY-NC-SA](https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode)
