Accounting Store App
=========

Accounting Store System Web App
Symfony project created on March 13, 2019, 4:12 pm.

### About
About
This app is intended to assist stores, self-employment industry to manage their sales, deliveries & bills and notify for needed deliveries.
---

### Demo


---

### Installation

From terminal run these commands:

```
git clone https://github.com/IvanTodorov910812/PHP-MVC-Symfony.git
cd storesystem/
composer install
```

In order to configure [Amazon S3](https://s3.console.aws.amazon.com) file upload, you will be asked to set your credentials:
```
aws_key: YOUR_AWS_KEY
aws_secret_key: YOUR_AWS_SECRET_KEY
aws_default_region: YOUR_AWS_DEFAULT_REGION
aws_bucket_name: YOUR_AWS_BUCKET_NAME
aws_base_url: YOUR_AWS_BASE_URL
```

In order to configure [FireBase Database REST API](https://firebase.google.com/) 
```
.JSON/GET, POST, PATCH, DELETE/  

```

...and finally, run fixtures to populate database with basic data

```
./bin/console doctrine:fixtures:load
```

That's all! Enjoy!
