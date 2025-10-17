# Structure Nexus pour le projet ReLeaf (PHP/Laravel)

## Vue d'ensemble

Ce document décrit la structure des artefacts déployés dans Nexus Repository pour le projet **ReLeaf**, une application Laravel/PHP au lieu d'un projet Maven.

## Structure dans Nexus Repository

```
raw-releases/
└── com/
    └── example/
        └── releaf/
            ├── application/
            │   ├── 1/
            │   │   └── releaf-application-1.tar.gz
            │   ├── 2/
            │   │   └── releaf-application-2.tar.gz
            │   └── latest/
            │       └── releaf-application-latest.tar.gz
            ├── complete/
            │   ├── 1/
            │   │   └── releaf-complete-1.tar.gz
            │   ├── 2/
            │   │   └── releaf-complete-2.tar.gz
            │   └── latest/
            │       └── releaf-complete-latest.tar.gz
            └── metadata/
                ├── 1/
                │   └── project-info.json
                ├── 2/
                │   └── project-info.json
                └── latest/
                    └── project-info.json
```

## Types d'artefacts

### 1. Application Package (`application/`)
- **Contenu** : Application Laravel complète (`projet_laravel/`)
- **Usage** : Déploiement de l'application seule
- **Nom** : `releaf-application-{version}.tar.gz`

### 2. Complete Package (`complete/`)
- **Contenu** : Projet complet avec configuration
  - Application Laravel (`projet_laravel/`)
  - Configuration Composer (`composer.json`)
  - Dockerfile
  - Configuration SonarQube (`sonar-project.properties`)
- **Usage** : Déploiement complet avec toutes les dépendances
- **Nom** : `releaf-complete-{version}.tar.gz`

### 3. Metadata (`metadata/`)
- **Contenu** : Informations sur le projet
- **Fichier** : `project-info.json`
- **Usage** : Métadonnées pour l'automatisation et le déploiement

## Exemple de project-info.json

```json
{
    "projectName": "ReLeaf",
    "projectType": "Laravel/PHP",
    "version": "1",
    "buildDate": "2024-01-15T10:30:00Z",
    "framework": "Laravel 12.x",
    "phpVersion": "8.2+",
    "description": "ReLeaf - Event Management Platform",
    "dependencies": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/socialite": "^5.23"
    },
    "structure": {
        "application": "projet_laravel/",
        "config": "composer.json",
        "docker": "Dockerfile",
        "quality": "sonar-project.properties"
    }
}
```

## Différences avec Maven

| Aspect | Maven | ReLeaf (PHP/Laravel) |
|--------|-------|---------------------|
| **Format** | JAR/WAR | TAR.GZ |
| **Structure** | groupId/artifactId/version | com/example/releaf/type/version |
| **Métadonnées** | pom.xml | project-info.json |
| **Dépendances** | Maven Central | Composer (Packagist) |
| **Build** | Maven | Composer + NPM |

## Utilisation

### Télécharger la dernière version
```bash
# Application seule
curl -O ${NEXUS_URL}/repository/raw-releases/com/example/releaf/application/latest/releaf-application-latest.tar.gz

# Projet complet
curl -O ${NEXUS_URL}/repository/raw-releases/com/example/releaf/complete/latest/releaf-complete-latest.tar.gz

# Métadonnées
curl -O ${NEXUS_URL}/repository/raw-releases/com/example/releaf/metadata/latest/project-info.json
```

### Déploiement
```bash
# Extraire et déployer
tar -xzf releaf-complete-latest.tar.gz
cd releaf/latest
composer install --optimize-autoloader --no-dev
php artisan migrate
php artisan config:cache
```

## Configuration Jenkins

Le pipeline Jenkins est configuré pour :
1. Créer automatiquement cette structure
2. Générer les métadonnées
3. Uploader tous les artefacts
4. Maintenir les versions `latest`

## Avantages de cette structure

1. **Organisation claire** : Séparation des types d'artefacts
2. **Versioning** : Gestion des versions numériques et `latest`
3. **Métadonnées** : Informations structurées sur chaque build
4. **Flexibilité** : Packages séparés selon les besoins de déploiement
5. **Compatibilité** : Structure familière pour les équipes DevOps
