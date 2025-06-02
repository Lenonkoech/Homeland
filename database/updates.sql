-- New tables for enhanced features

-- Table for saved searches
CREATE TABLE IF NOT EXISTS saved_searches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    search_name VARCHAR(255),
    search_params TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for property features
CREATE TABLE IF NOT EXISTS property_features (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    feature_name VARCHAR(255),
    feature_value VARCHAR(255),
    FOREIGN KEY (property_id) REFERENCES props(id) ON DELETE CASCADE
);

-- Table for property views
CREATE TABLE IF NOT EXISTS property_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    user_id INT,
    view_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES props(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for saved properties
CREATE TABLE IF NOT EXISTS saved_properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    user_id INT,
    saved_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(255),
    message TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for blog posts
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    image VARCHAR(255),
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for property tours
CREATE TABLE IF NOT EXISTS property_tours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    tour_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Table for contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    message TEXT,
    attachment VARCHAR(255),
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for callback requests
CREATE TABLE IF NOT EXISTS callback_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    phone VARCHAR(50),
    preferred_time VARCHAR(255),
    status ENUM('pending', 'scheduled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Check and add new columns to props table
ALTER TABLE `props`
ADD COLUMN IF NOT EXISTS `virtual_tour_url` varchar(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `featured` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `view_count` int(11) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `last_price_update` timestamp NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `latitude` decimal(10,8) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `longitude` decimal(11,8) DEFAULT NULL;

-- Check and add new columns to users table
ALTER TABLE `users`
ADD COLUMN IF NOT EXISTS `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN IF NOT EXISTS `two_factor_secret` varchar(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `last_login` timestamp NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `notification_preferences` text DEFAULT NULL;

-- Create indexes for better performance
CREATE INDEX idx_property_views ON property_views(property_id, user_id);
CREATE INDEX idx_saved_properties ON saved_properties(property_id, user_id);
CREATE INDEX idx_notifications ON notifications(user_id, is_read);
CREATE INDEX idx_property_features ON property_features(property_id);
CREATE INDEX idx_saved_searches ON saved_searches(user_id); 