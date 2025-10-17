# Corrections SonarQube - Projet ReLeaf

## Problème identifié

Le stage SonarQube passait très vite (3 secondes) et le projet n'apparaissait pas dans l'interface SonarQube à `http://192.168.50.4:9000`.

## Causes probables

1. **Analyse échouant silencieusement** : Pas de logs détaillés
2. **Configuration incorrecte** : Chemins de sources mal définis
3. **Connectivité** : Problème de communication avec SonarQube
4. **Token d'authentification** : Token invalide ou expiré

## Corrections apportées

### 1. **Logs détaillés et debug**
- Ajout de logs de debug complets
- Vérification de la connectivité SonarQube
- Affichage des versions Java/PHP
- Mode verbose (`-X`) pour SonarScanner

### 2. **Configuration robuste**
- Création dynamique du fichier `sonar-project.properties`
- Chemins de sources adaptés à Laravel
- Configuration PHP spécifique
- Vérification de l'existence des fichiers

### 3. **Gestion d'erreurs améliorée**
- Test de connectivité avant analyse
- Vérification de l'existence de SonarScanner
- Messages d'erreur explicites

### 4. **Configuration SonarQube optimisée**

```properties
# Sources Laravel
sonar.sources=app,routes,config,database/migrations
sonar.tests=tests

# Exclusions Laravel
sonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,node_modules/**,public/build/**

# Configuration PHP
sonar.php.file.suffixes=php
sonar.qualitygate.wait=true
```

## Script de test

Un script `test-sonar.sh` a été créé pour diagnostiquer les problèmes :

```bash
# Tester la connectivité
curl -f -s http://192.168.50.4:9000/api/system/status

# Vérifier les projets existants
curl -s http://192.168.50.4:9000/api/projects/search
```

## Résultat attendu

Après ces corrections, vous devriez voir :

1. **Logs détaillés** dans Jenkins montrant le processus d'analyse
2. **Durée plus longue** du stage SonarQube (30-60 secondes)
3. **Projet visible** dans SonarQube à : `http://192.168.50.4:9000/projects`
4. **Dashboard du projet** accessible à : `http://192.168.50.4:9000/dashboard?id=releaf`

## URLs importantes

- **Interface SonarQube** : http://192.168.50.4:9000
- **Projets** : http://192.168.50.4:9000/projects
- **Projet ReLeaf** : http://192.168.50.4:9000/dashboard?id=releaf
- **API Status** : http://192.168.50.4:9000/api/system/status

## Vérifications

### 1. Vérifier que SonarQube est accessible
```bash
curl http://192.168.50.4:9000/api/system/status
```

### 2. Vérifier les projets existants
```bash
curl http://192.168.50.4:9000/api/projects/search
```

### 3. Vérifier le plugin PHP
- Aller dans Administration > Marketplace
- Chercher "PHP" et s'assurer qu'il est installé

## Troubleshooting

Si le problème persiste :

1. **Vérifier le token** : Le token SonarQube est-il valide ?
2. **Vérifier les permissions** : L'utilisateur a-t-il les droits de créer des projets ?
3. **Vérifier les plugins** : Le plugin PHP est-il installé ?
4. **Vérifier les logs** : Consulter les logs SonarQube pour plus de détails

## Commandes utiles

```bash
# Test de connectivité
curl -f http://192.168.50.4:9000/api/system/status

# Lister les projets
curl -s http://192.168.50.4:9000/api/projects/search | jq '.components[].key'

# Vérifier un projet spécifique
curl -s http://192.168.50.4:9000/api/components/show?component=releaf
```
