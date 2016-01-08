#!/bin/bash

############################################################
# Configures the development server settings for this site #
############################################################

# Set this variable:

# Set a URL for the loca dev environment for this site
SITE_URL="mysite.app"

## Do not change below this line ##
SERVER_IP="192.168.10.10"
CURRENT_DIR=`pwd`
printf "Current directory is $CURRENT_DIR\n"

printf "Checking dependencies:\n"

if hash bower 2>/dev/null; then
	printf "Bower is installed\n"
else
	printf "Installing Bower\n"
	npm install -g bower
fi

if hash npm 2>/dev/null; then
	printf "Node is installed\n"
else
	printf "Node is required. Download it here: https://nodejs.org/\n"
	printf "Setup cannot continue.\n"
	exit 1
fi

if hash vagrant 2>/dev/null; then
	printf "Vagrant is installed\n"
else
	printf "Vagrant is required. Download it here: http://www.vagrantup.com/downloads\n"
	printf "Setup cannot continue.\n"
	exit 1
fi

if vagrant box list | grep '0.4.'; then
	printf "Vagrant Box 0.4 is installed\n"
else
	printf "Installing vagrant box\n"
	vagrant box add laravel/homestead --box-version 0.4.0 --provider virtualbox
fi

# Repalce values in the Homestead.yaml file
printf "Updating Homestead configuration file\n"
sed -i "s/\{\{CURRENT_DIR\}\}/$CURRENT_DIR/g" Homestead.yaml
sed -i "s/\{\{SERVER_IP\}\}/$SERVER_IP/g" Homestead.yaml

# Add the testing domain to the hosts file
if grep -Fxq "$SITE_URL" /private/etc/hosts; then
    printf "Site has already been added to hosts file\n"
else
    printf "Adding the host to the hosts file.\n"
	echo "$SERVER_IP    $SITE_URL" | sudo tee -a /private/etc/hosts
	dscacheutil -flushcache
fi

# Install the site files
printf "Installing site files.\n"
php composer.phar selfupdate
php composer.phar install
npm install
su bower install

# Set up the .env file, if necessary
if [ -f ./.env ]; then
    printf ".env file already exists.\n"
else
	printf "Copying .env.example to .env\n"
	cp ./env.example ./env
	sed -i "s/\{\{SITE_URL\}\}/$SITE_URL/g" .env
fi

# Regenerate the app key
printf "Regenerating the app key.\n"
php artisan key:generate

# Populate the database
