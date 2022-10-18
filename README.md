# telegramBot
Simple telegram bot script for php enviroment which you can deploy and configure in 30 minutes!

Capatibilities and futures:

Simple script which performs execution shell script depends customer keywords in telegram bot:
    - scripts running via crontab service with defined interval and collect users message updates
  	- scripn includes two external configuration files: with secured params and bussness logic 
  	- script save latest processed message id and can avoid de duplicates shell commands executions 
    - script have simlple integface which can be used for initial diagnostic and maintenance 
  	- script writen by clear PHP and not needed any critical depends 

Requrements:
- linux OS;
- nginx/apache2;
- php7.4 (tested);
- common libs and tools like git, mbstring, certbot;


How to install:
- make git clone to web-root folder and configure your web server to serve php scripts (recomended php-fpm);
- script have no dependency and easy to integrate into php/python project as well as working alone;
- find follow configuration files and put your owd params and settings

How to configure:

