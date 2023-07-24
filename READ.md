# Technologies used:
-	Codeigniter 3.1.11 (PHP Framework)
-	Bootstrap (CSS Framework)
-	Jquery using ajax
-	Jquery Datatables
-	Mysql Database
-	Any library for qr code generator
    * qr scanner: https://github.com/zxing-js/browser
    * qr generator: https://davidshimjs.github.io/qrcodejs/

# For setting up the app,
1. Docker build and run background:
    docker-compose -f docker-compose.yml up --build -d

2. To access the app, 
    localhost:8041 

    and to access PHPMyAdmin, 
    localhost:8042 

3. First run the migration setup using this URL, 
    localhost:8041/migration/setup

4. Initial users added,
    * username: admin
    * password: Password1234+

# ====
You can also test it here: https://apptest.dizondeveloper.com
Use these credentials:
    * username: admin
    * password: Password1234+