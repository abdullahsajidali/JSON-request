# JSON-request
Instructions for running the application.
clientrequest folder 2 files, 
1).payload.JSON which is the input the application will be using
2).request.php this file reads JSON input and uses cURL to speak to the PHP application

vectorapp folder has 1 file, which is the actual PHP script to perform all the desired operations.

Database Information:
vectormedia.sql is the database file that needs to be imported in your mysql db.

INDEXES:

For table `person` Primary Key is email, so it should be unique

Table `person_interests` uses a composite primary key of email and interest, their combination should be unique.
