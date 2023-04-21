# Use an official Apache runtime as a parent image
FROM ubuntu:latest
RUN apt-get update && apt-get install -y apache2
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND

# Copy a custom index.html file to the document root directory
COPY index.html /var/www/html/
