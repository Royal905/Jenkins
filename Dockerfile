# Use an official Apache runtime as a parent image
FROM httpd:2.4

# Copy a custom index.html file to the document root directory
COPY index.html /var/www/html/
