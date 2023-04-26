#!/bin/bash

# Define variables
REMOTE_USER="root" # Replace with your remote server's username
REMOTE_HOST="20.42.118.110" # Replace with your remote server's hostname or IP address
REMOTE_DIR="/root" # Replace with the directory on the remote server where you want to create the files

# SSH into the remote server and create files
ssh $REMOTE_USER@$REMOTE_HOST "cd $REMOTE_DIR && touch file4.txt file5.txt file6.txt"

echo "Files created on $REMOTE_HOST in $REMOTE_DIR"
