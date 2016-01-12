#!/bin/bash

############################################################
# Configures the development server settings for this site #
############################################################

# Set this variable:

# Set a URL for the local dev environment for this site. This will have ".app appended", so don't add a tld.
SITE_URL="mysite"

## Do not change below this line ##
SERVER_IP="192.168.10.10"
SITE_FULL_URL="$SITE_URL.app"
CURRENT_DIR=`pwd`
THIS_USER_EFFECTIVE_ID=$EUID
THIS_USER_ID=$SUDO_UID
printf "Current directory is $CURRENT_DIR\n"
printf "Current User: $THIS_USER_ID\n";

COLOR_BLUE=$(tput setaf 4)
COLOR_NORMAL=$(tput sgr0)
UNDERLINE=$(tput smul)


printf "Checking dependencies:\n"

if hash bower 2>/dev/null; then
	printf "Bower is installed\n"
else
	printf "Installing Bower\n"
	npm install -g bower
fi

if hash npm 2>/dev/null; then
	printf "Node is installed..\n"
else
	printf "Node is required. Download it here: {$UNDERLINE}https://nodejs.org/${COLOR_NORMAL}\n"
	printf "Setup cannot continue.\n"
	exit 1
fi

if hash vagrant 2>/dev/null; then
	printf "Vagrant is installed\n"
else
	printf "Vagrant is required. Download it here: ${UNDERLINE}http://www.vagrantup.com/downloads${COLOR_NORMAL}\n"
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
PATTERN='{{CURRENT_DIR}}'
REPLACE=$(echo $CURRENT_DIR | sed -e 's/[\/&]/\\&/g')
sed -i '' 's/'$PATTERN'/'$REPLACE'/g' Homestead.yaml
PATTERN='{{SERVER_IP}}'
REPLACE=$(echo $SERVER_IP | sed -e 's/[\/&]/\\&/g')
sed -i '' 's/'$PATTERN'/'$REPLACE'/g' Homestead.yaml
PATTERN='{{SITE_URL}}'
REPLACE=$(echo $SITE_URL | sed -e 's/[\/&]/\\&/g')
sed -i '' 's/'$PATTERN'/'$REPLACE'/g' Homestead.yaml

# Add the testing domain to the hosts file
if grep -q "$SITE_FULL_URL" /private/etc/hosts; then
    printf "Site has already been added to hosts file\n"
else
    printf "Adding the host to the hosts file.\n"
	echo "$SERVER_IP    $SITE_FULL_URL" | sudo tee -a /private/etc/hosts
	dscacheutil -flushcache
fi

# If node_module already exists, delete it
if [ -d "./node_modules" ]; then
	printf "Deleting existing node_modules directory\n"
	rm -rf ./node_modules
fi

# Install the site files
printf "Installing site files.\n"
php composer.phar selfupdate
php composer.phar install
npm install
bower install --allow-root

# Set up the .env file, if necessary
if [ -f ./.env ]; then
    printf ".env file already exists.\n"
else
	printf "Copying .env.example to .env\n"
	cp ./.env.example ./.env
	PATTERN='{{SITE_URL}}'
	REPLACE=$(echo $SITE_URL | sed -e 's/[\/&]/\\&/g')
	sed -i '' 's/'$PATTERN'/'$REPLACE'/g' .env
fi

# Regenerate the app key
printf "Regenerating the app key.\n"
php artisan key:generate

# Start the server
if vagrant status | grep 'The VM is running.'; then
	printf "Removing the previously created virtual machine\n"
	vagrant destroy -f
elif vagrant status | grep 'The VM is powered off.'; then
	printf "Removing the previously created virtual machine\n"
	vagrant destroy -f
fi

printf "Provisioning the development server\n"
vagrant provision
printf "Launching the development server\n"
vagrant up

# Populate the database
printf "Populating the database\n"
echo "php site/artisan migrate:refresh && php site/artisan db:seed && exit" | vagrant ssh

# Shut down the VM so that it can be started by the current user instead of root
printf "Shutting down the virutal machine.\n"
vagrant suspend
#echo $THIS_USER_ID > .vagrant/machines/default/virtualbox/creator_uid
#chown -R $THIS_USER_ID .vagrant

printf "\nInstallation complete\n\n"
printf "To start your development server, type: ${COLOR_BLUE}vagrant up${COLOR_NORMAL}\n"
printf "You can then visit your site at ${UNDERLINE}http://$SITE_URL.app${COLOR_NORMAL}\n"
printf "To stop the server, run ${COLOR_BLUE}vagrant suspend${COLOR_NORMAL}\n"
exit 0