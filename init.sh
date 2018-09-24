#!/usr/bin/env bash

a2enmod rewrite
service apache2 restart
apache2-foreground