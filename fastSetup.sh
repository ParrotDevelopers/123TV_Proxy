#!/bin/bash

sudo echo "Installing dependencies..."
sudo apt-get update
sudo apt-get install -y php
sudo apt-get install -y php-curl
echo "Dependencies installed"
echo "Run 'php -S ip:port'"