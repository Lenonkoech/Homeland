#!/bin/bash

# Get the absolute path of the check-and-start.php script
SCRIPT_PATH="$(cd "$(dirname "$0")" && pwd)/check-and-start.php"

# Create the cron job to run every minute
(crontab -l 2>/dev/null; echo "* * * * * php $SCRIPT_PATH") | crontab -

# echo "Cron job has been set up to check for pending emails every minute"
# echo "You can check the cron jobs with: crontab -l" 