#!/bin/bash

# Function to check if XAMPP is running
check_xampp() {
    if pgrep -x "mysqld" > /dev/null; then
        return 0
    else
        return 1
    fi
}

# Function to check if Node.js is installed
check_node() {
    if ! command -v node &> /dev/null; then
        echo "Error: Node.js is not installed. Please install Node.js first."
        exit 1
    fi
}

# Function to check if npm is installed
check_npm() {
    if ! command -v npm &> /dev/null; then
        echo "Error: npm is not installed. Please install npm first."
        exit 1
    fi
}

# Function to install dependencies
install_dependencies() {
    if [ ! -d "node_modules" ]; then
        # echo "Installing dependencies..."
        npm install
    fi
}

# Function to start the server
start_server() {
    # echo "Starting email service..."
    node index.js
}

# Main script
main() {
    # Change to the services directory
    cd "$(dirname "$0")"

    # Check prerequisites
    check_node
    check_npm

    # Check if running in XAMPP environment
    if check_xampp; then
        echo "XAMPP environment detected"
        # Wait for MySQL to be ready (handled by Node.js now)
    else
        echo "Production environment detected"
    fi

    # Install dependencies
    install_dependencies

    # Start the server
    start_server
}

# Run the main function
main 