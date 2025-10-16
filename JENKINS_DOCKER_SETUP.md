# Configuration Docker pour Jenkins

## Problème Actuel
```
ERROR: permission denied while trying to connect to the Docker daemon socket
```

## Solutions

### Option 1: Ajouter Jenkins au groupe docker (Recommandé)

```bash
# Sur le serveur Jenkins
sudo usermod -aG docker jenkins
sudo systemctl restart jenkins
```

### Option 2: Configurer Docker-in-Docker (DinD)

```bash
# Dans le Jenkinsfile, ajouter un agent Docker
pipeline {
    agent {
        docker {
            image 'docker:latest'
            args '-v /var/run/docker.sock:/var/run/docker.sock'
        }
    }
    // ... rest of pipeline
}
```

### Option 3: Utiliser Docker avec sudo

```bash
# Modifier le Jenkinsfile pour utiliser sudo
docker build -> sudo docker build
docker push -> sudo docker push
```

### Option 4: Configuration Docker Socket

```bash
# Vérifier les permissions du socket Docker
ls -la /var/run/docker.sock
# Doit être : srw-rw---- 1 root docker

# Si nécessaire, corriger les permissions
sudo chmod 666 /var/run/docker.sock
```

## Configuration Jenkins Global

1. **Aller dans Jenkins > Manage Jenkins > Manage Plugins**
2. **Installer le plugin "Docker Pipeline"**
3. **Configurer Docker dans Jenkins > Manage Jenkins > Configure System**
4. **Ajouter un Cloud Docker si nécessaire**

## Test de Configuration

```bash
# Tester Docker dans Jenkins
sudo -u jenkins docker --version
sudo -u jenkins docker info
```

## Variables d'Environnement

Ajouter dans Jenkins > Manage Jenkins > Configure System > Global Properties:

```
DOCKER_HOST=unix:///var/run/docker.sock
DOCKER_BUILDKIT=1
```

## Sécurité

⚠️ **Attention**: Ajouter Jenkins au groupe docker donne des privilèges élevés.
Considérez utiliser des agents Docker isolés pour la production.
