# Diagramme Pipeline ReLeaf - Version Mermaid

## Pipeline DevOps Complet

```mermaid
flowchart TD
    subgraph "Source Control"
        A[📁 GitHub Repository<br/>Repository: Aya-Bs/ReLeaf<br/>Branche: devops] --> B[🔍 GIT Checkout<br/>Duration: 2-8s<br/>Credentials: github-credentials]
    end
    
    subgraph "Build & Setup"
        B --> C[📦 Composer Install<br/>Duration: 15-53s<br/>Optimize autoloader]
        C --> D[⚙️ Environment Setup<br/>Duration: 1-3s<br/>.env, DB, App Key]
        D --> E[🎨 NPM Install & Build<br/>Duration: 9-24s<br/>Vite Build Assets]
    end
    
    subgraph "Quality Assurance"
        E --> F[🧪 PHPUnit Tests<br/>Duration: 2-3s<br/>Laravel Test Suite]
        F --> G[✨ Laravel Pint<br/>Duration: 1-9s<br/>Code Style Check]
        G --> H{🔍 SonarQube Analysis<br/>Duration: 18s-1min19s<br/>Quality Gate}
    end
    
    subgraph "Artifacts Deployment"
        H -->|✅ Success| I[📦 Nexus Deploy<br/>Duration: 1min32s-4min3s<br/>PHP/Laravel Structure]
        H -->|❌ Failed| J[⚠️ Continue UNSTABLE<br/>Skip SonarQube Issues]
        J --> I
    end
    
    subgraph "Containerization"
        I --> K[🐳 Docker Build<br/>Duration: 2min59s-8min29s<br/>PHP 8.2 Alpine Image]
        K --> L[📤 Docker Push<br/>Duration: 2min41s-12min3s<br/>Docker Hub: firaszn/releaf]
    end
    
    subgraph "Deployment"
        L --> M{Branch Check}
        M -->|main branch| N[🚀 Deploy to Staging<br/>Production Ready]
        M -->|other branch| O[⏭️ Skip Staging<br/>Develop Only]
        N --> P[📢 Post Actions<br/>Notifications & Reports]
        O --> P
    end
    
    style A fill:#e3f2fd,stroke:#1976d2,stroke-width:2px
    style H fill:#fff3e0,stroke:#f57c00,stroke-width:2px
    style I fill:#e8f5e8,stroke:#388e3c,stroke-width:2px
    style K fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    style L fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    style N fill:#e0f2f1,stroke:#00695c,stroke-width:2px
```

## Architecture des Outils DevOps

```mermaid
graph TB
    subgraph "Development Environment"
        DEV[👩‍💻 Developer<br/>Code Changes] --> GIT[📁 GitHub<br/>192.168.50.4]
    end
    
    subgraph "CI/CD Pipeline"
        GIT --> JEN[🔄 Jenkins<br/>Pipeline Orchestrator]
        
        subgraph "Quality Tools"
            JEN --> SONAR[🔍 SonarQube<br/>192.168.50.4:9000<br/>Code Quality & Security]
        end
        
        subgraph "Artifact Management"
            JEN --> NEXUS[📦 Nexus Repository<br/>192.168.50.4:8082<br/>Artifacts Storage]
        end
        
        subgraph "Container Registry"
            JEN --> DOCKER[🐳 Docker Hub<br/>hub.docker.com/firaszn<br/>Container Images]
        end
    end
    
    subgraph "Target Environments"
        DOCKER --> STAGE[🧪 Staging Environment<br/>Testing & Validation]
        STAGE --> PROD[🚀 Production Environment<br/>Live Application]
    end
    
    style DEV fill:#e1f5fe,stroke:#01579b
    style JEN fill:#fff3e0,stroke:#e65100
    style SONAR fill:#f3e5f5,stroke:#4a148c
    style NEXUS fill:#e8f5e8,stroke:#1b5e20
    style DOCKER fill:#fce4ec,stroke:#880e4f
    style STAGE fill:#fff8e1,stroke:#ff6f00
    style PROD fill:#e0f2f1,stroke:#004d40
```

## Flux de Données et Métriques

```mermaid
sequenceDiagram
    participant Dev as Developer
    participant GH as GitHub
    participant J as Jenkins
    participant S as SonarQube
    participant N as Nexus
    participant D as Docker Hub
    
    Dev->>GH: Push Code (devops branch)
    GH->>J: Trigger Pipeline
    
    Note over J: Stage 1-4: Setup & Build
    J->>J: Checkout, Composer, NPM
    
    Note over J: Stage 5-6: Quality Check
    J->>J: PHPUnit Tests, Laravel Pint
    
    Note over J: Stage 7: Code Analysis
    J->>S: Upload Source Code
    S->>S: Analyze (18s-1min19s)
    S->>J: Quality Report
    
    Note over J: Stage 8: Artifacts
    J->>N: Deploy Structure (1min32s-4min3s)
    Note over N: com/example/releaf/<br/>[application|complete|metadata]/<br/>[version|latest]
    
    Note over J: Stage 9-10: Containerization
    J->>J: Build Docker Image (2min59s-8min29s)
    J->>D: Push Image (2min41s-12min3s)
    
    Note over J: Stage 11: Deployment
    alt Branch = main
        J->>J: Deploy to Staging
    end
    
    J->>Dev: Pipeline Complete Notification
```

## Métriques de Performance par Build

```mermaid
xychart-beta
    title "Pipeline Duration Trend (Last 3 Builds)"
    x-axis [Build 23, Build 24, Build 25]
    y-axis "Duration (minutes)" 0 --> 25
    
    bar [3.2, 4.8, 6.9]
```

## Structure des Artifacts Déployés

```mermaid
graph TD
    subgraph "Nexus Repository Structure"
        ROOT[📁 raw-releases] --> COM[📁 com]
        COM --> EXAMPLE[📁 example]
        EXAMPLE --> RELEAF[📁 releaf]
        
        RELEAF --> APP[📁 application]
        RELEAF --> COMP[📁 complete]
        RELEAF --> META[📁 metadata]
        
        APP --> VERSION1[📁 [BUILD_NUMBER]]
        APP --> LATEST1[📁 latest]
        
        COMP --> VERSION2[📁 [BUILD_NUMBER]]
        COMP --> LATEST2[📁 latest]
        
        META --> VERSION3[📁 [BUILD_NUMBER]]
        META --> LATEST3[📁 latest]
        
        VERSION1 --> TAR1[📄 releaf-application-[version].tar.gz<br/>Laravel App Only]
        COMP --> TAR2[📄 releaf-complete-[version].tar.gz<br/>Full Project with Config]
        META --> JSON[📄 project-info.json<br/>Metadata & Dependencies]
    end
    
    style ROOT fill:#e3f2fd
    style TAR1 fill:#e8f5e8
    style TAR2 fill:#fff3e0
    style JSON fill:#f3e5f5
```

---

## Légende

| Symbole | Signification |
|---------|---------------|
| 📁 | Dossier/Répertoire |
| 🔄 | Process automatique |
| 🧪 | Tests/Validation |
| 🔍 | Analyse/Inspection |
| 📦 | Artifacts/Packages |
| 🐳 | Docker/Containers |
| 🚀 | Déploiement |
| ⚠️ | Gestion d'erreur |
| ✅ | Succès |
| ❌ | Échec |

*Ce diagramme représente l'architecture complète du pipeline DevOps pour le projet ReLeaf, incluant tous les outils, flux de données et métriques de performance observés.*
