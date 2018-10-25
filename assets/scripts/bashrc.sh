#!/usr/bin/env bash
#
# ~/.bashrc: executed by bash(1) for non-login shells.

# Note: PS1 and umask are already set in /etc/profile. You should not
# need this unless you want different defaults for root.
PS1='Docker:$(hostname -i):\w\$ '
# umask 022

# You may uncomment the following lines if you want `ls' to be colorized:
export LS_OPTIONS='--color=auto'
export LS_COLORS=$LS_COLORS:'di=1;33:'
#eval "`dircolors`"
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -l'
alias l='ls $LS_OPTIONS -lA'
#
# Some more alias to avoid making mistakes:
alias rm='rm -i'
alias cp='cp -i'
alias mv='mv -i'

sleep 1

type /root/.copy_sshkey.sh &>/dev/null && /root/.copy_sshkey.sh

export PATH="/var/www/.bin:$PATH"


echo
echo
echo " **********************************************************************"
echo " * This Docker container is for an interactive BASH shell.  It has     "
echo " * the following tools pre-loaded:                                     "
echo " *                                                                     "
echo " * composer"
echo " * phpunit"
echo " * phpunit/dbunit"
echo " * phing "
echo " * phpcpd "
echo " * phploc "
echo " * phpmd "
echo " * phpcs "
echo " * mysql "
echo " * curl "
echo " * net-tool "
echo " *                                                                     "
echo " **********************************************************************"
echo
echo

#exec bash

