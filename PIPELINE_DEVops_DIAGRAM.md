# Diagramme Pipeline DevOps - Projet ReLeaf

## Vue d'ensemble du Pipeline

Ce document présente le diagramme explicatif du pipeline CI/CD mis en place pour le projet **ReLeaf** (Application Laravel/PHP).

## Technologies utilisées

- **Source Control**: GitHub (Branche `devops`)
- **CI/CD**: Jenkins
- **Analyse de code**: SonarQube
- **Artifacts**: Nexus Repository
- **Containerisation**: Docker
- **Registry**: Docker Hub

## Diagramme du Pipeline

```mermaid
graph TD
    A[GitHub Repository<br/>Branche: devops] --> B[GIT Checkout]
    
    B --> C[Composer Install<br/>Installation des dépendances PHP]
    C --> D[Environment Setup<br/>Configuration .env, DB, Clé app]
    D --> E[NPM Install & Build<br/>Assets frontend avec Vite]
    
    E --> F[PHPUnit Tests<br/>Tests unitaires Laravel]
    F --> G[Laravel Pint<br/>Vérification style de code]
    
    G --> H{SonarQube Analysis<br/>Analyse qualité et sécurité}
    H -->|Succès| I[Nexus Deploy]
    H -->|Échec| J[Continue avec UNSTABLE]
    J --> I
    
    I --> K[Docker Build<br/>Construction image Docker]
    K --> L[Docker Push<br/>Pousser vers Docker Hub]
    L --> M{Deploy to Staging}
    M -->|Branche main| N[Deployment Staging]
    M -->|Autre branche| O[Skip Staging]
    
    N --> P[Post Actions<br/>Notifications]
    O --> P
    
    style A fill:#e1f5fe
    style H fill:#fff3e0
    style I fill:#e8f5e8
    style K fill:#f3e5f5
    style L fill:#f3e5f5
    style N fill:#e0f2f1
```

## Détail des Stages

### 1. **Source Control & Setup**
```mermaid
graph LR
    A[GitHub<br/>devops branch] --> B[Checkout Code]
    B --> C[Composer Install<br/>~15-53s]
    C --> D[Env Setup<br/>~1-3s]
    D --> E[NPM Build<br/>~9-24s]
```

### 2. **Quality Assurance**
```mermaid
graph LR
    A[PHPUnit Tests<br/>~2-3s] --> B[Laravel Pint<br/>Style Check<br/>~1-9s]
    B --> C[SonarQube<br/>Code Analysis<br/>~18s-1min19s]
```

### 3. **Artifacts & Deployment**
```mermaid
graph LR
    A[Nexus Deploy<br/>Structure PHP<br/>~1min32s-4min3s] --> B[Docker Build<br/>Image PHP 8.2<br/>~2min59s-8min29s]
    B --> C[Docker Push<br/>Docker Hub<br/>~2min41s-12min3s]
    C --> D[Staging Deploy<br/>Si branche main]
```

## Structure des Artifacts Nexus

```
nexus-repo/
└── raw-releases/
    └── com/
        └── example/
            └── releaf/
                ├── application/
                │   ├── [version]/
                │   │   └── releaf-application-[version].tar.gz
                │   └── latest/
                ├── complete/
                │   ├── [version]/
                │   │   └── releaf-complete-[version].tar.gz
                │   └── latest/
                └── metadata/
                    ├── [version]/
                    │   └── project-info.json
                    └── latest/
```

## Configuration des Outils

### SonarQube
- **URL**: http://192.168.50.4:9000
- **Project Key**: releaf
- **Langages**: PHP, JavaScript
- **Plugins**: SonarPHP (inclus)

### Nexus Repository
- **URL**: http://192.168.50.4:8082
- **Repository**: raw-releases
- **Structure**: Com/Example/ReLeaf/[Type]/[Version]

### Docker
- **Base Image**: php:8.2-fpm-alpine
- **Registry**: docker.io/firaszn
- **Tags**: [BUILD_NUMBER], latest (si main)

## Métriques de Performance

| Stage | Durée Typique | Durée Max Observée |
|-------|---------------|-------------------|
| Git Checkout | 2-8s | 8s |
| Composer Install | 15-53s | 53s |
| Environment Setup | 1-3s | 3s |
| NPM Build | 9-24s | 24s |
| PHPUnit Tests | 2-3s | 3s |
| Laravel Pint | 1-9s | 9s |
| SonarQube | 18s-1min19s | 1min19s |
| Nexus Deploy | 1min32s-4min3s | 4min3s |
| Docker Build | 2min59s-8min29s | 8min29s |
| Docker Push | 2min41s-12min3s | 12min3s |

## Points de Contrôle Qualité

1. **Tests Unitaires** : PHPUnit avec gestion d'échecs gracieux
2. **Style de Code** : Laravel Pint pour la cohérence
3. **Analyse Statique** : SonarQube avec Quality Gate
4. **Artifacts Versionnés** : Structure organisée dans Nexus
5. **Images Docker** : Build et push automatiques

## Gestion des Erreurs

- **Tests échoués** : Pipeline continue avec statut UNSTABLE
- **Style Code** : Pipeline continue après détection
- **SonarQube** : Continue avec UNSTABLE si échec
- **Docker** : Continue avec UNSTABLE si échec

## Notifications

- **Succès** : Pipeline executed successfully!
- **Échec** : Pipeline failed!
- **Extensions possibles** : Email, Slack, Teams

---

*Ce pipeline assure la qualité, la traçabilité et la déployabilité continue du projet ReLeaf.*
