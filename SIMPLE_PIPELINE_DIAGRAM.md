# Diagramme Pipeline DevOps - Version Simplifiée

## Vue d'ensemble du Pipeline ReLeaf

Ce diagramme présente le pipeline CI/CD complet pour le projet **ReLeaf**, une application Laravel/PHP de gestion d'événements.

## Pipeline Principal

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           PIPELINE CI/CD RELEAF                                 │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   🔄 SOURCE     │    │   🔧 BUILD      │    │   🧪 TEST       │
│                 │    │                 │    │                 │
│ GitHub (devops) │───▶│ Composer + NPM  │───▶│ PHPUnit + Pint  │
│ Repository      │    │ Environment     │    │ Code Quality    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                                       │
┌─────────────────┐    ┌─────────────────┐            ▼
│   🚀 DEPLOY     │    │   📦 ARTIFACTS  │    ┌─────────────────┐
│                 │    │                 │    │   🔍 ANALYZE    │
│ Staging/Prod    │◀───│ Nexus + Docker  │◀───│ SonarQube       │
│ Environment     │    │ Hub Repository  │    │ Code Analysis   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## Détail des Stages

### 1. **Source & Build** (0-2 min)
```
GitHub (devops) → Checkout → Composer Install → Environment Setup → NPM Build
```

### 2. **Quality & Testing** (1-3 min)
```
PHPUnit Tests → Laravel Pint → SonarQube Analysis
```

### 3. **Artifacts & Containerization** (4-20 min)
```
Nexus Deploy → Docker Build → Docker Push → Deploy (si main)
```

## Technologies & Outils

| Catégorie | Outil | URL/Description |
|-----------|-------|----------------|
| **Source Control** | GitHub | Repository: Aya-Bs/ReLeaf (branche devops) |
| **CI/CD** | Jenkins | Orchestrateur principal |
| **Quality** | SonarQube | http://192.168.50.4:9000 |
| **Artifacts** | Nexus | http://192.168.50.4:8082 |
| **Container** | Docker Hub | hub.docker.com/firaszn/releaf |
| **Framework** | Laravel 12.x | PHP 8.2+ |

## Métriques de Performance

| Stage | Durée Min | Durée Max | Statut |
|-------|-----------|-----------|---------|
| Git Checkout | 2s | 8s | ✅ Stable |
| Composer Install | 15s | 53s | ✅ Variable |
| Environment Setup | 1s | 3s | ✅ Stable |
| NPM Build | 9s | 24s | ✅ Stable |
| PHPUnit Tests | 2s | 3s | ✅ Stable |
| Laravel Pint | 1s | 9s | ✅ Stable |
| **SonarQube** | 18s | 1min19s | ⚠️ Amélioré |
| **Nexus Deploy** | 1min32s | 4min3s | ✅ Stable |
| **Docker Build** | 2min59s | 8min29s | ✅ Stable |
| **Docker Push** | 2min41s | 12min3s | ✅ Variable |

## Gestion des Erreurs

- **Tests échoués** → Continue avec statut UNSTABLE
- **SonarQube échec** → Continue avec UNSTABLE  
- **Docker échec** → Continue avec UNSTABLE
- **Pipeline robuste** → Ne s'arrête jamais complètement

## Points Forts du Pipeline

✅ **Qualité** : Analyse SonarQube + Tests automatisés  
✅ **Traçabilité** : Artifacts versionnés dans Nexus  
✅ **Containerisation** : Images Docker automatiques  
✅ **Déploiement** : Staging automatique (branche main)  
✅ **Robustesse** : Gestion gracieuse des erreurs  

---

*Pipeline DevOps complet pour le projet ReLeaf - Application de gestion d'événements Laravel/PHP*
