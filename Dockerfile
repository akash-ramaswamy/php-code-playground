FROM php:8.2.28-zts-alpine3.21

# Create working directory
WORKDIR /app

# Copy PHP code
COPY container_src/run.php .

# Expose port 80
EXPOSE 8000

# Start PHP's built-in dev server
CMD ["php", "-S", "0.0.0.0:8000", "run.php"]
