# php-starter
        XKCD COMIC MAILER 

# Live demo

Link: 

[http://18.206.118.37/](http://18.206.118.37/)

or

[http://ec2-18-206.118.37.compute-1.amazonaws.com/](http://ec2-18-206.118.37.compute-1.amazonaws.com/)


# Prerequisites

1. OS (ubuntu used in this project)
2. Apache or nginx server
3. Php
4. Mysql database
5. Mail(SMTP) server
6. crontab
  

# Procedure

1. Launch one ubuntu instance from any cloud service (AWS,Azure,Google cloud,Digital Ocean etc..) 
2. Install LAMP stack (Linux, Apache, Mysql, PHP) and grant permission(mysql) for the user you want to connect.
3. Create SMTP server for the mail services, I've installed  `Postfix` and configured with my google account 
4. Create `databaseConnection.php` file and connect with mysql database securely
5. Create `index.php` file and create form with one input tag for email and one submit button.
6. Fetch the values and sanitize them in PHP, check for duplicate entry, push it to the database and send mail to the user using mail inbuilt function.
7. Verify link should pass  the `email ` and `vkey` (hex format)  by appending to the url

       eg:   http://23.44.xxx.x/verify.php?email=butrabalaprasad370@gmail.com&vkey=hghg63hdueyuhdncjhcuyeyd23j989idcmnednfk098866767;

8. Verify user by fetching the values from url, Please sanitize them before accessing those values.
9. Create cron job for every five minutes and give path 

        */5 * * * *  /usr/bin/php  filepath
10. Every email id receive comic mail if its action field set to start.



# screenshots


## subscribe

![index page](screenshots/Screenshot%20(17).png)
![index page](screenshots/Screenshot%20(18).png)
![index page](screenshots/Screenshot%20(19).png)
![index page](screenshots/Screenshot%20(20).png)
![index page](screenshots/Screenshot%20(21).png)
![index page](screenshots/Screenshot%20(22).png)
![index page](screenshots/Screenshot%20(23).png)
![index page](screenshots/Screenshot%20(24).png)
![index page](screenshots/Screenshot%20(25).png)


## auto mailer

![index page](screenshots/Screenshot%20(26).png)
![index page](screenshots/Screenshot%20(27).png)
![index page](screenshots/Screenshot%20(29).png)

## unsubscribe
![index page](screenshots/Screenshot%20(28).png)
![index page](screenshots/Screenshot%20(30).png)
