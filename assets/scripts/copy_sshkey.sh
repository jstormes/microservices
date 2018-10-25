#!/usr/bin/env bash
# Copy SSH key into local setup.
if [ ! -f ~/.ssh/id_rsa ]; then
    if [ -f /var/www/.ssh/id_rsa ]; then
        mkdir ~/.ssh
        # Force Unix line endings.
        sed -e 's/\r\n/\n/g' /var/www/.ssh/id_rsa > ~/.ssh/id_rsa
        chmod -R 400 ~/.ssh
        echo "StrictHostKeyChecking no" >> /etc/ssh/ssh_config
        echo "###########################################################################"
        echo "# WARNING!!!!"
        echo "# SSH Deploy key was copied into docker image.  You should NOT push this image"
        echo "# to a Docker repo unless you delete it!!!!"
        echo "###########################################################################"
    else
        echo "###########################################################################"
        echo "# SSH Deploy Key not found!!!"
        echo "# file not found: \\var\\www\\.ssh\\id_rsa"
        echo "# Copy a valid id_rsh key file into .ssh if you need to use secure git."
        echo "###########################################################################"
    fi
fi

