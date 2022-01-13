# Social DC club

### PHP v7.4.26
### NPM v7.19.1
### Node v16.5.0

Welcome to the new social media tailored for DC students.

Currently in developement with symfony 5, follow this instructions to run the project:

- Clone the repo
- Make sure to have access to a SQL Database (using MAMP on Mac for exemple)

#### Inside the repo, install the dependencies with:
- ```composer install```
- ```npm install```

Once installed, change the options in the .env to match your database parameters.

Then run:
- ```php bin/console make:migration```
- ```php bin/console d:m:m```

If you want to populate the databse with some data, run ```php bin/console doctrine:fixtures:load```

When everything work as expected (hopefully), you can start the servers:

- Front: ```npm run dev```
- Back: `symfony server:start`

The application is ready on localhost:8000 !

