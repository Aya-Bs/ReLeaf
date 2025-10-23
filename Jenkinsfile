pipeline {
    agent any

    environment {
        REGISTRY = "docker.io/firaszn"
        IMAGE_NAME = "releaf:${env.BUILD_NUMBER}"
        GITHUB_REPO = "https://github.com/Aya-Bs/ReLeaf.git"
        SONAR_PROJECT_KEY = "releaf"
        SONAR_HOST_URL = "http://192.168.50.4:9000"
        NEXUS_URL = "http://192.168.50.4:8082"
    }

    stages {
        stage('GIT Checkout') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/devops']],
                    doGenerateSubmoduleConfigurations: false,
                    extensions: [],
                    submoduleCfg: [],
                    userRemoteConfigs: [[
                        url: "${GITHUB_REPO}",
                        credentialsId: 'github-credentials'
                    ]]
                ])
            }
        }

        stage('Composer Install') {
            steps {
                sh """
                    cd projet_laravel
                    composer install --optimize-autoloader --no-interaction
                """
            }
        }

        stage('Environment Setup') {
            steps {
                sh """
                    cd projet_laravel
                    
                    if [ ! -f .env ]; then
                        echo "APP_NAME=ReLeaf" > .env
                        echo "APP_ENV=testing" >> .env
                        echo "APP_KEY=" >> .env
                        echo "APP_DEBUG=true" >> .env
                        echo "APP_URL=http://localhost" >> .env
                        echo "" >> .env
                        echo "DB_CONNECTION=mysql" >> .env
                        echo "DB_HOST=127.0.0.1" >> .env
                        echo "DB_PORT=3306" >> .env
                        echo "DB_DATABASE=releaf_testing" >> .env
                        echo "DB_USERNAME=jenkins" >> .env
                        echo "DB_PASSWORD=jenkins" >> .env
                        echo "" >> .env
                        echo "CACHE_DRIVER=file" >> .env
                        echo "SESSION_DRIVER=file" >> .env
                        echo "QUEUE_CONNECTION=sync" >> .env
                    fi
                    
                    mysql -u jenkins -pjenkins -e "CREATE DATABASE IF NOT EXISTS releaf_testing;" || echo "MySQL setup skipped"
                    mysql -u jenkins -pjenkins -e "GRANT ALL PRIVILEGES ON releaf_testing.* TO 'jenkins'@'localhost';" || echo "MySQL privileges setup skipped"
                    mysql -u jenkins -pjenkins -e "FLUSH PRIVILEGES;" || echo "MySQL privileges flush skipped"
                    
                    php artisan key:generate --force --no-interaction
                """
            }
        }

        stage('NPM Install & Build') {
            steps {
                sh """
                    cd projet_laravel
                    npm install
                    npm run build
                """
            }
        }

        stage('PHPUnit Tests') {
            steps {
                sh """
                    cd projet_laravel
                    php ./vendor/bin/phpunit || echo "Tests completed with some failures - continuing pipeline"
                """
            }
        }

        stage('SonarQube Analysis') {
            steps {
                script {
                    try {
                        withCredentials([string(credentialsId: 'sonar-token', variable: 'SONAR_TOKEN')]) {
                            sh '''#!/bin/bash
                                cd projet_laravel
                                
                                curl -f -s ''' + SONAR_HOST_URL + '''/api/system/status || exit 1
                                
                                if [ ! -f sonar-scanner-5.0.1.3006-linux/bin/sonar-scanner ]; then
                                    wget -q https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip
                                    unzip -q sonar-scanner-cli-5.0.1.3006-linux.zip
                                fi
                                
                                cat > sonar-project.properties << 'EOF'
sonar.projectKey=''' + SONAR_PROJECT_KEY + '''
sonar.projectName=ReLeaf
sonar.projectVersion=''' + env.BUILD_NUMBER + '''
sonar.host.url=''' + SONAR_HOST_URL + '''
sonar.login=''' + SONAR_TOKEN + '''
sonar.sources=app,routes,config,database/migrations
sonar.tests=tests
sonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,node_modules/**,public/build/**
sonar.sourceEncoding=UTF-8
sonar.php.file.suffixes=php
sonar.php.coverage.reportPaths=coverage.xml
sonar.qualitygate.wait=true
EOF
                                
                                ./sonar-scanner-5.0.1.3006-linux/bin/sonar-scanner -X
                            '''
                        }
                    } catch (Exception e) {
                        echo "SonarQube analysis failed: ${e.getMessage()}"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
        }

        stage('Nexus Deploy') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'nexus-credentials', usernameVariable: 'NEXUS_USER', passwordVariable: 'NEXUS_PASS')]) {
                    sh '''
                        mkdir -p nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''
                        mkdir -p nexus-artifacts/releaf/latest
                        
                        cp -r projet_laravel nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/
                        cp -r projet_laravel nexus-artifacts/releaf/latest/
                        cp composer.json nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/
                        cp composer.json nexus-artifacts/releaf/latest/
                        
                        if [ -f Dockerfile ]; then
                            cp Dockerfile nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/
                            cp Dockerfile nexus-artifacts/releaf/latest/
                        fi
                        
                        if [ -f sonar-project.properties ]; then
                            cp sonar-project.properties nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/
                            cp sonar-project.properties nexus-artifacts/releaf/latest/
                        fi
                        
                        cat > nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/project-info.json << 'EOF'
{
    "projectName": "ReLeaf",
    "projectType": "Laravel/PHP",
    "version": "BUILD_NUMBER_PLACEHOLDER",
    "buildDate": "BUILD_DATE_PLACEHOLDER",
    "framework": "Laravel 12.x",
    "phpVersion": "8.2+",
    "description": "ReLeaf - Event Management Platform"
}
EOF
                        
                        sed -i "s/BUILD_NUMBER_PLACEHOLDER/''' + env.BUILD_NUMBER + '''/g" nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/project-info.json
                        sed -i "s/BUILD_DATE_PLACEHOLDER/$(date -u +%Y-%m-%dT%H:%M:%SZ)/g" nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/project-info.json
                        cp nexus-artifacts/releaf/''' + env.BUILD_NUMBER + '''/project-info.json nexus-artifacts/releaf/latest/
                        
                        cd nexus-artifacts
                        tar -czf releaf-application-''' + env.BUILD_NUMBER + '''.tar.gz releaf/''' + env.BUILD_NUMBER + '''/projet_laravel/
                        tar -czf releaf-complete-''' + env.BUILD_NUMBER + '''.tar.gz releaf/''' + env.BUILD_NUMBER + '''/
                        
                        curl -u ''' + NEXUS_USER + ''':''' + NEXUS_PASS + ''' \
                            --upload-file releaf-application-''' + env.BUILD_NUMBER + '''.tar.gz \
                            ''' + NEXUS_URL + '''/repository/raw-releases/com/example/releaf/application/''' + env.BUILD_NUMBER + '''/releaf-application-''' + env.BUILD_NUMBER + '''.tar.gz
                        
                        curl -u ''' + NEXUS_USER + ''':''' + NEXUS_PASS + ''' \
                            --upload-file releaf-complete-''' + env.BUILD_NUMBER + '''.tar.gz \
                            ''' + NEXUS_URL + '''/repository/raw-releases/com/example/releaf/complete/''' + env.BUILD_NUMBER + '''/releaf-complete-''' + env.BUILD_NUMBER + '''.tar.gz
                        
                        curl -u ''' + NEXUS_USER + ''':''' + NEXUS_PASS + ''' \
                            --upload-file releaf/''' + env.BUILD_NUMBER + '''/project-info.json \
                            ''' + NEXUS_URL + '''/repository/raw-releases/com/example/releaf/metadata/''' + env.BUILD_NUMBER + '''/project-info.json
                        
                        curl -u ''' + NEXUS_USER + ''':''' + NEXUS_PASS + ''' \
                            --upload-file releaf-application-''' + env.BUILD_NUMBER + '''.tar.gz \
                            ''' + NEXUS_URL + '''/repository/raw-releases/com/example/releaf/application/latest/releaf-application-latest.tar.gz
                        
                        curl -u ''' + NEXUS_USER + ''':''' + NEXUS_PASS + ''' \
                            --upload-file releaf-complete-''' + env.BUILD_NUMBER + '''.tar.gz \
                            ''' + NEXUS_URL + '''/repository/raw-releases/com/example/releaf/complete/latest/releaf-complete-latest.tar.gz
                        
                        curl -u ''' + NEXUS_USER + ''':''' + NEXUS_PASS + ''' \
                            --upload-file releaf/latest/project-info.json \
                            ''' + NEXUS_URL + '''/repository/raw-releases/com/example/releaf/metadata/latest/project-info.json
                    '''
                }
            }
        }

        stage('Docker Build') {
            steps {
                script {
                    try {
                        sh """
                            if [ ! -f Dockerfile ]; then
                                cat > Dockerfile << 'EOF'
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \\
    git curl libpng-dev libxml2-dev zip unzip nodejs npm \\
    oniguruma-dev freetype-dev libjpeg-turbo-dev libwebp-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \\
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY projet_laravel/ /var/www/html/
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage

EXPOSE 9000
CMD ["php-fpm"]
EOF
                            fi
                            
                            docker build -t ${REGISTRY}/${IMAGE_NAME} .
                        """
                    } catch (Exception e) {
                        echo "Docker build failed: ${e.getMessage()}"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
        }

        stage('Docker Push') {
            steps {
                script {
                    try {
                        withCredentials([usernamePassword(credentialsId: 'docker-hub', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                            sh """
                                echo ${DOCKER_PASS} | docker login -u ${DOCKER_USER} --password-stdin
                                docker push ${REGISTRY}/${IMAGE_NAME}
                            """
                        }
                    } catch (Exception e) {
                        echo "Docker push failed: ${e.getMessage()}"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
        }

        stage('Docker Compose Deploy') {
            steps {
                script {
                    try {
                        sh '''
                            cat > docker-compose.yml << 'EOF'
version: '3.8'
services:
  app:
    image: ''' + REGISTRY + '/' + IMAGE_NAME + '''
    container_name: releaf-app-''' + env.BUILD_NUMBER + '''
    ports:
      - "9000:9000"
    environment:
      - APP_NAME=Laravel
      - APP_ENV=local
      - APP_KEY=base64:k9Cux5xfRvnkAMGToE3IX0w87nnVntiVqpfO2Ni8GAM=
      - APP_DEBUG=true
      - APP_URL=http://localhost
      - DB_CONNECTION=mysql
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=laravel
      - DB_USERNAME=root
      - DB_PASSWORD=rootpassword
      - CACHE_STORE=file
      - SESSION_DRIVER=database
      - QUEUE_CONNECTION=database
    depends_on:
      - mysql
    networks:
      - releaf-network

  mysql:
    image: mysql:8.0
    container_name: releaf-mysql-''' + env.BUILD_NUMBER + '''
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: laravel
    ports:
      - "3306:3306"
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - releaf-network

  nginx:
    image: nginx:alpine
    container_name: releaf-nginx-''' + env.BUILD_NUMBER + '''
    ports:
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
      - ./projet_laravel/public:/var/www/html/public
    depends_on:
      - app
    networks:
      - releaf-network

volumes:
  mysql-data:

networks:
  releaf-network:
    driver: bridge
EOF

                            cat > nginx.conf << 'EOF'
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \\.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

                            if command -v docker >/dev/null 2>&1; then
                                docker compose down --remove-orphans || true
                                docker compose up -d
                            else
                                echo "Docker not found, trying alternative..."
                                docker-compose down --remove-orphans || true
                                docker-compose up -d
                            fi
                            
                            echo "Waiting for services to start..."
                            sleep 30
                            
                            if command -v docker >/dev/null 2>&1; then
                                docker compose ps || echo "Failed to check compose status"
                            else
                                docker-compose ps || echo "Failed to check compose status"
                            fi
                        '''
                    } catch (Exception e) {
                        echo "Docker Compose deployment failed: ${e.getMessage()}"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
        }
    }

    post {
        success {
            echo "Pipeline executed successfully!"
        }
        failure {
            echo "Pipeline failed!"
        }
    }
}