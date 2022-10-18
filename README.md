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
- linux OS (fedora/debian);
- nginx(recomended)/apache2;
- php7.4 (tested);
- common libs and packeges tools like git, mbstring, certbot, nginx/apache2, ssh;


How to install:
- make git clone to web-root folder and configure your web server to serve php scripts (recomended php-fpm);
- script have no dependency and easy to integrate into php/python project as well as working alone;
- find following configuration files and put your own params for your needs; 


How to configure:


getUpdates.php  - main project file. You dont need chenge here anything;

values01.json   - configuration file, here you can put key=>value and assign which text that user type in Telegram Bot will meet appropriate shell commands (you can use it not only with shell commands)

last_message.txt - here is a txt file with last processed message udpate_id. Dont touch it, it must fullfilment in a first run;

config.json      - here is place where you must put your own TelegramBot APIKEY (get from Telegram admin bot: @GotFather)

Thats all that you need!


