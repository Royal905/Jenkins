# Use an official Apache runtime as a parent image
FROM ubuntu:latest
RUN apt-get update && apt-get install -y apache2
COPY index.html /var/www/html/



# Copy a custom index.html file to the document root directory
