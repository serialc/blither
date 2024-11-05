# blither
A disc golf performance tracker for basket cases

# Installation

1. Download PHP packages with `composer update`.
2. Configure the **database connection** and **email server settings** in php/config.php (copy php/config_template.php)
3. Ensure that the apache2 rewrite module is installed with `sudo a2enmod rewrite` and then restart apache `systemctl restart apache2`.
4. Create the database tables in `../sql/make_tables.sql`.
5. You may require installing a connector between MariaDB and PHP.

Go to the home page /start and create the administrator account.
