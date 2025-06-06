#!/bin/bash

# Make the start script executable
chmod +x "$(dirname "$0")/start-server.sh"

# Copy the service file to systemd directory
sudo cp "$(dirname "$0")/homeland-email.service" /etc/systemd/system/

# Reload systemd to recognize the new service
sudo systemctl daemon-reload

# Enable the service to start on boot
sudo systemctl enable homeland-email.service

# Start the service
sudo systemctl start homeland-email.service

echo "Homeland Email Service has been installed and started."
echo "You can check the status with: sudo systemctl status homeland-email.service" 