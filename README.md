# test techensis symfony

A small app to parse and display images from a website in a gallery format

## Installation

* Clone the repository
```bash
git clone
```

* Make the bin directory executable
```bash
chmod +x bin/*
```

* Generate a self-signed SSL certificate for the Nginx server
```bash
bin/generate-certs
```

* Copy the php.ini-development or php.ini-production file to php.ini
```bash
cp ./docker/php/conf/php.ini-production ./docker/php/conf/php.ini
```

* Copy the .env.example files to .env and fill in the appropriate values
```bash
cp .env.example .env
cp src/.env src/.env.local
```

* Update the .env file with the appropriate values
```bash
nano .env
```

* Generate secret and put it in the src/.env.local file
```bash
bin/generate-secret
```

* Run the docker-compose up command to start the containers
```bash
docker-compose up -d
```

* Install the composer dependencies
```bash
bin/composer install
```